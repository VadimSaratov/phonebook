<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\Contacts */
/* @var $modelsNumbers app\models\Contacts */

$this->title = "Редактирование контакта: {$model->name} {$model->surname}";
$this->params['breadcrumbs'][] = ['label' => $model->name, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Редактирование';
?>
<div class="contacts-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
	    'modelsNumbers'=> $modelsNumbers,
    ]) ?>

</div>
