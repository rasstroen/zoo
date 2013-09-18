<?php

function th_process_block($block_name) {
    $moduleDatas = App::i()->getBlockModulesData($block_name);
    if (false == $moduleDatas)
        return;
    foreach ($moduleDatas as $moduleData) {
        /* @var $moduleData ModuleData */
        $template_function = $moduleData->getTemplateFunctionName();
        $template_filename = $moduleData->getTemplateFileName();
        if (is_readable($template_filename)) {
            ?><!--<?php echo $template_function; ?> --><?php
            require_once $template_filename;
        }
        else
            throw new ApplicationException('template ' . $template_filename . ' missed');
        if (function_exists($template_function)) {
            $template_function($moduleData);
        }
        else
            echo ('<!-- -->missed function ' . $template_function . '(ModuleData $data){}<!-- -->');
    }
}

