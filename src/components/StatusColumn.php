<?php

namespace rats\forum\components;

use Yii;
use kartik\grid\DataColumn;
use kartik\grid\GridView;

class StatusColumn extends DataColumn
{
    public $attribute = 'status';
    
    public function init()
    {
        parent::init();

        if ($this->value === null) {
            $this->value = function ($model) {
                if (method_exists($model, 'printStatus')) {
                    return $model->printStatus();
                }
                return Yii::t('app', 'Unknown status'); 
            };
        }

        if ($this->filterType === null) {
            $this->filterType = GridView::FILTER_SELECT2;
        }

        $statusData = $this->getStatusData();

        if (empty($this->filterWidgetOptions)) {
            $this->filterWidgetOptions = [
                'hideSearch' => true,
                'options' => ['prompt' => ''],
                'pluginOptions' => [
                    'allowClear' => true,
                ],
                'data' => $statusData,
            ];
        } else {
            $this->filterWidgetOptions['data'] = $statusData;
        }

        if (empty($this->headerOptions)) {
            $this->headerOptions = ['style' => 'min-width:150px'];
        }

    }

    protected function getStatusData()
    {
        $modelClass = $this->grid->filterModel;
        if ($modelClass && method_exists($modelClass, 'getStatusOptions')) {
            return $modelClass::getStatusOptions();
        }

        return [];
    }
}

