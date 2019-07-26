<?php

class exportShared extends structureElementAction
{
    public function execute(&$structureManager, &$controller, &$structureElement)
    {
        $exportData = [];

        foreach ($structureElement->getContentList() as $element) {
            $data = $element->getExportData();
            $exportData[] = $data;
        }
        $renderer = $this->getService('renderer');
        $renderer->assign('exportData', $exportData);

        // language id to code
        $languageCodes = [];
        $languagesManager = $this->getService('LanguagesManager');
        $languagesList = $languagesManager->getLanguagesList();
        foreach ($languagesList as $languagesItem) {
            $languageCodes[$languagesItem->id] = $languagesItem->iso6393;
        }
        $renderer->assign('languagesList', $languageCodes);
        $path = $this->getService('PathsManager')->getPath('trickster');
        $renderer->setTemplatesFolder($path . 'cms/templates/xml');
        $renderer->setTemplate('xml.export.tpl');
        $renderer->setCacheControl('no-cache');
        $renderer->setContentDisposition('attachment');
        $renderer->setContentType('application/xml');
        $renderer->display();
        exit;
    }
}

