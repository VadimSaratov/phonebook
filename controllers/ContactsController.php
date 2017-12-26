<?php

namespace app\controllers;

use app\models\Numbers;
use TheSeer\Tokenizer\NamespaceUri;
use Yii;
use app\models\Contacts;
use app\models\ContactsSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use app\models\Model;
use yii\web\Response;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;

/**
 * ContactsController implements the CRUD actions for Contacts model.
 */
class ContactsController extends Controller
{
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['POST'],
                ],
            ],
        ];
    }

    /**
     * Lists all Contacts models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new ContactsSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Contacts model.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {
	    $model = $this->findModel($id);
	    $numbers = Contacts::find()->one()->getNumbers()->asArray()->all();
        return $this->render('view', [
            'model' => $model,
            'numbers' => $numbers,
        ]);
    }

    /**
     * Creates a new Contacts model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Contacts();
	    $modelsNumbers = [new Numbers()];

        if ($model->load(Yii::$app->request->post()) && $model->save()) {

	        $modelsNumbers = Model::createMultiple(Numbers::classname());
	        Model::loadMultiple($modelsNumbers, Yii::$app->request->post());

	        // ajax validation
//	        if (Yii::$app->request->isAjax) {
//		        Yii::$app->response->format = Response::FORMAT_JSON;
//		        return ArrayHelper::merge(
//			        ActiveForm::validateMultiple($modelsNumbers),
//			        ActiveForm::validate($model)
//		        );
//	        }

	        // validate all models
	        $valid = $model->validate();
	        $valid = Model::validateMultiple($modelsNumbers) && $valid;

	        if ($valid) {
		        $transaction = \Yii::$app->db->beginTransaction();
		        try {
			        if ($flag = $model->save(false)) {
				        foreach ($modelsNumbers as $modelNumbers) {
					        $modelNumbers->contact_id = $model->id;
					        if (! ($flag = $modelNumbers->save(false))) {
						        $transaction->rollBack();
						        break;
					        }
				        }
			        }
			        if ($flag) {
				        $transaction->commit();
				        return $this->redirect(['view', 'id' => $model->id]);
			        }
		        } catch (\Exception $e) {
			        $transaction->rollBack();
		        }
	        }
        }


        return $this->render('create', [
            'model' => $model,
	        'modelsNumbers' => (empty($modelsNumbers)) ? [new Numbers()] : $modelsNumbers,
        ]);
    }

    /**
     * Updates an existing Contacts model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
	    $modelsNumbers = $model->numbers;

        if ($model->load(Yii::$app->request->post()) && $model->save()) {

	        $oldIDs = ArrayHelper::map($modelsNumbers, 'id', 'id');
	        $modelsNumbers = Model::createMultiple(Numbers::classname(), $modelsNumbers);
	        Model::loadMultiple($modelsNumbers, Yii::$app->request->post());
	        $deletedIDs = array_diff($oldIDs, array_filter(ArrayHelper::map($modelsNumbers, 'id', 'id')));

	        // ajax validation
//	        if (Yii::$app->request->isAjax) {
//		        Yii::$app->response->format = Response::FORMAT_JSON;
//		        return ArrayHelper::merge(
//			        ActiveForm::validateMultiple($modelsNumbers),
//			        ActiveForm::validate($model)
//		        );
//	        }

	        // validate all models
	        $valid = $model->validate();
	        $valid = Model::validateMultiple($modelsNumbers) && $valid;

	        if ($valid) {
		        $transaction = \Yii::$app->db->beginTransaction();
		        try {
			        if ($flag = $model->save(false)) {
				        if (! empty($deletedIDs)) {
					        Numbers::deleteAll(['id' => $deletedIDs]);
				        }
				        foreach ($modelsNumbers as $modelNumbers) {
					        $modelNumbers->contact_id = $model->id;
					        if (! ($flag = $modelNumbers->save(false))) {
						        $transaction->rollBack();
						        break;
					        }
				        }
			        }
			        if ($flag) {
				        $transaction->commit();
				        return $this->redirect(['view', 'id' => $model->id]);
			        }
		        } catch (\Exception $e) {
			        $transaction->rollBack();
		        }
	        }
        }
        return $this->render('update', [
            'model' => $model,
            'modelsNumbers' => (empty($modelsNumbers)) ? [new Numbers()] : $modelsNumbers,
        ]);
    }

    /**
     * Deletes an existing Contacts model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the Contacts model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Contacts the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Contacts::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
