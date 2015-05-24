<?php

namespace frontend\models;

use Yii;

/**
 * This is the model class for table "{{%account_type}}".
 *
 * @property integer $id
 * @property string $account_type_name
 * @property string $comment
 *
 * @property Account[] $accounts
 */
class AccountType extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%account_type}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['account_type_name'], 'required'],
            [['comment'], 'string'],
            [['account_type_name'], 'string', 'max' => 45]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'account_type_name' => 'Тип счёта',
            'comment' => 'Комментарий',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAccounts()
    {
        return $this->hasMany(Account::className(), ['account_type_id' => 'id']);
    }
}
