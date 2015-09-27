<?php namespace APISchema\Contracts;

interface IInputModel extends PropertyBagContract, PropertyBagValidateContract {

    public function getDefaults();

}