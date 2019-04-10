<?php

class socialPostShared extends structureElementAction
{
    protected $loggable = true;

    public function execute(&$structureManager, &$controller, &$structureElement)
    {
        $url = false;
        if ($socialPostsElement = $structureManager->getElementByMarker('socialPosts')) {
            $url = $socialPostsElement->URL . 'type:socialPost/action:showForm/element/' . $structureElement->id;
        }
        if ($url) {
            $controller->redirect($url);
        }
    }
}

