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
    <div class="box box-default collapsed-box">
        <div class="box-header with-border">
            <h3 class="box-title">Добавить счёт</h3>
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

    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <div class="box">
        <div class="box-body">

            <?php
            Pjax::begin(['id' => 'accounts']);

            echo GridView::widget([
                'dataProvider' => $dataProvider,
                'filterModel' => $searchModel,
                'columns' => [
                    [
                        'class' => 'yii\grid\SerialColumn'
                    ],
                    //'id',
                    'account_name',
                    [
                        'attribute' => 'account_type_id',
                        'label' => 'Тип счёта',
                        'value' => 'accountTypeName',
                        'footer' => 'Итого:',
                        'filter' => $model->accountTypeList
                    ],
                    [
                        'class' => SumColumn::className(),
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

            Pjax::end()
            ?>
        </div><!-- /.box-body -->
    </div><!-- /.box -->

</div>
