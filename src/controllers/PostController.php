<?php

/**
 * @file PostController.php
 *
 * @brief This file contains the PostController class.
 *
 * @author kazda01, mifka01
 */

namespace rats\forum\controllers;

use rats\forum\ForumModule;
use rats\forum\models\form\PostForm;
use rats\forum\models\Thread;
use rats\forum\models\User;
use rats\forum\models\Post;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\web\Controller;
use yii\web\Response;
use yii\widgets\ActiveForm;
use rats\forum\models\form\ImageUploadForm;
use yii\helpers\Json;
use Yii;

class PostController extends Controller
{
    public function init()
    {
        $this->layout = $this->module->forumLayout;
        parent::init();
    }

    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'actions' => ['create', 'upload-image'],
                        'allow' => true,
                        'roles' => ['forum-createPost'],
                        'matchCallback' => function () {
                            return !User::findOne(\Yii::$app->user->identity->id)->isMuted();
                        }
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::class,
                'actions' => [
                    'create' => ['post'],
                    'upload-image' => ['post'],
                ],
            ],
        ];
    }

    public function actionCreate()
    {
        $model = new PostForm();
        $thread = Thread::findOne(\Yii::$app->request->post('PostForm')['fk_thread']);
        if ($model->load(\Yii::$app->request->post()) && $model->validate() && $model->addPost()) {
            return $this->redirect(['/' . ForumModule::getInstance()->id . '/thread/highlight', 'id' => $thread->id, 'path' => $thread->slug, 'post_id' => $model->_post->id]);
        }

        if (\Yii::$app->request->isAjax) {
            \Yii::$app->response->format = Response::FORMAT_JSON;

            return ActiveForm::validate($model);
        }

        return $this->redirect(['/' . ForumModule::getInstance()->id . '/thread/view', 'id' => $thread->id, 'path' => $thread->slug]);
    }

    public function actionUploadImage()
    {
        $model = new ImageUploadForm(ImageUploadForm::DIR_PATH_POST);

        if (
            Yii::$app->request->getIsPost() &&
            $model->load(Yii::$app->request->post()) &&
            ($file = $model->upload()) !== false
        ) {
            return Json::encode([
                'filename' => $file->getFileUrl(),
            ]);
        }
    }
}
