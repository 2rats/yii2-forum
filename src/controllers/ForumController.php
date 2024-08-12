<?php

/**
 * @file ForumController.php
 *
 * @brief This file contains the ForumController class.
 *
 * @author kazda01, mifka01
 */

namespace rats\forum\controllers;

use rats\forum\ForumModule;
use rats\forum\models\Category;
use rats\forum\models\Forum;
use rats\forum\models\User;
use rats\forum\models\Thread;
use yii\data\Pagination;
use yii\data\Sort;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\data\ActiveDataProvider;

class ForumController extends Controller
{
    public $page_items = 20;

    public function init()
    {
        $this->layout = $this->module->forumLayout;
        parent::init();
    }

    public function actionIndex()
    {
        return $this->render('category', [
            'categories' => Category::find()->active()->ordered()->all(),
        ]);
    }

    public function actionView($id, $path)
    {
        $forum = Forum::find()->andWhere(['id' => $id])->active()->one();
        if (null == $forum) {
            throw new NotFoundHttpException(\Yii::t('app', 'Forum not found'));
        }
        if ($path != $forum->slug) {
            return $this->redirect(['/' . ForumModule::getInstance()->id . 'forum/view', 'id' => $forum->id, 'path' => $forum->slug]);
        }

        $sort = new Sort([
            'attributes' => [
                'posts',
                'views',
                'name',
                'created_at',
                'last_post.created_at'
            ],
            'defaultOrder' => [
                'last_post.created_at' => SORT_DESC
            ]
        ]);

        $subquery = $forum->getThreads()->leftJoin('forum_post', 'forum_thread.fk_last_post = forum_post.id')->select('forum_thread.id as thread_id, forum_post.created_at');
        $query = $forum->getThreads()->select(['forum_thread.*'])
            ->innerJoin(['last_post' => $subquery], 'forum_thread.id = last_post.thread_id')
            ->active()->orderBy(['pinned' => SORT_DESC] + $sort->orders);

        $countQuery = clone $query;
        $pages = new Pagination(['totalCount' => $countQuery->count(), 'pageSize' => $this->page_items]);
        $models = $query->offset($pages->offset)
            ->limit($pages->limit)
            ->all();

        return $this->render('view', [
            'forum' => $forum,
            'threads' => $models,
            'pages' => $pages,
            'sort' => $sort,
        ]);
    }

    public function actionMembers()
    {
        return $this->render('members');
    }
}
