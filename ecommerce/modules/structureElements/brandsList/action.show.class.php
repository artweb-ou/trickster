<?php

class showBrandsList extends structureElementAction
{
    public function execute(&$structureManager, &$controller, &$structureElement)
    {
        $structureElement->setViewName('content');
    }
}

