<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\widgets\Pjax;

/* @var $this yii\web\View */
/* @var $model frontend\models\AccountType */
/* @var $form yii\widgets\ActiveForm */
?>

<?php
$this->registerJs(
        '$("document").ready(function(){
            $("#new_account-type").on("pjax:end", function() {
            $.pjax.reload({container:"#account-types"});  //Reload GridView
        });
    });'
);
?>

<div class="account-type-form">

    <?php Pjax::begin(['id' => 'new_account-type']) ?>

    <?php $form = ActiveForm::begin(['options' => ['data-pjax' => TRUE]]); ?>

    <?= $form->field($model, 'account_type_name')->textInput(['maxlength' => 45]) ?>

    <?= $form->field($model, 'comment')->textarea(['rows' => 6]) ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

    <?php Pjax::end() ?>

</div>
