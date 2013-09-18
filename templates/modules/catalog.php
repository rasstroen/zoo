<?php
function tp_catalog_list_main_menu_items(ModuleData $data)
{
	?>
	<ul>
		<li>Каталог</li>
		<ul>
			<li><a href="/catalog/kittens">Котята</a></li>
			<li><a href="/catalog/puppies">Щенки</a></li>
		</ul>
	</ul>

<?php
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
function tp_catalog_list_catalog_childrens(ModuleData $data)
{
	?><?php
}

/**
 * Список хитов каталога
 * @param ModuleData $data
 */
function tp_catalog_list_catalog_top(ModuleData $data)
{
	?><?php
}