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
use yii\web\NotFoundHttpException;

class PostController extends Controller
{
    public function init()
    {
        if ($this->layout === null) {
            $this->layout = $this->module->forumLayout;
        }
        parent::init();
    }

    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::class,
                'rules' => [
                    [
                        'actions' => ['create', 'upload-image'],
                        'allow' => true,
                        'roles' => ['forum-createPost'],
                        'matchCallback' => function () {
                            return !User::findOne(\Yii::$app->user->identity->id)->isMuted();
                        }
                    ],
                    [
                        'actions' => ['update', 'delete'],
                        'allow' => true,
                        'roles' => ['forum-editPost'],
                        'matchCallback' => function () {
                            return !User::findOne(\Yii::$app->user->identity->id)->isMuted();
                        },
                        'roleParams' => function () {
                            return ['model' => Post::findOne(\Yii::$app->request->get('id'))];
                        },
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

    public function actionUpdate($id = null)
    {
        $post = $this->findModel($id);
        $post->content = $post->printContent(false, false);

        $model = new PostForm([], $post);
        if ($model->load(\Yii::$app->request->post()) && $model->validate() && $model->save() && ($post = $model->getPost()) !== null) {
            

            if(\Yii::$app->request->isAjax) {
                \Yii::$app->response->setStatusCode(201);
                $response = [
                    'success' => true,
                    'url' => $post->getUrl(),
                ];
                return $this->asJson($response);
            }
            return $this->redirect($post->getUrl());
        }

        if (\Yii::$app->request->isAjax) {
            \Yii::$app->response->format = Response::FORMAT_JSON;

            return ActiveForm::validate($model);
        }

        return $this->render(
            '/post/update', [
            'model' => $model,
            ]
        );
    }


    public function actionCreate()
    {
        $model = new PostForm();
        $thread = Thread::findOne(\Yii::$app->request->post('PostForm')['fk_thread']);

        if ($model->load(\Yii::$app->request->post(), "PostForm")) {

            if(!\Yii::$app->request->isAjax) {
                // Not ajax, save the record and redirect
                if ($model->validate() && $model->save() && $model->getPost() !== null) {
                    $post = $model->getPost();
                    return $this->redirect($post->getUrl());
                }
            } else if(count($model->images) > 0 && $model->validate() && $model->save() && $model->getPost() !== null) {
                // With images, ajax, validate, save and return url JSON
                $post = $model->getPost();
                $response = [
                    'success' => true,
                    'url' => $post->getUrl(),
                ];
                return $this->asJson($response);
            } else {
                // With images invalid / without images valid or invalid, ajax, return validation errors 
                \Yii::$app->response->format = Response::FORMAT_JSON;
    
                return ActiveForm::validate($model);
            }
        }

        return $this->redirect(['/' . ForumModule::getInstance()->id . '/thread/view', 'id' => $thread->id, 'path' => $thread->slug]);
    }

    public function actionDelete()
    {
        $post = $this->findModel(\Yii::$app->request->get('id'));
        $thread = $post->thread;
        if ($post->delete()) {
            if($thread->lastPost !== null) {
                return $this->redirect($thread->lastPost->getUrl());
            }
            return $this->redirect($thread->getUrl());
        }
    }

    public function actionUploadImage()
    {
        $model = new ImageUploadForm(ImageUploadForm::DIR_PATH_POST);

        if (Yii::$app->request->getIsPost() 
            && $model->load(Yii::$app->request->post()) 
            && ($file = $model->upload()) !== false
        ) {
            return Json::encode(
                [
                'filename' => $file->getFileUrl(),
                ]
            );
        }
        return $this->asJson(
            [
            'error' => $model->getFirstError('file'),
            ]
        );
    }

    /**
     * Finds the Post model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     *
     * @param  int $id ID
     * @return Post the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Post::find()->active()->andWhere(['id' => $id])->one()) !== null) {
            return $model;
        }

        throw new NotFoundHttpException(Yii::t('app', 'The requested page does not exist.'));
    }
}
