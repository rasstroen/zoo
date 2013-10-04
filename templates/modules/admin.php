<?php
function tp_admin_list_admin_menu_items(ModuleData $data)
{
	?>
	<ul class="admin_list_admin_menu_items">
	<li><a href="<?php echo App::i()->_request()->getRequestUri() ?>catalog">Каталог</a></li>
	<li><a href="<?php echo App::i()->_request()->getRequestUri() ?>theme">Темы</a></li>
	<li><a href="<?php echo App::i()->_request()->getRequestUri() ?>client">Клиенты</a></li>
	<li><a href="<?php echo App::i()->_request()->getRequestUri() ?>geo">Гео</a></li>
	</ul><?php
}

function tp_admin_list_admin_full_menu(ModuleData $data)
{
	?>
	<ul class="admin_list_admin_full_menu">

	</ul><?php
}

function tp_admin_list_catalog_tree(ModuleData $data)
{
	$catalogues = $data->getRaw('catalogues');
	if (is_array($catalogues)) {
		?>
		<form method="post">
		<input type="hidden" name="writemodule" value="admin">
		<input type="hidden" name="action" value="manage_catalogues">

		<div class="add"><a href="#">добавить подкаталог первого уровня</a></div>
		<ul class="level0 catalog_list_main_menu_items"><?php
			$first = true;
			$last = false;
			$cnt = count($catalogues[0]);
			$i = 1;
			foreach ($catalogues[0] as $catalog) {
				/*@var CatalogItem $catalog*/
				if ($cnt == $i++) $last = true;
				_th_recursive_draw_admin_list_catalog_tree($catalog, $catalogues, 0, $first, $last);
				$first = false;
			}
			?>

		</ul></form><?php
	}
}

function _th_recursive_draw_admin_list_catalog_tree(CatalogItem $catalog, $catalogues, $level = 1, $first, $last)
{
	?>
	<li>
	<div>
		<input name="id[<?php echo $catalog->id; ?>]" disabled value="<?php echo $catalog->id; ?>">
		<input name="name[<?php echo $catalog->id; ?>]" value="<?php echo htmlspecialchars($catalog->getName()) ?>">
		<input name="title[<?php echo $catalog->id; ?>]" value="<?php echo htmlspecialchars($catalog->getTitle()) ?>">

		<div class="up"><?php if (!$first) { ?><a href="#">выше</a><?php } ?></div>
		<div class="up"><?php if (!$last) { ?><a href="#">ниже</a><?php } ?></div>
		<div class="add"><a href="#">добавить подкаталог к <?php echo $catalog->getTitle(); ?></a></div>
	</div>
	<?php
	if (isset($catalogues[$catalog->id])) {
		?>
		<ul class="level<?php echo $level; ?>"><?php
		$first = true;
		$last = false;
		$cnt = count($catalogues[$catalog->id]);
		$i = 1;
		foreach ($catalogues[$catalog->id] as $_catalog) {
			if ($cnt == $i++) $last = true;
			_th_recursive_draw_admin_list_catalog_tree($_catalog, $catalogues, $level + 1, $first, $last);
			$first = false;
		}
		?></ul><?php
	}
	?>

	</li><?php
}

function tp_admin_list_geo(ModuleData $data)
{
	?>
	<div>Данные по гео текущего пользователя:</div>
	<pre><?php print_r($geo = $data->getRaw('current_geo'));?></pre>
	<pre><?php print_r($geo->getFullPathArray());?></pre>

	<ul class="districts"><?php
	$geos = $data->getRaw('geo');

	foreach ($geos[0] as $district) {
		$country = GEO::$countries[intval($district['country_id'])];
		?>
		<li class="district"><?php echo htmlspecialchars($district['name']) ?>, <em
			class="country"><?php echo htmlspecialchars($country) ?></em></li><?php
		if (isset($geos[$district['id']])) {
			?>
			<ul class="regions"><?php
			foreach ($geos[$district['id']] as $region) {
				?>
				<li class="region"><span class="id"><?php echo $region['id'];?></span><?php echo htmlspecialchars($region['name']) ?></li><?php
				if (isset($geos[$region['id']])) {
					?>
					<ul class="regions"><?php
					foreach ($geos[$region['id']] as $city) {
						?>
						<li class="region"><span class="id"><?php echo $city['id'];?></span><?php echo htmlspecialchars($city['name']) ?></li><?php
					}
					?></ul><?php
				}
			}
			?></ul><?
		}
	}
	?></ul><?php
}
















