<?php

use yii\grid\GridView;
use yii\jui\DatePicker;
use yii\widgets\Pjax;
use yii\bootstrap\Collapse;
use yii\helpers\ArrayHelper;

/* @var $this yii\web\View */
/* @var $searchModel frontend\models\TransactionSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Транзакции';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="transaction-index">
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
            [
                'attribute' => 'transaction_date',
//                'format' => ['date', 'php:d.m.Y'],
                'filter' => DatePicker::widget(
                        [
                            'model' => $searchModel,
                            'attribute' => 'transaction_date',
                            'options' => [
                                'class' => 'form-control'
                            ],
//                            'clientOptions' => [
//                                'dateFormat' => 'yyyy-MM-dd',
//                            ]
                        ]
                )
            ],
            [
                'attribute' => 'amount',
                'format' => ['decimal', 2],
                'contentOptions' => [
                    'style' => 'white-space: nowrap; text-align: right;'
                ]
            ],
            [
                'attribute' => 'currency_id',
                'label' => 'Валюта',
                'value' => 'currency.currency_short',
                'filter' => $model->currencyList
            ],
            [
                'attribute' => 'account_id',
                'label' => 'Счёт',
                'value' => 'account.account_name',
                'filter' => $model->accountList
            ],
            'comment:ntext',
            [
                'label' => 'Теги',
                'value' => function($model) {
                    return implode('; ', ArrayHelper::map($model->tags, 'id', 'name'));
                }
            ],
            [
                'class' => 'yii\grid\ActionColumn',
                'contentOptions' => ['style' => 'white-space: nowrap; text-align: center; letter-spacing: 0.1em; max-width: 7em;']
            ],
        ],
    ]);
    ?>

    <?php Pjax::end() ?>

</div>
