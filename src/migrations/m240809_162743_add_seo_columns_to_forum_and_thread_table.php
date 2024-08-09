<?php

use yii\db\Migration;

/**
 * Handles adding columns to `{{%forum_forum}} and {{%forum_thread}} table`.
 */
class m240809_162743_add_seo_columns_to_forum_and_thread_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('{{%forum_forum}}', 'seo_title', $this->string(191)->null() . ' AFTER status');
        $this->addColumn('{{%forum_forum}}', 'seo_description', $this->text()->null() . ' AFTER seo_title');
        $this->addColumn('{{%forum_forum}}', 'seo_keywords', $this->text()->null() . ' AFTER seo_description');

        $this->addColumn('{{%forum_thread}}', 'seo_title', $this->string(191)->null() . ' AFTER status');
        $this->addColumn('{{%forum_thread}}', 'seo_description', $this->text()->null() . ' AFTER seo_title');
        $this->addColumn('{{%forum_thread}}', 'seo_keywords', $this->text()->null() . ' AFTER seo_description');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('{{%forum_forum}}', 'seo_title');
        $this->dropColumn('{{%forum_forum}}', 'seo_description');
        $this->dropColumn('{{%forum_forum}}', 'seo_keywords');

        $this->dropColumn('{{%forum_thread}}', 'seo_title');
        $this->dropColumn('{{%forum_thread}}', 'seo_description');
        $this->dropColumn('{{%forum_thread}}', 'seo_keywords');
    }
}
