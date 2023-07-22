<?php

namespace rats\forum\models;

/**
 * This is the model class for table "forum_thread".
 *
 * @property int         $id
 * @property int         $fk_forum
 * @property string      $name
 * @property int         $status
 * @property int         $threads
 * @property int         $views
 * @property int         $pinned
 * @property int         $created_by
 * @property int         $updated_by
 * @property string|null $created_at
 * @property string|null $updated_at
 * @property User        $createdBy
 * @property Forum       $forum
 * @property Post[]      $posts
 * @property User        $updatedBy
 */
class Thread extends ActiveRecord
{
    public const STATUS_INACTIVE = 0;
    public const STATUS_ACTIVE = 1;

    public const PINNED_FALSE = 0;
    public const PINNED_TRUE = 1;

    public static function tableName()
    {
        return 'forum_thread';
    }

    public function rules()
    {
        return [
            [['fk_forum', 'name'], 'required'],
            [['fk_forum', 'status', 'views', 'pinned', 'created_by', 'updated_by'], 'integer'],
            [['created_at', 'updated_at'], 'safe'],
            [['name'], 'string', 'max' => 191],
            [['fk_forum'], 'exist', 'skipOnError' => true, 'targetClass' => Forum::class, 'targetAttribute' => ['fk_forum' => 'id']],
        ];
    }

    public function attributeLabels()
    {
        return [
            'id' => \Yii::t('app', 'ID'),
            'fk_forum' => \Yii::t('app', 'Forum'),
            'name' => \Yii::t('app', 'Name'),
            'status' => \Yii::t('app', 'Status'),
            'views' => \Yii::t('app', 'Views'),
            'pinned' => \Yii::t('app', 'Pinned'),
            'created_by' => \Yii::t('app', 'Created by'),
            'updated_by' => \Yii::t('app', 'Updated by'),
            'created_at' => \Yii::t('app', 'Created at'),
            'updated_at' => \Yii::t('app', 'Updated at'),
        ];
    }

    /**
     * Gets query for [[CreatedBy]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getCreatedBy()
    {
        return $this->hasOne(User::class, ['id' => 'created_by']);
    }

    /**
     * Gets query for [[Forum]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getForum()
    {
        return $this->hasOne(Forum::class, ['id' => 'fk_forum']);
    }

    /**
     * Gets query for [[Posts]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getPosts()
    {
        return $this->hasMany(Post::class, ['fk_thread' => 'id'])->orderBy('id ASC');
    }

    /**
     * Gets query for [[LastPost]].
     *
     * @return Post|null
     */
    public function getLastPost()
    {
        return null === $this->fk_last_post ? null : $this->hasOne(Post::class, ['id' => 'fk_last_post']);
    }

    /**
     * Gets query for [[UpdatedBy]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getUpdatedBy()
    {
        return $this->hasOne(User::class, ['id' => 'updated_by']);
    }

    /**
     * @return string slug
     */
    public function getSlug()
    {
        return \yii\helpers\Inflector::slug($this->name);
    }
}
