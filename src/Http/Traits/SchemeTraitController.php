<?php namespace APISchema\Http\Traits;

trait SchemeTraitController {

    public function callAction($method, $parameters) {
        $result = $this->getScheme($method);

        if (is_null($result)) {
            $result = parent::callAction($method, $parameters);
        }

        return $result;
    }

    public function getScheme($method) {

        if (!Request::exists('scheme')) {
            return null;
        }

        $result = ['schema' => [],
                   'validations' => [],
                   'help' => []
        ];
        $rc = new \ReflectionClass($this);
        $comment = $rc->getMethod($method)->getDocComment();

        $commentParsed = array();
        if (preg_match_all('/@(\w+)\s+(.*)\r?\n/m', $comment, $matches)){
            for($index=0;$index<count($matches[1]);$index++) {
                $commentParsed[$matches[1][$index]][] = trim($matches[2][$index]);
            }
        }

        if (isset($commentParsed['input'])) {
            $input = $commentParsed['input'][0];
            $input = new $input();
            $inputScheme = ['schema' => $input->getDefaults(),
                            'validations' => $input->getAllValidators(),
                            'help' => $input->getHelp()
            ];
            $this->result->setData($inputScheme);
            $result = $this->result->getResponseJSON();
        }

        return $result;
    }

}
