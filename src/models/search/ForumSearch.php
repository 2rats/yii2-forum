<?php

namespace rats\forum\models\search;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use rats\forum\models\Forum;

/**
 * ForumSearch represents the model behind the search form of `rats\forum\models\Forum`.
 */
class ForumSearch extends Forum
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'fk_category', 'fk_parent', 'fk_last_post', 'status', 'threads', 'posts', 'created_by', 'updated_by'], 'integer'],
            [['name', 'description', 'created_at', 'updated_at'], 'safe'],
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
        $query = Forum::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'id' => $this->id,
            'fk_category' => $this->fk_category,
            'fk_parent' => $this->fk_parent,
            'fk_last_post' => $this->fk_last_post,
            'status' => $this->status,
            'threads' => $this->threads,
            'posts' => $this->posts,
            'created_by' => $this->created_by,
            'updated_by' => $this->updated_by,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ]);

        $query->andFilterWhere(['like', 'name', $this->name])
            ->andFilterWhere(['like', 'description', $this->description]);

        return $dataProvider;
    }
}
