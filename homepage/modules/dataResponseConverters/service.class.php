<?php

class serviceDataResponseConverter extends StructuredDataResponseConverter
{
    protected $defaultPreset = 'api';

    protected function getRelationStructure()
    {
        return [
            'id' => 'id',
            'title' => 'title',
            'searchTitle' => function ($element) {
                if ($relatedLanguage = $element->getRelatedLanguageElement()) {
                    return $element->title . '(' . $relatedLanguage->iso6393 . ')';
                } else {
                    return $element->title;
                }
            }, 'url' => 'getUrl',
            'structureType' => 'structureType',
            'image' => 'image',
            'content' => 'content',
            'introduction' => 'introduction',
            'dateCreated' => function ($element) {
                return $element->getValue('dateCreated');
            },
            'dateModified' => function ($element) {
                return $element->getValue('dateModified');
            },
        ];
    }

    protected function getPresetsStructure()
    {
        return [
            'api' => [
                'id',
                'title',
                'dateCreated',
                'dateModified',
                'url',
                'image',
                'content',
                'introduction',
            ],
            'search' => [
                'id',
                'searchTitle',
                'url',
                'structureType',
            ],
        ];
    }
}