<?php

namespace frontend\models;

use Yii;

/**
 * This is the model class for table "currency".
 *
 * @property string $id
 * @property string $currency_name
 * @property string $currency_short
 * @property string $comment
 *
 * @property Transaction[] $transactions
 */
class Currency extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%currency}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['currency_name', 'currency_short'], 'required'],
            [['comment'], 'string'],
            [['currency_name'], 'string', 'max' => 45],
            [['currency_short'], 'string', 'max' => 4]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'currency_name' => 'Название валюты',
            'currency_short' => 'Обозначение валюты',
            'comment' => 'Комментарий',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTransactions()
    {
        return $this->hasMany(Transaction::className(), ['currency_id' => 'id']);
    }
}
