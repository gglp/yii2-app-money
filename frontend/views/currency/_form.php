<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\widgets\Pjax;

/* @var $this yii\web\View */
/* @var $model frontend\models\Currency */
/* @var $form yii\widgets\ActiveForm */
?>

<?php
$this->registerJs(
        '$("document").ready(function(){
            $("#new_currency").on("pjax:end", function() {
            $.pjax.reload({container:"#currencies"});  //Reload GridView
        });
    });'
);
?>

<div class="currency-form">

    <?php Pjax::begin(['id' => 'new_currency']) ?>

    <?php $form = ActiveForm::begin(['options' => ['data-pjax' => TRUE]]); ?>

    <?= $form->field($model, 'currency_name')->textInput(['maxlength' => 45]) ?>

    <?= $form->field($model, 'currency_short')->textInput(['maxlength' => 4]) ?>

    <?= $form->field($model, 'comment')->textarea(['rows' => 6]) ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Создать' : 'Сохранить', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

    <?php Pjax::end() ?>

</div>
