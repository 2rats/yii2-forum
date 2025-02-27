<?php

/**
 * @file ForumModule.php
 *
 * @brief This file contains the ForumModule class.
 *
 * @author kazda01, mifka01
 */

namespace rats\forum;

use rats\forum\models\User;
use yii\base\BootstrapInterface;
use yii\base\Module;

/**
 * @brief This class represents forum module.
 *
 * @param class  $userClass   – class that represents user identity in your app
 * @param string $forumLayout – layout that is used in forum sites
 * @param string $adminLayout – layout that is used in administration of the forum
 */
class ForumModule extends Module implements BootstrapInterface
{
    public $userClass = \app\models\User::class;
    public $forumLayout = 'forum';
    public $adminLayout = 'admin';
    public $viewPath = '@rats/forum/views';
    
    public $threadSubscriptionEmailView = '@rats/forum/views/mail/threadSubscriptionNotify';
    public $senderName = 'Forum';
    public $senderEmail = 'noreply@example.com';

    public const USERNAME_LENGTH = 191;

    public function bootstrap($app)
    {
        if ($app instanceof \yii\web\Application) {
            $app->getUrlManager()->addRules([
                [
                    'class' => 'yii\web\UrlRule',
                    'pattern' => $this->id . '/profile/<id:\d+>',
                    'route' => $this->id . '/profile/view'
                ],
                [
                    'class' => 'yii\web\UrlRule',
                    'pattern' => $this->id . '/profile/<id:\d+>/edit',
                    'route' => $this->id . '/profile/update'
                ],
                [
                    'class' => 'yii\web\UrlRule',
                    'pattern' => $this->id,
                    'route' => $this->id . '/forum'
                ],
                [
                    'class' => 'yii\web\UrlRule',
                    'pattern' => $this->id,
                    'suffix' => '/',
                    'route' => $this->id . '/forum'
                ],
                [
                    'class' => 'yii\web\UrlRule',
                    'pattern' => $this->id . '/admin',
                    'route' => $this->id . '/admin/admin/index'
                ],
                [
                    'class' => 'yii\web\UrlRule',
                    'pattern' => $this->id . '/<path:[0-9a-z\-]+>/<id:\d+>',
                    'route' => $this->id . '/forum/view'
                ],
                [
                    'class' => 'yii\web\UrlRule',
                    'pattern' => $this->id . '/thread/<path:[0-9a-z\-]+>/<id:\d+>/<post_id:\d+>',
                    'route' => $this->id . '/thread/highlight'
                ],
                [
                    'class' => 'yii\web\UrlRule',
                    'pattern' => $this->id . '/thread/<path:[0-9a-z\-]+>/<id:\d+>',
                    'route' => $this->id . '/thread/view'
                ],
            ], false);
        }
    }

    public function init()
    {
        $this->setViewPath($this->viewPath);

        if (!class_exists($this->userClass)) {
            throw new \yii\base\InvalidConfigException("Class `{$this->userClass}` does not exist. You can choose different class in the module configuration (attribute 'userClass').");
        }
        if (!is_subclass_of($this->userClass, 'yii\db\ActiveRecord')) {
            throw new \yii\base\InvalidConfigException("Class `{$this->userClass}` does not extend `yii\db\ActiveRecord`. You can choose different class in the module configuration (attribute 'userClass').");
        }
        if (is_null(\Yii::$app->authManager)) {
            throw new \yii\base\InvalidConfigException("Module rats/yii2-forum depends on RBAC, set it up using the Yii2 docs.\n Link: https://www.yiiframework.com/doc/guide/2.0/en/security-authorization#configuring-rbac");
        }

        \Yii::$app->params['bsVersion'] = '5.x';

        parent::init();

        $this->modules = [
            'gridview' => [
                'class' => '\kartik\grid\Module',
            ],
        ];
    }

    public function getMigrationPath()
    {
        return \Yii::getAlias('@rats/forum/migrations');
    }

    public function run()
    {
        ForumAsset::register($this->getView());

        return parent::run();
    }

    /**
     * Sign up a new user for the forum.
     *
     * @param rats/forum/models/User $user the user object containing signup information
     *
     * @return bool returns true if the user was successfully signed up, otherwise false
     */
    public static function signupUser($user)
    {
        $forum_user = new User();
        $forum_user->username = substr($user->username, 0, self::USERNAME_LENGTH);
        $forum_user->id = $user->id;
        $forum_user->email = $user->email;

        if ($forum_user->save()) {
            $role = \Yii::$app->authManager->getRole('forum-user');
            \Yii::$app->authManager->assign($role, $forum_user->id);

            return true;
        }

        return false;
    }
}
