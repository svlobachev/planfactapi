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


		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/classes/class-planfactapi-core.php';

		/**
		 * The class responsible for defining all actions that occur in the admin area.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/classes/class-planfactapi-admin.php';

		/**
		 * The class responsible for defining all actions that occur in the public-facing
		 * side of the site.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'public/class-planfactapi-public.php';

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

		$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_styles' );
		$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_scripts' );
        // Add Settings link to the plugin
        $this->loader->add_action( 'admin_menu', $plugin_admin, 'add_plugin_admin_menu' );
        $plugin_basename = plugin_basename( plugin_dir_path( __DIR__ ) . $this->plugin_name . '.php' );
        $this->loader->add_filter( 'plugin_action_links_' . "$plugin_basename", $plugin_admin, 'add_action_links' );
        $this->loader->add_action( 'admin_menu', $plugin_admin, 'remove_menu_setting_links' );

        //добавим поля в форму регистрации на фронте
        $this->loader->add_action( 'register_form', $plugin_admin, 'regform_show_fields', 1 );
        $this->loader->add_filter( 'registration_errors', $plugin_admin, 'regform_check_fields', 25, 3 );
        $this->loader->add_action( 'user_register', $plugin_admin, 'regform_register_fields' );
        //добавим поля в админку в форму добавить пользователя
        $this->loader->add_action( 'user_new_form', $plugin_admin, 'regform_admin_registration_form' );
        $this->loader->add_action( 'user_profile_update_errors', $plugin_admin, 'regform_validate_fields_in_admin', 10, 3 );
        $this->loader->add_action( 'edit_user_created_user', $plugin_admin, 'regform_register_admin_fields' );
        //добавим поля в админку в профиль пользователя
        //добавим регистрацию на кириллице
        $this->loader->add_filter('sanitize_user',  $plugin_admin, 'allow_cyrillic_usernames', 10, 3);
        // когда пользователь сам редактирует свой профиль
        $this->loader->add_action( 'show_user_profile', $plugin_admin,  'regform_show_profile_fields' );
        // когда чей-то профиль редактируется админом например
        $this->loader->add_action( 'edit_user_profile', $plugin_admin,  'regform_show_profile_fields' );
        // когда пользователь сам редактирует свой профиль
        $this->loader->add_action( 'personal_options_update', $plugin_admin,  'regform_save_profile_fields' );
        // когда чей-то профиль редактируется админом например
        $this->loader->add_action( 'edit_user_profile_update', $plugin_admin, 'regform_save_profile_fields' );


        // измененное уведомление админу о регистрации пользователя
        $this->loader->add_filter( 'wp_new_user_notification_email_admin', $plugin_admin, 'custom_wp_new_user_notification_email_admin', 10, 3 );
        // отключае отправку писем  при регистрации
        remove_action( 'register_new_user', 'wp_send_new_user_notifications' );
        // разрешаем отправку писем админу при регистрации нового пользователя
        $this->loader->add_action( 'register_new_user', $plugin_admin,  'notify_only_admin' );
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

		$this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'enqueue_styles' );
		$this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'enqueue_scripts' );

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
