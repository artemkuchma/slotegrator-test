<?php

use yii\db\Migration;

/**
 * Class m220406_080820_table_items
 */
class m220406_080820_table_items extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {


        $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';


        $this->createTable('{{%items}}', [
            'id' => $this->primaryKey(),
            'name' => $this->string()->null(),
            'number' => $this->integer()->null(),
            'created_at' => $this->integer()->notNull(),
            'updated_at' => $this->integer()->notNull(),
        ], $tableOptions);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%items}}');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m220406_080820_table_items cannot be reverted.\n";

        return false;
    }
    */
}
