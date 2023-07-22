<?php

namespace rats\forum\models;

/**
 * This is the model class for table "forum_vote".
 *
 * @property int         $id
 * @property int         $fk_post
 * @property int|null    $fk_user
 * @property int         $value
 * @property string|null $created_at
 * @property string|null $updated_at
 * @property Post        $post
 * @property User        $user
 */
class Vote extends ActiveRecord
{
    public const VALUE_DOWNVOTE = 0;
    public const VALUE_UPVOTE = 1;

    public static function tableName()
    {
        return 'forum_vote';
    }

    public function rules()
    {
        return [
            [['fk_post', 'value'], 'required'],
            [['fk_post', 'fk_user', 'value'], 'integer'],
            [['created_at', 'updated_at'], 'safe'],
            [['fk_post'], 'exist', 'skipOnError' => true, 'targetClass' => Post::class, 'targetAttribute' => ['fk_post' => 'id']],
            [['fk_user'], 'exist', 'skipOnError' => true, 'targetClass' => User::class, 'targetAttribute' => ['fk_user' => 'id']],
        ];
    }

    public function attributeLabels()
    {
        return [
            'id' => \Yii::t('app', 'ID'),
            'fk_post' => \Yii::t('app', 'Post'),
            'fk_user' => \Yii::t('app', 'User'),
            'value' => \Yii::t('app', 'Value'),
            'created_at' => \Yii::t('app', 'Created at'),
            'updated_at' => \Yii::t('app', 'Updated at'),
        ];
    }

    /**
     * Gets query for [[Post]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getPost()
    {
        return $this->hasOne(Post::class, ['id' => 'fk_post']);
    }

    /**
     * Gets query for [[User]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(User::class, ['id' => 'fk_user']);
    }
}
