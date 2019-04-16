<?php

class categoryProductFilter extends productFilter
{
    protected $type = 'category';
    protected $optionsChildrenIds = [];

    public function getOptionsInfo()
    {
        if ($this->options === null) {
            $this->options = [];
            if ($categories = $this->productsListElement->getProductsListCategories()) {
                foreach ($categories as &$category) {
                    if (!$category->hidden) {
                        $this->options[] = [
                            'title' => $category->title,
                            'selected' => isset($argumentMap[$category->id]) || $category->requested,
                            'id' => $category->id,
                            'url' => $category->URL,
                        ];
                    }
                }
            }
        }
        return $this->options;
    }

//    protected function limitOptions(array $productsIds)
//    {
//        if ($productsIds) {
//            $collection = persistableCollection::getInstance('structure_links');
//            $conditions = [
//                [
//                    'childStructureId',
//                    'IN',
//                    $productsIds,
//                ],
//                [
//                    'type',
//                    '=',
//                    "catalogue",
//                ],
//            ];
//            if ($records = $collection->conditionalLoad('distinct(parentStructureId)', $conditions, [], [], [], true)
//            ) {
//                $productsCategoriesIds = [];
//                foreach ($records as &$record) {
//                    $productsCategoriesIds[] = $record['parentStructureId'];
//                }
//                if (!$this->options) {
//                    $this->options = $productsCategoriesIds;
//                } else {
//                    $this->options = array_intersect($this->options, $productsCategoriesIds);
//                    foreach ($this->optionsChildrenIds as $key => &$childrenIds) {
//                        if (count(array_intersect($childrenIds, $productsCategoriesIds)) > 0) {
//                            $this->options[] = $key;
//                        }
//                    }
//                    $this->options = array_unique($this->options);
//                }
//            }
//        }
//    }
//
//    protected function gatherSubCategoriesIds($categoryId)
//    {
//        $subCategoriesIds = $this->getService('linksManager')
//            ->getConnectedIdList($categoryId, 'structure', 'parent', false);
//        if ($subCategoriesIds) {
//            $subSubCategoriesIds = [];
//            foreach ($subCategoriesIds as &$subCategoryId) {
//                $subSubCategoriesIds = array_merge($subSubCategoriesIds, $this->gatherSubCategoriesIds($subCategoryId));
//            }
//            if ($subSubCategoriesIds) {
//                $subCategoriesIds = array_merge($subCategoriesIds, $subSubCategoriesIds);
//            }
//        }
//        return $subCategoriesIds;
//    }
//
//    protected function gatherParentCategoriesIds($categoryId)
//    {
//        $categoriesIds = [];
//        if ($elementsIds = $this->gatherParentElementsIds($categoryId)) {
//            $collection = persistableCollection::getInstance('module_category');
//            $conditions = [
//                [
//                    'ID',
//                    'IN',
//                    $elementsIds,
//                ],
//            ];
//            if ($records = $collection->conditionalLoad('distinct(ID)', $conditions, [], [], [], true)
//            ) {
//                foreach ($records as &$record) {
//                    $categoriesIds[] = $record['ID'];
//                }
//            }
//        }
//        return $categoriesIds;
//    }
//
//    protected function gatherParentElementsIds($categoryId)
//    {
//        $parentCategoriesIds = $this->getService('linksManager')
//            ->getConnectedIdList($categoryId, 'structure', 'child', false);
//        if ($parentCategoriesIds) {
//            foreach ($parentCategoriesIds as &$parentCategoryId) {
//                $parentCategoriesIds = array_merge($parentCategoriesIds, $this->gatherParentElementsIds($parentCategoryId));
//            }
//        }
//        return $parentCategoriesIds;
//    }
//
//    protected function loadRelatedIds()
//    {
//        $this->relatedIds = [];
//        if ($this->arguments) {
//            $categoriesIds = $this->arguments;
//
//            $collection = persistableCollection::getInstance('structure_links');
//            $conditions = [
//                [
//                    'parentStructureId',
//                    'IN',
//                    $categoriesIds,
//                ],
//                [
//                    'type',
//                    '=',
//                    "catalogue",
//                ],
//            ];
//            if ($records = $collection->conditionalLoad('distinct(childStructureId)', $conditions, [], [], [], true)
//            ) {
//                foreach ($records as &$record) {
//                    $this->relatedIds[] = $record['childStructureId'];
//                }
//            }
//        }
//    }
}