<?php namespace APISchema\Http\Input;


use APISchema\Contracts\IInputModel;
use APISchema\PropertyBag;
use APISchema\PropertyBagValidate;

abstract class InputModel implements IInputModel {

    use PropertyBag;
    use PropertyBagValidate;

    public function __construct($columns = []) {
        $this->init();
        $this->fill($columns);
    }

    public function init() {

    }

    public function getDefaults() {
        return $this->toArray();
    }

    public function getHelp() {
        $result = [];

        $rc = new \ReflectionClass($this);
        foreach($rc->getProperties() as $property) {
            $name = $property->getName();
            $result[$name] = [];
            $result[$name] = array_merge($result[$name], $this->getHelpPropertyVars($property));
            $result[$name] = array_merge($result[$name], $this->getHelpPropertyDescription($property));
        }


        return $result;
    }

    /**
     * @param $property
     * @param $matches
     * @param $result
     *
     * @return mixed
     */
    private function getHelpPropertyVars(\ReflectionProperty $property) {

        $comment = $property->getDocComment();
        $commentVars = array();

        if (preg_match_all('/@(\w+)\s+(.*)\r?\n/m', $comment, $matches)) {
            for ($index = 0; $index < count($matches[1]); $index++) {
                $commentVars[$matches[1][$index]] = trim($matches[2][$index]);
            }
        }

        return $commentVars;
    }

    private function getHelpPropertyDescription(\ReflectionProperty $property) {

        $comment = $property->getDocComment();
        $matches = [];

        $commentVars = '';
        if (preg_match_all('/(\* )(?!\@)(.*)\r?\n/m', $comment, $matches)) {
            for ($index = 0; $index < count($matches[1]); $index++) {
                $commentVars .= trim($matches[2][$index]) . ". ";
            }
        }

        return ['description' => $commentVars];
    }


}