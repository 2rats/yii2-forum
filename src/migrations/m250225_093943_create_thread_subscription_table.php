<?php

/**
 * @author kazda01, mifka01
 */

use yii\db\Migration;

class m250225_093943_create_thread_subscription_table extends Migration
{
    private $table_name = 'forum_thread_subscription';

    public function safeUp()
    {
        $table_options = Yii::$app->params['migrationTableOptions'] ?? null;
        $user_table_name = Yii::$app->params['userTableName'] ?? 'user';

        if ($this->db->driverName === 'mysql' && $table_options === null) {
            $table_options = 'CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE=InnoDB';
        }

        $this->createTable("{{%{$this->table_name}}}", [
            'id' => $this->primaryKey(),
            'fk_user' => $this->integer()->notNull(),
            'fk_thread' => $this->integer()->notNull(),
            'fk_last_post' => $this->integer(),
            'token' => $this->string(64)->notNull(),
            'created_at' => $this->timestamp(),
            'updated_at' => $this->timestamp()->defaultExpression('NOW()'),
        ], $table_options);

        $this->createIndex(
            "{{%idx-{$this->table_name}-fk_user}}",
            "{{%{$this->table_name}}}",
            'fk_user'
        );
        $this->addForeignKey(
            "{{%fk-{$this->table_name}-fk_user}}",
            "{{%{$this->table_name}}}",
            'fk_user',
            "{{%{$user_table_name}}}",
            'id',
            'CASCADE'
        );

        $this->createIndex(
            "{{%idx-{$this->table_name}-fk_thread}}",
            "{{%{$this->table_name}}}",
            'fk_thread'
        );
        $this->addForeignKey(
            "{{%fk-{$this->table_name}-fk_thread}}",
            "{{%{$this->table_name}}}",
            'fk_thread',
            "{{%forum_thread}}",
            'id',
            'CASCADE'
        );

        $this->createIndex(
            "{{%idx-{$this->table_name}-fk_last_post}}",
            "{{%{$this->table_name}}}",
            'fk_last_post'
        );
        $this->addForeignKey(
            "{{%fk-{$this->table_name}-fk_last_post}}",
            "{{%{$this->table_name}}}",
            'fk_last_post',
            "{{%forum_post}}",
            'id',
            'SET NULL',
        );
    }

    public function safeDown()
    {
        $this->dropForeignKey(
            "{{%fk-{$this->table_name}-fk_last_post}}",
            "{{%{$this->table_name}}}",
        );
        $this->dropIndex(
            "{{%idx-{$this->table_name}-fk_last_post}}",
            "{{%{$this->table_name}}}",
        );
        
        $this->dropForeignKey(
            "{{%fk-{$this->table_name}-fk_thread}}",
            "{{%{$this->table_name}}}",
        );
        $this->dropIndex(
            "{{%idx-{$this->table_name}-fk_thread}}",
            "{{%{$this->table_name}}}",
        );
        
        $this->dropForeignKey(
            "{{%fk-{$this->table_name}-fk_user}}",
            "{{%{$this->table_name}}}",
        );
        $this->dropIndex(
            "{{%idx-{$this->table_name}-fk_user}}",
            "{{%{$this->table_name}}}",
        );

        $this->dropTable("{{%{$this->table_name}}}");
    }
}
