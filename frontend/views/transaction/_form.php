<?php

use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use yii\widgets\ActiveForm;
use yii\widgets\Pjax;
use yii\jui\DatePicker;

/* @var $this yii\web\View */
/* @var $model frontend\models\Transaction */
/* @var $form yii\widgets\ActiveForm */
?>

<?php
$this->registerJs(
        '$("document").ready(function(){
            $("#new_transaction").on("pjax:end", function() {
            $.pjax.reload({container:"#transactions"});  //Reload GridView
        });
    });'
);
?>

<div class="transaction-form">

    <?php Pjax::begin(['id' => 'new_transaction']) ?>

    <?php $form = ActiveForm::begin(['options' => ['data-pjax' => TRUE]]); ?>

    <?=
    $form->field($model, 'transaction_date')->widget(DatePicker::className(), [
        'language' => 'ru',
        'dateFormat' => 'yyyy-MM-dd',
        'options' => [
            'class' => 'form-control',
        ]
    ])
    ?> 

    <?php // $form->field($model, 'transaction_date')->textInput()  ?>

    <?= $form->field($model, 'amount')->textInput(['maxlength' => 10]) ?>

    <?= $form->field($model, 'currency_id')->dropDownList($model->currencyList) ?>

    <?= $form->field($model, 'account_id')->dropDownList($model->accountList) ?>

    <?= $form->field($model, 'comment')->textarea(['rows' => 6]) ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Создать' : 'Сохранить', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

    <?php Pjax::end() ?>

</div>
