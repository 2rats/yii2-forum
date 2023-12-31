<?php

namespace rats\forum\models;

use rats\forum\models\query\ForumQuery;

/**
 * This is the model class for table "forum_forum".
 *
 * @property int         $id
 * @property int         $fk_category
 * @property int|null    $fk_parent
 * @property int|null    $fk_last_post
 * @property string      $name
 * @property string|null $description
 * @property int         $status
 * @property int         $threads
 * @property int         $posts
 * @property int         $ordering
 * @property int         $created_by
 * @property int         $updated_by
 * @property string|null $created_at
 * @property string|null $updated_at
 * @property User        $createdBy
 * @property Forum       $parent
 * @property Thread[]    $threads
 * @property Forum[]     $forums
 * @property User        $updatedBy
 */
class Forum extends ActiveRecord
{
    public const STATUS_INACTIVE = 0;
    public const STATUS_ACTIVE_UNLOCKED = 1;
    public const STATUS_ACTIVE_LOCKED = 2;

    public static function tableName()
    {
        return 'forum_forum';
    }

    public function rules()
    {
        return [
            [['ordering', 'fk_parent', 'status', 'created_by', 'updated_by'], 'integer'],
            [['name', 'fk_category'], 'required'],
            [['description'], 'string'],
            [['ordering', 'created_at', 'updated_at'], 'safe'],
            [['name'], 'string', 'max' => 191],
            [['fk_parent'], 'exist', 'skipOnError' => true, 'targetClass' => Forum::class, 'targetAttribute' => ['fk_parent' => 'id']],
        ];
    }

    public function attributeLabels()
    {
        return [
            'id' => \Yii::t('app', 'ID'),
            'fk_parent' => \Yii::t('app', 'Parent'),
            'fk_category' => \Yii::t('app', 'Category'),
            'fk_last_post' => \Yii::t('app', 'Last post'),
            'name' => \Yii::t('app', 'Name'),
            'description' => \Yii::t('app', 'Description'),
            'status' => \Yii::t('app', 'Status'),
            'threads' => \Yii::t('app', 'Threads'),
            'posts' => \Yii::t('app', 'Posts'),
            'ordering' => \Yii::t('app', 'Ordering'),
            'created_by' => \Yii::t('app', 'Created by'),
            'updated_by' => \Yii::t('app', 'Updated by'),
            'created_at' => \Yii::t('app', 'Created at'),
            'updated_at' => \Yii::t('app', 'Updated at'),
        ];
    }

    public function beforeSave($insert)
    {
        if ($insert && $this->isNewRecord) {
            $this->ordering = static::find()->max('ordering') + 1;
        }

        return parent::beforeSave($insert);
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
     * Gets query for [[Parent]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getParent()
    {
        return $this->hasOne(Forum::class, ['id' => 'fk_parent']);
    }

    /**
     * Gets query for [[Category]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getCategory()
    {
        return $this->hasOne(Category::class, ['id' => 'fk_category']);
    }

    /**
     * Gets query for [[Threads]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getThreads($direct_only = true, $processed_forum_ids = [])
    {
        $threads_query = $this->hasMany(Thread::class, ['fk_forum' => 'id']);
        if ($direct_only) {
            return $threads_query;
        }

        if (!in_array($this->id, $processed_forum_ids, true)) {
            $processed_forum_ids[] = $this->id;

            foreach ($this->forums as $sub_forum) {
                $threads_query->union($sub_forum->getThreads(false, $processed_forum_ids));
            }
        }

        return $threads_query;
    }

    /**
     * Gets query for [[Posts]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getPosts($direct_only = true, $processed_forum_ids = [])
    {
        $posts_query = $this->hasMany(Post::class, ['fk_thread' => 'id'])
            ->viaTable('forum_thread', ['fk_forum' => 'id']);
        if ($direct_only) {
            return $posts_query;
        }

        if (!in_array($this->id, $processed_forum_ids, true)) {
            $processed_forum_ids[] = $this->id;

            foreach ($this->forums as $sub_forum) {
                $posts_query->union($sub_forum->getPosts(false, $processed_forum_ids));
            }
        }

        return $posts_query;
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
     * Gets query for [[Forums]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getForums()
    {
        return $this->hasMany(Forum::class, ['fk_parent' => 'id']);
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

    /**
     * Gets forum status in printable form.
     *
     * @return string
     */
    public function printStatus()
    {
        switch ($this->status) {
            case $this::STATUS_INACTIVE:
                return \Yii::t('app', 'Inactive');
                break;
            case $this::STATUS_ACTIVE_LOCKED:
                return \Yii::t('app', 'Locked');
                break;
            case $this::STATUS_ACTIVE_UNLOCKED:
                return \Yii::t('app', 'Unlocked');
                break;
        }

        return \Yii::t('app', 'Unknown status');
    }

    /**
     * {@inheritdoc}
     * @return ForumQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new ForumQuery(get_called_class());
    }
}
