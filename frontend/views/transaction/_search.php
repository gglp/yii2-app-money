<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\widgets\Pjax;
use yii\jui\DatePicker;


/* @var $this yii\web\View */
/* @var $model frontend\models\TransactionSearch */
/* @var $form yii\widgets\ActiveForm */
?>
<?php
$this->registerJs(
        '$("document").ready(function(){
            $("#filter_transaction").on("pjax:end", function() {
            $.pjax.reload({container:"#transactions"});  //Reload GridView
        });
    });'
);
?>

<div class="transaction-search">
    
    <?php Pjax::begin(['id' => 'filter_transaction']) ?>

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
        'options' => [
            'data-pjax' => true
        ]
    ]); ?>

    <?= $form->field($model, 'transaction_date')->widget(DatePicker::className(), [
        'language' => 'ru',
        'dateFormat' => 'yyyy-MM-dd',
        'options' => [
            'class' => 'form-control',
        ]
    ]) ?>

    <?= $form->field($model, 'amount') ?>

    <?= $form->field($model, 'currency_id')->dropDownList($model->currencyList) ?>

    <?= $form->field($model, 'account_id')->dropDownList($model->accountList) ?>

    <?= $form->field($model, 'comment') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>
    
    <?php Pjax::end() ?>

</div>
