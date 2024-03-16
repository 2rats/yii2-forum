<?php

namespace rats\forum\models\search;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use rats\forum\models\Thread;

/**
 * ThreadSearch represents the model behind the search form of `rats\forum\models\Thread`.
 */
class ThreadSearch extends Thread
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'fk_forum', 'fk_last_post', 'status', 'posts', 'views', 'pinned', 'created_by', 'updated_by'], 'integer'],
            [['name', 'created_at', 'updated_at'], 'safe'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    /**
     * Creates data provider instance with search query applied
     *
     * @param array $params
     *
     * @return ActiveDataProvider
     */
    public function search($params)
    {
        $query = Thread::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        // grid filtering conditions
        $query->andFilterWhere([
            'id' => $this->id,
            'fk_forum' => $this->fk_forum,
            'fk_last_post' => $this->fk_last_post,
            'status' => $this->status,
            'posts' => $this->posts,
            'views' => $this->views,
            'pinned' => $this->pinned,
            'created_by' => $this->created_by,
            'updated_by' => $this->updated_by,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ]);

        $query->andFilterWhere(['like', 'name', $this->name]);

        return $dataProvider;
    }
}
