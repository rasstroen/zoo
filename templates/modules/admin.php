<?php
function tp_admin_list_admin_menu_items(ModuleData $data)
{
	?>
	<ul class="admin_list_admin_menu_items">
		<li><a href="<?php echo App::i()->_request()->getRequestUri()?>catalog">Каталог</a></li>
	<li><a href="<?php echo App::i()->_request()->getRequestUri()?>catalog">Клиенты</a></li>
	</ul><?php
}

function tp_admin_list_admin_full_menu(ModuleData $data)
{
	?>
	<ul class="admin_list_admin_full_menu">

	</ul><?php
}