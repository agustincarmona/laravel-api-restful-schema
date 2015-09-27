<?php namespace APISchema;

use Illuminate\Database\Eloquent\Model;

trait PropertyBag {

    private $FIELD_FILTERS = 'filters';

    /**
     * Access property without case sensitive
     *
     * @param $var
     *
     * @return null
     */
    function __get($var) {
        $vars = get_class_vars(get_class($this));
        foreach ($vars as $key => $value) {
            if (strtolower($var) == strtolower($key)) {
                return $this->$key;
                break;
            }
        }

        return null;
    }

    /**
     * Access property without case sensitive
     *
     * @param $name
     * @param $value
     */
    function __set($name, $value) {
        $vars = get_class_vars(get_class($this));
        foreach ($vars as $key => $value) {
            if (strtolower($name) == strtolower($key)) {
                $this->$key = $value;
                break;
            }
        }

        $methodChanged = 'propertyChanged';
        if (method_exists($this, $methodChanged)) {
            $this->{$methodChanged}($name, $value);
        }
    }

    public function set($data) {
        $this->fill($data);
    }

    public function toArray() {
        return (array) $this;
    }

    /**
     * Filter used when data is sent throw FilterInterfaceModel from interfaces (web,mobile,etc)
     *
     * @param array $filter
     */
    public function setDataFilter(array $filter) {

        foreach ($filter[$this->FIELD_FILTERS] as $key => $data) {

            if (isset($data['key']) && property_exists($this, $data['key']) && isset($data['value'])) {
                $this->{$data['key']} = $data['value'];
            }
        }
    }

    public function fillModelWithData(Model &$model) {

        $vars = $this->toArray();
        foreach ($vars as $key => $value) {
            if (!empty($this->{$key})) {
                $model->{$key} = $this->{$key};
            }
        }
    }

    /**
     * @param array $columns
     */
    public function fill($columns) {
        if (!empty($columns)) {
            foreach ($columns as $key => $value) {

                $methodNameNormal = $this->getTransformMethodNameNormal($key);

                $propertyNameUnderscores = $this->getPropertyNameUnderscore($key);

                if (method_exists($this, $methodNameNormal)) {
                    $this->{$methodNameNormal}($value);
                } else if (property_exists($this, $key)) {
                    $this->$key = $value;
                } else if (property_exists($this, $propertyNameUnderscores)) {
                    $this->{$propertyNameUnderscores} = $value;
                } else {
                    continue;
                }
            }
        }

        $methodFilled = 'filled';
        if (method_exists($this, $methodFilled)) {
            $this->{$methodFilled}($columns);
        }
    }


    /**
     * Generates a possible method that transforms
     *
     * @param string $name
     *
     * @return string
     */
    protected function getTransformMethodNameNormal($name) {
        return 'transform' . ucfirst($name);
    }

    /**
     * Generates the property name for fields separated with underscores
     * Ex:  ds_order >> dsOrder
     *
     * @param $name
     *
     * @return string
     */
    protected function getPropertyNameUnderscore($name) {
        $composedName = '';
        $auxArray = explode('_', $name);
        foreach ($auxArray as $word) {
            $composedName .= ucfirst($word);
        }

        return lcfirst($composedName);
    }

}
