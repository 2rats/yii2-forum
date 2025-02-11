<?php

namespace rats\forum\models;

use rats\forum\ForumModule;
use yii\behaviors\BlameableBehavior;
use yii\behaviors\TimestampBehavior;
use yii\db\Expression;
use yii\helpers\Html;
use yii\helpers\Markdown;
use Yii;

class ActiveRecord extends \yii\db\ActiveRecord
{
    public function behaviors()
    {
        return [
            [
                'class' => BlameableBehavior::class,
                'createdByAttribute' => 'created_by',
                'updatedByAttribute' => 'updated_by',
                'defaultValue' => 1,
            ],
            [
                'class' => TimestampBehavior::class,
                'createdAtAttribute' => 'created_at',
                'updatedAtAttribute' => 'updated_at',
                'value' => new Expression('NOW()')
            ]
        ];
    }

    /**
     * Formats the created_at attribute using Yii's formatter.
     *
     * @param string|null $format The format to use for the date/time. If null, a global default will be used.
     * @return string Formatted date/time string.
     */
    public function getCreatedAtString($format = null)
    {
        if ($format === null) {
            $format = 'd. M. Y, HH:mm';
        }

        return Yii::$app->formatter->asDateTime($this->created_at, $format);
    }

    /**
     * Formats the updated_ attribute using Yii's formatter.
     *
     * @param string|null $format The format to use for the date/time. If null, a global default will be used.
     * @return string Formatted date/time string.
     */
    public function getUpdatedAtString($format = null)
    {
        if ($format === null) {
            $format = 'd. M. Y, HH:mm';
        }

        return Yii::$app->formatter->asDateTime($this->updated_at, $format);
    }

    /**
     * Gets username of User who created Model or "deleted" if Model or User was removed.
     *
     * @return string
     */
    public function getCreatedByHtml()
    {
        if ($this->createdBy->isDeleted() || (method_exists($this, 'isDeleted') && $this->isDeleted())) {
            return Html::tag('em', \Yii::t('app', 'deleted'), ['class' => 'small']);
        }
        return Html::a($this->createdBy->getDisplayName(), $this->createdBy->getUrl(), ['class' => 'link-secondary link-underline-opacity-0 link-underline-opacity-100-hover']);
    }

    /**
     * Gets username of User who updated Model or "deleted" if Model or User was removed.
     *
     * @return string
     */
    public function getUpdatedByHtml()
    {
        if ($this->updatedBy->isDeleted() || (method_exists($this, 'isDeleted') && $this->isDeleted())) {
            return Html::tag('em', \Yii::t('app', 'deleted'), ['class' => 'small']);
        }
        return Html::a($this->updatedBy->getDisplayName(), $this->updatedBy->getUrl(), ['class' => 'link-secondary link-underline-opacity-0 link-underline-opacity-100-hover']);
    }

    /**
     * Gets roles of User who created Model or empty array if Model or User was removed.
     *
     * @return string[]
     */
    public function getCreatedByRoles()
    {
        if ($this->createdBy->isDeleted() || (method_exists($this, 'isDeleted') && $this->isDeleted())) {
            return [];
        }

        return $this->createdBy->roles;
    }

    /**
     * Gets signature of User who created Model or null if Model or User was removed.
     *
     * @return string|null
     */
    public function getCreatedBySignature()
    {
        if ($this->createdBy->isDeleted() || (method_exists($this, 'isDeleted') && $this->isDeleted())) {
            return null;
        }

        return Markdown::process($this->createdBy->signature ?? '', 'gfm-comment');
    }
}
