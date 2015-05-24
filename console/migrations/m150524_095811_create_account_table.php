<?php

use yii\db\Schema;
use yii\db\Migration;

class m150524_095811_create_account_table extends Migration {

    public function safeUp() {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB COMMENT=\'Счета\'';
        }

        $this->createTable('{{%account}}', [
            'id' => Schema::TYPE_PK,
            'account_name' => Schema::TYPE_STRING . '(45) NOT NULL COMMENT \'Название счёта\'',
            'account_type_id' => Schema::TYPE_INTEGER . ' NOT NULL COMMENT \'Тип счёта\'',
            'comment' => Schema::TYPE_TEXT . ' COMMENT \'Комментарий\'',
                ], $tableOptions
        );

        // Счета ссылаются на Тип счета
        $this->addForeignKey('fk_account_accounttype', '{{%account}}', 'account_type_id', '{{%account_type}}', 'id', 'CASCADE', 'RESTRICT');
    }

    public function safeDown() {
        $this->dropForeignKey('fk_account_accounttype', '{{%account}}');

        $this->dropTable('{{%account}}');
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
