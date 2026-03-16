<?php

use yii\db\Migration;

class m260216_131642_TASK extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable("{{%TASK}}", [
            'id' => $this->primaryKey(),
            'title' => $this->string()->unique()->notNull(),
            'dueDate' => $this->dateTime()->notNull(),
            'description' => $this->text()->notNull(),
            'subjectId' => $this->integer()->notNull(),

            'task_document' => $this->binary()->null(),
            'file_extension' => $this->string()->null()
        ]);

        $this->addForeignKey(
            'subject-Id',
            '{{%TASK}}',
            'subjectId',
            '{{%SUBJECT}}',
            'id',
            'CASCADE'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropForeignKey('subject-Id', '{{%TASK}}');
        $this->dropTable("{{%TASK}}");
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m260216_131642_TASK cannot be reverted.\n";

        return false;
    }
    */
}
