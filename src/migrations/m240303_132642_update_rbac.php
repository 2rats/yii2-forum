<?php

use yii\db\Migration;

/**
 * Class m240303_132642_update_rbac
 */
class m240303_132642_update_rbac extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $auth = Yii::$app->authManager;

        $rule = new \app\rbac\ForumModeratorRule();
        $auth->add($rule);


        $editForum = $auth->getPermission('forum-editForum');
        $editThread = $auth->getPermission('forum-editThread');
        $editPost = $auth->getPermission('forum-editPost');


        $editAssignedForum = $auth->createPermission('forum-editAssignedForum');
        $editAssignedForum->description = 'Edit assigned forum';
        $editAssignedForum->ruleName = $rule->name;
        $auth->add($editAssignedForum);

        $editAssignedThread = $auth->createPermission('forum-editAssignedThread');
        $editAssignedThread->description = 'Edit assigned thread';
        $editAssignedThread->ruleName = $rule->name;
        $auth->add($editAssignedThread);

        $editAssignedPost = $auth->createPermission('forum-editAssignedPost');
        $editAssignedPost->description = 'Edit assigned post';
        $editAssignedPost->ruleName = $rule->name;
        $auth->add($editAssignedPost);

        
        $auth->addChild($editAssignedForum, $editForum);
        $auth->addChild($editAssignedThread, $editThread);
        $auth->addChild($editAssignedPost, $editPost);


        $moderator = $auth->getRole('forum-moderator');
        $auth->addChild($moderator, $editAssignedForum);
        $auth->addChild($moderator, $editAssignedThread);
        $auth->addChild($moderator, $editAssignedPost);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $auth = Yii::$app->authManager;

        $auth->remove($auth->getPermission('forum-editAssignedForum'));	
        $auth->remove($auth->getPermission('forum-editAssignedThread'));
        $auth->remove($auth->getPermission('forum-editAssignedPost'));

        $auth->remove($auth->getRule('isAssignedToForum'));
    }
}
