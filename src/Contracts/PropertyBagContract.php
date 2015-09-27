<?php namespace APISchema\Contracts;

interface PropertyBagContract {

    public function set($data);

    public function fillModelWithData(\Illuminate\Database\Eloquent\Model &$model);

    public function toArray();

}
