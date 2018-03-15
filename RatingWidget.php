<?php

namespace kl83\widgets;

use yii\helpers\Html;
use yii\widgets\InputWidget;

class RatingWidget extends InputWidget
{
    public $maxRating = 5;
    public $filledFaClass = 'fa-star';
    public $halfFaClass = 'fa-star-half-o';
    public $emptyFaClass = 'fa-star-o';
    public $readOnly = false;

    
    public function run()
    {
        $content = '';
        
        $this->options['id'] = $this->id;
        $this->options['data-filled-class'] = $this->filledFaClass;
        $this->options['data-empty-class'] = $this->emptyFaClass;
        $this->options['class'] = isset($this->options['class'])?
                'rating-widget ' . $this->options['class']:
                'rating-widget';
        
        $value = $this->name ? $this->value : $this->model->{$this->attribute};
        
        if ($this->readOnly) {
            $this->options['class'] .= ' read-only';
            $value = round($value * 2) / 2;
            
            RatingWidgetReadOnlyAsset::register($this->view);
        } else {
            $inputId = $this->name ? "$this->id-input" : Html::getInputId($this->model, $this->attribute);
            $name = $this->name ? $this->name : Html::getInputName($this->model, $this->attribute);
            $value = round($value);
            $content .= Html::hiddenInput($name, $value, [ 'id' => $inputId ]);
            
            RatingWidgetAsset::register($this->view);
        }
        
        for ( $i = 0; $i < floor($value); $i++ ) {
            $content .= Html::tag('i','',['class' => "rating-widget-item fa {$this->filledFaClass}"]);
        }
        if ( round($value) != $value ) {
            $content .= Html::tag('i','',['class' => "rating-widget-item fa {$this->halfFaClass}"]);
        }
        for ( $i = ceil($value); $i < $this->maxRating; $i++ ) {
            $content .= Html::tag('i','',['class' => "rating-widget-item fa {$this->emptyFaClass}"]);
        }
        
        return Html::tag('div', $content, $this->options);
    }

    
    public static function widget($config = [])
    {
        if ( !isset($config['name']) && 
             !isset($config['model']) ) {
            
            $config['name'] = 'empty';
            $config['readOnly'] = true;
        }
        
        return parent::widget($config);
    }
}
