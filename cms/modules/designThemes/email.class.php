<?php

class emailDesignTheme extends designTheme
{
    public function initialize()
    {
        $this->inheritedThemes = ['document'];
        $pathsManager = controller::getInstance()->getPathsManager();
        $tricksterPath = $pathsManager->getPath('trickster');
        $this->cssPath = $tricksterPath . 'cms/css/email/';
        $this->templatesFolder = $tricksterPath . 'cms/templates/email/';
    }
}