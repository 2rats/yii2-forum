<?php

/**
 * @file ForumController.php
 * @brief This file contains the ForumController class.
 * @author kazda01, mifka01
 */

namespace rats\forum\controllers;

use rats\forum\ForumModule;
use rats\forum\models\form\ThreadCreateForm;
use Yii;
use rats\forum\models\User;
use rats\forum\models\Forum;
use rats\forum\models\Post;
use rats\forum\models\Thread;
use yii\data\Pagination;
use yii\data\Sort;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\helpers\ArrayHelper;
use yii\filters\AccessControl;
use yii\web\Response;
use yii\widgets\ActiveForm;

class ThreadController extends Controller
{
    public $page_items = 10;

    public function init()
    {
        if ($this->layout === null) {
            $this->layout = $this->module->forumLayout;
        }
        parent::init();
    }

    /**
     * @inheritDoc
     */
    public function behaviors()
    {
        return ArrayHelper::merge(
            parent::behaviors(),
            [
                'access' => [
                    'class' => AccessControl::class,
                    'rules' => [
                        [
                            'allow' => true,
                            'roles' => ['@'],
                            'actions' => ['create']
                        ],
                        [
                            'allow' => true,
                            'actions' => ['view', 'hot', 'highlight']
                        ]
                    ]
                ]
            ]
        );
    }

    public function actionHot()
    {
        $query = Thread::find()->select(['forum_thread.*'])
            ->leftJoin('forum_post AS last_post', 'forum_thread.fk_last_post = last_post.id')
            ->active()->orderBy(['last_post.created_at' => SORT_DESC])->andWhere('last_post.created_at > NOW() - INTERVAL 3 DAY');

        $countQuery = clone $query;
        $pages = new Pagination(['totalCount' => $countQuery->count(), 'pageSize' => 20]);
        $models = $query->offset($pages->offset)
            ->limit($pages->limit)
            ->all();

        return $this->render('hot', [
            'threads' => $models,
            'pages' => $pages,
        ]);
    }

    public function actionView($id, $path)
    {
        $thread = Thread::find()->active()->andWhere(['id' => $id])->one();
        if ($thread == null) {
            throw new NotFoundHttpException(Yii::t('app', 'Thread not found'));
        }
        if ($path != $thread->slug) {
            return $this->redirect(['/' . ForumModule::getInstance()->id . '/thread/view', 'id' => $thread->id, 'path' => $thread->slug]);
        }

        $query = $thread->getPosts();
        $countQuery = clone $query;
        $pages = new Pagination(['totalCount' => $countQuery->count(), 'pageSize' => $this->page_items]);
        $models = $query->offset($pages->offset)->with(['createdBy'])
            ->limit($pages->limit)
            ->all();

        // Log view
        $cookies = Yii::$app->request->cookies;
        $visited_threads = $cookies->getValue('visited_threads', []);
        if (!\yii\helpers\ArrayHelper::isIn($thread->id, $visited_threads)) {
            $cookies = Yii::$app->response->cookies;
            // add a new cookie to the response to be sent
            $cookies->add(new \yii\web\Cookie([
                'name' => 'visited_threads',
                'value' => \yii\helpers\ArrayHelper::merge([$thread->id], $visited_threads),
                'expire' => 0, // until the browser is closed
            ]));
            $thread->updateCounters(['views' => 1]);
        }

        return $this->render('view', [
            'thread' => $thread,
            'posts' => $models,
            'pages' => $pages,
        ]);
    }

    public function actionHighlight($id, $post_id)
    {
        $post = Post::findOne($post_id);
        if (!$post) {
            throw new NotFoundHttpException(Yii::t('app', 'Post not found'));
        }

        $thread = Thread::find()->active()->andWhere(['id' => $id])->one();
        if ($thread == null) {
            $thread = $post->thread;
            if ($thread == null) {
                throw new NotFoundHttpException(Yii::t('app', 'Thread not found'));
            }
        }

        $page = 1;
        foreach ($thread->getPosts()->asArray()->select('id')->all() as $index => $data) {
            if ($data['id'] == $post->id) break;
            if (($index + 1) % $this->page_items == 0) $page += 1;
        }
        return $this->redirect($thread->getUrl(['post_id' => $post_id, 'page' => $page]));
    }

    public function actionCreate($fk_forum)
    {
        $forum = Forum::find()->active()->andWhere(['id' => $fk_forum])->one();
        if ($forum == null) {
            throw new NotFoundHttpException(Yii::t('app', 'Forum not found'));
        }
        if (!User::findOne(Yii::$app->user->identity->id)->canCreateThread($forum)) {
            throw new NotFoundHttpException(Yii::t('app', 'You are not allowed to create threads'));
        }


        $model = new ThreadCreateForm($forum);

        if ($model->load(Yii::$app->request->post())) {

            if (!Yii::$app->request->isAjax) {
                // Not ajax, save the record and redirect
                if ($model->validate() && $model->save() && $model->getThread() !== null) {
                    $thread = $model->getThread();
                    return $this->redirect($thread->getUrl());
                }
            } else if (count($model->getImages()) > 0 && $model->validate() && $model->save() && $model->getThread() !== null) {
                // With images, ajax, validate, save and return url JSON
                $thread = $model->getThread();
                $response = [
                    'success' => true,
                    'url' => $thread->getUrl(),
                ];
                return $this->asJson($response);
            } else {
                // With images invalid / without images valid or invalid, ajax, return validation errors 
                Yii::$app->response->format = Response::FORMAT_JSON;

                return ActiveForm::validate($model);
            }
        }

        return $this->render('create', [
            'model' => $model,
            'forum' => $forum,
        ]);
    }
}
