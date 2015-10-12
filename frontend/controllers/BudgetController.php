<?php

namespace frontend\controllers;

use Yii;
use frontend\models\Budget;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;

/**
 * TransactionController implements the CRUD actions for Transaction model.
 */
class BudgetController extends Controller {

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

    public function actionIndex(){
        $arrayModel = new Budget();
        $arrayOfDataProviders = $arrayModel->report();
//        $arrayDataProviderMonth = $arrayModel->reportArray();
        
        return $this->render('index',[
            'model' => $arrayModel,
            'arrayOfDataProviders' => $arrayOfDataProviders,
//            'dataProviderMonth' => $arrayDataProviderMonth
        ]);
    }
}
