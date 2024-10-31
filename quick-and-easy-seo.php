<?php

/**
 * Plugin Name:       Quick And Easy SEO Tool
 * Description:       A light weight yet powerful plugin that fixes all SEO loopholes in wordpress. Woocommerce Compatible.
 * Version:           1.4.7
 * Author:            Mukesh Mani
 * Author URI:        http://orbitingweb.com
 * License:           GPL-3.0+
 * License URI:       https://www.gnu.org/licenses/gpl-3.0.txt
  */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * Stuff to run during plugin activation.
 */
function activate_quick_and_easy_seo() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-quick-and-easy-seo-activator.php';
	Quick_And_Easy_Seo_Activator::activate();
}

/**
 *Stuff to run during plugin deactivation.
  */
function deactivate_quick_and_easy_seo() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-quick-and-easy-seo-deactivator.php';
	Quick_And_Easy_Seo_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_quick_and_easy_seo' );
register_deactivation_hook( __FILE__, 'deactivate_quick_and_easy_seo' );

/**
 * Require class that adds admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-quick-and-easy-seo.php';

/**
 * Run the plugin.
 */
function run_quick_and_easy_seo() {

	$plugin = new Quick_And_Easy_Seo();
	$plugin->run();

}
run_quick_and_easy_seo();
