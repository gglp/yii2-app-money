<?php

use yii\db\Schema;
use yii\db\Migration;

class m150925_092226_add_type_column_transaction_table extends Migration
{
    public function up()
    {
        $this->addColumn('{{%transaction}}', 'type', Schema::TYPE_INTEGER . '(1) NOT NULL DEFAULT \'0\' COMMENT \'Тип транзакции\'');
    }

    public function down() {
        $this->dropColumn('{{%transaction}}', 'type');
    }

    /*
    // Use safeUp/safeDown to run migration code within a transaction
    public function safeUp()
    {
    }

    public function safeDown()
    {
    }
    */
}
