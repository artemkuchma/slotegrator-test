<?php

use yii\db\Migration;

/**
 * Class m220406_083408_table_user_prize
 */
class m220406_083408_table_user_prize extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';


        $this->createTable('{{%user_prize}}', [
            'id' => $this->primaryKey(),
            'uid' => $this->integer()->notNull(),
            'ptid' => $this->integer()->null(),
            'bonus' => $this->integer()->null(),
            'many' => $this->integer()->null(),
            'item_id' => $this->integer()->null(),
            'status' => $this->integer()->null(),
            'created_at' => $this->integer()->notNull(),
            'updated_at' => $this->integer()->notNull(),
        ], $tableOptions);

        $this->createIndex(
            'idx-user_prize-uid',
            'user_prize',
            'uid'
        );

        $this->createIndex(
            'idx-user_prize-ptid',
            'user_prize',
            'ptid'
        );

        $this->createIndex(
            'idx-user_prize-item_id',
            'user_prize',
            'item_id'
        );

        $this->createIndex(
            'idx-user_prize-status',
            'user_prize',
            'status'
        );


        $this->addForeignKey(
            'fk-user_prize-item_id',
            'user_prize',
            'item_id',
            'items',
            'id',
            'CASCADE'
        );


        $this->addForeignKey(
            'fk-user_prize-ptid',
            'user_prize',
            'ptid',
            'prize_type',
            'id',
            'CASCADE'
        );

        $this->addForeignKey(
            'fk-user_prize-uid',
            'user_prize',
            'uid',
            'user',
            'id',
            'CASCADE'
        );

        $this->addForeignKey(
            'fk-user_prize-status',
            'user_prize',
            'status',
            'prize_status',
            'id',
            'CASCADE'
        );

    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropForeignKey(
            'fk-user_prize-uid',
            'user_prize'
        );

        $this->dropForeignKey(
            'fk-user_prize-ptid',
            'user_prize'
        );

        $this->dropForeignKey(
            'fk-user_prize-item_id',
            'user_prize'
        );


        $this->dropIndex(
            'idx-user_prize-uid',
            'user_prize'
        );

        $this->dropIndex(
            'idx-user_prize-ptid',
            'user_prize'
        );

        $this->dropIndex(
            'idx-user_prize-item_id',
            'user_prize'
        );

        $this->dropTable('{{%user_prize}}');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m220406_083408_table_user_prize cannot be reverted.\n";

        return false;
    }
    */
}
