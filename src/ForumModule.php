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
 * @param string $migrationTableOptions – migration table options
 * @param string $userTableName – user table name
 */
class ForumModule extends Module implements BootstrapInterface
{
    public $migrationTableOptions = 'CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE=InnoDB';
    public $userTableName = 'user';

    /**
     * {@inheritdoc}
     */
    public function bootstrap($app)
    {
        if ($app instanceof \yii\web\Application) {
            $app->getUrlManager()->addRules([
                [
                    'class' => 'yii\web\UrlRule',
                    'pattern' => $this->id . '/forum',
                    'route' => $this->id . '/forum'
                ],
            ], false);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function init()
    {
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
}
