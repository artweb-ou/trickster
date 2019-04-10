<?php

/**
 * Class shoppingBasketElement
 *
 * @property string conditionsLink
 * @property string paymentSuccessfulText
 * @property string paymentDeferredText
 * @property string paymentInvoiceText
 * @property string paymentQueryText
 * @property string paymentFailedText
 * @property mixed|null structureElement
 */
class shoppingBasketElement extends dynamicFieldsStructureElement implements clientScriptsProviderInterface
{
    use EventLoggingElementTrait;
    protected $loggable = true;
    protected $currentOrder;
    public $dataResourceName = 'module_shoppingbasket';
    public $defaultActionName = 'show';
    protected $allowedTypes = [
        'login',
        'registration',
    ];
    public $role = 'container';
    protected $deliveryTypesList = false;
    protected $displayedProducts = false;
    protected $currentStep = 'selection';
    public $errorMessage = '';
    //todo: make $shoppingBasket protected, provide getter
    /**
     * @var shoppingBasket
     */
    public $shoppingBasket;
    protected $paymentStatus = false;

    protected function setModuleStructure(&$moduleStructure)
    {
        $moduleStructure['title'] = 'text';
        $moduleStructure['destination'] = 'email';
        $moduleStructure['productAddedText'] = 'html';
        $moduleStructure['paymentInvoiceText'] = 'html';
        $moduleStructure['paymentQueryText'] = 'html';
        $moduleStructure['paymentSuccessfulText'] = 'html';
        $moduleStructure['paymentDeferredText'] = 'html';
        $moduleStructure['paymentFailedText'] = 'html';
        $moduleStructure['columns'] = 'text';

        $moduleStructure['deliveryType'] = 'text';

        $moduleStructure['payerCompany'] = 'text';
        $moduleStructure['payerFirstName'] = 'text';
        $moduleStructure['payerLastName'] = 'text';
        $moduleStructure['payerEmail'] = 'email';
        $moduleStructure['payerPhone'] = 'text';
        $moduleStructure['payerAddress'] = 'text';
        $moduleStructure['payerCity'] = 'text';
        $moduleStructure['payerPostIndex'] = 'text';
        $moduleStructure['payerCountry'] = 'text';
        $moduleStructure['conditionsLink'] = 'url';

        $moduleStructure['receiverIsPayer'] = 'checkbox';

        $moduleStructure['conditions'] = 'checkbox';
        $moduleStructure['subscribe'] = 'checkbox';
        $moduleStructure['hidden'] = 'checkbox';
        $moduleStructure['paymentMethodId'] = 'naturalNumber';
    }

    public function prepareFormInformation()
    {
        $shoppingBasket = $this->getService('shoppingBasket');
        $formData = $shoppingBasket->getBasketFormData();
        if (count($formData) > 0) {
            $this->setFormValue('payerCompany', $formData['payerCompany']);
            $this->setFormValue('payerFirstName', $formData['payerFirstName']);
            $this->setFormValue('payerLastName', $formData['payerLastName']);
            $this->setFormValue('payerAddress', $formData['payerAddress']);
            $this->setFormValue('payerCity', $formData['payerCity']);
            $this->setFormValue('payerPostIndex', $formData['payerPostIndex']);
            $this->setFormValue('payerCountry', $formData['payerCountry']);
            $this->setFormValue('payerEmail', $formData['payerEmail']);
            $this->setFormValue('payerPhone', $formData['payerPhone']);

            $this->setFormValue('receiverIsPayer', $formData['receiverIsPayer']);
        } else {
            $user = $this->getService('user');
            if ($user->userName != 'anonymous') {
                $this->setFormValue('payerCompany', $user->company);
                $this->setFormValue('payerFirstName', $user->firstName);
                $this->setFormValue('payerLastName', $user->lastName);
                $this->setFormValue('payerAddress', $user->address);
                $this->setFormValue('payerCity', $user->city);
                $this->setFormValue('payerPostIndex', $user->postIndex);
                $this->setFormValue('payerCountry', $user->country);
                $this->setFormValue('payerEmail', $user->email);
                $this->setFormValue('payerPhone', $user->phone);
            }
        }
    }

    /**
     * @return bool
     */
    public function isPaymentStatus()
    {
        return $this->paymentStatus;
    }

    /**
     * @param bool $paymentStatatus
     */
    public function setPaymentStatus($paymentStatatus)
    {
        $this->paymentStatus = $paymentStatatus;
    }

    public function saveShoppingBasketForm()
    {
        $shoppingBasket = $this->getService('shoppingBasket');

        $formData = [];
        $formData['payerCompany'] = $this->payerCompany;
        $formData['payerFirstName'] = $this->payerFirstName;
        $formData['payerLastName'] = $this->payerLastName;
        $formData['payerAddress'] = $this->payerAddress;
        $formData['payerCity'] = $this->payerCity;
        $formData['payerPostIndex'] = $this->payerPostIndex;
        $formData['payerCountry'] = $this->payerCountry;
        $formData['payerEmail'] = $this->payerEmail;
        $formData['payerPhone'] = $this->payerPhone;

        $formData['receiverIsPayer'] = $this->receiverIsPayer;

        $shoppingBasket->setBasketFormData($formData);
    }

    public function getCustomFieldsList()
    {
        if (is_null($this->customFieldsList)) {
            $this->customFieldsList = [];
            $shoppingBasket = $this->getService('shoppingBasket');
            $deliveryType = $shoppingBasket->getSelectedDeliveryType();

            $structureManager = $this->getService('structureManager');

            if ($deliveryType && ($elements = $structureManager->getElementsByIdList([$deliveryType->id], false, true))
            ) {
                if ($deliveryTypeElement = reset($elements)) {
                    $fieldsList = $deliveryTypeElement->getFieldsList();

                    foreach ($fieldsList as &$record) {
                        if ($fieldElement = $structureManager->getElementById($record->fieldId)) {
                            $this->customFieldsList[] = $fieldElement;
                            $fieldElement->required = $record->required;
                        }
                    }
                }
            }
        }
        return $this->customFieldsList;
    }

    protected function getBrands($id) {
        $brandIds = $this->getService('linksManager')
            ->getConnectedIdList($id, 'product', 'parent');
        $connectedBrands = "";
        if (!empty($brandIds)) {
            $languageManager = $this->getService('languagesManager');
            $defaultLanguage = $languageManager->getDefaultLanguage('adminLanguages');
            $structureManager = $this->getService('structureManager');
            foreach ($brandIds as &$brandId) {
                if ($brandId && $brandElement = $structureManager->getElementById($brandId)) {
                    $connectedBrands .= $brandElement->getValue('title', $defaultLanguage->id). " ";
                }
            }
        }
        return $connectedBrands;
    }

    public function getElementData()
    {
        $structureManager = $this->getService('structureManager');
        $languageManager = $this->getService('languagesManager');
        $defaultLanguage = $languageManager->getDefaultLanguage('adminLanguages');
        $deliveryTypeid = $this->shoppingBasket->getSelectedDeliveryTypeId();
        $structureManager->getElementsByIdList([$deliveryTypeid], false, true);
        $deliveryTypeElement = $structureManager->getElementById($deliveryTypeid);
        // generic
        $configManager = $this->getService('ConfigManager');
        $configManager->get('main.defaultSessionLifeTime');
        $currentOrder = $this->getCurrentOrder();
        $data['orderId'] = $currentOrder ? $currentOrder->id : null;
        $data['displayVat'] = $configManager->get('main.displayVat');
        $data['displayTotals'] = $this->displayTotals();
        $data["elementId"] = $this->id;
        $data["totalPrice"] = $this->shoppingBasket->getTotalPrice();
        $data["productsPrice"] = $this->shoppingBasket->getProductsPrice();
        $data["vatAmount"] = $this->shoppingBasket->getVatAmount();
        $data["vatLessTotalPrice"] = $this->shoppingBasket->getVatLessTotalPrice(false);
        $data["deliveryPrice"] = $this->shoppingBasket->getDeliveryPrice();
        $data["selectedDeliveryTypeId"] = $deliveryTypeid;
        $data["selectedDeliveryTypeTitleDl"] = $deliveryTypeElement?  $deliveryTypeElement->getValue('title', $defaultLanguage->id): '';
        $data["selectedCountryId"] = $this->shoppingBasket->getSelectedCountryId();
        $data["selectedCityId"] = $this->shoppingBasket->getSelectedCityId();
        $data["promoCodeDiscountId"] = $this->shoppingBasket->getPromoCodeDiscountId();
        $data["productsAmount"] = $this->shoppingBasket->getProductsAmount();
        $data["productsSalesPrice"] = 0;
        $data["paymentStatus"] = $this->isPaymentStatus();
        // products
        $data["productsList"] = [];
        $products = $this->shoppingBasket->getProductsList();

        foreach ($products as &$product) {
            $productData = [];
            $productData["productId"] = $product->productId;
            $productData["basketProductId"] = $product->basketProductId;
            $productData["title"] = $product->title;
            $productData["title_dl"] = $product->title_dl;
            $productData["code"] = $product->code;
            $productData["price"] = $product->getPrice(false);
            $productData["emptyPrice"] = $product->emptyPrice;
            $productData["unit"] = $product->unit;
            $productData["category"] = $structureManager->getElementById($product->productId)
                ->getCurrentParentElement()
                ->getValue('title', $defaultLanguage->id);
            $productData["brand"] = $this->getBrands($product->productId);
            //@todo refactor - move functionality to shoppingbasketdiscounts.class
            $productData['salesPrice'] = $product->getPrice(false);
            $productData["amount"] = $product->amount;
            $productData['salesPrice'] -= $product->discount;
            $productData["variation"] = $product->variation;
            $productData["variation_dl"] = $product->variation_dl;
            $productData["description"] = $product->description;
            $productData["image"] = $product->image;
            $productData["url"] = $product->URL;
            $productData["minimumOrder"] = $product->minimumOrder;
            $data["productsSalesPrice"] += $productData['amount'] * $productData['salesPrice'];
            $data["productsList"][] = $productData;
        }
        // countries
        $data["countriesList"] = [];
        $countries = $this->shoppingBasket->getCountriesList();
        foreach ($countries as &$country) {
            $countryData["id"] = $country->id;
            $countryData["title"] = $country->title;
            $countryData["iso3166_1a2"] = $country->iso3166_1a2;
            $countryData["conditionsText"] = $country->conditionsText;
            $countryData["citiesList"] = [];
            foreach ($country->getActiveCitiesList() as $city) {
                $cityData = [];
                $cityData["id"] = $city->id;
                $cityData["title"] = $city->title;
                $countryData["citiesList"][] = $cityData;
            }
            $data["countriesList"][] = $countryData;
        }

        // deliveries
        $data["deliveryTypesList"] = [];
        $deliveryTypes = $this->shoppingBasket->getDeliveryTypesList();
        foreach ($deliveryTypes as &$deliveryType) {
            $deliveriesData = [];
            $deliveriesData["id"] = $deliveryType->id;
            $deliveriesData["code"] = $deliveryType->code;
            $deliveriesData["title"] = $deliveryType->title;
            $deliveriesData["price"] = $deliveryType->getPrice();

            $deliveriesData["deliveryFormFields"] = [];
            $hasNeededReceiverFields = true;
            $fieldExistence = [
                "firstName" => false,
                "lastName" => false,
                "email" => false,
            ];
            foreach ($deliveryType->deliveryFormFields as &$field) {
                $fieldsData = [];
                $fieldsData["id"] = $field["id"];
                $fieldsData["fieldName"] = $field["fieldName"];
                $fieldsData["fieldType"] = $field["fieldType"];
                $fieldsData["dataChunk"] = $field["dataChunk"];
                if ($field["required"]) {
                    foreach ($fieldExistence as $key => $value) {
                        if ($field["autocomplete"] == $key) {
                            $fieldExistence[$key] = true;
                        }
                    }
                    $fieldsData["required"] = true;
                } else {
                    $fieldsData["required"] = false;
                }

                $fieldsData["validator"] = $field["validator"];
                $fieldsData["autocomplete"] = $field["autocomplete"];
                $fieldsData["title"] = $field["title"];
                $fieldsData["value"] = $field["value"];
                $fieldsData["error"] = $field["error"];
                if ($field["fieldType"] == 'select') {
                    $fieldsData["options"] = $field["options"];
                } elseif ($field["fieldType"] == 'input') {
                    $fieldsData['helpLinkUrl'] = $field['helpLinkUrl'];
                    $fieldsData['helpLinkText'] = $field['helpLinkText'];
                }
                $deliveriesData["deliveryFormFields"][] = $fieldsData;
            }
            foreach ($fieldExistence as $fieldExists) {
                if (!$fieldExists) {
                    $hasNeededReceiverFields = false;
                }
            }
            $deliveriesData["hasNeededReceiverFields"] = $hasNeededReceiverFields;
            $data["deliveryTypesList"][] = $deliveriesData;
        }

        // discounts
        $data["discountsList"] = [];
        $discounts = $this->shoppingBasket->getDiscountsList();
        foreach ($discounts as &$discount) {
            $discountData = [];
            $discountData["id"] = $discount->id;
            $discountData["amount"] = $discount->getAllDiscountsAmount();
            $discountData["code"] = $discount->code;
            $discountData["title"] = $discount->title;
            $data["discountsList"][] = $discountData;
        }

        $data["showInBasketDiscountsList"] = [];
        $showInBasketDiscounts = $this->shoppingBasket->getShowInBasketDiscountsList();
        foreach ($showInBasketDiscounts as $discount) {
            $applicableProductsIds = [];
            if ($discount->displayProductsInBasket) {
                if ($discount->targetAllProducts) {
                    $db = $this->getService('db');
                    $query = $db->table('module_product')
                        ->select('id')
                        ->where('inactive', '=', '0')
                        ->limit(6)
                        ->orderByRaw('RAND()');

                    $applicableProductsIds = $query->pluck('id');
                } else {
                    $applicableProductsIds = array_slice($discount->getApplicableProductsIds(), 0, 6);
                }
            }
            if (!$discount->displayProductsInBasket || $applicableProductsIds || $discount->discountDelivery) {
                $discountData = [];
                $discountData["id"] = $discount->id;
                $discountData["code"] = $discount->code;
                $discountData["title"] = $discount->title;
                $discountData["displayText"] = $discount->displayText;
                $discountData["basketText"] = str_replace('{total}', ($discount->conditionPrice - $data['totalPrice']), $discount->basketText);
                $discountData["displayProductsInBasket"] = $discount->displayProductsInBasket;
                $discountData["products"] = [];
                $translationsManager = $this->getService('translationsManager');
                foreach ($applicableProductsIds as $applicableProductId) {
                    $product = $structureManager->getElementById($applicableProductId);
                    if ($product) {
                        $discountData["products"][] = [
                            'id' => $product->id,
                            'image' => $product->image,
                            'originalName' => $product->originalName,
                            'title' => $product->title,
                            'URL' => $product->URL,
                            'isPurchasable' => $product->isPurchasable(),
                            'addtobasket' => $translationsManager->getTranslationByName("product.addtobasket"),
                            'price' => $product->getPrice(false),
                            'oldPrice' => $product->getOldPrice(),
                            'discountPercent' => round($product->getDiscountPercent()),
                            'icons' => $product->getIconsCompleteList(),
                            'connectedDiscounts' => $product->getCampaignDiscounts(),
                        ];
                    }
                }

                $data["showInBasketDiscountsList"][] = $discountData;
            }
        }
        // services
        $data["servicesList"] = [];
        $services = $this->shoppingBasket->getServicesList();
        foreach ($services as &$service) {
            $serviceData = [];
            $serviceData["id"] = $service->id;
            $serviceData["title"] = $service->title;
            $serviceData["price"] = $service->getPrice();
            $serviceData["selected"] = $service->isSelected();

            $data["servicesList"][] = $serviceData;
        }
        $data["selectedServicesPrice"] = $this->shoppingBasket->getSelectedServicesPrice();

        $data["message"] = $this->shoppingBasket->getMessage();

        return $data;
    }

    public function getAvailablePaymentMethods()
    {
        static $result;

        if ($result === null) {
            $result = [];
            if ($selectedDeliveryTypeId = $this->shoppingBasket->getSelectedDeliveryTypeId()) {
                $paymentMethodsIds = $this->getService('linksManager')
                    ->getConnectedIdList($selectedDeliveryTypeId, "deliveryTypePaymentMethod", "parent");
                if ($paymentMethodsIds) {
                    $result = $this->getService('structureManager')
                        ->getElementsByIdList($paymentMethodsIds, $this->id);
                }
            }
        }
        return $result;
    }

    public function getConditionsLabel()
    {
        $label = $this->getService('translationsManager')->getTranslationByName('shoppingbasket.agreewithconditions');
        if (strpos($label, '%S1') !== false && strpos($label, '%S2') !== false) {
            if ($this->conditionsLink) {
                if (stripos($label, '%s1') !== false) {
                    $label = str_replace('%S1', '<a href="' . $this->conditionsLink . '" target="_blank">', $label);
                    $label = str_replace('%S2', '</a>', $label);
                }
            } else {
                $label = str_replace('%S1', '', $label);
                $label = str_replace('%S2', '', $label);
            }
        } elseif ($this->conditionsLink) {
            $label = '<a href="' . $this->conditionsLink . '" target="_blank">' . $label . '</a>';
        }
        return $label;
    }

    public function hasInvoicePaymentOptionOnly()
    {
        $availableMethods = $this->getAvailablePaymentMethods();
        return count($availableMethods) == 1 && reset($availableMethods)->getName() == 'invoice';
    }

    public function getLoginForm()
    {
        $result = null;
        foreach ($this->getChildrenList() as $childElement) {
            if ($childElement->structureType != 'login') {
                continue;
            }
            $result = $childElement;
        }
        return $result;
    }

    public function getRegistrationForm()
    {
        $result = null;
        foreach ($this->getChildrenList() as $childElement) {
            if ($childElement->structureType != 'registration') {
                continue;
            }
            $result = $childElement;
        }
        return $result;
    }

    public function getCurrentStep()
    {
        return $this->currentStep;
    }

    public function setCurrentStep($step)
    {
        $this->currentStep = $step;
    }

    public function isAccountStepSkippable()
    {
        return !!$this->getService('ConfigManager')->get('main.shoppingasketAccountStepSkippable');
    }

    public function isCheckoutStepEnabled()
    {
        return !!$this->getService('ConfigManager')->get('main.shoppingasketCheckoutStepEnabled');
    }

    public function getClientScripts()
    {
        $result = [];
        $scriptNeeded = false;
        /**
         * @var ConfigManager $configManager
         */
        $configManager = $this->getService('ConfigManager');
        $config = $configManager->getConfig('main');
        $deliverySettings = [
            'dpdEnabled',
            'post24Enabled',
            'smartPostEnabled',
        ];
        foreach ($deliverySettings as $setting) {
            $configValue = $config->get($setting);
            if ($configValue !== false) {
                $scriptNeeded = true;
                break;
            }
        }
        if ($scriptNeeded === true) {
            $controller = $this->getService('controller');
            $resourcesUniterHelper = $this->getService('ResourcesUniterHelper'
                , ['currentThemeCode' => 'shoppingBasketData'], true);
            $result[] = $controller->baseURL . 'javascript/set:shoppingBasketData/file:'
                . $resourcesUniterHelper->getResourceCacheFileName('js') . '.js';
        }
        return $result;
    }

    public function displayTotals()
    {
        foreach ($this->shoppingBasket->getProductsList() as $product) {
            if (!$product->emptyPrice) {
                return true;
            }
        }

        return false;
    }

    /**
     * @return null|orderElement
     */
    public function getCurrentOrder()
    {
        if ($this->currentOrder === null) {
            $controller = $this->getService('controller');
            if ($controller->getParameter('order')) {
                $orderId = $controller->getParameter('order');
                $structureManager = $this->getService('structureManager');
                $structureManager->getElementsByIdList($orderId, $this->id);
                /**
                 * @var orderElement $orderElement
                 */
                if ($orderElement = $structureManager->getElementById($orderId)) {
                    $this->currentOrder = $orderElement;
                }
            }
        }
        return $this->currentOrder;
    }

    /**
     * @param mixed $currentOrder
     */
    public function setCurrentOrder($currentOrder)
    {
        $this->currentOrder = $currentOrder;
    }

    public function hasPromoDiscounts()
    {
        return $this->getService('shoppingBasketDiscounts')->hasPromoDiscounts();
    }
}