<?php

class receivePaymentSettingsMoneybookersPaymentMethod extends structureElementAction
{
    protected $loggable = true;

    public function execute(&$structureManager, &$controller, &$structureElement)
    {
        if ($this->validated) {
            $structureElement->prepareActualData();
            $structureElement->importExternalData([]);
            $structureElement->persistElementData();
            $controller->redirect($structureElement->URL);
        }
        $structureElement->executeAction("showForm");
    }

    public function setExpectedFields(&$expectedFields)
    {
        $expectedFields = [
            'sellerName',
            'sellerAccount',
            'sellerWord',
            'sellerWordMD5',
        ];
    }

    public function setValidators(&$validators)
    {
    }
}