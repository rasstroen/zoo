<?php
abstract class CGrab extends Cron
{
	protected $client_id = 0;

	function run()
	{
		while ($url = $this->getNextPageToGrab($i)) {
			$i++;
			$content = $this->grabPage($url);
			$sells = $this->parse($content);
			$this->saveSells($sells);
		}
	}

	abstract function getNextPageToGrab($i = 0);

	abstract function parse($content);


	function grabPage($url)
	{
		return curl_simple($url);
	}

	function saveSells(array $sells = null)
	{
		foreach ($sells as $sell) {
			if ($sell['price'] && $sell['title'] && $sell['link']) {
				$client_id = $this->client_id;
				$sell_id = Sell::getSellIdByData($sell, $client_id);
				$data = array(
					$sell_id,
					$client_id,
					$sell['offer_id'],
					$sell['title'],
					(isset($sell['description']) ? $sell['description'] : ''),
					(isset($sell['parameters']) ? json_encode($sell['parameters']) : ''),
					(int)$sell['price'],
					$sell['link'],
					$sell['image_url'],
					time()
				);
				App::i()->_db()->web->query('INSERT INTO sells_parsed(sell_id,client_id,offer_id,title,description,
					parameters,price,link,image_url,parse_date)
				VALUES(?,?,?,?,?,?,?,?,?,?) ON DUPLICATE KEY UPDATE `parse_date`=unix_timestamp() ', $data);
			}
		}
	}


}