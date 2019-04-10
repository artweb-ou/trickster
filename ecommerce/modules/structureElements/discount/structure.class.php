<?php

class discountElement extends productsListStructureElement
{
    use ConfigurableLayoutsProviderTrait;

    use ConnectedProductsProviderTrait;
    use ConnectedBrandsProviderTrait;
    use ConnectedCategoriesProviderTrait;
    public $dataResourceName = 'module_discount';
    protected $allowedTypes = [];
    public $defaultActionName = 'show';
    public $role = 'content';
    protected $connectedDeliveryTypes;
    protected $connectedDeliveryTypesIds;
    protected $connectedDiscounts;
    protected $connectedDiscountsIds;
    protected $deliveryTypesDiscountsIndex;

    protected function setModuleStructure(&$moduleStructure)
    {
        $moduleStructure['title'] = 'text';
        $moduleStructure['code'] = 'text';
        $moduleStructure['conditionPrice'] = 'text';
        $moduleStructure['conditionPriceMax'] = 'text';
        $moduleStructure['conditionUserGroupId'] = 'text';
        $moduleStructure['logic'] = 'text';
        $moduleStructure['startDate'] = 'date';
        $moduleStructure['endDate'] = 'date';
        $moduleStructure['promoCode'] = 'text';
        $moduleStructure['groupBehaviour'] = 'text'; // cooperate/dominateSmaller//smallestAvailable
        $moduleStructure['productDiscount'] = 'text';
        $moduleStructure['deliveryTypesDiscountInfo'] = 'code';
        $moduleStructure['deliveryTypesDiscountInfoForm'] = 'array'; // tmp

        // these variables specify what will be discounted
        $moduleStructure['targetAllProducts'] = 'checkbox';
        $moduleStructure['products'] = 'numbersArray';
        $moduleStructure['categories'] = 'numbersArray';
        $moduleStructure['brands'] = 'numbersArray';
        $moduleStructure['deliveryTypes'] = 'numbersArray';

        // discount fields
        $moduleStructure['content'] = 'html';
        $moduleStructure['image'] = 'image';
        $moduleStructure['originalName'] = 'text';
        $moduleStructure['icon'] = 'image';
        $moduleStructure['iconOriginalName'] = 'text';
        $moduleStructure['link'] = 'url';

        //        $moduleStructure['priceSortingEnabled'] = 'text';
        //        $moduleStructure['nameSortingEnabled'] = 'text';
        //        $moduleStructure['dateSortingEnabled'] = 'text';
        //        $moduleStructure['availabilityFilterEnabled'] = 'checkbox';
        //        $moduleStructure['brandFilterEnabled'] = 'checkbox';
        //        $moduleStructure['parameterFilterEnabled'] = 'checkbox';
        //        $moduleStructure['amountOnPageEnabled'] = 'checkbox';

        $moduleStructure['metaTitle'] = 'text';
        $moduleStructure['metaDescription'] = 'text';
        $moduleStructure['canonicalUrl'] = 'url';
        $moduleStructure['metaDenyIndex'] = 'checkbox';
        $moduleStructure['fixedPrice'] = 'money';
        $moduleStructure['showInBasket'] = 'checkbox';
        $moduleStructure['basketText'] = 'text';
        $moduleStructure['displayProductsInBasket'] = 'checkbox';
        $moduleStructure['reference'] = 'html';
        $moduleStructure['iconWidth'] = 'floatNumber';
    }

    protected function setMultiLanguageFields(&$multiLanguageFields)
    {
        $multiLanguageFields[] = 'title';
        $multiLanguageFields[] = 'content';
        $multiLanguageFields[] = 'metaTitle';
        $multiLanguageFields[] = 'metaDescription';
        $multiLanguageFields[] = 'reference';
        $multiLanguageFields[] = 'image';
        $multiLanguageFields[] = 'originalName';
        $multiLanguageFields[] = 'icon';
        $multiLanguageFields[] = 'iconOriginalName';
        $multiLanguageFields[] = 'iconWidth';
    }

    protected function getTabsList()
    {
        return [
            'showFullList',
            'showForm',
            'showSeoForm',
            'showPositions',
            'showPrivileges',
        ];
    }

    protected function isExpired()
    {
        $result = false;
        $currentTime = mktime();
        $endTimeStamp = $this->getTimeStamp($this->endDate);
        $startTimeStamp = $this->getTimeStamp($this->startDate);
        if ($endTimeStamp && ($currentTime >= $endTimeStamp)) {
            $result = true;
        } elseif ($startTimeStamp && ($currentTime < $startTimeStamp)) {
            $result = true;
        }
        return $result;
    }

    public function getDiscountForDeliveryType($id)
    {
        $deliveryTypesDiscountsIndex = $this->getDeliveryTypesDiscountsIndex();
        return (!empty($deliveryTypesDiscountsIndex[$id])) ? $deliveryTypesDiscountsIndex[$id] : '';
    }

    public function getDeliveryTypesDiscountsIndex()
    {
        if ($this->deliveryTypesDiscountsIndex == null) {
            $this->deliveryTypesDiscountsIndex = [];
            if ($this->deliveryTypesDiscountInfo) {
                $this->deliveryTypesDiscountsIndex = unserialize($this->deliveryTypesDiscountInfo);
            }
        }
        return $this->deliveryTypesDiscountsIndex;
    }

    public function getUserGroups()
    {
        $selectedId = $this->formData['conditionUserGroupId'];
        $userGroupsList = [];
        $structureManager = $this->getService('structureManager');
        if ($userGroupsFolder = $structureManager->getElementByMarker('userGroups')) {
            foreach ($structureManager->getElementsChildren($userGroupsFolder->id) as $element) {
                $item = [];
                $item['id'] = $element->id;
                $item['title'] = $element->getTitle();
                $item['select'] = $element->id == $selectedId;
                $userGroupsList[] = $item;
            }
        }
        return $userGroupsList;
    }

    public function getConnectedDeliveryTypes()
    {
        if (is_null($this->connectedDeliveryTypes)) {
            $this->connectedDeliveryTypes = [];
            if ($deliveryTypeIds = $this->getConnectedDeliveryTypesIds()) {
                $structureManager = $this->getService('structureManager');
                foreach ($deliveryTypeIds as &$deliveryTypeId) {
                    if ($deliveryTypeId && $deliveryTypeElement = $structureManager->getElementById($deliveryTypeId)) {
                        $this->connectedDeliveryTypes[] = $deliveryTypeElement;
                    }
                }
            }
        }
        return $this->connectedDeliveryTypes;
    }

    public function getConnectedDeliveryTypesIds()
    {
        if (is_null($this->connectedDeliveryTypesIds)) {
            $linksManager = $this->getService('linksManager');
            $this->connectedDeliveryTypesIds = $linksManager->getConnectedIdList($this->id, "discountDeliveryType", "parent");
        }
        return $this->connectedDeliveryTypesIds;
    }

    final public function getTimeStamp($dateString)
    {
        $stamp = false;
        $dateParts = explode(".", $dateString);
        if (count($dateParts) == 3) {
            $stamp = mktime(0, 0, 0, $dateParts[1], $dateParts[0], $dateParts[2]);
        }
        return $stamp;
    }

    //    public function getDefaultOrder()
    //    {
    //        return 'manual';
    //    }

    /**
     * @return array
     *
     * @deprecated
     */
    public function getConnectedDiscounts()
    {
        $this->logError('deprecated method getConnectedDiscounts used');
        return [];
    }

    /**
     * @return array
     *
     * @deprecated
     */
    public function getConnectedDiscountsIds()
    {
        $this->logError('deprecated method getConnectedDiscountsIds used');
        return [];
    }

    protected function getPotentialIdsForPublicProductsList()
    {
        $shoppingBasketDiscounts = $this->getService('shoppingBasketDiscounts');
        if ($logicObject = $shoppingBasketDiscounts->getDiscount($this->id)) {
            return $logicObject->getApplicableProductsIds();
        }
        return [];
    }

    protected function getProductsListParentElementsIds()
    {
        $result = [$this->id];
        $shoppingBasketDiscounts = $this->getService('shoppingBasketDiscounts');
        if ($logicObject = $shoppingBasketDiscounts->getDiscount($this->id)) {
            if ($categories = $logicObject->discountedCategoriesIds) {
                $result = array_merge($result, $logicObject->discountedCategoriesIds);
            }
            if ($brands = $logicObject->discountedBrandIds) {
                $result = array_merge($result, $brands);
            }
        }
        return $result;
    }

    public function isFilterableByAvailability()
    {
        return $this->availabilityFilterEnabled;
    }

    public function isFilterableByParameter()
    {
        return $this->parameterFilterEnabled;
    }

    public function isFilterableByBrand()
    {
        return $this->brandFilterEnabled;
    }

    public function loadDiscountsListFilterData()
    {
        if ($parent = $this->getRequestedDiscountsList()) {
            $this->priceSortingEnabled = $parent->priceSortingEnabled;
            $this->nameSortingEnabled = $parent->nameSortingEnabled;
            $this->dateSortingEnabled = $parent->dateSortingEnabled;
            $this->brandSortingEnabled = $parent->brandSortingEnabled;
            $this->brandFilterEnabled = $parent->brandFilterEnabled;
            $this->parameterFilterEnabled = $parent->parameterFilterEnabled;
            $this->availabilityFilterEnabled = $parent->availabilityFilterEnabled;
            $this->manualSortingEnabled = $parent->manualSortingEnabled;
            $this->defaultOrder = $parent->defaultOrder;
            $this->parameters = $parent->parameters;
            $this->amountOnPageEnabled = $parent->amountOnPageEnabled;
        }
    }

    public function getRequestedDiscountsList()
    {
        foreach ($this->getService('structureManager')->getElementsParents($this->id) as $parent) {
            if ($parent->structureType == 'discountsList') {
                if ($parent->requested) {
                    return $parent;
                }
            }
        }
        return false;
    }

    public function getDiscountsListProductsLayout()
    {
        if ($parent = $this->getRequestedDiscountsList()) {
            return $parent->getCurrentLayout('selectedDiscountProductsLayout');
        }
    }

    public function getDefaultLimit()
    {
        if (!$this->requested) {
            return 4;
        }
        return parent::getDefaultLimit();
    }

    public function asd($a, $b)
    {
        $this->multilanguageChunks[$b]['iconOriginalName'] = $a;
    }
}