<?php

class ModuleData {

    private $configuration = array();
    private $data = array();
    private $write = array(); 

    function __construct(array $data, array $configuration, array $write) {
        $this->data = $data;
        $this->write = $write;
        $this->configuration = $configuration;
    }

    function getResponseRaw() {
        return $this->data + $this->write;
    }

    function __get($name) {
        if (isset($this->data[$name]))
            return $this->data[$name];
        throw new ApplicationException($name . ' variable is not set');
    }

    function __isset($name) {
        return isset($this->data[$name]);
    }

    function getTemplateFunctionName() {
        return $this->configuration['template_function'];
    }

    function getTemplateFileName() {
        return $this->configuration['template'];
    }

    function get($field, $default = null) {
        return isset($this->data[$field]) ? htmlspecialchars($this->data[$field]) : $default;
    }

    function getRaw($field, $default = null) {
        return isset($this->data[$field]) ? ($this->data[$field]) : $default;
    }

    function getWriteParam($field, $default = null) {
        return isset($this->write[$field]) ? htmlspecialchars($this->write[$field]) : $default;
    }

}