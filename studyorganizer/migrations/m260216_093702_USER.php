<?php

use yii\db\Migration;
use yii\base\Security;

class m260216_093702_USER extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable("{{%USER}}", [
            'id' => $this->primaryKey(),
            'email' => $this->string()->notNull()->unique(),
            'username' => $this->string()->notNull()->unique(),
            'passwordHash' => $this->string()->notNull(),
            'authKey' => $this->string()->notNull()->unique(),
            'accessToken' => $this->string()->notNull()->unique(),
            'role' => $this->string(8)->notNull()->defaultValue("User"),
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable("{{%USER}}");
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m260216_093702_USER cannot be reverted.\n";

        return false;
    }
    */
}
