<?php

use yii\db\Migration;

/**
 * Class m250210_235724_fix_foreign_key_cascade_ondelete_in_forum_post_table
 */
class m250210_235724_fix_foreign_key_cascade_ondelete_in_forum_post_table extends Migration
{
    /**
     * {@inheritDoc}
     */
    public function safeUp()
    {
        $this->dropForeignKey('fk-forum_post-fk_parent', 'forum_post');
        $this->addForeignKey('fk-forum_post-fk_parent', 'forum_post', 'fk_parent', 'forum_post', 'id', 'SET NULL');
    }

    /**
     * {@inheritDoc}
     */
    public function safeDown() {
        $this->dropForeignKey('fk-forum_post-fk_parent', 'forum_post');
        $this->addForeignKey('fk-forum_post-fk_parent', 'forum_post', 'fk_parent', 'forum_post', 'id', 'CASCADE');
    }
}
