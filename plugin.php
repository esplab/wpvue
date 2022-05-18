<?php

/**
 * @wordpress-plugin
 * Plugin Name:       WordPress Vue
 * Plugin URI:        http://selias.eu
 * Description:       A Wordpress Vue Tailwind plugin template
 * Version:           1.0.0
 * Author:            Spathopoulos Ilias
 * Author URI:        selias.eu
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       wpvue-plugin
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if (!defined('WPINC')) {
    die;
}

require('mix.php');

class WpVuePlugin
{
    public $plugin;
    protected $textdomain = 'wpvue-plugin';
    protected $slug = 'wpvue';
    protected $id = 'wpvue';
    protected $mountpoint;
    protected $namespace = 'wpvue';
    protected $data = [];

    public function __construct()
    {
        $this->plugin = plugin_basename(__FILE__);
        $this->mountpoint = '#' . $this->id;
        $this->data = [
            'slug' => $this->slug,
            'mountpoint' => $this->mountpoint,
            'url' => plugin_dir_url(__FILE__),
            'path' => plugin_dir_path(__FILE__),
            'namespace' => $this->namespace
        ];
    }

    public function register()
    {
        add_action('admin_menu', array($this, 'add_admin_page'));
        add_action('admin_enqueue_scripts', array($this, 'enqueue_assets'));
        add_filter('plugin_action_links_' . $this->plugin, array($this, 'settings_link'));
    }

    public function settings_link($links)
    {
        $settings_link = '<a href="' . $this->route('settings') . '">Settings</a>';
        array_push($links, $settings_link);
        return $links;
    }

    public function enqueue_assets()
    {
        $pass = 'const ' . $this->namespace . ' = ' . json_encode($this->data);

        wp_enqueue_style($this->plugin.'-css', mix('/app.css'), null, null);
        wp_enqueue_script($this->plugin.'-js', mix('/app.js'), null, null, true);
        wp_add_inline_script($this->plugin.'-js', $pass, 'before');
    }

    public function route($name = '')
    {
        return 'admin.php?page=' . $this->slug . '#/' . $name;
    }

    public function add_admin_page()
    {
        global $submenu;

        $capability = 'manage_options';

        add_menu_page(__('WpVue', 'textdomain'), 'WpVue', $capability, $this->slug, [$this, 'admin_index'], '');

        if (current_user_can($capability)) {
            $submenu[$this->slug][] = [__('App', $this->textdomain), $capability, $this->route('')];
            $submenu[$this->slug][] = [__('Settings', $this->textdomain), $capability, $this->route('settings')];
            $submenu[$this->slug][] = [__('About', $this->textdomain), $capability, $this->route('about')];
        }
    }

    public function admin_index()
    {   // Use an index.php if you want more complex layout
        // include the following div in your file and comment out 
        // the line with the echo.

        // require_once plugin_dir_path(__FILE__) . 'public/index.php';

        echo '<div id="' . $this->id . '"></div>';
    }
}

if (class_exists('WpVuePlugin')) {
    $wpvuePlugin = new WpVuePlugin();
    $wpvuePlugin->register();
}

class wpvueActivate
{
    public static function activate()
    {
        flush_rewrite_rules();
    }
}

class wpvueDeactivate
{
    public static function deactivate()
    {
        flush_rewrite_rules();
    }
}

register_activation_hook(__FILE__, array('wpvueActivate', 'activate'));
register_deactivation_hook(__FILE__, array('wpvueDeactivate', 'deactivate'));
