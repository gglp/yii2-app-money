<?php

use yii\grid\GridView;
use yii\widgets\Pjax;

/* @var $this yii\web\View */
/* @var $searchModel frontend\models\TransactionSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Бюджет';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="budget-index">
    
<?php
    foreach ($arrayOfDataProviders AS $dataProvider) {
?>
    <div class="box box-default">
        <div class="box-header with-border">
            <h3 class="box-title"><?= $dataProvider['title'] ?></h3>
            <div class="box-tools pull-right">
                <button class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
            </div><!-- /.box-tools -->
        </div><!-- /.box-header -->
        <div class="box-body">
            <?=
            GridView::widget([
                'dataProvider' => $dataProvider['dp'],
                'summary' => '',
                'columns' => [
                    'tag',
                    [
                        'attribute' => '4',
                        'format' => ['decimal', 2],
                        'contentOptions' => [
                            'style' => 'white-space: nowrap; text-align: right;'
                        ]
                    ],
                    [
                        'attribute' => '5',
                        'format' => ['decimal', 2],
                        'contentOptions' => [
                            'style' => 'white-space: nowrap; text-align: right;'
                        ]
                    ],
                    [
                        'attribute' => '6',
                        'format' => ['decimal', 2],
                        'contentOptions' => [
                            'style' => 'white-space: nowrap; text-align: right;'
                        ]
                    ],
                    [
                        'attribute' => '7',
                        'format' => ['decimal', 2],
                        'contentOptions' => [
                            'style' => 'white-space: nowrap; text-align: right;'
                        ]
                    ],
                    [
                        'attribute' => '8',
                        'format' => ['decimal', 2],
                        'contentOptions' => [
                            'style' => 'white-space: nowrap; text-align: right;'
                        ]
                    ],
                    [
                        'attribute' => '9',
                        'format' => ['decimal', 2],
                        'contentOptions' => [
                            'style' => 'white-space: nowrap; text-align: right;'
                        ]
                    ],
                    [
                        'attribute' => '10',
                        'format' => ['decimal', 2],
                        'contentOptions' => [
                            'style' => 'white-space: nowrap; text-align: right;'
                        ]
                    ],
                    [
                        'attribute' => '11',
                        'format' => ['decimal', 2],
                        'contentOptions' => [
                            'style' => 'white-space: nowrap; text-align: right;'
                        ]
                    ],
                    [
                        'attribute' => '12',
                        'format' => ['decimal', 2],
                        'contentOptions' => [
                            'style' => 'white-space: nowrap; text-align: right;'
                        ]
                    ],
                    [
                        'class' => 'yii\grid\ActionColumn',
                        'contentOptions' => ['style' => 'white-space: nowrap; text-align: center; letter-spacing: 0.1em; max-width: 7em;']
                    ],
                ]
            ]);
            ?>
        </div><!-- /.box-body -->
    </div><!-- /.box -->
<?php
    }
?>
</div>
