<?php
use yii\helpers\Url;
use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use yii\widgets\ActiveForm;
?>

<div class="user-sesion-form">

    <?php $form = ActiveForm::begin(['options' => ['enctype' => 'multipart/form-data']]); ?>

    <div class="row">
        <div class="col-md-6">
            <?= $form->field($model, 'start')->textInput() ?>
        </div>
        <div class="col-md-6">
            <?php if($model->stop && $model->stop != '0000-00-00 00:00:00') { ?>
                <?= $form->field($model, 'stop')->textInput() ?>
            <?php } ?>
        </div>
    </div>

    <div class="row form-group" style="text-align: center; padding-top: 30px;">
        <?= Html::submitButton($model->isNewRecord ? 'Создать' : 'Сохранить', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
