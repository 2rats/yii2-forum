<?php

/**
 * @file ForumModule.php
 * @brief This file contains the ForumModule class.
 * @author kazda01, mifka01
 */

namespace rats\forum;

use Yii;
use yii\base\Module;
use yii\base\BootstrapInterface;

/**
 * @brief This class represents forum module.
 * @param class $userClass – class that represents user identity in your app
 */
class ForumModule extends Module implements BootstrapInterface
{
    public $userClass = \app\models\User::class;

    /**
     * {@inheritdoc}
     */
    public function bootstrap($app)
    {
        if ($app instanceof \yii\web\Application) {
            $app->getUrlManager()->addRules([
                [
                    'class' => 'yii\web\UrlRule',
                    'pattern' => $this->id,
                    'route' => $this->id . '/forum'
                ],
                [
                    'class' => 'yii\web\UrlRule',
                    'pattern' => $this->id . '/<path:[0-9a-z\-]+>/<id:\d+>',
                    'route' => $this->id . '/forum/view'
                ],
                [
                    'class' => 'yii\web\UrlRule',
                    'pattern' => $this->id . '/thread/<path:[0-9a-z\-]+>/<id:\d+>',
                    'route' => $this->id . '/thread/view'
                ],
            ], false);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function init()
    {
        if (!class_exists($this->userClass)) {
            throw new \yii\base\InvalidConfigException("Class `{$this->userClass}` does not exist. You can choose different class in the module configuration (attribute 'userClass').");
        }
        if (!is_subclass_of($this->userClass, 'yii\db\ActiveRecord')) {
            throw new \yii\base\InvalidConfigException("Class `{$this->userClass}` does not extend `yii\db\ActiveRecord`. You can choose different class in the module configuration (attribute 'userClass').");
        }

        Yii::setAlias('@2ratsForum', __DIR__);

        parent::init();
    }

    /**
     * {@inheritdoc}
     */
    public function getViewPath()
    {
        return Yii::getAlias('@2ratsForum/views');
    }

    /**
     * {@inheritdoc}
     */
    public function getMigrationPath()
    {
        return Yii::getAlias('@2ratsForum/migrations');
    }

    /**
     * {@inheritdoc}
     */
    public function run()
    {
        ForumAsset::register($this->getView());
        return parent::run();
    }
}
