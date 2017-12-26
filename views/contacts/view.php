<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\Contacts */
/* @var $numbers app\models\Contacts */

$this->title = $model->name;

$this->params['breadcrumbs'][] = $this->title;
?>
<div class="contacts-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Редактировать', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Удалить', ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Вы хотите удалить этот контакт?',
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
             'name',
            'surname',
            'patronymic',
        ],

    ]) ?>

    <div class="panel panel-default">
        <div class="panel-heading"><h4><i class="glyphicon glyphicon-earphone"></i> Номера телефонов</h4></div>
        <div class="panel-body">
            <div class="container-items"><!-- widgetContainer -->
                <?php foreach ($numbers as $i => $number): ?>
                <div class="item panel panel-default"><!-- widgetBody -->
                    <div class="panel-heading">
                        <h3 class="panel-title pull-left">Номер <?=($i+1)?>:</h3>
                        <div class="clearfix"></div>
                    </div>
                    <div class="panel-body">
                        <?=$number['number_val'];?>
                    </div>
                </div>
            </div>
        </div>
    </div>
                <?php endforeach; ?>
</div>
