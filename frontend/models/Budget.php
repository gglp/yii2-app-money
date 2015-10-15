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
        //Получим имеющиеся данные по транзакциям, просуммированные по тегам
        $dataSource = $this->reportData();
        //Получим для каждого дерева сумму транзакций, не связанных ни с корнем ни с потомками
        $balanceSource = $this->reportBalance();
        //Получим сальдо на начало периода
        $saldoSource = $this->reportSaldo($reportDate = '2015-04-01');
            
        $data = array();

        //Приведём $data к нужному виду: $data['tagid']['year']['month']
        foreach ($dataSource AS $item) {
            $data[$item['tagid']][$item['year']][$item['month']] = $item['summa'];
        }
        
        //Дополним $data данными о полном обороте за месяц
        foreach ($balanceSource AS $item) {
            $data['total'][$item['year']][$item['month']] = $item['summa'];
        }

        //Массив для разных провайдеров = количеству корней
        $arrayOfDataProviders = array();

        foreach ($roots as $tree) {
            $lines = 0;
            $matrix = array();
            $saldo = $saldoSource + 0;
            //Заполним матрицу нулями
            //Надо получить 1-ое число месяца 6 месяцев назад от текущего
            //Надо получить последнее число месяца 6 месяцев вперёд от текущего
            //Пока возьмём волюнтаристски: 4 и 12
            $matrix[$lines]['tag'] = "Сальдо";
            $matrix[$lines+1]['tag'] = "Баланс";
            $matrix[$lines+2]['tag'] = "Распределено по списку \"$tree->name\"";
            for ($i = 4; $i <= 12; $i++) {
                $saldo += isset($data['total']['2015'][$i]) ? $data['total']['2015'][$i] : 0;
                $matrix[$lines][$i] = $saldo;
                //Проверяем наличие оборота за месяцу в целом, иначе 0
                $matrix[$lines+1][$i] = isset($data['total']['2015'][$i]) ? $data['total']['2015'][$i] : 0;
                //Проверяем наличие оборота за месяц по тегу, иначе 0
                $matrix[$lines+2][$i] = isset($data[$tree->id]['2015'][$i]) ? $data[$tree->id]['2015'][$i] : 0;
            }

            $lines += 3;

            //Получим потомков
            $treeNodes = $tree->children()->asArray()->all();
            foreach ($treeNodes as $node) {
                //Название тега вместе с его уровнем в иерархии
                $matrix[$lines]['tag'] = str_repeat(' - ', $node['depth']).$node['name'];
                for ($i = 4; $i <= 12; $i++) {
                    $matrix[$lines][$i] = isset($data[$node['id']]['2015'][$i]) ? $data[$node['id']]['2015'][$i] : 0;
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

    private function reportBalance() {

        $query = new Query;

        $data = $query
                ->select([
                    'year' => 'YEAR({{%transaction}}.[[transaction_date]])',
                    'month' => 'MONTH({{%transaction}}.[[transaction_date]])',
                    'summa' => 'SUM({{%transaction}}.[[amount]])'
                ])
                ->from(['{{%transaction}}'])
                ->groupBy(['year', 'month'])
                ->orderBy('year, month')
                ->all()
        ;

        return $data;
    }
    
    
    /**
     * Функция, вычисляющая сальдо на начало запрошенного периода
     * @param string $reportDate Дата в формате 2015-10-15, показывает до какой даты будет считаться сальдо
     * @return string сумма сальдо на начало периода
     */
    private function reportSaldo($reportDate){
        $saldo = Transaction::find()
                ->andWhere(['<', '[[transaction_date]]', $reportDate])
                ->sum('[[amount]]')
                ;
        
        return $saldo;
    }
}
