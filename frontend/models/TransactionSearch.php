<?php

namespace frontend\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use frontend\models\Transaction;

/**
 * TransactionSearch represents the model behind the search form about `frontend\models\Transaction`.
 */
class TransactionSearch extends Transaction
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'currency_id', 'account_id'], 'integer'],
            [['transaction_date', 'comment'], 'safe'],
            [['amount'], 'number'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function scenarios()
    {
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
    public function search($params)
    {
        $query = Transaction::find()
                ->with('tags', 'currency', 'account');

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
			'sort' => [
				'defaultOrder' => [
					'transaction_date'=>SORT_DESC,
					'id'=>SORT_DESC,
				]
			]
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        $query->andFilterWhere([
            'id' => $this->id,
            'transaction_date' => $this->transaction_date,
            'amount' => $this->amount,
            'currency_id' => $this->currency_id,
            'account_id' => $this->account_id,
        ]);

        $query->andFilterWhere(['like', 'comment', $this->comment]);

        return $dataProvider;
    }
}
