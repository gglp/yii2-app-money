<?php

use yii\db\Schema;
use yii\db\Migration;

class m150804_164950_create_tag_tables extends Migration {

    public function up() {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_general_ci ENGINE=InnoDB COMMENT=\'Иерархические теги по методу Nested Sets\'';
        }

        $this->createTable('{{%tag}}', [
            'id' => Schema::TYPE_PK,
            'tree' => Schema::TYPE_INTEGER,
            'lft' => Schema::TYPE_INTEGER . ' NOT NULL',
            'rgt' => Schema::TYPE_INTEGER . ' NOT NULL',
            'depth' => Schema::TYPE_INTEGER . ' NOT NULL',
            'name' => Schema::TYPE_STRING . ' NOT NULL',
                ], $tableOptions);

        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_general_ci ENGINE=InnoDB COMMENT=\'Таблица связей транзакций с тегами\'';
        }

        $this->createTable('{{%transaction_tag}}', [
            'transaction_id' => Schema::TYPE_INTEGER,
            'tag_id' => Schema::TYPE_INTEGER
                ], $tableOptions);

        $this->createIndex('i_tag', '{{%transaction_tag}}', 'tag_id');
        $this->addForeignKey(
                'fk_tag_transaction', '{{%transaction_tag}}', 'tag_id', '{{%tag}}', 'id', 'SET NULL', 'CASCADE'
        );

        $this->createIndex('i_transaction', '{{%transaction_tag}}', 'transaction_id');
        $this->addForeignKey(
                'fk_transaction_tag', '{{%transaction_tag}}', 'transaction_id', '{{%transaction}}', 'id', 'SET NULL', 'CASCADE'
        );
    }

    public function down() {
        $this->dropForeignKey('fk_tag_transaction', '{{%transaction_tag}}');
        $this->dropForeignKey('fk_transaction_tag', '{{%transaction_tag}}');

        $this->dropTable('{{%tag}}');
        $this->dropTable('{{%transaction_tag}}');
    }

}
