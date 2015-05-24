<?php

use yii\db\Schema;
use yii\db\Migration;

class m150524_095747_create_accounttype_table extends Migration {

    public function safeUp() {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB COMMENT=\'Типы счетов\'';
        }

        $this->createTable('{{%account_type}}', [
            'id' => Schema::TYPE_PK,
            'account_type_name' => Schema::TYPE_STRING . '(45) NOT NULL COMMENT \'Тип счёта\'',
            'comment' => Schema::TYPE_TEXT . ' COMMENT \'Комментарий\'',
                ], $tableOptions
        );
    }

    public function safeDown() {
        $this->dropTable('{{%account_type}}');
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
