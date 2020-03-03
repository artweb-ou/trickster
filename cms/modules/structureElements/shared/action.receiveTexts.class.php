<?php

class receiveTextsShared extends structureElementAction
{
    protected $loggable = true;

    public function execute(&$structureManager, &$controller, &$structureElement)
    {
        if ($this->validated) {
            $structureElement->prepareActualData();
            $structureElement->persistElementData();
            $controller->redirect($structureElement->URL);
        }
        $structureElement->executeAction("showForm");
    }

    public function setExpectedFields(&$expectedFields)
    {
        if($this->structureElement->getExpectedField('texts')) {
            $expectedFields = $this->structureElement->getExpectedField('texts');
        }
    }

    public function setValidators(&$validators)
    {
    }
}

