<?php

class copyElementsRoot extends structureElementAction
{
    public function execute(&$structureManager, &$controller, &$structureElement)
    {
        $user = $this->getService('user');
        $structureElement->executeAction('showFullList');
        $navigationRoot = $structureManager->getElementByMarker($this->getService('ConfigManager')
            ->get('main.rootMarkerAdmin'));
        $navigateId = $controller->getParameter('navigateId');
        if ($contentType = $controller->getParameter('view')) {
            $structureManager->setNewElementLinkType($contentType);
        }
        $renderer = $this->getService('renderer');
        $renderer->assign('contentType', $contentType);

        if (!$navigateId) {
            $copyInformation = [];

            $copyInformation['elementsToCopy'] = [];
            $moveInformation['elementsToMove'] = [];
            $copyInformation['structureTypes'] = [];
            $elements = $structureElement->elements;
            $sourceId = false;
            if (is_array($elements)) {
                foreach ($elements as $elementID => &$value) {
                    if ($copiedElement = $structureManager->getElementById($elementID)) {
                        if ((!$sourceId || !$navigateId) && ($firstParent = $structureManager->getElementsFirstParent($copiedElement->id))
                        ) {
                            if (!$sourceId) {
                                $sourceId = $firstParent->id;
                            }
                            if (!$navigateId) {
                                $navigateId = $firstParent->id;
                            }
                        }

                        $copyInformation['elementsToCopy'][] = $copiedElement->id;
                        $copyInformation['structureTypes'][$copiedElement->structureType] = true;
                    }
                }
            }
            $copyInformation['sourceId'] = $sourceId;
            $user->setStorageAttribute('copyInformation', $copyInformation);
            $user->setStorageAttribute('moveInformation', false);
        } else {
            $copyInformation = $user->getStorageAttribute('copyInformation');
        }

        if ($copyInformation && count($copyInformation['elementsToCopy']) > 0) {
            if ($navigatedElement = $structureManager->getElementById($navigateId)) {
                $structureManager->setCurrentElement($structureElement);
                $structureElement->navigationRoot = $navigationRoot;
                $structureElement->navigationTree = [$navigationRoot];
                $structureElement->destinationElement = $navigatedElement;
                $this->markNavigatedChain($navigationRoot, $navigatedElement);
                $structureElement->pasteAllowed = $this->checkAllowedTypes($navigatedElement, array_keys($copyInformation['structureTypes']));

                $structureElement->setTemplate('shared.copy.tpl');
            }
        }
    }

    public function setExpectedFields(&$expectedFields)
    {
        $expectedFields = ['elements'];
    }

    protected function markNavigatedChain($navigationRoot, $currentElement)
    {
        $structureManager = $this->getService('structureManager');
        $currentElement->navigated = true;
        $structureManager->getElementsChildren($currentElement->id, 'container');
        if ($currentElement->id != $navigationRoot->id) {
            if ($parentElement = $structureManager->getElementsFirstParent($currentElement->id)) {
                $this->markNavigatedChain($navigationRoot, $parentElement);
            }
        }
    }

    protected function checkAllowedTypes(structureElement $destinationElement, array $typesList)
    {
        $result = true;
        $allowedTypes = $destinationElement->getAllowedTypes();
        foreach ($typesList as &$type) {
            if (!in_array($type, $allowedTypes)) {
                $result = false;
                break;
            }
        }
        return $result;
    }
}

