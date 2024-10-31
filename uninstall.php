<?php

/**
 * Fired when the plugin is uninstalled.
 * @since      1.0.0
 *
 * @package    Quick_And_Easy_Seo
 */

// If uninstall not called from WordPress, then exit.
if ( ! defined( 'WP_UNINSTALL_PLUGIN' ) ) {
	exit;
}

if ( ! current_user_can( 'activate_plugins' ) ) {
	return;
}


/* drop option */
delete_option( 'quick-and-easy-seo' );
/* drop title tag post meta */
delete_post_meta_by_key( '_qeseo_title_tag_meta_value' );
/* drop meta description post meta */
delete_post_meta_by_key( '_qeseo_metades_meta_value' );

	
