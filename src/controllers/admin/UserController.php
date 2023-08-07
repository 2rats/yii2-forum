<?php

namespace rats\forum\controllers\admin;

use rats\forum\models\search\UserSearch;
use rats\forum\models\User;
use Yii;
use yii\bootstrap5\ActiveForm;
use yii\data\ActiveDataProvider;
use yii\web\NotFoundHttpException;
use yii\web\Response;
use yii\filters\AccessControl;
use yii\helpers\ArrayHelper;



/**
 * UserController implements the CRUD actions for User model.
 */
class UserController extends AdminController
{
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

    /**
     * Lists all User models.
     *
     * @return string
     */
    public function actionIndex()
    {
        $searchModel = new UserSearch();
        $dataProvider = $searchModel->search($this->request->queryParams);
        return $this->render('index', [
            'searchModel' => $searchModel, 
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single User model.
     * @param int $id
     * @return string
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }


    /**
     * Updates an existing User model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param int $id
     * @return string|\yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $user = $this->findModel($id);

        if (
            !$user->load($this->request->post())
        ) return $this->render('update', [
            'model' => $user,
        ]);

        // for Ajax validation
        if (Yii::$app->request->isAjax) {
            Yii::$app->response->format = Response::FORMAT_JSON;
            return ActiveForm::validate($user);
        }

        $transaction = Yii::$app->db->beginTransaction();

        if (
            $this->request->isPost && $user->save()
        ) {
            // assign role
            $role = Yii::$app->authManager->getRole($this->request->post('role'));
            if (!is_null($role)) {
                $has_perms = false;

                if ($role->name == 'forum-moderator' || $role->name == 'forum-user') {
                    if (Yii::$app->authManager->checkAccess(Yii::$app->user->identity->id, 'forum-assignModerator')) {
                        $has_perms = true;
                    }
                }

                if ($has_perms) {
                    Yii::$app->authManager->revokeAll($user->id);
                    Yii::$app->authManager->assign($role, $user->id);
                    $transaction->commit();
                    return $this->redirect(['view', 'id' => $user->id]);
                }
            }
        }

        $transaction->rollBack();
        return $this->render('update', [
            'model' => $user,
        ]);
    }

    /**
     * Deletes an existing User model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param int $id
     * @return \yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        $user = $this->findModel($id);
        $user_role = implode('', array_keys(Yii::$app->authManager->getRolesByUser($id)));

        if (str_contains($user_role, 'forum-moderator') || str_contains($user_role, 'forum-user')) {
            if (Yii::$app->authManager->checkAccess(Yii::$app->user->identity->id, 'forum-assignModerator')) {
                $user->status = User::STATUS_DELETED;
            }
        }

        $user->save();

        return $this->redirect(['index']);
    }

    /**
     * Finds the User model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param int $id
     * @return User the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = User::findOne(['id' => $id])) !== null) {
            return $model;
        }

        throw new NotFoundHttpException(Yii::t('app', 'The requested page does not exist.'));
    }
}
