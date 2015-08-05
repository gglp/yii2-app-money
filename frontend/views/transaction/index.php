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
            [
                'attribute' => 'amount',
                'format' => ['decimal', 2],
                'contentOptions' => [
                    'style' => 'white-space: nowrap; text-align: right;'
                ]
            ],
            [
                'label' => 'Валюта',
                'value' => 'currencyShort'
            ],
            [
                'label' => 'Счёт',
                'value' => 'accountName'
            ],
            'comment:ntext',
            [
                'label' => 'Теги',
                'value' => function($data) {
                    $tags = [];
                    foreach ($data->getTags()->all() as $tag) {
                        $tags[] = $tag['name'];
                    };
                    return implode($tags, '; ');
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
