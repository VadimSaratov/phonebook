<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use wbraganca\dynamicform\DynamicFormWidget;

/* @var $this yii\web\View */
/* @var $model app\models\Contacts */
/* @var $modelsNumbers app\models\Numbers */
/* @var $form yii\widgets\ActiveForm */


$js = '
jQuery(".dynamicform_wrapper").on("afterInsert", function(e, item) {
    jQuery(".dynamicform_wrapper h3.pull-left").each(function(index) {
        jQuery(this).html("Номер " + (index + 1) + ":")
    });
});

jQuery(".dynamicform_wrapper").on("afterDelete", function(e) {
    jQuery(".dynamicform_wrapper h3.pull-left").each(function(index) {
        jQuery(this).html("Номер " + (index + 1) + ":")
    });
});
';

$this->registerJs($js);

?>

<div class="contacts-form">

    <?php $form = ActiveForm::begin(['id' => 'dynamic-form']); ?>

    <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'surname')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'patronymic')->textInput(['maxlength' => true]) ?>

    <div class="panel panel-default">
        <div class="panel-heading"><h4><i class="glyphicon glyphicon-envelope"></i> Номера телефона</h4></div>
        <div class="panel-body">
			<?php DynamicFormWidget::begin([
				'widgetContainer' => 'dynamicform_wrapper', // required: only alphanumeric characters plus "_" [A-Za-z0-9_]
				'widgetBody' => '.container-items', // required: css class selector
				'widgetItem' => '.item', // required: css class
				'limit' => 10, // the maximum times, an element can be cloned (default 999)
				'min' => 1, // 0 or 1 (default 1)
				'insertButton' => '.add-item', // css class
				'deleteButton' => '.remove-item', // css class
				'model' => $modelsNumbers[0],
				'formId' => 'dynamic-form',
				'formFields' => [
					'number_val',
				],
			]); ?>

            <div class="container-items"><!-- widgetContainer -->
	            <?php foreach ($modelsNumbers as $i => $modelNumbers): ?>
                    <div class="item panel panel-default"><!-- widgetBody -->
                        <div class="panel-heading">
                            <h3 class="panel-title pull-left">Номер <?=($i+1)?>:</h3>
                            <div class="pull-right">
                                <button type="button" class="add-item btn btn-success btn-xs"><i class="glyphicon glyphicon-plus"></i></button>
                                <button type="button" class="remove-item btn btn-danger btn-xs"><i class="glyphicon glyphicon-minus"></i></button>
                            </div>
                            <div class="clearfix"></div>
                        </div>
                        <div class="panel-body">
							<?php
							// necessary for update action.
							if (! $modelNumbers->isNewRecord) {
								echo Html::activeHiddenInput($modelNumbers, "[{$i}]id");
							}
							?>
							<?= $form->field($modelNumbers, "[{$i}]number_val")->textInput(['maxlength' => true]) ?>
                        </div>
                    </div>
				<?php endforeach; ?>
            </div>
			<?php DynamicFormWidget::end(); ?>
        </div>
    </div>


    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
