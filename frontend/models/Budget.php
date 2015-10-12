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
        $dataSource = $this->reportData();
        $data = array();

        //Приведём $data к нужному виду: $data['tagid']['year']['month']
        foreach ($dataSource AS $item) {
            $data [$item['tagid']][$item['year']][$item['month']] = $item['summa'];
        }

        $lines = 0;
        //Массив для разных провайдеров = количеству корней
        $arrayOfDataProviders = array();

        foreach ($roots as $tree) {
            $matrix = array();
            //Заполним матрицу нулями
            //Надо получить 1-ое число месяца 6 месяцев назад от текущего
            //Надо получить последнее число месяца 6 месяцев вперёд от текущего
            //Пока возьмём волюнтаристски: 4 и 12
            $matrix[$lines]['tag'] = "Итого по списку \"$tree->name\"";
            for ($i = 4; $i <= 12; $i++) {
                //Если есть значение в суммах, подставляем его, иначе 0
                if (isset($data[$tree->id]['2015'][$i])) {
                    $matrix[$lines][$i] = $data[$tree->id]['2015'][$i];
                } else {
                    $matrix[$lines][$i] = 0;
                }
            }

            $lines++;

            //Получим потомков
            $treeNodes = $tree->children()->asArray()->all();
            foreach ($treeNodes as $node) {
                $matrix[$lines]['tag'] = str_repeat(' - ', $node['depth']).$node['name'];
                for ($i = 4; $i <= 12; $i++) {
                    if (isset($data[$node['id']]['2015'][$i])) {
                        $matrix[$lines][$i] = $data[$node['id']]['2015'][$i];
                    } else {
                        $matrix[$lines][$i] = 0;
                    }
                }
                $lines++;
            }
            //Зададим провайдера данных для GridView
            $arrayOfDataProviders[$tree->id]['dp'] = new ArrayDataProvider([
                'allModels' => $matrix,
                'pagination' => [
                    'pageSize' => 1000,
                ],
            ]);
            //Зададим название для блока данных для GridView
            $arrayOfDataProviders[$tree->id]['title'] = $tree->name;
        }

        return $arrayOfDataProviders;
    }

    private function reportData() {

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
                    'tagid' => '{{parent}}.[[id]]',
                    'tagtree' => '{{parent}}.[[tree]]',
                    'tagname' => '{{parent}}.[[name]]',
                    '{{parent}}.[[depth]]',
                    'year' => 'YEAR({{%transaction}}.[[transaction_date]])',
                    'month' => 'MONTH({{%transaction}}.[[transaction_date]])',
                    'summa' => 'SUM({{%transaction}}.[[amount]])'
                ])
                ->from(['parent' => '{{%tag}}'])
                ->innerJoin('{{%transaction_tag}}', ['in', '{{%transaction_tag}}.[[tag_id]]', $queryChildren])
                ->innerJoin('{{%transaction}}', ['=', '{{%transaction_tag}}.[[transaction_id]]', new Expression('{{%transaction}}.[[id]]')])
//                ->andWhere(['{{parent}}.[[tree]]' => 19])
                ->groupBy(['year', 'month', 'parent.tree', 'parent.lft'])
                ->orderBy('year, month, parent.tree, parent.lft')
                ->all()
        ;

        return $data;
    }

    public function reportOld() {
        $arrayDataProvider = new ArrayDataProvider([
            'allModels' => $this->reportData(),
            'sort' => [
                'attributes' => ['year, month, parent.lft'],
            ],
            'pagination' => [
                'pageSize' => 1000,
            ],
        ]);

        return $arrayDataProvider;
    }

}
