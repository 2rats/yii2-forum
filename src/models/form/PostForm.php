<?php

namespace rats\forum\models\form;

use rats\forum\models\Post;
use rats\forum\models\Thread;
use rats\forum\models\User;
use Yii;
use yii\base\Model;

/**
 * PostForm is the model behind the login form.
 *
 */
class PostForm extends Model
{
    public $fk_parent;

    public $fk_thread;

    public $content;

    /**
     * @var Post|null
     */
    private $post = null;

    public function __construct($config = [], ?Post $post = null)
    {
        parent::__construct($config);
        $this->post = $post;
        if ($post !== null) {
            $this->fk_parent = $post->fk_parent;
            $this->fk_thread = $post->fk_thread;
            $this->content = $post->content;
        }
    }

    /**
     * @return array the validation rules.
     */
    public function rules()
    {
        return [
            [['content'], 'required'],
            [['content'], 'string'],
            [['content'], 'validateMaxlength', 'params' => ['max' => 5000]],
            ['content', 'filter', 'filter' => function ($value) {
                return \yii\helpers\HtmlPurifier::process($value);
            }],
            [['fk_parent'], 'exist', 'skipOnError' => false, 'targetClass' => Post::class, 'targetAttribute' => ['fk_parent' => 'id']],
            [['fk_thread'], 'exist', 'skipOnError' => false, 'targetClass' => Thread::class, 'targetAttribute' => ['fk_thread' => 'id']],
        ];
    }

    public function validateMaxlength($attribute, $params)
    {
        if (mb_strlen($this->$attribute) > $params['max']) {
            $this->addError($attribute, Yii::t(
                'app',
                '{attribute} should contain at most {max, number} characters. (currently {current})',
                [
                    'attribute' => $this->attributeLabels()[$attribute],
                    'max' => $params['max'],
                    'current' => mb_strlen($this->$attribute),
                ]
            ));
        }
    }

    public function attributeLabels()
    {
        return [
            'content' => \Yii::t('app', 'Post text'),
        ];
    }

    public function save()
    {
        if ($this->post === null) {
            $this->post = new Post();
            $this->post->fk_parent = $this->fk_parent;
            $this->post->fk_thread = $this->fk_thread;
            $this->post->status = Post::STATUS_ACTIVE;
        }
        $this->post->content = $this->content;

        if ($this->post->save()) {
            $user = User::findOne(Yii::$app->user->id);
            $user->last_post_id = $this->post->id;
            $user->save();
            return true;
        }

        $this->post = null;
        return false;
    }

    public function getPost(): ?Post
    {
        return $this->post;
    }
}
