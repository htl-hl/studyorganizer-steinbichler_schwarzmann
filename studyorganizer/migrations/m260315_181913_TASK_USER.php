<?php

use yii\db\Migration;

class m260315_181913_TASK_USER extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%TASK_USER}}', [
            'userId' => $this->integer()->notNull(),
            'taskId' => $this->integer()->notNull(),
            'isCompleted' => $this->boolean()->notNull()->defaultValue(false),
            'auto_submitted' => $this->boolean()->notNull()->defaultValue(false),
            'return_document' => $this->binary()->null(),
            'file_extension' => $this->string()->null(),
            'PRIMARY KEY (taskId, userId)'
        ]);

        $this->addForeignKey(
            'fk-task_user-user',
            '{{%TASK_USER}}',
            'userId',
            '{{%USER}}',
            'id',
            'CASCADE'
        );

        $this->addForeignKey(
            'fk-task_user-task',
            '{{%TASK_USER}}',
            'taskId',
            '{{%TASK}}',
            'id',
            'CASCADE'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropForeignKey('fk-task_user-user', '{{%TASK_USER}}');
        $this->dropForeignKey('fk-task_user-task', '{{%TASK_USER}}');
        $this->dropTable('{{%TASK_USER}}');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m260315_181913_TASK_USER cannot be reverted.\n";

        return false;
    }
    */
}
