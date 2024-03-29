<?php

namespace frontend\models;

use Yii;
use yii\helpers\ArrayHelper;
use frontend\models\Account;
use frontend\models\Currency;

/**
 * This is the model class for table "transaction".
 *
 * @property string $id
 * @property string $transaction_date
 * @property string $amount
 * @property string $currency_id
 * @property string $account_id
 * @property string $comment
 *
 * @property Currency $currency
 * @property Account $account
 */
class Transaction extends \yii\db\ActiveRecord {

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return '{{%transaction}}';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['transaction_date', 'amount', 'currency_id', 'account_id', 'type'], 'required'],
            [['transaction_date', 'tags'], 'safe'],
            [['amount'], 'number'],
            [['currency_id', 'account_id', 'type'], 'integer'],
            [['comment'], 'string']
        ];
    }

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

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCurrency() {
        return $this->hasOne(Currency::className(), ['id' => 'currency_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAccount() {
        return $this->hasOne(Account::className(), ['id' => 'account_id']);
    }

    /**
     * Записи для подстановки в таблицы и выпадающие списки
     */
    public function getAccountName() {
        return $this->account ? $this->account->account_name : '- Укажите счёт -';
    }

    public static function getAccountList() {
        $droptions = Account::find()->asArray()->all();
        return Arrayhelper::map($droptions, 'id', 'account_name');
    }

    public function getCurrencyShort() {
        return $this->currency ? $this->currency->currency_short : '- Укажите валюту -';
    }

    public static function getCurrencyList() {
        $droptions = Currency::find()->asArray()->all();
        return Arrayhelper::map($droptions, 'id', 'currency_short');
    }

    // Работаем с тегами

    protected $tags = [];

    public function setTags($tagsId) {
        $this->tags = (array) $tagsId;
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTransactionTags() {
        return $this->hasMany(TransactionTag::className(), ['transaction_id' => 'id']);
    }

    public function getTags() {
        return $this->hasMany(Tag::className(), ['id' => 'tag_id'])
                        ->via('transactionTags');
    }

    public static function getTagList() {
        $droptions = Tag::find()->asArray()->all();
        return Arrayhelper::map($droptions, 'id', 'name');
    }

    public function afterSave($insert, $changedAttributes) {
        TransactionTag::deleteAll(['transaction_id' => $this->id]);

        if (is_array($this->tags)) {
            $tags = [];
            foreach ($this->tags as $tag_id) {
                if ((int) $tag_id === 0) {
                    $tag = new Tag(['name' => $tag_id]);
                    $tag->makeRoot();
                    $tag_id = $tag->id;
                }
                $tags[] = [$this->id, $tag_id];
            }
            self::getDb()->createCommand()->batchInsert(TransactionTag::tableName(), ['transaction_id', 'tag_id'], $tags)->execute();
        }

        parent::afterSave($insert, $changedAttributes);
    }

}
