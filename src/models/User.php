<?php

namespace rats\forum\models;

use rats\forum\ForumModule;
use rats\forum\models\query\UserQuery;

/**
 * This is the model class for table "forum_user".
 *
 * @property int                    $id
 * @property string                 $username
 * @property string|null            $email
 * @property string|null            $real_name
 * @property int                    $status
 * @property string|null            $signature
 * @property int                    $created_by
 * @property int                    $updated_by
 * @property string|null            $created_at
 * @property string|null            $updated_at
 * @property ForumModule::userClass $createdBy
 * @property ForumModule::userClass $updatedBy
 */
class User extends ActiveRecord
{
    public const STATUS_DELETED = 0;
    public const STATUS_ACTIVE = 1;
    public const STATUS_MUTED = 2;

    public static function tableName()
    {
        return 'forum_user';
    }

    public function rules()
    {
        return [
            [['username'], 'required'],
            [['status', 'created_by', 'updated_by'], 'integer'],
            [['signature'], 'string'],
            [['created_at', 'updated_at'], 'safe'],
            [['username', 'email', 'real_name'], 'string', 'max' => 191],
        ];
    }

    public function attributeLabels()
    {
        return [
            'id' => \Yii::t('app', 'ID'),
            'username' => \Yii::t('app', 'Username'),
            'email' => \Yii::t('app', 'Email'),
            'real_name' => \Yii::t('app', 'Real name'),
            'status' => \Yii::t('app', 'Status'),
            'signature' => \Yii::t('app', 'Signature'),
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
     * Gets query for [[Threads]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getThreads()
    {
        return $this->hasMany(Thread::class, ['created_by' => 'id']);
    }

    /**
     * Gets user roles.
     *
     * @return string[]
     */
    public function getRoles()
    {
        return array_map(function ($role) {
            $role->name = \Yii::t('app', ucfirst(substr($role->name, mb_strlen('forum-'))));

            return $role;
        }, array_filter(\Yii::$app->authManager->getRolesByUser($this->id), function ($role) {
            return 0 === strpos($role->name, 'forum-');
        }));
    }

    /**
     * Gets user assignable roles as data for Select2.
     * Used in admin/user/.
     *
     * @return string[]
     */
    public static function getAvailableRoles()
    {
        $roles = [
            'forum-user' => \Yii::t('app', 'User'),
        ];

        if (\Yii::$app->authManager->checkAccess(\Yii::$app->user->identity->id, 'forum-assignModerator')) {
            $roles['forum-moderator'] = \Yii::t('app', 'Moderator');
        }

        return $roles;
    }

    /**
     * Gets user status options.
     *
     * @return string
     */
    public static function getStatusOptions()
    {
        return [
            self::STATUS_ACTIVE => \Yii::t('app', 'Active'),
            self::STATUS_DELETED => \Yii::t('app', 'Deleted'),
            self::STATUS_MUTED => \Yii::t('app', 'Muted'),
        ];
    }

    /**
     * Gets user status in printable form.
     *
     * @return string
     */
    public function printStatus()
    {
        $statuses = self::getStatusOptions();
        return isset($statuses[$this->status]) ? $statuses[$this->status] : Yii::t('app', 'Unknown status');
    }

    /**
     * Gets user roles in printable form.
     *
     * @return string
     */
    public function printRoles()
    {
        return implode(', ', array_map(
            function ($role) {
                return \Yii::t('app', ucfirst($role->name));
            },
            $this->roles
        ));
    }

    /**
     * {@inheritdoc}
     * @return UserQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new UserQuery(get_called_class());
    }

    /**
     * @return bool whether user is active
     */
    public function isActive()
    {
        return $this->status == self::STATUS_ACTIVE;
    }

    /**
     * @return bool whether user is muted
     */
    public function isMuted()
    {
        return $this->status == self::STATUS_MUTED;
    }


    /**
     * @param Thread $thread
     * @return bool whether user can post in thread
     */
    public function canPost(Thread $thread)
    {
        return \Yii::$app->user->can('forum-createPost') &&
            !$this->isMuted() &&
            $thread->isActive() && !$thread->isLocked();
    }
}
