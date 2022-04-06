<?php

use yii\db\Migration;

/**
 * Class m220406_073946_table_users_info
 */
class m220406_073946_table_users_info extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {

        $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';


        $this->createTable('{{%users_info}}', [
            'id' => $this->primaryKey(),
            'uid' => $this->integer()->notNull(),
            'card_n' => $this->string()->null(),
            'address' => $this->string()->null(),
            'created_at' => $this->integer()->notNull(),
            'updated_at' => $this->integer()->notNull(),
        ], $tableOptions);

        $this->createIndex(
            'idx-users_info-uid',
            'users_info',
            'uid'
        );

        $this->addForeignKey(
            'fk-users_info-uid',
            'users_info',
            'uid',
            'user',
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
            'fk-users_info-uid',
            'users_info'
        );

        $this->dropIndex(
            'idx-users_info-uid',
            'users_info'
        );

        $this->dropTable('{{%users_info}}');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m220406_073946_table_users_info cannot be reverted.\n";

        return false;
    }
    */
}
