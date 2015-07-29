<?php

use yii\db\Schema;
use yii\db\Migration;

class m150729_123712_add_overdraft_column_account_table extends Migration {

    public function up() {
        $this->addColumn('{{%account}}', 'overdraft', Schema::TYPE_DECIMAL . '(10, 2) NOT NULL DEFAULT \'0\' COMMENT \'Овердрафт\'');
    }

    public function down() {
        $this->dropColumn('{{%account}}', 'overdraft');
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
