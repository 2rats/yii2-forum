<?php

/**
 * @author kazda01, mifka01
 */

use yii\db\Migration;

class m230715_011013_create_vote_table extends Migration
{

    private $table_name = 'forum_vote';

    public function safeUp()
    {

        $table_options = Yii::$app->params['migrationTableOptions'] ?? null;
        $user_table_name = Yii::$app->params['userTableName'] ?? 'user';

        if ($this->db->driverName === 'mysql' && $table_options === null) {
            $table_options = 'CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE=InnoDB';
        }

        $this->createTable("{{%{$this->table_name}}}", [
            'id' => $this->primaryKey(),
            'fk_post' => $this->integer()->notNull(),
            'fk_user' => $this->integer()->defaultValue(null),
            'value' => $this->smallInteger()->notNull(),
            'created_at' => $this->timestamp(),
            'updated_at' => $this->timestamp(),
        ], $table_options);

        // creates index for column `fk_post`
        $this->createIndex(
            "{{%idx-{$this->table_name}-fk_post}}",
            "{{%{$this->table_name}}}",
            'fk_post'
        );

        // creates index for column `fk_user`
        $this->createIndex(
            "{{%idx-{$this->table_name}-fk_user}}",
            "{{%{$this->table_name}}}",
            'fk_user'
        );


        // add foreign key for this post table
        $this->addForeignKey(
            "{{%fk-{$this->table_name}-fk_post}}",
            "{{%{$this->table_name}}}",
            'fk_post',
            "{{%forum_post}}",
            'id',
            'CASCADE'
        );
        // add foreign key for user table
        $this->addForeignKey(
            "{{%fk-{$this->table_name}-fk_user}}",
            "{{%{$this->table_name}}}",
            'fk_user',
            "{{%{$user_table_name}}}",
            'id',
            'CASCADE'
        );
    }

    public function safeDown()
    {

        // drops foreign key for post table
        $this->dropForeignKey(
            "{{%fk-{$this->table_name}-fk_post}}",
            "{{%{$this->table_name}}}",
        );

        // drops index for column `fk_post`
        $this->dropIndex(
            "{{%idx-{$this->table_name}-fk_post}}",
            "{{%{$this->table_name}}}",
        );

        // drops foreign key for user
        $this->dropForeignKey(
            "{{%fk-{$this->table_name}-fk_user}}",
            "{{%{$this->table_name}}}",
        );

        // drops index for column `user`
        $this->dropIndex(
            "{{%idx-{$this->table_name}-fk_user}}",
            "{{%{$this->table_name}}}",
        );

        $this->dropTable("{{%{$this->table_name}}}");
    }
}
