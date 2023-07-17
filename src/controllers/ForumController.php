<?php

/**
 * @file ForumController.php
 * @brief This file contains the ForumController class.
 * @author kazda01, mifka01
 */

namespace rats\forum\controllers;

use rats\forum\ForumModule;
use Yii;
use rats\forum\models\Forum;
use rats\forum\models\Thread;
use yii\web\Controller;
use yii\web\NotFoundHttpException;

class ForumController extends Controller
{
    public $layout = 'main';

    public function actionIndex()
    {
        return $this->render('index', [
            'forums' => Forum::find()->andWhere([
                'fk_parent' => null,
                'status' => [
                    Forum::STATUS_ACTIVE_LOCKED,
                    Forum::STATUS_ACTIVE_UNLOCKED
                ]
            ])->all(),
            'subforum' => false
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
        return $this->render('view', [
            'forum' => $forum,
            'threads' => $forum->getThreads()->andWhere(['status' => Thread::STATUS_ACTIVE])->all()
        ]);
    }
}
