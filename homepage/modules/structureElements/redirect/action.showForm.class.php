<?php

class showFormRedirect extends structureElementAction
{
    public function execute(&$structureManager, &$controller, &$structureElement)
    {
        if ($structureElement->final) {
            if ($redirectionId = intval($controller->getParameter("logId"))) {
                $collection = persistableCollection::getInstance('404_log');

                $conditions = [
                    'id' => $redirectionId,
                ];

                if ($logRow = $collection->loadObject($conditions)) {
                    $structureElement->setFormValue("sourceUrl", $logRow->errorUrl);
                }
            }
            $structureElement->setTemplate('shared.content.tpl');
            $renderer = $this->getService('renderer');
            $renderer->assign('contentSubTemplate', 'component.form.tpl');
            $renderer->assign('form', $structureElement->getForm('form'));
        }
    }
}