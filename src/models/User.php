<?php

namespace rats\forum\models;

use rats\forum\ForumModule;
use Yii;

/**
 * This is the model class for table "forum_user".
 *
 * @property int $id
 * @property string $username
 * @property string|null $email
 * @property string|null $real_name
 * @property int $status
 * @property string|null $signature
 * @property int $created_by
 * @property int $updated_by
 * @property string|null $created_at
 * @property string|null $updated_at
 *
 * @property ForumModule::userClass $createdBy
 * @property ForumModule::userClass $updatedBy
 */
class User extends \yii\db\ActiveRecord
{
    const STATUS_DELETED = 0;
    const STATUS_ACTIVE = 1;

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'forum_user';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['username', 'created_by', 'updated_by'], 'required'],
            [['status', 'created_by', 'updated_by'], 'integer'],
            [['signature'], 'string'],
            [['created_at', 'updated_at'], 'safe'],
            [['username', 'email', 'real_name'], 'string', 'max' => 191],
            [['id'], 'exist', 'skipOnError' => true, 'targetClass' => ForumModule::getInstance()->userClass, 'targetAttribute' => ['id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'username' => Yii::t('app', 'Username'),
            'email' => Yii::t('app', 'Email'),
            'real_name' => Yii::t('app', 'Real name'),
            'status' => Yii::t('app', 'Status'),
            'signature' => Yii::t('app', 'Signature'),
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
        return $this->hasOne(ForumModule::getInstance()->userClass, ['id' => 'created_by']);
    }

    /**
     * Gets query for [[UpdatedBy]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getUpdatedBy()
    {
        return $this->hasOne(ForumModule::getInstance()->userClass, ['id' => 'updated_by']);
    }

    /**
     * Gets query for [[Posts]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getPosts()
    {
        return $this->hasMany(Post::class, ['created_by' => 'id']);
    }

    /**
     * Gets user roles.
     *
     * @return String[]
     */
    public function getRoles()
    {
        return Yii::$app->authManager->getRolesByUser($this->id);
    }
}
