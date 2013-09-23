<?php
class Geo
{
	public function getByIp($ip = false)
	{
		$ip = $ip ? $ip : $_SERVER['REMOTE_ADDR'];
		$long_ip = ip2long($ip);
		$geo_place = App::i()->_db()->web->sql2array('SELECT * FROM `geo__base` WHERE `long_ip1`<=? AND `long_ip2`>=?', array($long_ip, $long_ip));
		return $geo_place;
	}
}