<?php

namespace rats\forum\models\form;

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
    public $email;
    public $signature;

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
            [['username', 'email', 'real_name'], 'string', 'max' => 191],
            [['signature'], 'string', 'max' => 512],
            [['username', 'email'], 'required'],
            [['real_name'], 'default'],
            [['email'], 'email'],
            [['username', 'email'], 'unique', 'targetClass' => User::class, 'filter' => function($query) {
                $query->andWhere(['<>', 'id', Yii::$app->user->identity->id]);
            }],
        ];
    }

    public function attributeLabels()
    {
        return [
            'real_name' => \Yii::t('app', 'Real name'),
            'username' => \Yii::t('app', 'Username'),
            'email' => \Yii::t('app', 'Email'),
            'signature' => \Yii::t('app', 'Signature'),
            'image' => \Yii::t('app', 'Profile picture'),
        ];
    }

    public function init()
    {
        $this->_profile = User::findOne(Yii::$app->user->identity->id);
        $this->real_name = $this->_profile->real_name;
        $this->username = $this->_profile->username;
        $this->email = $this->_profile->email;
        $this->signature = $this->_profile->signature;
    }

    public function save()
    {
        $profile = User::findOne(Yii::$app->user->id);
        $profile->username = $this->username;
        $profile->real_name = $this->real_name;
        $profile->email = $this->email;
        $profile->signature = $this->signature;
        if ($profile->save()) {
            $this->_profile = $profile;
            return true;
        }
        return false;
    }
}
