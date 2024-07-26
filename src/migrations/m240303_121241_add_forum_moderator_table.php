<?php

use yii\db\Migration;

/**
 * Class m240303_121241_add_forum_moderator_table
 */
class m240303_121241_add_forum_moderator_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('forum_moderator', [
            'id' => $this->primaryKey(),
            'fk_forum' => $this->integer()->notNull(),
            'fk_user' => $this->integer()->notNull(),
        ]);

        $this->addForeignKey('fk_forum_moderator_forum', 'forum_moderator', 'fk_forum', 'forum_forum', 'id', 'CASCADE', 'CASCADE');
        $this->addForeignKey('fk_forum_moderator_user', 'forum_moderator', 'fk_user', 'user', 'id', 'CASCADE', 'CASCADE');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropForeignKey('fk_forum_moderator_forum', 'forum_moderator');
        $this->dropForeignKey('fk_forum_moderator_user', 'forum_moderator');

        $this->dropTable('forum_moderator');
    }
}
