<?php

    /**
     * Class genericIconElement
     */
class genericIconElement extends structureElement implements ImageUrlProviderInterface
{
    use ConnectedProductsProviderTrait;
    use ConnectedBrandsProviderTrait;
    use ConnectedCategoriesProviderTrait;
    use ConnectedParametersProviderTrait;
    use ImageUrlProviderTrait;
    use ProductsAvailabilityOptionsTrait;
    use ProductIconLocationOptionsTrait;
    use ProductIconRoleOptionsTrait;

    public $dataResourceName = 'module_generic_icon';
    public $defaultActionName = 'show';
    public $role = 'content';

    protected function setModuleStructure(&$moduleStructure)
    {
        $moduleStructure['title'] = 'text';
        $moduleStructure['image'] = 'image';
        $moduleStructure['originalName'] = 'fileName';
        $moduleStructure['products'] = 'numbersArray';
        $moduleStructure['categories'] = 'numbersArray';
        $moduleStructure['brands'] = 'numbersArray';
        $moduleStructure['startDate'] = 'date';
        $moduleStructure['endDate'] = 'date';
        $moduleStructure['days'] = 'naturalNumber';
        $moduleStructure['iconWidth'] = 'floatNumber';
        $moduleStructure['iconLocation'] = 'naturalNumber';
        $moduleStructure['iconBgColor'] = 'text';
        $moduleStructure['iconTextColor'] = 'text';
        $moduleStructure['iconRole'] = 'naturalNumber';
        $moduleStructure['iconProductAvail'] = 'serializedIndex';
        $moduleStructure['iconProductParameters'] = 'numbersArray';
    }

    protected function setMultiLanguageFields(&$multiLanguageFields)
    {
        $multiLanguageFields[] = 'title';
        $multiLanguageFields[] = 'image';
        $multiLanguageFields[] = 'originalName';
        $multiLanguageFields[] = 'iconWidth';
    }

}