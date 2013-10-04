<?php

abstract class Module {

    private $configuration = array();
    private $data = array();

    function setConfiguration(array $configuration) {
        $this->configuration = $configuration;
    }

    function beforeRun() {
        
    }

    function run() {
        $this->beforeRun();
        $this->executeMethod($this->configuration['action'], $this->configuration['mode']);
    }

    function executeMethod($action, $mode) {
        $method_name = 'method_' . $action . '_' . $mode;
        if (!method_exists($this, $method_name)) {
            throw new MisconfigurationException('no method ' . $method_name . ' exists in module ' . $this->configuration['name']);
        }
	    App::i()->_logger()->timing($method_name);
        $this->data = $this->$method_name($this->configuration);
	    App::i()->_logger()->timing($method_name);
    }

    function getResponseObject() {
        $write = App::i()->getWriteParams($this->configuration['name'], $this->configuration['action']);
        return new ModuleData($this->data, $this->configuration, $write);
    }

}