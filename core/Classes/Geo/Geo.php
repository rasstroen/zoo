<?php
class Geo
{
	private $geoIpCache = array();

	public static $countries = array(
		1 => 'Россия',
		2 => 'Украина'
	);

	const DEFAULT_CITY_ID = 830;

	public function getByIp($ip = false)
	{
		if (isset($this->geoIpCache[$ip])) {
			return $this->geoIpCache[$ip];
		}
		$ip = $ip ? $ip : $_SERVER['REMOTE_ADDR'];
		App::i()->log('getting geo for ip ' . $ip);
		$long_ip = ip2long($ip);
		$raw = App::i()->_db()->web->sql2row('SELECT * FROM `geo__base` WHERE `long_ip1`<=? AND `long_ip2`>=?', array($long_ip, $long_ip));
		$geo = array();
		$geo_current_table = $this->getGeoTable();
		if ($raw['city_id']) {
			$geo = App::i()->_db()->web->sql2row('SELECT * FROM `' . $geo_current_table . '` WHERE `city_id`=?', array($raw['city_id']));
			$geo['ip'] = true;
			App::i()->log('got geo for ip=' . $geo['name'] . ' ' . $geo['id']);
		} else {
			$geo = App::i()->_db()->web->sql2row('SELECT * FROM `' . $geo_current_table . '` WHERE `id`=?', array(Geo::DEFAULT_CITY_ID));
			$geo['default'] = true;
			App::i()->log('default[not found] geo for ip=' . $geo['name'] . ' ' . $geo['id']);
		}

		$this->geoIpCache[$ip] = new Geo_Place($geo);
		return $this->geoIpCache[$ip];
	}

	public function getFullTree()
	{
		$geo_current_table = $this->getGeoTable();
		$geos = App::i()->_db()->web->sql2array('SELECT * FROM `' . $geo_current_table . '` ORDER BY `parent_id`,`sort`', null, 'id');
		return $geos;
	}

	public function regenerate()
	{
		$perPage = 1000;
		$page = 0;
		$citiesLeft = true;
		$geo_generation_table = $this->getGeoGenerationTable();
		$geo_current_table = $this->getGeoTable();
		App::i()->_db()->web->query('TRUNCATE `' . $geo_generation_table . '`');
		App::i()->_db()->web->query('INSERT INTO `' . $geo_generation_table . '` (SELECT * FROM `' . $geo_current_table . '`)');
		$geo_all = App::i()->_db()->web->sql2array('SELECT * FROM `' . $geo_current_table . '`');
		foreach ($geo_all as $row) {
			if ($row['parent_id'] && !$row['last']) {
				$geo_objects[$row['name']] = $row['id'];
			} else if (!$row['last']) {
				$geo_districts[$row['name']] = $row['id'];
			}
		}
		while ($citiesLeft) {
			$query = 'SELECT * FROM `geo__cities` LIMIT ' . ($page * $perPage) . ',' . $perPage;
			$page += 1;
			$citiesRaw = App::i()->_db()->web->sql2array($query);
			$citiesLeft = count($citiesRaw);
			foreach ($citiesRaw as $city) {
				// insert grand
				if (!isset($geo_districts[$city['district']])) {
					$params = array($city['district'], $city['lat'], $city['lng'], 0, $city['lat'], $city['lng'], $city['district']);
					App::i()->_db()->web->query('INSERT INTO `' . $geo_generation_table . '` SET `last`=0,`sort`=0,`name`=?,lat=?,lon=?,parent_id=? ON DUPLICATE KEY UPDATE lat=?,lon=?,name=?', $params);
					$region_parent_id = App::i()->_db()->web->lastId();
					$geo_districts[$city['district']] = $region_parent_id;
					echo "not exists\n";
				} else {
					$region_parent_id = $geo_districts[$city['district']];
				}
				// insert parent
				if (!isset($geo_objects[$city['region']])) {
					$params = array($city['region'], $city['lat'], $city['lng'], $region_parent_id, $city['lat'], $city['lng'], $city['region']);
					App::i()->_db()->web->query('INSERT INTO `' . $geo_generation_table . '` SET `last`=0,`sort`=0,`name`=?,lat=?,lon=?,parent_id=? ON DUPLICATE KEY UPDATE lat=?,lon=?,name=?', $params);
					$city_parent_id = App::i()->_db()->web->lastId();
					$geo_objects[$city['region']] = $city_parent_id;
					echo "not exists\n";
				} else {
					$city_parent_id = $geo_objects[$city['region']];
				}
				// insert into city
				$params = array($city['city_id'], $city['city'], $city['lat'], $city['lng'], $city_parent_id, $city['lat'], $city['lng'], $city['city']);
				App::i()->_db()->web->query('INSERT INTO `' . $geo_generation_table . '` SET city_id=?, `last`=1,`sort`=0, `name`=?,lat=?,lon=?,parent_id=? ON DUPLICATE KEY UPDATE lat=?,lon=?,name=?', $params);
			}
		}
		$this->swapGeoTable();
	}

	private function swapGeoTable()
	{
		return App::i()->_db()->web->query('UPDATE `gen_status` SET `current_geo_table`=' . str_replace('geo_', '', $this->getGeoGenerationTable()));
	}

	private function getGeoGenerationTable()
	{
		$current_geo_table = $this->getGeoTable();
		return ($current_geo_table == 'geo_1') ? 'geo_2' : 'geo_1';
	}

	public function getGeoTable()
	{
		$current_geo_table_id = App::i()->_db()->web->sql2single('SELECT `current_geo_table` FROM `gen_status`');
		return 'geo_' . $current_geo_table_id;
	}
}