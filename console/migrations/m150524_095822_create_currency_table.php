<?php

use yii\db\Schema;
use yii\db\Migration;

class m150524_095822_create_currency_table extends Migration {

    public function safeUp() {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB COMMENT=\'Валюты\'';
        }

        $this->createTable('{{%currency}}', [
            'id' => Schema::TYPE_PK,
            'currency_name' => Schema::TYPE_STRING . '(45) NOT NULL COMMENT \'Название валюты\'',
            'currency_short' => Schema::TYPE_STRING . '(4) NOT NULL COMMENT \'Обозначение валюты\'',
            'comment' => Schema::TYPE_TEXT . ' COMMENT \'Комментарий\'',
                ], $tableOptions
        );
    }

    public function safeDown() {
        $this->dropTable('{{%currency}}');
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
