<?php

class ajaxSearchApplication extends controllerApplication
{
    protected $applicationName = 'ajaxSearch';
    protected $mode = 'public';
    public $rendererName = 'json';

    public function initialize()
    {
        $controller = controller::getInstance();
        if ($controller->getParameter('mode')) {
            $mode = $controller->getParameter('mode');
            if ($mode == 'admin') {
                $this->mode = 'admin';
            } else {
                $this->mode = 'public';
            }
        }
        $configManager = $controller->getConfigManager();
        if ($this->mode == 'admin') {
            $this->startSession($this->mode, $configManager->get('main.adminSessionLifeTime'));
        } else {
            $this->startSession($this->mode, $configManager->get('main.publicSessionLifeTime'));
        }

        $this->createRenderer();
    }

    public function execute($controller)
    {
        /**
         * @var Cache $cache
         */
        $cache = $this->getService('Cache');
        $cache->enable();

        $response = new ajaxResponse();
        $languagesManager = $this->getService('languagesManager');

        $response->setPreset('search');

        if ($this->mode == 'admin') {
            $structureManager = $this->getService('structureManager', [
                'rootUrl' => $controller->rootURL,
                'rootMarker' => $this->getService('ConfigManager')->get('main.rootMarkerAdmin'),
                'configActions' => true,
            ], true);
        } else {
            $structureManager = $this->getService('structureManager', [
                'rootUrl' => $controller->rootURL,
                'rootMarker' => $this->getService('ConfigManager')->get('main.rootMarkerPublic'),
                'configActions' => false,
            ], true);
            $structureManager->setRequestedPath([$languagesManager->getCurrentLanguageCode()]);
        }
        if ($query = $controller->getParameter('query')) {
            $query = urldecode($query);
            $query = htmlspecialchars(trim($query), ENT_QUOTES);
        }
        if ($query) {
            if ($controller->getParameter('types')) {
                $types = explode(',', $controller->getParameter('types'));
            } else {
                $types = [
                    'product',
                    'news',
                    'folder',
                ];
            }
            $resultsLimit = (int)$controller->getParameter('resultsLimit');
            if (!$resultsLimit || $resultsLimit > 100) {
                $resultsLimit = 30;
            }

            $filters = [];
            $filtersParameter = $controller->getParameter('filters');
            if ($filtersParameter) {
                $filtersStrings = explode(';', $filtersParameter);
                foreach ($filtersStrings as &$filterString) {
                    if (trim($filterString) != '') {
                        $subStrings = explode('=', $filterString);
                        if (isset($subStrings[0])) {
                            $filterName = $subStrings[0];
                            if (isset($subStrings[1])) {
                                $filters[$filterName] = explode(',', $subStrings[1]);
                            } else {
                                $filters[$filterName] = true;
                            }
                        }
                    }
                }
            }

            $page = (int)$controller->getParameter('page');
            $offset = max(0, $page - 1) * $resultsLimit;
            $search = new Search();
            if ($this->mode == 'public'){
                $search->setLanguageId($languagesManager->getCurrentLanguageId());
            }
            $search->setInput($query);
            $search->setOffset($offset);
            $search->setLimit($resultsLimit);
            $search->setTypes($types);
            $search->setPartialMatching(false);
            $search->setContentMatching(false);
            $search->setFilters($filters);
            $search->setSinglePageCombining(true);
            $result = $search->getResult();
            if ($result->count) {
                if ($this->mode == "public") {
                    $searchId = $this->getService('searchQueriesManager')->logInstantSearch($query, $result->count);
                    foreach ($result->elements as &$element) {
                        $element->URL .= "qid:" . $searchId . "/";
                    }
                }

                $response->setResponseData('searchTotal', $result->getSearchTotal());

                foreach ($result->sets as $set) {
                    $response->setResponseData($set->type, $set->elements);
                }
            }
        }
        $status = 'success';
        $this->renderer->assign('responseStatus', $status);
        $this->renderer->assign('responseData', $response->responseData);

        $this->renderer->setCacheControl('no-cache');
        $this->renderer->display();
    }

    public function getUrlName()
    {
        if ($this->mode == 'admin') {
            return 'admin';
        } else {
            return '';
        }
    }
}

