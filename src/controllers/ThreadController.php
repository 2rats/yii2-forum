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
    public $layout = 'main';

    public function actionView($id, $path)
    {
        $thread = Thread::find()->andWhere(['id' => $id, 'status' => Thread::STATUS_ACTIVE])->one();
        if ($thread == null) {
            throw new NotFoundHttpException(Yii::t('app', 'Thread not found'));
        }
        if ($path != $thread->slug) {
            return $this->redirect('/' . ForumModule::getInstance()->id . '/thread/' . $thread->slug . '/' . $thread->id);
        }

        $query = $thread->getPosts();
        $countQuery = clone $query;
        $pages = new Pagination(['totalCount' => $countQuery->count(), 'pageSize' => $this->page_items]);
        $models = $query->offset($pages->offset)
            ->limit($pages->limit)
            ->all();

        return $this->render('view', [
            'thread' => $thread,
            'posts' => $models,
            'pages' => $pages,
        ]);
    }

    public function actionHighlight($id, $path, $post_id)
    {
        $thread = Thread::find()->andWhere(['id' => $id, 'status' => Thread::STATUS_ACTIVE])->one();
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
        return $this->redirect("/" . ForumModule::getInstance()->id . "/thread/$path/$id?page=$page&post_id=$post_id");
    }
}
