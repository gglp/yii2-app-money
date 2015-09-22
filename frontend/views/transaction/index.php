<?php

use yii\grid\GridView;
use yii\widgets\Pjax;
//use yii\bootstrap\Collapse;
use yii\helpers\ArrayHelper;
use common\models\gridview\SumColumn;

/* @var $this yii\web\View */
/* @var $searchModel frontend\models\TransactionSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Транзакции';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="transaction-index">
    <div class="box box-default collapsed-box">
        <div class="box-header with-border">
            <h3 class="box-title">Добавить транзакцию</h3>
            <div class="box-tools pull-right">
                <button class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-plus"></i></button>
            </div><!-- /.box-tools -->
        </div><!-- /.box-header -->
        <div class="box-body">
            <?=
            $this->render('_form', [
                'model' => $model,
            ])
            ?>
        </div><!-- /.box-body -->
    </div><!-- /.box -->
    <div class="box box-default collapsed-box">
        <div class="box-header with-border">
            <h3 class="box-title">Фильтр транзакций</h3>
            <div class="box-tools pull-right">
                <button class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-plus"></i></button>
            </div><!-- /.box-tools -->
        </div><!-- /.box-header -->
        <div class="box-body">
            <?= $this->render('_search', ['model' => $searchModel]); ?>
        </div><!-- /.box-body -->
    </div><!-- /.box -->
    
    <div class="box">
        <div class="box-body">

            <?php Pjax::begin(['id' => 'transactions']) ?>

            <?=
            GridView::widget([
                'dataProvider' => $dataProvider,
//                'filterModel' => $searchModel,
                'columns' => [
                    ['class' => 'yii\grid\SerialColumn'],
                    //'id',
                    'transaction_date',
                    [
                        'class' => SumColumn::className(),
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
                'showFooter' => true
            ]);
            ?>

            <?php Pjax::end() ?>
        </div><!-- /.box-body -->
    </div><!-- /.box -->

</div>
