<?php

function tp_html_show_menu(ModuleData $data)
{
	?><?php
}

function tp_html_show_main_title(ModuleData $data)
{
	?><h1>ZOO</h1><?php
}

function tp_html_show_main(ModuleData $data)
{
	?><?php
}

function tp_html_show_header_logo(ModuleData $data)
{
	$current_geo =  $data->getRaw('geo');
	/**@var Geo_Place $geo*/
	$geo_tree =  $data->getRaw('geos');
	?>
	<div class="logo_line">
		<?php _th_geo_plank($current_geo,$geo_tree);?>
	</div>
<?php
}

function _th_geo_plank(Geo_Place $geo, array $tree)
{
	$path = $geo->getFullPathArray();
	$geo_last = array_shift($path);
	echo App::i()->_language()->t('region');?>: <span class="geo_selector" title="<?php echo implode(', ',array_reverse($path))?>"><?php echo $geo_last?></span><?php
}

function tp_html_show_footer(ModuleData $data)
{
	?><?php
}