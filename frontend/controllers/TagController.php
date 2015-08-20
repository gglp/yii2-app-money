<?php

namespace frontend\controllers;

use Yii;
use frontend\models\Tag;
use frontend\models\TagSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * TagController implements the CRUD actions for Tag model.
 */
class TagController extends Controller {

    public function behaviors() {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['get'],
                ],
            ],
        ];
    }

    /**
     * Отображает дерево тегов
     * @return mixed
     */
    public function actionTree() {
        return $this->render('tree', [
                    'model' => new Tag(),
        ]);
    }

    public function actionChilds($id) {
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        if ($id === '#') {
            $model = new Tag();
            return $model->roots;
        } else {
            $model = $this->findModel($id);
            return $model->nodes;
        }
    }

    /**
     * Создаём корневой узел
     * @return mixed
     */
    public function actionRoot() {
        $root = new Tag(['name' => 'Новый корневой узел']);

        $root->makeRoot();

        return $this->redirect(['tree']);
    }

    /**
     * Создаём подчинённый узел
     * @param integer $id
     * @return mixed
     */
    public function actionAppend($id) {
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        $parent = $this->findModel($id);

        $node = new Tag(['name' => Yii::$app->request->get('name')]);

        $node->appendTo($parent);

        return array('id' => $node->id);
    }

    /**
     * Переименовываем узел
     * @param integer $id
     * @return mixed
     */
    public function actionRename($id) {
        $model = $this->findModel($id);

        $model->setAttributes(Yii::$app->request->get());

        if ($model->save()) {
            return true;
        } else {
            return 'Ошибка изменения названия узла'; // TODO: сделать правильно.
        }
    }

    /**
     * Удаляет выбранный узел вместе с потомками
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id) {
        $this->findModel($id)->deleteWithChildren();

        return true;
    }

    /**
     * Перемещаем узел
     * Перед перемещением необходимо выяснить:
     * 1. Какую операцию перемещения использовать (добавить в начало / добавить в конец или добавить перед / добавить после)
     * 2.а В первых двух случаях взять ID родителя
     * 2.б Во вторых двух случаях выяснить ID узла, перед / после которого вставлять запись
     * @param integer $node_id ID перемещаемого узла
     * @param integer $parent_id ID родителя, в который перемещается узел
     * @param integer $position Позиция по счёту, в которую вставляется узел
     * @return boolean Успешно ли завершена операция перемещения
     */
    public function actionMove($node_id, $parent_id, $position) {
        $node = $this->findModel((int) $node_id);
        if ($parent_id === '#') {
            if (!$node->isRoot()) {
                $node->makeRoot();
                return 'Узел добавлен в качестве корневого узла';
            }
        } else {
            $parent = $this->findModel((int) $parent_id);
            if ((int) $position === 0) {
                $node->prependTo($parent);
                return 'Узел добавлен в начало';
            } else {
                $children = $parent->children(1)->asArray()->all();
                if ($position < count($children)) {
                    $otherNode = $this->findModel($children[$position]['id']);
                    $node->insertAfter($otherNode);
                } else {
                    $node->appendTo($parent);
                    return "Узел добавлен в конец";
                }
                return "Узел добавлен в позицию {$position}";
            }
        }
    }

    /**
     * Finds the Tag model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Tag the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id) {
        if (($model = Tag::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

}
