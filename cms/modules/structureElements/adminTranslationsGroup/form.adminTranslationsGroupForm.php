<?php

class AdminTranslationsGroupFormStructure extends ElementForm
{
    protected $structure = [
        'title' => [
            'type' => 'input.text',
        ],
    ];

    protected $additionalContent = 'shared.contentlist_singlepage';
}