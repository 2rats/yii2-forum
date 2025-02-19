<?php

namespace rats\forum\models\form;

use rats\forum\models\Forum;
use rats\forum\models\Post;
use rats\forum\models\Thread;
use Yii;
use yii\base\Model;

class ThreadCreateForm extends Model
{
    private Forum $forum;

    private PostForm $postForm;

    public function __construct(Forum $forum, $config = [])
    {
        $this->forum = $forum;
        parent::__construct($config);

        $this->postForm = new PostForm();
    }

    private ?Thread $newThread = null;

    public $name;

    public $content;

    public $images;

    /**
     * @return array the validation rules.
     */
    public function rules()
    {
        return [
            [['name'], 'required'],
            [['name'], 'string', 'max' => 191],

            [['content', 'images'], 'safe'],
        ];
    }

    public function attributeLabels()
    {
        return [
            'name' => \Yii::t('app', 'Name'),
            'content' => \Yii::t('app', 'Post text'),
        ];
    }

    public function load($data, $formName = null)
    {
        $loaded = parent::load($data, $formName);
        $loaded = $this->postForm->load($data, 'ThreadCreateForm') && $loaded;

        return $loaded;
    }

    public function validate($attributeNames = null, $clearErrors = true)
    {
        $valid = parent::validate($attributeNames, $clearErrors);
        $valid = $this->postForm->validate() && $valid;

        return $valid;
    }

    public function getErrors($attribute = null)
    {
        $errors = parent::getErrors($attribute);
        $errors = array_merge($this->postForm->getErrors(), $errors);

        return $errors;
    }

    public function save(): bool
    {
        $transaction = Yii::$app->db->beginTransaction();

        $thread = new Thread();
        $thread->fk_forum = $this->forum->id;
        $thread->status = Thread::STATUS_ACTIVE_UNLOCKED;
        $thread->name = $this->name;
        if ($thread->save()) {
            $this->postForm->fk_thread = $thread->id;

            if ($this->postForm->save()) {
                $transaction->commit();
                $this->newThread = $thread;
                return true;
            }
        }

        $transaction->rollBack();
        return false;
    }

    public function getThread(): ?Thread
    {
        return $this->newThread;
    }

    public function getImages()
    {
        return $this->postForm->images;
    }

    public function registerJs(): void
    {
        $this->postForm->registerJs('ThreadCreateForm');
    }
}
