<?php

class ArticleFormStructure extends ElementForm
{
    protected $structure = [
        'title' => [
            'type' => 'input.text',
        ],
        'hideTitle' => [
            'type' => 'input.checkbox',
            'translationGroup' => 'shared',
        ],
        'subTitle' => [
            'type' => 'input.textarea',
            'textarea_class' => 'short_textarea',
        ],
        'content' => [
            'type' => 'input.html',
        ],
        'image' => [
            'type' => 'input.image',
            'preset' => 'adminImage',
            'filename' => 'image',
        ],
        'allowComments' => [
            'type' => 'input.checkbox',
        ],
        'displayMenus' => [
            'type' => 'select.universal_options_multiple',
            'method' => 'getDisplayMenusInfo',
            'condition' => 'checkDisplayMenus',
        ],
    ];
    protected $additionalContent = 'shared.contentlist.tpl';
}