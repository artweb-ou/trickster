<?php

class showLhvPaymentMethod extends structureElementAction
{
    public function execute(&$structureManager, &$controller, &$structureElement)
    {
        $structureElement->setViewName('short');
    }
}