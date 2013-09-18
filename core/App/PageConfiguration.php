<?php

class PageConfiguration {

    private $configuration = array();

    function __construct(array $configuration) {
        $this->configuration = $configuration;
    }

    public function getSetting($setting_name, $default = array()) {
        return isset($this->configuration[$setting_name]) ? $this->configuration[$setting_name] : $default;
    }

    public function getBlockConfiguration($block_name) {
        return isset($this->configuration['blocks'][$block_name]) ? $this->configuration['blocks'][$block_name] : array();
    }

    public function getLayout() {
        return $this->configuration['layout'];
    }

    public function getBlocks() {
        return isset($this->configuration['blocks']) ? $this->configuration['blocks'] : array();
    }

    public function getPageTitle($default = '') {
        return isset($this->configuration['title']) ? $this->configuration['title'] : $default;
    }

}