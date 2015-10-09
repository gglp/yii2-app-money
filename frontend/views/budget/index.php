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
    <div class="box">
        <div class="box-body">
            <?=
            GridView::widget([
                'dataProvider' => $dataProvider,
                'columns' => [
                    'tagname',
                    'year',
                    'month',
                    'summa',
                    [
                        'class' => 'yii\grid\ActionColumn',
                        'contentOptions' => ['style' => 'white-space: nowrap; text-align: center; letter-spacing: 0.1em; max-width: 7em;']
                    ],
                ]
            ]);
            ?>
        </div><!-- /.box-body -->
    </div><!-- /.box -->

</div>
