<?php

/**
 * @file ForumController.php
 * @brief This file contains the ForumController class.
 * @author kazda01, mifka01
 */

namespace rats\forum\controllers;

use rats\forum\ForumModule;
use rats\forum\models\Category;
use Yii;
use rats\forum\models\Forum;
use rats\forum\models\Thread;
use yii\data\Pagination;
use yii\data\Sort;
use yii\web\Controller;
use yii\web\NotFoundHttpException;

class ForumController extends Controller
{
    public $layout = 'main';
    public $page_items = 20;

    public function actionIndex()
    {
        return $this->render('category', [
            'categories' => Category::find()->all(),
        ]);
    }

    public function actionView($id, $path)
    {
        $forum = Forum::find()->andWhere(['id' => $id, 'status' => [Forum::STATUS_ACTIVE_LOCKED, Forum::STATUS_ACTIVE_UNLOCKED]])->one();
        if ($forum == null) {
            throw new NotFoundHttpException(Yii::t('app', 'Forum not found'));
        }
        if ($path != $forum->slug) {
            return $this->redirect('/' . ForumModule::getInstance()->id . '/' . $forum->slug . '/' . $forum->id);
        }

        $sort = new Sort([
            'attributes' => [
                'posts',
                'views',
                'name',
                'created_at'
            ],
        ]);

        $query = $forum->getThreads()->andWhere(['status' => Thread::STATUS_ACTIVE])->orderBy($sort->orders);
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
}
