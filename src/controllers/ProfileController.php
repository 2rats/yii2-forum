<?php

/**
 * @file ProfileController.php
 * @brief This file contains the ProfileController class.
 * @author kazda01, mifka01
 */

namespace rats\forum\controllers;

use rats\forum\ForumModule;
use rats\forum\models\form\ProfileForm;
use Yii;
use rats\forum\models\User;
use yii\bootstrap5\ActiveForm;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\web\Response;
use rats\forum\models\form\ImageUploadForm;
use rats\forum\models\Post;
use yii\data\ActiveDataProvider;

class ProfileController extends Controller
{
    public function init()
    {
        $this->layout = $this->module->forumLayout;
        parent::init();
    }

    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'actions' => ['view'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                    [
                        'actions' => ['update', 'remove-image'],
                        'allow' => true,
                        'matchCallback' => function () {
                            return Yii::$app->user->identity->id == Yii::$app->request->get('id');
                        }
                    ],
                ],
            ],
        ];
    }

    public function actionView($id)
    {
        $user = User::find()->andWhere(['id' => $id])->active()->one();
        if ($user == null) {
            throw new NotFoundHttpException(Yii::t('app', 'User not found'));
        }

        $postsDataProvider = new ActiveDataProvider([
            'query' => Post::find()->active()->createdBy($id)->orderBy(['created_at' => SORT_DESC]),
            'pagination' => [
                'pageSize' => 20,
            ],
        ]);

        return $this->render('/profile/view', [
            'user' => $user,
            'postsDataProvider' => $postsDataProvider,
        ]);
    }

    public function actionRemoveImage($id)
    {
        $user = User::find()->andWhere(['id' => $id])->active()->one();
        if ($user == null) {
            throw new NotFoundHttpException(Yii::t('app', 'User not found'));
        }

        $user->fk_image = null;
        $user->save(false);

        return $this->redirect(['view', 'id' => $user->id]);
    }

    public function actionUpdate($id)
    {
        $model = new ProfileForm();
        $uploadImage = new ImageUploadForm(ImageUploadForm::DIR_PATH_PROFILE);
        $uploadImage->skipOnEmpty = true;
        $uploadImage->useActiveName = true;

        if ($model->load(Yii::$app->request->post()) && $model->validate() && $model->save()) {
            if ($uploadImage->load(Yii::$app->request->post())) {
                $file = $uploadImage->upload();
                if ($file != null) {
                    $model->_profile->fk_image = $file->id;
                    $model->_profile->save(false);
                }
            }
            return $this->redirect($model->_profile->getUrl());
        }

        if (Yii::$app->request->isAjax) {
            Yii::$app->response->format = Response::FORMAT_JSON;
            return ActiveForm::validate($model);
        }

        return $this->render('update', [
            'profileFormModel' => $model,
            'imageUploadFormModel' => $uploadImage,
        ]);
    }
}
