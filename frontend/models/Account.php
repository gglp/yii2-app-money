<?php

namespace frontend\models;

use Yii;
use yii\helpers\ArrayHelper;
use frontend\models\AccountType;

/**
 * This is the model class for table "account".
 *
 * @property string $id
 * @property string $account_name
 * @property string $account_type_id
 * @property string $comment
 *
 * @property AccountType $accountType
 * @property Transaction[] $transactions
 */
class Account extends \yii\db\ActiveRecord {

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return '{{%account}}';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['account_name', 'account_type_id'], 'required'],
            [['account_type_id'], 'integer'],
            [['comment'], 'string'],
            [['account_name'], 'string', 'max' => 45]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'id' => 'ID',
            'account_name' => 'Название счёта',
            'account_type_id' => 'Тип счёта',
            'comment' => 'Комментарий',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAccountType() {
        return $this->hasOne(AccountType::className(), ['id' => 'account_type_id']);
    }

    /**
     * Записи для подстановки в таблицы и выпадающие списки
     */
    public function getAccountTypeName() {
        return $this->accountType ? $this->accountType->account_type_name : '- Укажите тип счёта -';
    }

    public static function getAccountTypeList() {
        $droptions = AccountType::find()->asArray()->all();
        return Arrayhelper::map($droptions, 'id', 'account_type_name');
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTransactions() {
        return $this->hasMany(Transaction::className(), ['account_id' => 'id']);
    }

}
