<?php

/**
 * @file ForumController.php
 * @brief This file contains the ForumController class.
 * @author kazda01, mifka01
 */

namespace rats\forum\controllers;

use rats\forum\ForumModule;
use Yii;
use rats\forum\models\Post;
use rats\forum\models\Thread;
use yii\data\Pagination;
use yii\web\Controller;
use yii\web\NotFoundHttpException;

class ThreadController extends Controller
{
    public $page_items = 10;

    public function init()
    {
        $this->layout = $this->module->forumLayout;
        parent::init();
    }

    public function actionView($id, $path)
    {
        $thread = Thread::find()->andWhere(['id' => $id, 'status' => [Thread::STATUS_ACTIVE_LOCKED, Thread::STATUS_ACTIVE_UNLOCKED]])->one();
        if ($thread == null) {
            throw new NotFoundHttpException(Yii::t('app', 'Thread not found'));
        }
        if ($path != $thread->slug) {
            return $this->redirect(['/' . ForumModule::getInstance()->id . '/thread/view', 'id' => $thread->id, 'path' => $thread->slug]);
        }

        $query = $thread->getPosts();
        $countQuery = clone $query;
        $pages = new Pagination(['totalCount' => $countQuery->count(), 'pageSize' => $this->page_items]);
        $models = $query->offset($pages->offset)
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

    public function actionHighlight($id, $path, $post_id)
    {
        $thread = Thread::find()->andWhere(['id' => $id, 'status' => [Thread::STATUS_ACTIVE_LOCKED, Thread::STATUS_ACTIVE_UNLOCKED]])->one();
        if ($thread == null) {
            throw new NotFoundHttpException(Yii::t('app', 'Thread not found'));
        }
        $post = Post::findOne($post_id);
        if (!$post) {
            throw new NotFoundHttpException(Yii::t('app', 'Post not found'));
        }
        $page = 1;
        foreach ($thread->getPosts()->all() as $index => $thread_post) {
            if ($thread_post->id == $post->id) break;
            if (($index + 1) % $this->page_items == 0) $page += 1;
        }
        return $this->redirect(["/" . ForumModule::getInstance()->id . "/thread/view", 'id' => $id, 'path' => $path, 'post_id' => $post_id, 'page' => $page]);
    }
}
