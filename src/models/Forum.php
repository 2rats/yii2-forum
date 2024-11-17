<?php

namespace rats\forum\models;

use rats\forum\models\query\ForumQuery;
use yii\data\ActiveDataProvider;
use rats\forum\models\ForumModerator;


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
 * @property string|null $seo_title
 * @property string|null $seo_description
 * @property string|null $seo_keywords
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
            [['name', 'seo_title', 'seo_description', 'seo_keywords'], 'string', 'max' => 191],
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
     * @return \yii\db\ActiveQuery
     */
    public function getLastPost()
    {
        $this->hasOne(Post::class, ['id' => 'fk_last_post']);
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
     * Gets forum status options.
     *
     * @return string
     */
    public static function getStatusOptions()
    {
        return [
            self::STATUS_INACTIVE => \Yii::t('app', 'Inactive'),
            self::STATUS_ACTIVE_LOCKED => \Yii::t('app', 'Locked'),
            self::STATUS_ACTIVE_UNLOCKED => \Yii::t('app', 'Unlocked'),
        ];
    }

    /**
     * Gets forum status in printable form.
     *
     * @return string
     */
    public function printStatus()
    {
        $statuses = self::getStatusOptions();
        return isset($statuses[$this->status]) ? $statuses[$this->status] : Yii::t('app', 'Unknown status');
    }

    /**
     * @return bool whether forum is locked
     */
    public function isLocked(): bool
    {
        return $this->status == self::STATUS_ACTIVE_LOCKED;
    }


    /**
     * @return bool whether forum is active
     */
    public function isActive()
    {
        return $this->status !== self::STATUS_INACTIVE;
    }


    /**
     * {@inheritdoc}
     * @return ForumQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new ForumQuery(get_called_class());
    }

    public function getForumModerators()
    {
        return $this->hasMany(User::class, ['id' => 'fk_user'])->viaTable('forum_moderator', ['fk_forum' => 'id']);
    }


    public function getForumModeratorDataProvider()
    {
        return new ActiveDataProvider([
            'query' => ForumModerator::find()->where(['fk_forum' => $this->id]),
        ]);
    }

    /**
     * Get SEO title
     *
     * @return string
     */
    public function getSeoTitle()
    {
        if (!empty($this->seo_title)) {
            return $this->seo_title;
        }

        return $this->name;
    }


    /**
     * Get SEO description
     *
     * @return string
     */
    public function getSeoDescription()
    {
        return $this->seo_description;
    }


    /**
     * Get SEO keywords
     *
     * @return string
     */
    public function getSeoKeywords()
    {
        return $this->seo_keywords;
    }
}
