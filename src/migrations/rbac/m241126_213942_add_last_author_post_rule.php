<?php

use rats\forum\rbac\LastAuthorPostRule;
use rats\forum\rbac\AuthorRule;
use yii\db\Migration;

/**
 * Class m241126_213942_add_last_author_post_rule.
 */
class m241126_213942_add_last_author_post_rule extends Migration
{
    public function safeUp()
    {
        $auth = Yii::$app->authManager;

        $editOwnPost = $auth->getPermission('forum-editOwnPost');
        $auth->remove($editOwnPost);

        $authorRule = $auth->getRule('forum-isAuthor');
        $auth->remove($authorRule);
        
        $editPost = $auth->getPermission('forum-editPost');

        $lastPostAuthorRule = new LastAuthorPostRule();
        $auth->add($lastPostAuthorRule);

        $manageLastAuthorPost = $auth->createPermission('forum-manageLastAuthorPost');
        $manageLastAuthorPost->description = 'Manage last author post';
        $manageLastAuthorPost->ruleName = $lastPostAuthorRule->name;
        $auth->add($manageLastAuthorPost);

        $auth->addChild($manageLastAuthorPost, $editPost);

        $user = $auth->getRole('forum-user');
        $auth->addChild($user, $manageLastAuthorPost);

        $deletePost = $auth->getPermission('forum-deletePost');
        $auth->addChild($manageLastAuthorPost, $deletePost);

        $this->addColumn('forum_user', 'last_post_id', $this->integer()->defaultValue(null));
        $this->addForeignKey('fk-forum_user-last_post_id', 'forum_user', 'last_post_id', 'forum_post', 'id', 'SET NULL', 'SET NULL');

        return true;
    }

    public function safeDown()
    {
        $auth = Yii::$app->authManager;

        $manageLastAuthorPost = $auth->getPermission('forum-manageLastAuthorPost');
        $auth->remove($manageLastAuthorPost);

        $editPost = $auth->getPermission('forum-editPost');
        $auth->removeChild($manageLastAuthorPost, $editPost);

        $deletePost = $auth->getPermission('forum-deletePost');
        $auth->removeChild($manageLastAuthorPost, $deletePost);

        $user = $auth->getRole('forum-user');
        $auth->removeChild($user, $manageLastAuthorPost);

        $lastPostAuthorRule = $auth->getRule('forum-isLastAuthorPost');
        $auth->remove($lastPostAuthorRule);

        $editOwnPost = $auth->createPermission('forum-editOwnPost');
        $editOwnPost->description = 'Edit own post';
        $auth->add($editOwnPost);

        $authorRule = new AuthorRule();
        $auth->add($authorRule);

        $editOwnPost->ruleName = $authorRule->name;
        $auth->update($editOwnPost->name, $editOwnPost);

        $this->dropForeignKey('fk-forum_user-last_post_id', 'forum_user');
        $this->dropColumn('forum_user', 'last_post_id');

        return true;
    }
}
