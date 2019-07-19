<?php

class NewsListLayoutStructure extends ElementForm
{
    protected $structure = [
        'cols' => [
            'type' => 'input.text',
            'inputType' => 'number',
//            'minValue'  => '2',
//            'maxValue'  => '4',
//            'stepValue' => '1',
        ],
        'captionLayout' => [
            'type' => 'select.index',
            'options' => [
                'hidden' => 'captionlayout_hidden',
                'above' => 'captionlayout_above',
                'below' => 'captionlayout_below',
                'over' => 'captionlayout_over',
            ],
        ],
    ];


    public function getFormComponents()
    {
        $structure = [];
        foreach ($this->element->getLayoutTypes() as $type) {
            $structure[$type] = [
                'type' => 'input.layouts_selection',
                'defaultLayout' => $this->element->getDefaultLayout($type),
                'layouts' => $this->element->getLayoutsSelection($type),
            ];
        }
        return $structure + $this->structure;
    }
}