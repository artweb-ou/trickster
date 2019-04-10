<?php

class newsMailSubContentCategoryElement extends menuDependantStructureElement
{
    public $dataResourceName = 'module_newsmail_subcontentcategory';
    protected $allowedTypes = [];
    public $defaultActionName = 'show';
    public $role = 'content';

    protected function setModuleStructure(&$moduleStructure)
    {
        $moduleStructure['title'] = 'text';
        $moduleStructure['code'] = 'text';
    }

    public function getConnectedSubContentElements()
    {
        $result = [];
        $linksManager = $this->getService('linksManager');
        $connectedCategoriesIds = $linksManager->getConnectedIdList($this->id,
            newsMailTextSubContentElement::LINK_TYPE_CATEGORY, 'parent');
        if ($connectedCategoriesIds) {
            $structureManager = $this->getService('structureManager');
            foreach ($connectedCategoriesIds as $connectedCategoryId) {
                $result[] = $structureManager->getElementById($connectedCategoryId);
            }
        }
        return $result;
    }

    public function getTitle()
    {
        return parent::getTitle(); // TODO: Change the autogenerated stub
    }
}