<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\widgets\Pjax;
use yii\jui\DatePicker;
use kartik\select2\Select2;

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

    <?php
    $form = ActiveForm::begin([
                'action' => ['index'],
                'method' => 'get',
                'options' => [
                    'data-pjax' => true
                ]
    ]);
    ?>

    <?= Html::activeHiddenInput($model, 'type', ['value' => $transactionType]) ?>

    <?=
    $form->field($model, 'transaction_date')->widget(DatePicker::className(), [
        'language' => 'ru',
        'dateFormat' => 'yyyy-MM-dd',
        'options' => [
            'class' => 'form-control',
        ]
    ])
    ?>

    <?= $form->field($model, 'amount') ?>

    <?=
    $form->field($model, 'currency_id')->widget(Select2::classname(), [
        'data' => $model->currencyList,
        'theme' => Select2::THEME_DEFAULT,
        'language' => 'ru',
        'options' => [
            'multiple' => true
        ],
        'pluginOptions' => [
            'allowClear' => true
        ],
    ]);
    ?>

    <?=
    $form->field($model, 'account_id')->widget(Select2::classname(), [
        'data' => $model->accountList,
        'theme' => Select2::THEME_DEFAULT,
        'language' => 'ru',
        'options' => [
            'multiple' => true
        ],
        'pluginOptions' => [
            'allowClear' => true
        ],
    ]);
    ?>

    <?=
    $form->field($model, 'tags')->widget(Select2::classname(), [
        'data' => $model->tagList,
        'theme' => Select2::THEME_DEFAULT,
        'language' => 'ru',
        'options' => [
            'multiple' => true
        ],
        'pluginOptions' => [
            'allowClear' => true
        ],
    ]);
    ?>


    <?= $form->field($model, 'comment') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

    <?php Pjax::end() ?>

</div>
