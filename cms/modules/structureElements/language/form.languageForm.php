<?php

class LanguageFormStructure extends ElementForm
{
    protected $structure = [
        'iso6393' => [
            'type' => 'input.text',
        ],
        'title' => [
            'type' => 'input.text',
        ],
        'hidden' => [
            'type' => 'input.checkbox',
        ],
        'image' => [
            'type' => 'input.image',
        ],
        'logoImage' => [
            'type' => 'input.image',
        ],
        'promoText' => [
            'type' => 'input.text',
        ],
        'backgroundImage' => [
            'type' => 'input.image',
        ],
    ];
}