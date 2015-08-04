<?php

namespace frontend\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use frontend\models\Account;

/**
 * AccountSearch represents the model behind the search form about `frontend\models\Account`.
 */
class AccountSearch extends Account {

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['id', 'account_type_id'], 'integer'],
            [['account_name', 'comment'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function scenarios() {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    /**
     * Creates data provider instance with search query applied
     *
     * @param array $params
     *
     * @return ActiveDataProvider
     */
    public function search($params) {
        $query = Account::find()
                ->select([
                    '{{%account}}.*',
                    'SUM({{%transaction}}.amount) AS account_balance',
                    'SUM({{%transaction}}.amount) - {{%account}}.overdraft AS account_control_amount',
                ])
                ->joinWith('transactions')
                ->groupBy('{{%account}}.id');

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        $query->andFilterWhere([
            'id' => $this->id,
            'account_type_id' => $this->account_type_id,
        ]);

        $query->andFilterWhere(['like', 'account_name', $this->account_name])
                ->andFilterWhere(['like', 'comment', $this->comment]);

        return $dataProvider;
    }

}
