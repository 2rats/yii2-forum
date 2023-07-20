<?php

namespace rats\forum\controllers\admin;

use yii\helpers\ArrayHelper;
use yii\filters\AccessControl;

/**
 * AdminController
 */
class AdminController extends \yii\web\Controller
{
    public $layout = 'main';

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
                            'roles' => ['forum-admin', 'forum-moderator']
                        ]
                    ]
                ]
            ]
        );
    }

    public function actionIndex()
    {
        return $this->render('/admin/index');
    }
}
