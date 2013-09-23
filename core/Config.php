<?php

class Config {

    private $configuration = array(
        'root_folder' => '/home/m.chubar/httpd/toc.mchubar.torg.ec.mail.ru/',
        /**
         *
         */
        'display_errors' => false,
        'error_reporting' => 0,
        /**
         * В каких директориях искать классы
         */
        'include_pathes' => array(
            'core/',
            'core/App/',
            'core/Exceptions',
            'core/Misc',
            'core/Modules',
            'core/Classes/',
            'core/Classes/Catalog/',
	        'core/Classes/Cron/',
			'core/Classes/Sell/',
			'core/Classes/Sell/Animal/',
			'core/Classes/Sell/Stuff/',
        ),
		/**
		 * бд
		 */
		'db' => array(
			'web' => array(
				'username' => 'root',
				'password' => '2912',
				'dbname' => 'zoo',
				'dbserver' => 'localhost',
			),
		)
    );

    public function getRootPath() {
        return $this->get('root_folder', '');
    }

    public function get($var_name, $default_value = null) {
        return isset($this->configuration[$var_name]) ? $this->configuration[$var_name] : $default_value;
    }

    function __construct(array $local_configuration = array()) {
        foreach ($local_configuration as $var_name => $value)
            $this->configuration[$var_name] = $value;
    }

}