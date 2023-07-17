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

class ThreadController extends Controller
{
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
        return $this->render('view', [
            'thread' => $thread,
            'posts' => $thread->posts
        ]);
    }
}