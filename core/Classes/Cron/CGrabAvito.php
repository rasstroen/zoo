<?php
class CGrabAvito extends CGrab
{
	protected $client_id = 1;
	function parse($content)
	{

		$pattern = '/\<div class\=\"t_i_i.*\"\>.*\<img.*alt=\"(.*)\".*src=\"(.*)\".*\>.*href=\"(.*)\".*\<span\>(.*)\<\/span\>.*руб\.(.*)\<div class=\"t_i_data\"\>/isU';
		preg_match_all($pattern, $content, $matches);
		$k = 0;
		foreach ($matches[1] as $i => $j) {
			$k++;
			$id = explode('_',$matches[3][$i]);
			$id = array_pop($id);
			$link = 'http://avito.ru'.$matches[3][$i];
			$description = curl_simple($link);
			sleep(1);
			list($strain,$description) = $this->parseDescription($description);
			$sells[$k]['title'] = $matches[1][$i];
			$sells[$k]['offer_id'] = $id;
			$sells[$k]['image_url'] = str_replace('140x105','640x480',$matches[2][$i]);
			$sells[$k]['image_url'] = str_replace('//www.avito.ru/s/a/i/0.gif ','',$sells[$k]['image_url']);
			if(!$sells[$k]['image_url']) {
				unset($sells[$k]);
				continue;
			}
			$sells[$k]['link'] = $link;
			$sells[$k]['price'] = intval(str_replace('&nbsp;','',$matches[4][$i]));
			$sells[$k]['description'] = $description;
			$sells[$k]['parameters'] = array('strain' => $strain);
		}
		return $sells;
	}

	function parseDescription($content){
		$pattern = '/(Порода\:.*\<strong\>(.*)\<\/strong\>).*description?.*\<p\>(.*)\<div class=\"item_sku"\>/isU';
		preg_match_all($pattern, $content, $matches);
		$strain = $matches[2][0];// попрода
		$description=$matches[3][0];
		return array($strain,$description);
	}

	function getNextPageToGrab($i = 0)
	{
		if($i>5) return;
		sleep(5);
		return 'http://www.avito.ru/moskva/koshki?p=' . ($i + 1) . '&i=1';
	}
}