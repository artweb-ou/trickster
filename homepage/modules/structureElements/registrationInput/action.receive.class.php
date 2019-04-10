<?php

class receiveRegistrationInput extends structureElementAction
{
    protected $loggable = true;

    /**
     * @param structureManager $structureManager
     * @param controller $controller
     * @param registrationInputElement $structureElement
     * @return mixed|void
     */
    public function execute(&$structureManager, &$controller, &$structureElement)
    {
        if ($this->validated) {
            $structureElement->prepareActualData();

            $structureElement->fieldType = 'input';
            $structureElement->fieldName = 'field' . $structureElement->id;
            $structureElement->structureName = $structureElement->fieldName;

            $structureElement->persistElementData();

            $linksManager = $this->getService('linksManager');
            if ($formsIds = $structureElement->getConnectedFormsIds()) {
                foreach ($formsIds as $formId) {
                    if (!in_array($formId, $structureElement->registrationForms)) {
                        $linksManager->unLinkElements($formId, $structureElement->id, registrationInputElement::FIELD_LINK_TYPE);
                    }
                }
            }
            foreach ($structureElement->registrationForms as $formId) {
                if (!$formId) {
                    continue;
                }
                $linksManager->linkElements($formId, $structureElement->id, registrationInputElement::FIELD_LINK_TYPE);
            }

            if ($parentElement = $structureManager->getElementsFirstParent($structureElement->id)) {
                $controller->redirect($parentElement->URL);
            }
        } else {
            $structureElement->executeAction("showForm");
        }
    }

    public function setExpectedFields(&$expectedFields)
    {
        $expectedFields = [
            'title',
            'required',
            'validator',
            'autocomplete',
            'registrationForms',
        ];
    }

    public function setValidators(&$validators)
    {
        $validators['title'][] = 'notEmpty';
    }
}