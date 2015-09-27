<?php namespace APISchema;

use APISchema\Exceptions\InputModelException as ExceptionInputModel;
use Illuminate\Support\Facades\Validator;

trait PropertyBagValidate {

    /**
     * @var \Illuminate\Validation\Validator
     */
    private $validator;

    /**
     * @var array
     */
    private $failed;


    public function validate() {
        $data = $this->toArray();
        $this->validator = Validator::make($data, $this->getSingleValidator());
        $result = !$this->validator->fails();
        $this->failed = $this->validator->failed();

        foreach($this->getAllValidators() as $key => $validate ) {
            $validator = Validator::make($data[$key], $validate->getSingleValidator());
            $result = !$validator->fails() && $result;
            if ($validator->fails()) {
                $this->failed = array_merge($this->failed, [$key => $validator->failed()]);
            }
        }

        return $result;
    }

    public function validateAndThrow() {
        if (!$this->validate()) {
            $this->throwException();
        }
    }

    public function throwException($message = '') {
        throw new ExceptionInputModel($message != '' ? $message : 'Input data fails: ' . print_r($this->failed, true));
    }

    public function getAllValidators() {
        $result = [];
        $validators = $this->getValidator();

        if (!empty($validators)) {
            foreach($validators as $key => &$value) {
                if (is_string($value) && class_exists($value)) {
                    $result[$key] = new $value();
                }
            }
        }

        return $result;
    }

    public function getSingleValidator() {
        $result = [];
        $validators = $this->getValidator();
        foreach($validators as $key => &$value) {
            if (! (is_string($value) && class_exists($value))) {
                $result[$key] = $value;
            }
        }
        return $result;
    }

}
