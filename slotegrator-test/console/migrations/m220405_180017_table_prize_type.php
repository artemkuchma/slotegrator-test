<?php

use yii\db\Migration;

/**
 * Class m220405_180017_table_prize_type
 */
class m220405_180017_table_prize_type extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';


        $this->createTable('{{%prize_type}}', [
            'id' => $this->primaryKey(),
            'name' => $this->string()->notNull()->unique(),
            'total' => $this->integer()->defaultValue(0),
            'interval_from' => $this->integer()->defaultValue(0),
            'interval_to' => $this->integer()->defaultValue(0),
            'coefficient_to_many' => $this->float(),
            'created_at' => $this->integer()->notNull(),
            'updated_at' => $this->integer()->notNull(),
        ], $tableOptions);

        $this->batchInsert('{{%prize_type}}',['name','total','interval_from','interval_to','coefficient_to_many','created_at', 'updated_at'],[
            ['денежный', '10000','10', '1000', '1', time(), time()],
            ['бонусный', '-1','100', '10000', '0.2', time(), time()],
            ['предметный', 0,'1', '1', '0', time(), time()],
        ]);

    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%prize_type}}');

    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m220405_180017_table_prize_type cannot be reverted.\n";

        return false;
    }
    */
}
