<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;
use yii\bootstrap\Collapse;

/* @var $this yii\web\View */
/* @var $searchModel frontend\models\TransactionSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Транзакции';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="transaction-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <?php
    echo Collapse::widget([
        'items' => [
            [
                'label' => 'Добавить транзакцию',
                'content' => $this->render('_form', [
                    'model' => $model,
                ]),
                'contentOptions' => [],
                'options' => []
            ]
        ]
    ]);
    ?>

    <?php Pjax::begin(['id' => 'transactions']) ?>

    <?=
    GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
            //'id',
            'transaction_date',
            'amount',
            [
                'label' => 'Валюта',
                'value' => 'currencyShort'
            ],
            [
                'label' => 'Счёт',
                'value' => 'accountName'
            ],
            'comment:ntext',
            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]);
    ?>

    <?php Pjax::end() ?>

</div>
