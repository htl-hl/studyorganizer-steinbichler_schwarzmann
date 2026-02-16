<?php

use yii\db\Migration;

class m260216_122618_SUBJECT extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable("{{%SUBJECT}}", [
            'id' => $this->primaryKey(),
            'name' => $this->string()->unique()->notNull(),
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable("{{%SUBJECT}}");
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m260216_122618_SUBJECT cannot be reverted.\n";

        return false;
    }
    */
}
