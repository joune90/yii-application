<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model backend\models\UserUrl */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="user-url-form">

    <?php $form = ActiveForm::begin(); ?>



    <?= $form->field($model, 'url_location')->textInput(['maxlength' => true])->label('输入推广链接地址移动端') ?>

    <?= $form->field($model, 'url_location_pc')->textInput(['maxlength' => true])->label('输入推广链接地址pc端，如果不输入默认和移动端一致') ?>


    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
