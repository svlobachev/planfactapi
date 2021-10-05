<?php

/**
 * The file that defines the core plugin class
 *
 * A class definition that includes attributes and functions used across both the
 * public-facing side of the site and the admin area.
 *
 * @link       https://github.com/svlobachev/planfactapi
 * @since      1.0.0
 *
 * @package    Planfactapi
 * @subpackage Planfactapi/includes
 */

/**
 * The core plugin class.
 *
 * This is used to define internationalization, admin-specific hooks, and
 * public-facing site hooks.
 *
 * Also maintains the unique identifier of this plugin as well as the current
 * version of the plugin.
 *
 * @since      1.0.0
 * @package    Planfactapi
 * @subpackage Planfactapi/includes
 * @author     Sergei Lobachev <gsvlobachev@gmail.com>
 */
class Planfactapi {

	/**
	 * The loader that's responsible for maintaining and registering all hooks that power
	 * the plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      Planfactapi_Loader    $loader    Maintains and registers all hooks for the plugin.
	 */
	protected $loader;

	/**
	 * The unique identifier of this plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string    $plugin_name    The string used to uniquely identify this plugin.
	 */
	protected $plugin_name;

	/**
	 * The current version of the plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string    $version    The current version of the plugin.
	 */
	protected $version;

	/**
	 * Define the core functionality of the plugin.
	 *
	 * Set the plugin name and the plugin version that can be used throughout the plugin.
	 * Load the dependencies, define the locale, and set the hooks for the admin area and
	 * the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function __construct() {
		if ( defined( 'PLANFACTAPI_VERSION' ) ) {
			$this->version = PLANFACTAPI_VERSION;
		} else {
			$this->version = '1.0.0';
		}
		$this->plugin_name = 'planfactapi';

		$this->load_dependencies();
		$this->set_locale();
		$this->define_admin_hooks();
		$this->define_public_hooks();

	}

	/**
	 * Load the required dependencies for this plugin.
	 *
	 * Include the following files that make up the plugin:
	 *
	 * - Planfactapi_Loader. Orchestrates the hooks of the plugin.
	 * - Planfactapi_i18n. Defines internationalization functionality.
	 * - Planfactapi_Admin. Defines all hooks for the admin area.
	 * - Planfactapi_Public. Defines all hooks for the public side of the site.
	 *
	 * Create an instance of the loader which will be used to register the hooks
	 * with WordPress.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function load_dependencies() {

		/**
		 * The class responsible for orchestrating the actions and filters of the
		 * core plugin.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-planfactapi-loader.php';

		/**
		 * The class responsible for defining internationalization functionality
		 * of the plugin.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-planfactapi-i18n.php';

		/**
		 * The class responsible for defining all actions that occur in the admin area.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/class-planfactapi-admin.php';
        require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/classes/class-planfactapi-core.php';
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/classes/class-planfactapi-menu.php';


		/**
		 * The class responsible for defining all actions that occur in the public-facing
		 * side of the site.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'public/class-planfactapi-public.php';
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'public/classes/class-planfactapi-form-registation.php';
        require_once plugin_dir_path( dirname( __FILE__ ) ) . 'public/classes/class-planfactapi-sets.php';
        require_once plugin_dir_path( dirname( __FILE__ ) ) . 'public/classes/class-planfactapi-ajax-action.php';

		$this->loader = new Planfactapi_Loader();

	}

	/**
	 * Define the locale for this plugin for internationalization.
	 *
	 * Uses the Planfactapi_i18n class in order to set the domain and to register the hook
	 * with WordPress.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function set_locale() {

		$plugin_i18n = new Planfactapi_i18n();

		$this->loader->add_action( 'plugins_loaded', $plugin_i18n, 'load_plugin_textdomain' );

	}

	/**
	 * Register all of the hooks related to the admin area functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_admin_hooks() {

		$plugin_admin = new Planfactapi_Admin( $this->get_plugin_name(), $this->get_version() );
        //добавим хуки класса меню
        $plugin_menu = new Planfactapi_menu( $this->get_plugin_name(), $this->get_version() );


		$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_styles' );
		$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_scripts' );

        //добавим меню и ссылку настройки в админку плагина
        $this->loader->add_action( 'admin_menu', $plugin_menu, 'add_plugin_admin_menu' );
        $plugin_basename = plugin_basename( plugin_dir_path( __DIR__ ) . $this->plugin_name . '.php' );
        $this->loader->add_filter( 'plugin_action_links_' . "$plugin_basename", $plugin_menu, 'add_action_links' );
        $this->loader->add_action( 'admin_menu', $plugin_menu, 'remove_menu_setting_links' );






	}

	/**
	 * Register all of the hooks related to the public-facing functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_public_hooks() {

		$plugin_public = new Planfactapi_Public( $this->get_plugin_name(), $this->get_version() );
		$plugin_regform = new Planfactapi_public_regform( $this->get_plugin_name(), $this->get_version() );
		$plugin_sets = new Planfactapi_public_sets( $this->get_plugin_name(), $this->get_version() );
		$plugin_ajax_action = new Planfactapi_public_ajax_action( $this->get_plugin_name(), $this->get_version() );

		$this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'enqueue_styles' );
		$this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'enqueue_scripts' );

        $this->loader->add_shortcode( 'art_regform', $plugin_regform,  'art_regform' );
        $this->loader->add_action( 'wp_enqueue_scripts', $plugin_sets,  'art_regform_scripts' );
        $this->loader->add_action( 'wp_ajax_regform_action',$plugin_ajax_action, 'ajax_action_callback' );
        $this->loader->add_action( 'wp_ajax_nopriv_regform_action',$plugin_ajax_action, 'ajax_action_callback' );
	}

	/**
	 * Run the loader to execute all of the hooks with WordPress.
	 *
	 * @since    1.0.0
	 */
	public function run() {
		$this->loader->run();
	}

	/**
	 * The name of the plugin used to uniquely identify it within the context of
	 * WordPress and to define internationalization functionality.
	 *
	 * @since     1.0.0
	 * @return    string    The name of the plugin.
	 */
	public function get_plugin_name() {
		return $this->plugin_name;
	}

	/**
	 * The reference to the class that orchestrates the hooks with the plugin.
	 *
	 * @since     1.0.0
	 * @return    Planfactapi_Loader    Orchestrates the hooks of the plugin.
	 */
	public function get_loader() {
		return $this->loader;
	}

	/**
	 * Retrieve the version number of the plugin.
	 *
	 * @since     1.0.0
	 * @return    string    The version number of the plugin.
	 */
	public function get_version() {
		return $this->version;
	}

}
