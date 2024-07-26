<?php

namespace rats\forum\controllers\admin;

use rats\forum\models\Forum;
use rats\forum\models\Thread;
use rats\forum\models\search\ThreadSearch;
use Yii;
use yii\db\Query;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use rats\forum\ForumModule;

/**
 * ThreadController implements the CRUD actions for Thread model.
 */
class ThreadController extends AdminController
{
    /**
     * @inheritDoc
     */
    public function behaviors()
    {
        return array_merge(
            parent::behaviors(),
            [
                'verbs' => [
                    'class' => VerbFilter::className(),
                    'actions' => [
                        'delete' => ['POST'],
                    ],
                ],
            ]
        );
    }

    /**
     * Lists all Thread models.
     *
     * @return string
     */
    public function actionIndex()
    {
        $searchModel = new ThreadSearch();
        $dataProvider = $searchModel->search($this->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Thread model.
     * @param int $id ID
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
     * Creates a new Thread model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return string|\yii\web\Response
     */
    public function actionCreate()
    {
        $model = new Thread();

        if ($this->request->isPost) {
            if ($model->load($this->request->post()) && $model->save()) {
                return $this->redirect(['view', 'id' => $model->id]);
            }
        } else {
            $model->loadDefaultValues();
            $model->load(["Thread" => $this->request->get()]);
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing Thread model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param int $id ID
     * @return string|\yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($this->request->isPost && $model->load($this->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing Thread model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param int $id ID
     * @return \yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the Thread model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param int $id ID
     * @return Thread the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Thread::findOne(['id' => $id])) !== null) {
            return $model;
        }

        throw new NotFoundHttpException(Yii::t('app', 'The requested page does not exist.'));
    }

    public function actionForumList($q = null, $id = null)
    {
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        $out = ['results' => ['id' => '', 'text' => '']];
        if ($id > 0) {
            $out['results'] = ['id' => $id, 'text' => Forum::find($id)->name];
        }
        if (!is_null($q)) {
            $query = new Query();
            $query->select('id, name AS text')
                ->from('forum_forum')
                ->where(['like', 'name', $q])
                ->limit(20);
            $command = $query->createCommand();
            $data = $command->queryAll();
            $out['results'] = array_values($data);
        }
        return $out;
    }

    public function actionLock($id)
    {
        $model = $this->findModel($id);
        $model->status = Thread::STATUS_ACTIVE_LOCKED;
        $model->save();
        return $this->redirect(['/' . ForumModule::getInstance()->id . '/thread/view', 'id' => $model->id, 'path' => $model->slug]);
    }

    public function actionUnlock($id)
    {
        $model = $this->findModel($id);
        $model->status = Thread::STATUS_ACTIVE_UNLOCKED;
        $model->save();
        return $this->redirect(['/' . ForumModule::getInstance()->id . '/thread/view', 'id' => $model->id, 'path' => $model->slug]);
    }

    public function actionPin($id)
    {
        $model = $this->findModel($id);
        $model->pinned = $model::PINNED_TRUE;
        $model->save();
        return $this->redirect(['/' . ForumModule::getInstance()->id . '/thread/view', 'id' => $model->id, 'path' => $model->slug]);
    }

    public function actionUnpin($id)
    {
        $model = $this->findModel($id);
        $model->pinned = $model::PINNED_FALSE;
        $model->save();
        return $this->redirect(['/' . ForumModule::getInstance()->id . '/thread/view', 'id' => $model->id, 'path' => $model->slug]);
    }
}
