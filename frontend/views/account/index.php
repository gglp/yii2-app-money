<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;
use yii\bootstrap\Collapse;
use common\models\gridview\SumColumn;

/* @var $this yii\web\View */
/* @var $searchModel frontend\models\AccountSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Счета';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="account-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <?php
    echo Collapse::widget([
        'items' => [
            [
                'label' => 'Добавить счёт',
                'content' => $this->render('_form', [
                    'model' => $model,
                ]),
                'contentOptions' => [],
                'options' => []
            ]
        ]
    ]);
    ?>

    <?php Pjax::begin(['id' => 'accounts']) ?>

    <?=
    GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            [
                'class' => 'yii\grid\SerialColumn'
            ],
            //'id',
            'account_name',
            [
                'label' => 'Тип счёта',
                'value' => 'accountTypeName',
                'footer' => 'Итого:'
            ],
            [
                'attribute' => 'overdraft',
                'format' => ['decimal', 2],
                'contentOptions' => [
                    'style' => 'white-space: nowrap; text-align: right;'
                ]
            ],
            [
                'class' => SumColumn::className(),
                'attribute' => 'account_balance',
                'format' => ['decimal', 2],
                'contentOptions' => [
                    'style' => 'white-space: nowrap; text-align: right;'
                ]
            ],
            [
                'class' => SumColumn::className(),
                'attribute' => 'account_control_amount',
                'format' => ['decimal', 2],
                'contentOptions' => [
                    'style' => 'white-space: nowrap; text-align: right;'
                ]
            ],
            'comment:ntext',
            [
                'class' => 'yii\grid\ActionColumn',
                'contentOptions' => ['style' => 'white-space: nowrap; text-align: center; letter-spacing: 0.1em; max-width: 7em;']
            ],
        ],
        'showFooter' => true,
    ]);
    ?>

    <?php Pjax::end() ?>

</div>
