<?php

use yii\db\Migration;
use rats\forum\rbac\AuthorRule;

/**
 * Class m230717_161951_init_rbac
 * @note The following rights are available:
 *       USER
 *        forum-createThread
 *        forum-createPost
 *        forum-editOwnPost
 *        forum-vote
 *       MODERATOR
 *        forum-createForum
 *        forum-editForum
 *        forum-editThread
 *        forum-editPost
 *        forum-silenceUser
 *       ADMIN
 *        forum-deletePost
 *        forum-deleteThread
 *        forum-deleteForum
 *        forum-assignModerator
 *
 * @note There are two types of deletion soft and hard:
 *       - Soft: It is contained in the edit right (e.g. editPost), it just marks the model as deleted
 *       - Hard: It has its own delete right (e.g. deletePost), it hard deletes the record from the database
 */
class m230717_161951_init_rbac extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $auth = Yii::$app->authManager;

        // USER
        $createThread = $auth->createPermission('forum-createThread');
        $createThread->description = 'Create a new thread';
        $auth->add($createThread);

        $createPost = $auth->createPermission('forum-createPost');
        $createPost->description = 'Create a new post';
        $auth->add($createPost);

        $authorRule = new AuthorRule;
        $auth->add($authorRule);

        $editOwnPost = $auth->createPermission('forum-editOwnPost');
        $editOwnPost->description = 'Edit own post';
        $editOwnPost->ruleName = $authorRule->name;
        $auth->add($editOwnPost);

        $vote = $auth->createPermission('forum-vote');
        $vote->description = 'Vote on a post';
        $auth->add($vote);

        $user = $auth->createRole('user');
        $auth->add($user);
        $auth->addChild($user, $createThread);
        $auth->addChild($user, $createPost);
        $auth->addChild($user, $editOwnPost);
        $auth->addChild($user, $vote);

        // MODERATOR
        $createForum = $auth->createPermission('forum-createForum');
        $createForum->description = 'Create a forum';
        $auth->add($createForum);

        $editForum = $auth->createPermission('forum-editForum');
        $editForum->description = 'Edit a forum';
        $auth->add($editForum);

        $editThread = $auth->createPermission('forum-editThread');
        $editThread->description = 'Edit a thread';
        $auth->add($editThread);

        $editPost = $auth->createPermission('forum-editPost');
        $editPost->description = 'Edit a post';
        $auth->add($editPost);

        $silenceUser = $auth->createPermission('forum-silenceUser');
        $silenceUser->description = 'Silence a user';
        $auth->add($silenceUser);

        $moderator = $auth->createRole('moderator');
        $auth->add($moderator);
        $auth->addChild($moderator, $user);
        $auth->addChild($moderator, $createForum);
        $auth->addChild($moderator, $editForum);
        $auth->addChild($moderator, $editThread);
        $auth->addChild($moderator, $editPost);
        $auth->addChild($moderator, $silenceUser);


        // ADMIN
        $deletePost = $auth->createPermission('forum-deletePost');
        $deletePost->description = 'Delete a post';
        $auth->add($deletePost);

        $deleteThread = $auth->createPermission('forum-deleteThread');
        $deleteThread->description = 'Delete a thread';
        $auth->add($deleteThread);

        $deleteForum = $auth->createPermission('forum-deleteForum');
        $deleteForum->description = 'Delete a forum';
        $auth->add($deleteForum);

        $assignModerator = $auth->createPermission('forum-assignModerator');
        $assignModerator->description = 'Assign a moderator to a forum';
        $auth->add($assignModerator);

        $admin = $auth->createRole('admin');
        $auth->add($admin);
        $auth->addChild($admin, $moderator);
        $auth->addChild($admin, $deletePost);
        $auth->addChild($admin, $deleteThread);
        $auth->addChild($admin, $deleteForum);
        $auth->addChild($admin, $assignModerator);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $auth = Yii::$app->authManager;

        $auth->remove($auth->getRole('user'));
        $auth->remove($auth->getRole('moderator'));
        $auth->remove($auth->getRole('admin'));

        $auth->remove($auth->getRule('forum-isAuthor'));

        $auth->remove($auth->getPermission('forum-createThread'));
        $auth->remove($auth->getPermission('forum-createPost'));
        $auth->remove($auth->getPermission('forum-editOwnPost'));
        $auth->remove($auth->getPermission('forum-vote'));
        $auth->remove($auth->getPermission('forum-createForum'));
        $auth->remove($auth->getPermission('forum-editForum'));
        $auth->remove($auth->getPermission('forum-editThread'));
        $auth->remove($auth->getPermission('forum-editPost'));
        $auth->remove($auth->getPermission('forum-silenceUser'));
        $auth->remove($auth->getPermission('forum-deletePost'));
        $auth->remove($auth->getPermission('forum-deleteThread'));
        $auth->remove($auth->getPermission('forum-deleteForum'));
        $auth->remove($auth->getPermission('forum-assignModerator'));
    }
}
