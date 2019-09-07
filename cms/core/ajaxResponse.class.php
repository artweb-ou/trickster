<?php

class ajaxResponse
{
    use DataResponseConverterFactory;

    public $status = 'invalid';
    public $preset;
    public $responseData = [];

    public function setStatus($status)
    {
        $this->status = $status;
    }

    public function setResponseData($type, $data)
    {
        if ($converter = $this->getConverter($type)) {
            if ($converter instanceof PresetDataResponseConverterInterface) {
                $converter->setPreset($this->preset);
            }
            $responseData = $converter->convert($data);
            $this->responseData[$type] = $responseData;
        } else {
            $this->responseData[$type] = $data;
        }
    }

    public function setLiteralResponseData($type, $data)
    {
        $this->responseData[$type] = $data;
    }

    /**
     * @param mixed $preset
     */
    public function setPreset($preset)
    {
        $this->preset = $preset;
    }
}