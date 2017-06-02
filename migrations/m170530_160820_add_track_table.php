<?php

use yii\db\Migration;

class m170530_160820_add_track_table extends Migration
{
    public function up()
    {
        $this->createTable('{{%track_time}}', [
            'id' => $this->primaryKey(11)->notNull(),
            'board_id' => $this->string(32)->notNull(),
            'list_id' => $this->string(32)->notNull(),
            'card_id' => $this->string(32)->notNull(),
            'time' => $this->string(32)->null(),
            'created_at' => $this->dateTime(),
            'updated_at' => $this->dateTime(),
        ], 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB');
    }

    public function down()
    {
        $this->dropTable('{{%track_time}}');
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
