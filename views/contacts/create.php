<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\Contacts */
/* @var $modelsNumbers app\models\Numbers */

$this->title = 'Добавить контакт';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="contacts-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
        'modelsNumbers' => $modelsNumbers,
    ]) ?>

</div>
