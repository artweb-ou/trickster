<?php

class InstagramSocialPluginElement extends socialPluginElement
{
    public function getSpecialFields()
    {
        return [
            'appId' => [
                'format' => 'text',
                'multiLanguage' => false,
            ],
            'appKey' => [
                'format' => 'text',
                'multiLanguage' => false,
            ],
        ];
    }

    public function getApiClass()
    {
        return 'instagramSocialNetworkAdapter';
    }

//    public function makePost() {
//
//    }
}