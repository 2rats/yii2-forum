<?php

namespace rats\forum\controllers\admin;

use rats\forum\ForumModule;
use yii\helpers\ArrayHelper;
use yii\filters\AccessControl;

/**
 * AdminController
 */
class AdminController extends \yii\web\Controller
{
    public function init()
    {
        $this->layout = $this->module->adminLayout;
        parent::init();
    }

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
                            'roles' => ['forum-admin']
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
