<?php

class deployDesignTheme extends designTheme
{
    public function initialize()
    {
        $pathsManager = controller::getInstance()->getPathsManager();
        $tricksterPath = $pathsManager->getPath('trickster');
        $this->cssPath = $tricksterPath . 'cms/css/deploy/';
        $this->templatesFolder = $tricksterPath . 'cms/templates/deploy/';
    }
}

