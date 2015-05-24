<?php

use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use yii\widgets\ActiveForm;
use yii\widgets\Pjax;

/* @var $this yii\web\View */
/* @var $model frontend\models\Account */
/* @var $form yii\widgets\ActiveForm */
?>

<?php
$this->registerJs(
        '$("document").ready(function(){
            $("#new_account").on("pjax:end", function() {
            $.pjax.reload({container:"#accounts"});  //Reload GridView
        });
    });'
);
?>

<div class="account-form">

    <?php Pjax::begin(['id' => 'new_account']) ?>

    <?php $form = ActiveForm::begin(['options' => ['data-pjax' => TRUE]]); ?>

    <?= $form->field($model, 'account_name')->textInput(['maxlength' => 45]) ?>

    <?= $form->field($model, 'account_type_id')->dropDownList($model->accountTypeList) ?>

    <?= $form->field($model, 'comment')->textarea(['rows' => 6]) ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

    <?php Pjax::end() ?>

</div>
