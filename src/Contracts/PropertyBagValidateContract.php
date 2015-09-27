<?php namespace APISchema\Contracts;

interface PropertyBagValidateContract {

    public function validate();

    /**
     * @see \Validator
     * @return array
     */
    public function getValidator();

    public function validateAndThrow();

}
