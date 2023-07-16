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
    public function getThreads()
    {
        return $this->hasMany(Thread::class, ['fk_forum' => 'id']);
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
