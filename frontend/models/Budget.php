<?php

namespace frontend\models;

use Yii;
use yii\helpers\ArrayHelper;
use frontend\models\Tag;
use frontend\models\Transaction;
use yii\db\Query;
use yii\db\Expression;
use yii\data\ArrayDataProvider;

class Budget extends \yii\db\ActiveRecord {

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'id' => 'ID',
            'transaction_date' => 'Дата',
            'amount' => 'Сумма',
            'currency_id' => 'Валюта',
            'account_id' => 'Счёт',
            'comment' => 'Комментарий',
            'tags' => 'Теги',
        ];
    }

    public function report() {
        $roots = Tag::find()->roots()->all();
        
        foreach ($roots as $tree){
            $treeNodes = $tree->children()->asArray()->all();
            //Заполним матрицу нулями
            //Надо получить 1-ое число месяца 6 месяцев назад от текущего
            //Надо получить последнее число месяца 6 месяцев вперёд от текущего
            //Пока возьмём волюнтаристски: 4 и 12
            $matrix = array();
            for ($i = 4; $i <= 12; $i++ ){
                $matrix[]['tag'] = $treeNodes->name;
            }
            var_dump($tree->id);
        }
        
        $query = new Query;

        $queryChildren = (new Query())
                ->select('id')
                ->from(['children' => '{{%tag}}'])
                ->andWhere(['=', 'children.tree', new Expression('{{parent}}.[[tree]]')])
                ->andWhere(['>=', 'children.lft', new Expression('{{parent}}.[[lft]]')])
                ->andWhere(['<=', 'children.rgt', new Expression('{{parent}}.[[rgt]]')])
        ;

        $data = $query
                ->select([
                    'tagname' => '{{parent}}.[[name]]',
                    '{{parent}}.[[depth]]',
                    'year' => 'YEAR({{%transaction}}.[[transaction_date]])',
                    'month' => 'MONTH({{%transaction}}.[[transaction_date]])',
                    'summa' => 'SUM({{%transaction}}.[[amount]])'
                ])
                ->from(['parent' => '{{%tag}}'])
                ->leftJoin('{{%transaction_tag}}', ['in', '{{%transaction_tag}}.[[tag_id]]', $queryChildren])
                ->leftJoin('{{%transaction}}', ['=', '{{%transaction_tag}}.[[transaction_id]]', new Expression('{{%transaction}}.[[id]]')])
                ->andWhere(['{{parent}}.[[tree]]' => 19])
                ->groupBy(['year', 'month', 'parent.lft'])
                ->orderBy('year, month, parent.lft')
                ->all()
        ;
        $arrayDataProvider = new ArrayDataProvider([
            'allModels' => $data,
            'sort' => [
                'attributes' => ['year, month, parent.lft'],
            ],
            'pagination' => [
                'pageSize' => 1000,
            ],
        ]);
        
        return $arrayDataProvider;
    }
    
    private function getSumma(){
        return;
    }
}
