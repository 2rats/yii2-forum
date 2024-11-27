<?php

use yii\db\Migration;

/**
 * Class m241127_201124_fix_foreign_keys_cascade_ondelete
 */
class m241127_201124_fix_foreign_keys_cascade_ondelete extends Migration
{
    private const USER_RELATION_CONFIG = [
        'forum_category' => [
            'fk-forum_category-created_by' => ['created_by', 'user'],
            'fk-forum_category-updated_by' => ['updated_by', 'user'],
        ],
        'forum_file' => [
            'fk-forum_file-fk_user' => ['fk_user', 'user'],
        ],
        'forum_forum' => [
            'fk-forum_forum-created_by' => ['created_by', 'user'],
            'fk-forum_forum-updated_by' => ['updated_by', 'user'],
        ],
        'forum_post' => [
            'fk-forum_post-created_by' => ['created_by', 'user'],
            'fk-forum_post-updated_by' => ['updated_by', 'user'],
        ],
        'forum_thread' => [
            'fk-forum_thread-created_by' => ['created_by', 'user'],
            'fk-forum_thread-updated_by' => ['updated_by', 'user'],
        ],
        'forum_user' => [
            'fk-forum_user-created_by' => ['created_by', 'user'],
            'fk-forum_user-updated_by' => ['updated_by', 'user'],
        ],
        'forum_vote' => [
            'fk-forum_vote-fk_user' => ['fk_user', 'user'],
        ],
    ];

    /**
     * {@inheritDoc}
     */
    public function safeUp()
    {
        foreach (self::USER_RELATION_CONFIG as $table => $config) {
            foreach ($config as $foreignKey => $columnConfig) {
                [$column, $tableName] = $columnConfig;
                $this->dropForeignKey($foreignKey, $table);
                $this->alterColumn($table, $column, $this->integer());
                $this->addForeignKey($foreignKey, $table, $column, $tableName, 'id', 'SET NULL');
            }
        }

        $this->dropForeignKey('fk-forum_post-fk_parent', 'forum_post');
        $this->addForeignKey('fk-forum_post-fk_parent', 'forum_post', 'fk_parent', 'forum_post', 'id', 'CASCADE');
    }

    /**
     * {@inheritDoc}
     */
    public function safeDown() {
        foreach (self::USER_RELATION_CONFIG as $table => $config) {
            foreach ($config as $foreignKey => $columnConfig) {
                [$column, $tableName] = $columnConfig;
                $this->dropForeignKey($foreignKey, $table);
                $this->alterColumn($table, $column, $this->integer()->notNull());
                $this->addForeignKey($foreignKey, $table, $column, $tableName, 'id', 'CASCADE');
            }
        }

        $this->dropForeignKey('fk-forum_post-fk_parent', 'forum_post');
        $this->addForeignKey('fk-forum_post-fk_parent', 'forum_post', 'fk_parent', 'forum_post', 'id', 'SET NULL');
    }
}
