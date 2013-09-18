<?php
require_once App::i()->config->get('root_folder') . 'templates/includes/include.php';
require_once App::i()->config->get('root_folder') . 'templates/includes/head.php';
?>
<body>
    <div class="l-container">
        <div class="l-header"><?php th_process_block('header'); ?></div>
        <div class="l-wrapper"><div class="l-content"><?php th_process_block('left'); ?></div>
            <div class="l-sidebar"><?php th_process_block('content'); ?></div></div>
    </div>
    <div class="l-footer"><?php th_process_block('footer'); ?></div>
</body>