<?php

use yii\db\Migration;

class m260313_162738_TEACHER_SUBJECT extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%TEACHER_SUBJECT}}', [
            'teacherId' => $this->integer()->notNull(),
            'subjectId' => $this->integer()->notNull(),
        ]);


        $this->addForeignKey(
            'fk-teacher_subject-teacher',
            '{{%TEACHER_SUBJECT}}',
            'teacherId',
            '{{%TEACHER}}',
            'id',
            'CASCADE'
        );

        // Foreign Key zu Subject
        $this->addForeignKey(
            'fk-teacher_subject-subject',
            '{{%TEACHER_SUBJECT}}',
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
        $this->dropForeignKey('fk-teacher_subject-teacher', '{{%TEACHER_SUBJECT}}');
        $this->dropForeignKey('fk-teacher_subject-subject', '{{%TEACHER_SUBJECT}}');
        $this->dropTable('{{%teacher_subject}}');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m260313_162738_TEACHER_SUBJECT cannot be reverted.\n";

        return false;
    }
    */
}
