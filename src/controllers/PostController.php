<?php

/**
 * @file PostController.php
 * @brief This file contains the PostController class.
 * @author kazda01, mifka01
 */

namespace rats\forum\controllers;

use rats\forum\ForumModule;
use rats\forum\models\form\PostForm;
use rats\forum\models\Thread;
use Yii;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\web\Controller;

class PostController extends Controller
{
    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'actions' => ['create'],
                        'allow' => true,
                        'roles' => ['forum-createPost']
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::class,
                'actions' => [
                    'create' => ['post'],
                ],
            ],
        ];
    }
    public function actionCreate()
    {
        $model = new PostForm();
        $thread = Thread::findOne(Yii::$app->request->post('PostForm')['fk_thread']);
        if ($model->load(Yii::$app->request->post()) && $model->addPost()) {
            return $this->redirect(['/' . ForumModule::getInstance()->id . "/thread/{$thread->slug}/{$thread->id}/{$model->_post->id}"]);
        }

        return $this->redirect(['/' . ForumModule::getInstance()->id . "/thread/{$thread->slug}/{$thread->id}"]);
    }
}
