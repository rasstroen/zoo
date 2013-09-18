<?php

class Seo {

    private $seo = array();

    public function setSeoTitle($title) {
        $this->seo['title'] = $title;
    }

    public function setSeoDescription($title) {
        $this->seo['description'] = $title;
    }

    public function setSeoKeywords($title) {
        $this->seo['keywords'] = $title;
    }

    public function getSeoTitle() {
        return isset($this->seo['title']) ? $this->seo['title'] : '';
    }

    public function getSeoDescription() {
        return isset($this->seo['description']) ? $this->seo['description'] : '';
    }

    public function getSeoKeywords() {
        return isset($this->seo['keywords']) ? $this->seo['keywords'] : '';
    }

}