<?php

class aLoader {

    function setIncludePathes(Config $config) {
        $includePathes = $config->get('include_pathes');
        $root = $config->get('root_folder', '');
        foreach ($includePathes as &$path) {
            $path = $root . $path;
        }
        set_include_path(get_include_path() . PATH_SEPARATOR . implode(PATH_SEPARATOR, $includePathes));
    }

}

function __autoload($className) {
    require_once($className . '.php');
}