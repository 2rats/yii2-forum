<?php

/**
 * @file ProfileController.php
 * @brief This file contains the ProfileController class.
 * @author kazda01, mifka01
 */

namespace rats\forum\controllers;

use Yii;
use rats\forum\models\User;
use yii\web\Controller;
use yii\web\NotFoundHttpException;

class ProfileController extends Controller
{
    public function init()
    {
        $this->layout = $this->module->forumLayout;
        parent::init();
    }

    public function actionView($id)
    {
        $user = User::find()->andWhere(['id' => $id, 'status' => [USER::STATUS_ACTIVE]])->one();
        if ($user == null) {
            throw new NotFoundHttpException(Yii::t('app', 'User not found'));
        }

        return $this->render('/profile/view', [
            'user' => $user,
        ]);
    }
}
