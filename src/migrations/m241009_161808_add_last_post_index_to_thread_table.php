<?php

/**
 * @author kazda01, mifka01
 */

use yii\db\Migration;

class m241009_161808_add_last_post_index_to_thread_table extends Migration
{
    private $table_name = 'forum_thread';

    public function safeUp()
    {
        $this->createIndex(
            "{{%idx-{$this->table_name}-fk_last_post}}",
            "{{%{$this->table_name}}}",
            'fk_last_post'
        );
    }

    public function safeDown()
    {
    }
}
