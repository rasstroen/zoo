<?php

class ResponseConfiguration {

    /**
     * Конфигурация ответов для всех запросов к приложению.
     * Содержит в себе массив с ключом "имя запрошенной страницы",
     * Для ajax запросов страницы называются ajax/имя_метода.
     * Конфигурация включает layout, список модулей, настройки nginx
     * кеширования, настройки модулей. Любой модуль может показываться с
     * помощью ssi, и тогда у него также может иметься настройка nginx
     * кеширования.
     * @var array
     */
    private $pages_configuration = null;

    /**
     *
     * @var array
     */
    private $current_configuration = null;

    private function loadProjectPageConfiguration() {
        if (null === $this->pages_configuration) {
            $pfname = App::i()->config->get('root_folder', '') . 'project/pagesconfig.php';
            require_once $pfname;
            if (isset($pagesconfig))
                $this->pages_configuration = $pagesconfig;
        }
    }

    function getResponseModules() {
        return $this->getBlocks();
    }

    private function applyConfigPart($config_section, $config_data, array $result_config) {
        $method = 'apply_' . $config_section . '_Config';
        return $this->$method($config_data, $result_config);
    }

    /**
     * добавляем js в конфигурацию страницы
     *
     * @param array $config_data
     * @param array $result_config
     * @return array
     */
    private function apply_js_Config(array $config_data, array $result_config) {
        foreach ($config_data as $name) {
            if (strpos($name, 'http') === 0) {
                $result_config['js'][$name] = $name;
            } else {
                $result_config['js'][$name] = App::i()->config->get('www_static_path', '/static/') . 'js/' . $name . '.js?' . App::i()->config->get('static_files_version', '0');
            }
        }
        return $result_config;
    }

    private function apply_blocks_Config(array $config_data, array $result_config) {
        foreach ($config_data as $block => $modules) {
            foreach ($modules as $module) {
                $key = $module['name'] . '_' . $module['action'] . '_' . $module['mode'];
                $module_config = array();
                $module_config['name'] = $module['name'];
                $module_config['action'] = $module['action'];
                $module_config['mode'] = $module['mode'];
                /**
                 * additional block settings
                 */
                $module_config['params'] = $this->applyModuleBindedParams($module);
                $module_config['ssi'] = (isset($module['ssi']) && $module['ssi']) ? true : false;
                $template_name = (isset($module['template']) && $module['template']) ? $module['template'] : $module['name'];
                $module_config['template'] = App::i()->config->get('root_folder') . 'templates/modules/' . $template_name . '.php';
                $module_config['template_function'] = 'tp_' . $template_name . '_' . $module['action'] . '_' . $module['mode'];

                $result_config['blocks'][$block][$key] = $module_config;
            }
        }
        return $result_config;
    }

    private function apply_css_Config(array $config_data, array $result_config) {
        foreach ($config_data as $name) {
            if (strpos($name, 'http') === 0) {
                $result_config['css'][$name] = $name;
            } else {
                $result_config['css'][$name] = App::i()->config->get('www_static_path', '/static/') . 'css/' . $name . '.css?' . App::i()->config->get('static_files_version', '0');
            }
        }
        return $result_config;
    }

    private function applyModuleBindedParams($module) {
        $params = array();
        return $params;
    }

    function getResponseSettings($page_name) {
        $this->loadProjectPageConfiguration();
        if (!isset($this->pages_configuration[$page_name]))
            throw new NotFoundException('404 ' . $page_name . ' configuration missed');

        $found_config = $this->pages_configuration[$page_name];
        /**
         * отдадим подготовленный конфиг на основе сырого
         */
        $result_config = array(
            'css' => array(),
            'js' => array(),
            'blocks' => array(),
            'nginx_cache' => 0,
            'title' => '',
        );
        /**
         * Ищем конфигурацию  "по умолчанию" для страницы
         */
        if (!isset($found_config['layout'])) {
            throw new MisconfigurationException('No layout in configuration for page id#' . $page_id);
        }
        $result_config['layout'] = App::i()->config->get('root_folder') . 'templates/layouts/' . $found_config['layout'] . '.php';
        $result_config['title'] = isset($found_config['title']) ? $found_config['title'] : '';
        if (isset($this->pages_configuration['default'][$found_config['layout']])) {
            $def_config = $this->pages_configuration['default'][$found_config['layout']];
            /*
             * Сначала процессим все настройки "по умолчанию". Настройки самой страницы могут перетереть настройки умолчания.
             */
            if (isset($def_config['js'])) {
                $result_config = $this->applyConfigPart('js', $def_config['js'], $result_config);
            }
            if (isset($def_config['css'])) {
                $result_config = $this->applyConfigPart('css', $def_config['css'], $result_config);
            }

            if (isset($def_config['blocks'])) {
                $result_config = $this->applyConfigPart('blocks', $def_config['blocks'], $result_config);
            }
            /**
             * переопределяем время кеша
             */
            foreach (array('nginx_cache') as $config_section_name) {
                if (isset($def_config[$config_section_name])) {
                    $result_config[$config_section_name] = $def_config[$config_section_name];
                }
            }
        }

        if (isset($found_config['js'])) {
            $result_config = $this->applyConfigPart('js', $found_config['js'], $result_config);
        }
        if (isset($found_config['css'])) {
            $result_config = $this->applyConfigPart('css', $found_config['css'], $result_config);
        }
        if (isset($found_config['blocks'])) {
            $result_config = $this->applyConfigPart('blocks', $found_config['blocks'], $result_config);
        }
        /**
         * Собираем модули для блоков
         */
        $this->current_configuration = $result_config;
    }

    public function getSetting($setting_name, $default = array()) {
        return isset($this->current_configuration[$setting_name]) ? $this->current_configuration[$setting_name] : $default;
    }

    public function getBlockConfiguration($block_name) {
        return isset($this->current_configuration['blocks'][$block_name]) ? $this->current_configuration['blocks'][$block_name] : array();
    }

    public function getLayout() {
        return $this->current_configuration['layout'];
    }

    public function getBlocks() {
        return isset($this->current_configuration['blocks']) ? $this->current_configuration['blocks'] : array();
    }

    public function getPageTitle($default = '') {
        return isset($this->current_configuration['title']) ? $this->current_configuration['title'] : $default;
    }

    /**
     * Ищем имя страницы, по которому определим все настройки ответа
     * @return type
     */
    function getPageNameByRequest() {
        $page_name = false;
        /**
         * AJAX запросы обязательно содержат поле method
         */
        if (null !== App::i()->_request()->param('method', null)) {
            $page_name = App::i()->_request()->param('method');
        }
        /**
         * Иначе ищем страницу по реквесту по карте
         */ else {
            $page_name = $this->getPageNameByMap();
        }
        return $page_name;
    }

    function getPageNameByMap() {
        $map = new Map();
        return $map->getPageNameByRequest(App::i()->_request()->getRequestUriArray(), $strict = true);
    }

}