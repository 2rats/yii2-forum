<?php

/**
 * @file ForumController.php
 * @brief This file contains the ForumController class.
 * @author kazda01, mifka01
 */

namespace rats\forum\controllers;

use yii\web\Controller;

class ForumController extends Controller
{
    public function actionIndex()
    {
        return $this->render('index');
    }
}
