<?php
class Language{
	private $language = 'ru';
	private $t = array(
		'region' => array('ru'=>'Регион'),
	);
	public function t($hash)
	{
		return isset($this->t[$hash][$this->language])?$this->t[$hash][$this->language]:$hash;
	}
}