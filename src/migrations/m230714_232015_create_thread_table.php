<?php

/**
 * @author kazda01, mifka01
 */

use yii\db\Migration;

class m230714_232015_create_thread_table extends Migration
{
    private $table_name = 'forum_thread';

    public function safeUp()
    {
        $table_options = Yii::$app->params['migrationTableOptions'] ?? null;
        $user_table_name = Yii::$app->params['userTableName'] ?? 'user';

        if ('mysql' === $this->db->driverName && null === $table_options) {
            $table_options = 'CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE=InnoDB';
        }

        $this->createTable("{{%{$this->table_name}}}", [
            'id' => $this->primaryKey(),
            'fk_forum' => $this->integer()->notNull(),
            'name' => $this->string(191)->notNull(),
            'status' => $this->smallInteger()->notNull()->defaultValue(1),
            'views' => $this->integer()->notNull()->defaultValue(0),
            'pinned' => $this->boolean()->notNull()->defaultValue(false),
            'created_by' => $this->integer()->notNull(),
            'updated_by' => $this->integer()->notNull(),
            'created_at' => $this->timestamp(),
            'updated_at' => $this->timestamp(),
        ], $table_options);

        // creates index for column `fk_forum`
        $this->createIndex(
            "{{%idx-{$this->table_name}-fk_forum}}",
            "{{%{$this->table_name}}}",
            'fk_forum'
        );

        // creates index for column `created_by`
        $this->createIndex(
            "{{%idx-{$this->table_name}-created_by}}",
            "{{%{$this->table_name}}}",
            'created_by'
        );

        // creates index for column `updated_by`
        $this->createIndex(
            "{{%idx-{$this->table_name}-updated_by}}",
            "{{%{$this->table_name}}}",
            'updated_by'
        );

        // add foreign key for fk_forum
        $this->addForeignKey(
            "{{%fk-{$this->table_name}-fk_forum}}",
            "{{%{$this->table_name}}}",
            'fk_forum',
            '{{%forum_forum}}',
            'id',
            'CASCADE'
        );

        // add foreign key for table `{{%user}}`
        $this->addForeignKey(
            "{{%fk-{$this->table_name}-created_by}}",
            "{{%{$this->table_name}}}",
            'created_by',
            "{{%{$user_table_name}}}",
            'id',
            'CASCADE'
        );

        // add foreign key for table `{{%user}}`
        $this->addForeignKey(
            "{{%fk-{$this->table_name}-updated_by}}",
            "{{%{$this->table_name}}}",
            'updated_by',
            "{{%{$user_table_name}}}",
            'id',
            'CASCADE'
        );
    }

    public function safeDown()
    {
        // drops foreign key for fk_forum
        $this->dropForeignKey(
            "{{%fk-{$this->table_name}-fk_forum}}",
            "{{%{$this->table_name}}}",
        );

        // drops index for column 'fk_forum'
        $this->dropIndex(
            "{{%idx-{$this->table_name}-fk_forum}}",
            "{{%{$this->table_name}}}",
        );

        // drops foreign key for table `{{%user}}`
        $this->dropForeignKey(
            "{{%fk-{$this->table_name}-created_by}}",
            "{{%{$this->table_name}}}",
        );

        // drops index for column `created_by`
        $this->dropIndex(
            "{{%idx-{$this->table_name}-created_by}}",
            "{{%{$this->table_name}}}",
        );

        // drops foreign key for table `{{%user}}`
        $this->dropForeignKey(
            "{{%fk-{$this->table_name}-updated_by}}",
            "{{%{$this->table_name}}}",
        );

        // drops index for column `updated_by`
        $this->dropIndex(
            "{{%idx-{$this->table_name}-updated_by}}",
            "{{%{$this->table_name}}}",
        );

        $this->dropTable("{{%{$this->table_name}}}");
    }
}
