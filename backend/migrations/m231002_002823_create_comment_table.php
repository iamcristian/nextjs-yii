<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%comment}}`.
 */
class m231002_002823_create_comment_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%comment}}', [
            'id' => $this->primaryKey(),
            'title' => $this->string(512),
            'body' => $this->text(),
            'created_at' => $this->integer(),
            'updated_at' => $this->integer(),
            'post_id' => $this->integer()
        ]);

        $this->addForeignKey('fk_comment_post_post_id', '{{%comment}}', 'post_id', '{{%post}}', 'id');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropForeignKey('fk_comment_post_post_id', '{{%comment}}');
        $this->dropTable('{{%comment}}');
    }
}
