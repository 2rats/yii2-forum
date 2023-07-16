<?php

/**
 * @file ForumController.php
 * @brief This file contains the ForumController class.
 * @author kazda01, mifka01
 */

namespace rats\forum\controllers;

use rats\forum\models\Forum;
use yii\web\Controller;

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

        ]);
    }
}
