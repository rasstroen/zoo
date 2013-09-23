<?php
function tp_catalog_list_main_menu_items(ModuleData $data)
{
	$catalogues = $data->getRaw('catalogues');
	if (is_array($catalogues)) {
		?>
		<ul class="level0 catalog_list_main_menu_items"><?php
		foreach ($catalogues[0] as $catalog) {
			/*@var CatalogItem $catalog*/
			_th_recursive_draw_catalogue_main_menu($catalog, $catalogues);
		}
		?></ul><?php
	}
}

function _th_recursive_draw_catalogue_main_menu(CatalogItem $catalog, $catalogues, $level = 1)
{
	print_r(App::i()->_geo()->getByIp());
	?>
	<li><a
	href="<?php echo $catalog->getRelativeUrl() ?>"><?php echo htmlspecialchars($catalog->getTitle()); ?></a><?php
	if (isset($catalogues[$catalog->id])) {
		?>
		<ul class="level<?php echo $level; ?>"><?php
		foreach ($catalogues[$catalog->id] as $_catalog) {
			_th_recursive_draw_catalogue_main_menu($_catalog, $catalogues, $level + 1);
		}
		?></ul><?php
	}
	?></li><?php
}

function _th_recursive_draw_catalogue_menu(CatalogItem $catalog, $catalogues, $level = 1)
{
	?>
	<li><a
	href="<?php echo $catalog->getRelativeUrl() ?>"><?php echo htmlspecialchars($catalog->getTitle()); ?></a><?php
	if (isset($catalogues[$catalog->id])) {
		?>
		<ul class="level<?php echo $level; ?>"><?php
		foreach ($catalogues[$catalog->id] as $_catalog) {
			_th_recursive_draw_catalogue_menu($_catalog, $catalogues, $level + 1);
		}
		?></ul><?php
	}
	?></li><?php
}

/**
 * Популярные каталоги
 *
 * @param ModuleData $data
 */
function tp_catalog_list_main_popular_categories(ModuleData $data)
{
	?><?php
}

/**
 * Рисуем список подкаталогов текущего каталога
 * @param ModuleData $data
 */
function tp_catalog_list_catalog_menu_items(ModuleData $data)
{
	$catalog = $data->getRaw('catalog');
	/**@var  $catalog CatalogItem */
	?><a href="/">Каталог><?php echo htmlspecialchars($catalog->getTitle()); ?></a><?php
	$catalogues = $data->getRaw('catalogues');

	if (is_array($catalogues)) {
		if (isset($catalogues[$catalog->id])) {

			?>
			<ul class="level0 catalog_list_main_menu_items"><?php
			foreach ($catalogues[$catalog->id] as $catalog) {
				/*@var CatalogItem $catalog*/
				_th_recursive_draw_catalogue_menu($catalog, $catalogues);
			}
		}
		?></ul><?php
	}
}

/**
 * Список хитов каталога
 * @param ModuleData $data
 */
function tp_catalog_list_catalog_top(ModuleData $data)
{
	?><?php
}