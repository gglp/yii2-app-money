<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\widgets\Pjax;
use yii\jui\DatePicker;
use kartik\select2\Select2;

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

    <?=
    $form->field($model, 'tags')->widget(Select2::classname(), [
        'data' => $model->tagList,
        'language' => 'ru',
        'options' => [
            'placeholder' => 'Теги ...',
            'multiple' => true
        ],
        'pluginOptions' => [
            'allowClear' => true,
            'tags' => true  //включает добавление новых тегов
        ],
    ]);
    ?>

    <?= $form->field($model, 'comment')->textarea(['rows' => 2]) ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Создать' : 'Сохранить', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

    <?php Pjax::end() ?>

</div>
