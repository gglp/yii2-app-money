<?php

namespace frontend\controllers;

use Yii;
use frontend\models\Transaction;
use frontend\models\TransactionSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;

/**
 * TransactionController implements the CRUD actions for Transaction model.
 */
class TransactionController extends Controller {

    public function behaviors() {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['post'],
                ],
            ],
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
        ];
    }

    /**
     * Общий метод для отображения списка Транзакций
     * @param integer type Тип транзакции
     * @param string title Вид, который надо отобразить
     * @return mixed
     */
    public function index($viewParams = ['type' => 0, 'title' => 'Транзакции']) {
        $model = new Transaction();
        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            $model = new Transaction();
        }
        $searchModel = new TransactionSearch();
        $searchModel->type = $viewParams['type'];
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
                    'searchModel' => $searchModel,
                    'dataProvider' => $dataProvider,
                    'model' => $model,
                    'viewParams' => $viewParams,
        ]);
    }

    /**
     * Отображает список всех фактических Транзакций
     * @return mixed Отображает представление index с наложенным фильтром по типу транзакции
     */
    public function actionIndex() {
        $viewParams = ['type' => 0, 'title' => 'Транзакции'];
        return $this->index($viewParams);
    }

    /**
     * Отображает список всех плановых Транзакций
     * @return mixed
     */
    public function actionPlan() {
        $viewParams = ['type' => 1, 'title' => 'Плановые транзакции'];
        return $this->index($viewParams);
    }

    /**
     * Displays a single Transaction model.
     * @param string $id
     * @return mixed
     */
    public function actionView($id) {
        return $this->render('view', [
                    'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new Transaction model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate() {
        $model = new Transaction();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('create', [
                        'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing Transaction model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param string $id
     * @return mixed
     */
    public function actionUpdate($id) {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('update', [
                        'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing Transaction model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param string $id
     * @return mixed
     */
    public function actionDelete($id) {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the Transaction model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param string $id
     * @return Transaction the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id) {
        if (($model = Transaction::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

}
