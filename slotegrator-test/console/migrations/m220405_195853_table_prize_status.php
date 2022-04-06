<?php

use yii\db\Migration;

/**
 * Class m220405_195853_table_prize_status
 */
class m220405_195853_table_prize_status extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';


        $this->createTable('{{%prize_status}}', [
            'id' => $this->primaryKey(),
            'name' => $this->string()->notNull(),

        ], $tableOptions);

        $this->batchInsert('{{%prize_status}}',['name'],[
            ['selected'],
            ['confirmed'],
            ['sent'],
            ['canceled'],
        ]);

    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%prize_status}}');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m220405_195853_table_prize_status cannot be reverted.\n";

        return false;
    }
    */
}
