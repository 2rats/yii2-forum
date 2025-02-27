<?php

namespace rats\forum\models\form;

use rats\forum\models\File;
use rats\forum\models\Post;
use rats\forum\models\Thread;
use rats\forum\models\User;
use Yii;
use yii\base\Model;

/**
 * ProfileForm is the model behind the profile update form.
 *
 */
class ProfileForm extends Model
{
    public $real_name;
    public $username;
    public $signature;

    public $fk_image;

    /**
     * @var User
     */
    public $_profile;

    /**
     * @return array the validation rules.
     */
    public function rules()
    {
        return [
            [['fk_image'], 'integer'],
            [['username', 'real_name'], 'string', 'max' => 191],
            [['signature'], 'string', 'max' => 512],
            [['username'], 'required'],
            [['real_name'], 'default'],
            [['username'], 'unique', 'targetClass' => User::class, 'filter' => function ($query) {
                $query->andWhere(['<>', 'id', Yii::$app->user->identity->id]);
            }],
        ];
    }

    public function attributeLabels()
    {
        return [
            'real_name' => \Yii::t('app', 'Real name'),
            'username' => \Yii::t('app', 'Username'),
            'signature' => \Yii::t('app', 'Signature'),
            'image' => \Yii::t('app', 'Profile picture'),
            'fk_image' => \Yii::t('app', 'Profile picture'),
        ];
    }

    public function init()
    {
        $this->_profile = User::findOne(Yii::$app->user->identity->id);
        $this->real_name = $this->_profile->real_name;
        $this->username = $this->_profile->username;
        $this->signature = $this->_profile->signature;
        $this->fk_image = $this->_profile->fk_image;
    }

    public function save()
    {
        $profile = User::findOne(Yii::$app->user->id);
        $profile->username = $this->username;
        $profile->real_name = $this->real_name;
        $profile->signature = $this->signature;
        $profile->fk_image = $this->fk_image;

        if ($profile->save()) {
            $this->_profile = $profile;
            return true;
        }
        return false;
    }

    /**
     * @return File[]
     */
    public function getPreviousImages(): array
    {
        $userPreviousImages = File::find()
            ->where(['LIKE', 'filename', ImageUploadForm::DIR_PATH_PROFILE])
            ->andWhere(['fk_user' => $this->_profile->id])->all();
        $defaultImages = File::findAll(['is_default_profile_image' => true]);
        return array_merge($userPreviousImages, $defaultImages);
    }
}
