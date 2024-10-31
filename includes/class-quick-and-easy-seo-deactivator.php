<?php


/**
 * Fired during plugin deactivation.
 *
 * This class defines all code necessary to run during the plugin's deactivation.
 *
 * @since      1.0.0
 * @package    Quick_And_Easy_Seo
 * @subpackage Quick_And_Easy_Seo/includes
 */
class Quick_And_Easy_Seo_Deactivator {


	public static function deactivate() {
		
		if ( ! current_user_can( 'activate_plugins' ) ) {
			return;
		}		
	}

}

