<?php

class receiveArticle extends structureElementAction
{
    protected $loggable = true;

    /**
     * @param structureManager $structureManager
     * @param controller $controller
     * @param articleElement $structureElement
     * @return mixed|void
     */
    public function execute(&$structureManager, &$controller, &$structureElement)
    {
        if ($this->validated) {
            if ($structureElement->getDataChunk('image')->originalName !== null) {
                $structureElement->image = $structureElement->id;
                $structureElement->originalName = $structureElement->getDataChunk('image')->originalName;
            }
            $structureElement->structureName = $structureElement->title;
            $structureElement->persistElementData();

            $structureElement->persistDisplayMenusLinks();

            $controller->redirect($structureElement->URL);
        }
        $structureElement->setViewName('form');
    }

    public function setExpectedFields(&$expectedFields)
    {
        $expectedFields = [
            'title',
            'hideTitle',
            'content',
            'displayMenus',
            'allowComments',
            'image',
        ];
    }

    public function setValidators(&$validators)
    {
    }
}