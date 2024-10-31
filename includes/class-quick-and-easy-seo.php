<?php

/**
 * The core plugin class.
 *
 * Used to add admin-specific hooks, and public-facing site hooks.
 *
 * Also maintains the unique identifier of this plugin as well as the current
 * version of the plugin.
 *
 * @since      1.0.0
 * @package    Quick_And_Easy_Seo
 * @subpackage Quick_And_Easy_Seo/includes
 */
class Quick_And_Easy_Seo {

	
	/* The loader that's responsible for maintaining and registering all hooks that power */
	protected $loader;

	/* The unique identifier of this plugin. */
	protected $plugin_name;

	/* The current version of the plugin. */
	protected $version;

	
	 /* Define the core functionality of the plugin. */
	public function __construct() {

		$this->plugin_name = 'quick-and-easy-seo';
		$this->version = '1.4.5';

		$this->load_dependencies();
		$this->define_admin_hooks();
		$this->define_public_hooks();

	}

	/* Load the required dependencies for this plugin. */
	
	private function load_dependencies() {

		/* The class responsible for orchestrating the actions and filters of the core plugin. */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-quick-and-easy-seo-loader.php';

		/* The class responsible for defining all actions that occur in the admin area. */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/class-quick-and-easy-seo-admin.php';

		/* The class responsible for defining all actions that occur in the public-facing side of the site. */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'public/class-quick-and-easy-seo-public.php';
		
		/* New instance of loader class */
		$this->loader = new Quick_And_Easy_Seo_Loader();

	}

	/* Register all of the hooks related to the admin area functionality of the plugin. */
	private function define_admin_hooks() {

		$plugin_admin = new Quick_And_Easy_Seo_Admin( $this->get_plugin_name(), $this->get_version() );

		$plugin_basename = plugin_basename( plugin_dir_path( __DIR__ ) . $this->plugin_name . '.php' );
		
		/*Use this to load plugin scripts*/
		#$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_scripts' );
		
		/*Plugin forms and meta boxes*/
		$this->loader->add_filter( 'plugin_action_links_'.$plugin_basename, $plugin_admin, 'qeseo_plug_settings_link' );
		$this->loader->add_action( 'admin_menu', $plugin_admin, 'qeseo_add_sb_menu_link' );
		$this->loader->add_action( 'admin_init', $plugin_admin, 'qeseo_settings_form_fields' );
		$this->loader->add_action( 'add_meta_boxes', $plugin_admin, 'qeseo_add_title_tag_metabox' );
		$this->loader->add_action( 'add_meta_boxes', $plugin_admin, 'qeseo_add_metades_tag_metabox' );
		$this->loader->add_action( 'save_post', $plugin_admin, 'qeseo_title_tag_data' );
		$this->loader->add_action( 'save_post', $plugin_admin, 'qeseo_metades_data' );
		/* save media attachment tags*/
		$this->loader->add_action( 'edit_attachment', $plugin_admin, 'qeseo_title_tag_data' );
		$this->loader->add_action( 'edit_attachment', $plugin_admin, 'qeseo_metades_data' );
		/* Add meta box and update options for woocommerce product category and tag pages */
		$this->loader->add_action('product_cat_edit_form_fields', $plugin_admin, 'qeseo_woo_metades_title_form_fields');
		$this->loader->add_action('product_tag_edit_form_fields', $plugin_admin, 'qeseo_woo_metades_title_form_fields');
		$this->loader->add_action('edited_product_cat', $plugin_admin, 'qeseo_woo_metades_title_update');
		$this->loader->add_action('edited_product_tag', $plugin_admin, 'qeseo_woo_metades_title_update');

	}

	/* Register all of the hooks related to the public-facing functionality of the plugin. */
	private function define_public_hooks() {

		$plugin_public = new Quick_And_Easy_Seo_Public( $this->get_plugin_name(), $this->get_version() );
		
		$this->loader->add_filter( 'document_title_parts', $plugin_public, 'qeseo_add_custom_title_tag' );
		/* Set title tag sep */
		$this->loader->add_filter( 'document_title_separator', $plugin_public, 'qeseo_title_tag_sep' );
		$this->loader->add_action( 'wp_head', $plugin_public, 'qeseo_print_all_values', 2 );
		/* Add facebook og tag prefix*/
		$this->loader->add_action( 'language_attributes', $plugin_public, 'qeseo_fb_og_prefix' );
		}

	/* Run the loader to execute all of the hooks with WordPress. */
	public function run() {
		$this->loader->run();
	}

	/* The name of the plugin used to uniquely identify it */
	public function get_plugin_name() {
		return $this->plugin_name;
	}

	/* The reference to the class that orchestrates the hooks with the plugin. */
	public function get_loader() {
		return $this->loader;
	}

	/* Retrieve the version number of the plugin. */
	public function get_version() {
		return $this->version;
	}

}
