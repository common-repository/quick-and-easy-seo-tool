<?php

/**
 * Fired during plugin activation.
 *
 * This class defines all code necessary to run during the plugin's activation.
 *
 * @since      1.0.0
 * @package    Quick_And_Easy_Seo
 * @subpackage Quick_And_Easy_Seo/includes
 */
 
class Quick_And_Easy_Seo_Activator {

	public static function activate() {
		
		if ( ! current_user_can( 'activate_plugins' ) ) {
			return;
		}
		
		/* Set default options */
		$qeseo_default_ops = array(
			
			'qeseo_search' => 'ow_follow',
			'qeseo_404' => 'ow_follow',
			'qeseo_attachments' => 'ow_follow',
			'qeseo_date' => 'ow_follow',
			'qeseo_author' => 'ow_follow',
			'qeseo_category' => 'ow_follow_2nd_page',
			'qeseo_tag' => 'ow_follow_2nd_page',
			'qeseo_homepage' => 'ow_follow_2nd_page',
			'qeseo_remove_shortlink_tag' => 1,
			'qeseo_remove_nextprev_tag' => 1,
			'qeseo_remove_wlwmanifest_tag' => 1,
			'qeseo_remove_wp_generator_tag' => 1,
			'qeseo_remove_feed_links_tag' => 1,
			'qeseo_remove_xml_rpc_tag' => 1,
			'qeseo_remove_sitename_title' => 0,
			'qeseo_title_separator' => '-',
			'qeseo_title_meta_field' => 'ow_posts_pages_formats',
			#'qeseo_custom_metades_field' => 'ow_posts_pages_formats',
			'qeseo_add_nextprev_archives_field' => 1,
			'qeseo_add_nextprev_singlepages_field' => 1,
			'qeseo_add_ogtag_field' => 1
			
		);
		
		/* Add options on plugin activation if options not already set */
		if( get_option('quick-and-easy-seo') != false ){
			return;
		}else{			
			add_option( 'quick-and-easy-seo', $qeseo_default_ops );						
		}
	}

}
