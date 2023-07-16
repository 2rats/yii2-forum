<?php

namespace rats\forum\models;

use Yii;

/**
 * This is the model class for table "forum_forum".
 *
 * @property int $id
 * @property int|null $fk_parent
 * @property string $name
 * @property string|null $description
 * @property int $status
 * @property int $created_by
 * @property int $updated_by
 * @property string|null $created_at
 * @property string|null $updated_at
 *
 * @property User $createdBy
 * @property Forum $parent
 * @property Thread[] $threads
 * @property Forum[] $forums
 * @property User $updatedBy
 */
class Forum extends \yii\db\ActiveRecord
{
    const STATUS_INACTIVE = 0;
    const STATUS_ACTIVE_UNLOCKED = 1;
    const STATUS_ACTIVE_LOCKED = 2;

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'forum_forum';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['fk_parent', 'status', 'created_by', 'updated_by'], 'integer'],
            [['name', 'created_by', 'updated_by'], 'required'],
            [['description'], 'string'],
            [['created_at', 'updated_at'], 'safe'],
            [['name'], 'string', 'max' => 191],
            [['fk_parent'], 'exist', 'skipOnError' => true, 'targetClass' => Forum::class, 'targetAttribute' => ['fk_parent' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'fk_parent' => Yii::t('app', 'Parent'),
            'name' => Yii::t('app', 'Name'),
            'description' => Yii::t('app', 'Description'),
            'status' => Yii::t('app', 'Status'),
            'created_by' => Yii::t('app', 'Created by'),
            'updated_by' => Yii::t('app', 'Updated by'),
            'created_at' => Yii::t('app', 'Created at'),
            'updated_at' => Yii::t('app', 'Updated at'),
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
     * Gets query for [[Parent]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getParent()
    {
        return $this->hasOne(Forum::class, ['id' => 'fk_parent']);
    }

    /**
     * Gets query for [[Threads]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getThreads($direct_only = true, $processed_forum_ids = [])
    {
        $threads_query  = $this->hasMany(Thread::class, ['fk_forum' => 'id']);
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
        return $this->getPosts(false)->orderBy('created_at DESC')->one();
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
}
