<?php

use yii\db\Schema;
use yii\db\Migration;

class m150524_095834_create_transaction_table extends Migration
{
    public function safeUp()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB COMMENT=\'Транзакции\'';
        }

        $this->createTable('{{%transaction}}', [
            'id'                => Schema::TYPE_PK,
            'transaction_date'  => Schema::TYPE_DATE . ' NOT NULL COMMENT \'Дата\'',
            'amount'            => Schema::TYPE_DECIMAL . '(10, 2) NOT NULL COMMENT \'Сумма\'',
            'currency_id'       => Schema::TYPE_INTEGER . ' NOT NULL COMMENT \'Валюта\'',
            'account_id'        => Schema::TYPE_INTEGER . ' NOT NULL COMMENT \'Счёт\'',
            'comment'           => Schema::TYPE_TEXT . ' COMMENT \'Комментарий\'',
                ], $tableOptions
        );

        // Транзакции ссылаются на Счета
        $this->addForeignKey('fk_transaction_account', '{{%transaction}}', 'account_id', '{{%account}}', 'id', 'CASCADE', 'RESTRICT');
        
        // Транзакции ссылаются на Валюты
        $this->addForeignKey('fk_transaction_currency', '{{%transaction}}', 'currency_id', '{{%currency}}', 'id', 'CASCADE', 'RESTRICT');
    }

    public function safeDown()
    {
        $this->dropForeignKey('fk_transaction_currency', '{{%transaction}}');
        $this->dropForeignKey('fk_transaction_account', '{{%transaction}}');
        
        $this->dropTable('{{%transaction}}');
    }
}
