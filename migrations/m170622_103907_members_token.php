<?php

use yii\db\Migration;

class m170622_103907_members_token extends Migration
{
    public function up()
    {
        $this->createTable('{{%member_token}}', [
            'id' => $this->primaryKey(11)->notNull(),
            'member_id' => $this->string(100)->notNull(),
            'token' => $this->string(100)->notNull(),
            'created_at' => $this->dateTime(),
            'updated_at' => $this->dateTime(),
        ], 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB');
    }

    public function down()
    {
        $this->dropTable('{{%member_token}}');
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
