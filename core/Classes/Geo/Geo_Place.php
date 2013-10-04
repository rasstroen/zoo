<?php
class Geo_Place
{
	function __construct($data)
	{
		$this->id = $data['id'];
		$this->city_raw_id = $data['city_id'];
		if (isset($data['ip']))
			$this->got_by_ip = true;
		if (isset($data['default']))
			$this->default = true;
		$this->data = $data;
	}

	function getFullPathArray()
	{
		$geo_current_table = App::i()->_geo()->getGeoTable();
		$parent_id = $this->data['parent_id'];
		$path = array($this->id=>$this->data['name']);
		while ($parent_id) {
			$parent = App::i()->_db()->web->sql2row('SELECT * FROM `' . $geo_current_table . '` WHERE `id`=?',array($parent_id));
			$path[$parent['id']] = $parent['name'];
			$parent_id = $parent['parent_id'];
		}
		return $path;
	}
}