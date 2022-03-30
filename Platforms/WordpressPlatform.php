<?php


class WordpressPlatform extends PhpPlatform
{

    function __construct()
    {
        parent::__construct();
        require($this->platformRoot . '/wp-config.php');

        $this->host = DB_HOST;
        $this->database = DB_NAME;
        $this->username = DB_USER;
        $this->password = DB_PASSWORD;
        $this->version = get_bloginfo('version');
        $theme = wp_get_theme();
        $this->theme = [
            'name' => $theme->get('Name'),
            'version' => $theme->get('Version'),
            'location' => $theme->get_template_directory(),
        ];

        if (!function_exists('get_plugins')) {
            require_once ABSPATH . 'wp-admin/includes/plugin.php';
        }

        $this->plugins = get_plugins();
    }
}
