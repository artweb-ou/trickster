<?php

class linkListElement extends menuDependantStructureElement implements ConfigurableLayoutsProviderInterface
{
    use ConfigurableLayoutsProviderTrait;
    use SearchTypesProviderTrait;
    public $dataResourceName = 'module_linklist';
    protected $allowedTypes = ['linkListItem'];
    public $defaultActionName = 'show';
    public $role = 'content';
    public $linkItems = [];
    public $connectedMenu;
    protected $fixedElement;

    protected function setModuleStructure(&$moduleStructure)
    {
        $moduleStructure['title'] = 'text';
        $moduleStructure['hideTitle'] = 'checkbox';
        $moduleStructure['layout'] = 'text';
        $moduleStructure['colorLayout'] = 'text';
        $moduleStructure['image'] = 'image';
        $moduleStructure['originalName'] = 'fileName';
        $moduleStructure['fixedId'] = 'text';
        $moduleStructure['content'] = 'html';
        $moduleStructure['subTitle'] = 'text';
        $moduleStructure['cols'] = 'naturalNumber';
        $moduleStructure['gapValue'] = 'naturalNumber';
        $moduleStructure['gapUnit'] = 'text';
        $moduleStructure['titlePosition'] = 'text';
    }

    protected function getTabsList()
    {
        return [
            'showForm',
            'showLayoutForm',
            'showPositions',
            'showPrivileges',
        ];
    }

    public function getFixedElement()
    {
        if ($this->fixedElement === null && $this->fixedId) {
            $structureManager = $this->getService('structureManager');
            $this->fixedElement = $structureManager->getElementById($this->fixedId);
        }
        return $this->fixedElement;
    }
}