<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yiidreamteam\jstree\JsTree;
use yii\web\JsExpression;

/* @var $this yii\web\View */
/* @var $model frontend\models\Tag */

$this->title = 'Дерево тегов';
//$this->params['breadcrumbs'][] = ['label' => 'Tags', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
$containerId = 'tags-tree';
?>
<div class="tag-tree">
    <p>
        <?= Html::a('Добавить корень', ['root'], ['class' => 'btn btn-primary']) ?>
    </p>

    <?=
// Пример по работе с конфигом виджета jsTree: https://github.com/sganz/basic/blob/630a7a39afbf98b3a138614bb793b3859fcb7575/views/site/tree-edit.php
    JsTree::widget([
        'containerOptions' => [
            'class' => 'data-tree',
            'id' => $containerId
        ],
        'jsOptions' => [
            'core' => [
                'multiple' => false,
                'data' => [
                    'url' => Url::to(['tag/childs']),
                    'data' => new JsExpression('function (node) {return {\'id\': node.id};}'),
                ],
                'check_callback' => true
            /*            'themes' => [
              'name' => 'foobar',
              'url' => "/themes/foobar/js/jstree3/style.css",
              'dots' => true,
              'icons' => false,
              ]
             */
            ],
            'plugins' => ['dnd', 'contextmenu', 'state']
        ],
    ])
    ?>

    <?php
    $jsFunctions = [
        'rename_node' => Url::to(['tag/rename']),
        'delete_node' => Url::to(['tag/delete']),
        'create_node' => Url::to(['tag/append']),
        'move_node' => Url::to(['tag/move'])
    ];

    $js = <<< JS

                $('#{$containerId}')
                    .on('rename_node.jstree', function (e, data) {
                        $.get('{$jsFunctions['rename_node']}', { 'id' : data.node.id, 'name' : data.text })
                            .fail(function () {
                                data.instance.refresh();
                            });
                    })
                    .on('delete_node.jstree', function (e, data) {
                        $.get('{$jsFunctions['delete_node']}', { 'id' : data.node.id })
                            .fail(function () {
                                data.instance.refresh();
                            });
                    })
                    .on('create_node.jstree', function (e, data) {
                        $.get('{$jsFunctions['create_node']}', { 'id' : data.node.parent, 'position' : data.position, 'name' : data.node.text })
                            .done(function (d) {
                                data.instance.set_id(data.node, d.id);
                            })
                            .fail(function () {
                                data.instance.refresh();
                            });
                    })
                    .on('move_node.jstree', function (e, data) {
                        $.get('{$jsFunctions['move_node']}', { 'node_id' : data.node.id, 'parent_id' : data.parent, 'position' : data.position })
                            .fail(function () {
                                data.instance.refresh();
                            });
                    })
                ;
JS;
                        
    $this->registerJs($js);
    ?>

</div>
