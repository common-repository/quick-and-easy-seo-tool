<?php

/**
 * The public-facing functionality of the plugin.
 *
 * @since      1.0.0
 *
 * @package    Quick_And_Easy_Seo
 * @subpackage Quick_And_Easy_Seo/public
 */


class Quick_And_Easy_Seo_Public {

	private $plugin_name;
	private $version;
	private $qeseo_options;
	
	/**
	 * Initialize the class and set its properties.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;
		
		/* Get stored options from database */
		$this->qeseo_options = get_option( $this->plugin_name );
		
		/* Remove tags */
		$this->qeseo_remove_unwated_head_tags();
			
	}
	
	
	/* Check if woocommerce is installed */
	public function qeseo_check_woo_exists(){
		if(class_exists( 'WooCommerce' )){
			$woo_installed = 1;
		}else{
			$woo_installed = 0;
		}
		
		return $woo_installed;
	}
	
	/* Add Robots tag to search, 404 and attachment pages */
	public function qeseo_add_robots_tags(){
		
		$qeseo_follow = "<meta name='Robots' content='noindex,follow' /> ";
		$qeseo_nofollow = "<meta name='Robots' content='noindex,noFollow' /> ";
		
		/* Search pages*/
		if( is_search() ){
			
			$qeseo_value = $this->qeseo_options['qeseo_search'];
			if( $qeseo_value == 'ow_follow' ){
				return $qeseo_follow;
			}elseif( $qeseo_value == 'ow_nofollow'  ){
				return $qeseo_nofollow;
			}else{
				return;
			}	
		}
	
		/* 404 pages*/
		if( is_404() ){
			
			$qeseo_value = $this->qeseo_options['qeseo_404'];
			if( $qeseo_value == 'ow_follow' ){
				return $qeseo_follow;
			}elseif( $qeseo_value == 'ow_nofollow'  ){
				return $qeseo_nofollow;
			}else{
				return;
			}							
		}
		
		/* Attachment pages */
		if( is_attachment() ){
			
			$qeseo_value = $this->qeseo_options['qeseo_attachments'];
			if( $qeseo_value == 'ow_follow' ){
				return $qeseo_follow;
			}elseif( $qeseo_value == 'ow_nofollow'  ){
				return $qeseo_nofollow;
			}else{
				return;
			}
		}		
	}	
	
	/* Date archives */
	public function qeseo_robots_tag_archives(){
		
		$paged = get_query_var( 'paged' );
		$paged = intval( $paged );
		$qeseo_follow = "<meta name='robots' content='noindex,follow' /> ";
		$qeseo_nofollow = "<meta name='robots' content='noindex,nofollow' /> ";
		
		/* Date archives */
		if( is_date() ){
			$qeseo_value = $this->qeseo_options[ 'qeseo_date' ];
			
				if( $qeseo_value == 'ow_follow' ){
					return $qeseo_follow;
				}
				
				elseif( $qeseo_value == 'ow_nofollow' ){
					return $qeseo_nofollow;
				}
				
				elseif( ($qeseo_value == 'ow_follow_2nd_page') && ($paged > 1)  ){
					return $qeseo_follow;
				}
				
				elseif( ($qeseo_value == 'ow_nofollow_2nd_page') && ($paged > 1)  ){
					return $qeseo_nofollow;
				}
				
				else{ 
					return;
				}
			}
			
		/* Author archives */
		if( is_author() ){
			$qeseo_value = $this->qeseo_options[ 'qeseo_author' ];
			
			if( $qeseo_value == 'ow_follow' ){
				return $qeseo_follow;
			}elseif( $qeseo_value == 'ow_nofollow' ){
				return $qeseo_nofollow;
			}elseif( ($qeseo_value == 'ow_follow_2nd_page') && ($paged > 1)  ){
				return $qeseo_follow;
			}elseif( ($qeseo_value == 'ow_nofollow_2nd_page') && ($paged > 1)  ){
				return $qeseo_nofollow;
			}else{ 
				return;
			}
		}
		
		if( $this->qeseo_check_woo_exists() ){
			if( is_product_category() || is_shop() ){
				
				$qeseo_value = $this->qeseo_options[ 'qeseo_category' ];
				if( $qeseo_value == 'ow_follow' ){
					return $qeseo_follow;
				}elseif( $qeseo_value == 'ow_nofollow' ){
					return $qeseo_nofollow;
				}elseif( ($qeseo_value == 'ow_follow_2nd_page') && ($paged > 1)  ){
					return $qeseo_follow;
				}elseif( ($qeseo_value == 'ow_nofollow_2nd_page') && ($paged > 1)  ){
					return $qeseo_nofollow;
				}else{ 
					return;
				}
			}
			
			
		if( is_product_tag() ){			
			$qeseo_value = $this->qeseo_options[ 'qeseo_tag' ];
			
			if( $qeseo_value == 'ow_follow' ){
				return $qeseo_follow;
			}elseif( $qeseo_value == 'ow_nofollow' ){
				return $qeseo_nofollow;
			}elseif( ($qeseo_value == 'ow_follow_2nd_page') && ($paged > 1)  ){
				return $qeseo_follow;
			}elseif( ($qeseo_value == 'ow_nofollow_2nd_page') && ($paged > 1)  ){
				return $qeseo_nofollow;
			}else{ 
				return;
			}
		}
		
			
			
		}
			
		/* Category archives */
		if( is_category() ){
			$qeseo_value = $this->qeseo_options[ 'qeseo_category' ];
			
			if( $qeseo_value == 'ow_follow' ){
				return $qeseo_follow;
			}elseif( $qeseo_value == 'ow_nofollow' ){
				return $qeseo_nofollow;
			}elseif( ($qeseo_value == 'ow_follow_2nd_page') && ($paged > 1)  ){
				return $qeseo_follow;
			}elseif( ($qeseo_value == 'ow_nofollow_2nd_page') && ($paged > 1)  ){
				return $qeseo_nofollow;
			}else{ 
				return;
			}
		}
			
		/* Tag archives */
		if( is_tag() ){
			$qeseo_value = $this->qeseo_options[ 'qeseo_tag' ];
			
			if( $qeseo_value == 'ow_follow' ){
				return $qeseo_follow;
			}elseif( $qeseo_value == 'ow_nofollow' ){
				return $qeseo_nofollow;
			}elseif( ($qeseo_value == 'ow_follow_2nd_page') && ($paged > 1)  ){
				return $qeseo_follow;
			}elseif( ($qeseo_value == 'ow_nofollow_2nd_page') && ($paged > 1)  ){
				return $qeseo_nofollow;
			}else{ 
				return;
			}
		}
			
		/* Homepage archives */
		if( ( is_home() || is_front_page() ) && ( $paged > 1 ) ){
		$qeseo_value = $this->qeseo_options[ 'qeseo_homepage' ];
		
			if( ($qeseo_value == 'ow_follow_2nd_page') ){
				return $qeseo_follow;
			}elseif( ($qeseo_value == 'ow_nofollow_2nd_page') ){
				return $qeseo_nofollow;
			}else{ 
				return;
			}
		}
			
	}
	
	/* Check if homepage is posts or page and return URL */
	
	public function qeseo_hp_url(){
		$qeseo_hp_display = get_option( 'show_on_front' );
		if( $qeseo_hp_display === 'page' ){
			$post_page_url = get_permalink( get_option( 'page_for_posts' ));
		}else{
			$post_page_url = get_home_url();
		}
		return esc_url($post_page_url);
	}
	
	/* Title tag separator setting */
	
	public function qeseo_title_tag_sep( $sep ){
		if( isset($this->qeseo_options['qeseo_title_separator']) && !empty($this->qeseo_options['qeseo_title_separator']) && $this->qeseo_options['qeseo_title_separator'] !== '-'   ){
			$qeseo_title_tag_sep = trim(esc_html($this->qeseo_options['qeseo_title_separator']));
			if( $qeseo_title_tag_sep == 'bdash') $qeseo_title_tag_sep = '&#8212;';
			if( $qeseo_title_tag_sep == 'darrow') $qeseo_title_tag_sep = '&#187;';
			if( $qeseo_title_tag_sep == 'bdot') $qeseo_title_tag_sep = '&#8226;';
			if( $qeseo_title_tag_sep == 'diamond') $qeseo_title_tag_sep = '&#9830;';
			if( $qeseo_title_tag_sep == 'hearts') $qeseo_title_tag_sep = '&#9829;';
			return $qeseo_title_tag_sep;
		}else{
			return $sep;
		}
	}
	
	/*  */
	
	public function qeseo_woo_cat_tag_options(){
		$qeseo_meta = '';
		$qeseo_query_obj = get_queried_object();
		$qeseo_term_id = $qeseo_query_obj->term_id;
		$qeseo_meta = get_option("qeseo_to_" . $qeseo_term_id);
		return $qeseo_meta;
	}
		
	/* Title output for homepage */	
	public function qeseo_title_output(){
	$title_output = '';
	/* Check if frontpage is posts or page */
	if( is_home() || is_front_page() ){ 
	$hp_display = get_option( 'show_on_front' );
	/* If posts */
		if( $hp_display === 'posts' ){
			if( isset($this->qeseo_options[ 'qeseo_hp_title' ]) && !empty($this->qeseo_options[ 'qeseo_hp_title' ]) ){
				$title_output = trim(stripslashes(esc_html($this->qeseo_options[ 'qeseo_hp_title' ])));
				}
		}
		/* is a static page is being used */
		if( $hp_display === 'page' ){
			if( is_front_page() ){
				if( isset($this->qeseo_options[ 'qeseo_hp_title' ]) && !empty($this->qeseo_options[ 'qeseo_hp_title' ]) ){
					$title_output = trim(stripslashes(esc_html($this->qeseo_options[ 'qeseo_hp_title' ])));
				}else{
					$title_output = get_post_meta( get_the_id(), '_qeseo_title_tag_meta_value', true );
					/* If nothing is set, return single page title */
					if( !isset( $title_output ) || empty( $title_output ) ){
						$spid = intval(get_the_id());
						$title_output = get_the_title($spid);
					}
				}
			}else{
				$spid = get_option( 'page_for_posts' );
				$title_output = get_post_meta( $spid, '_qeseo_title_tag_meta_value', true );				
			}
		}
	}
	if( is_singular() && !is_front_page() && !is_home() ){
		$title_output = get_post_meta( get_the_id(), '_qeseo_title_tag_meta_value', true );
		if( !isset( $title_output ) || empty($title_output) ){
			$title_output = get_the_title();
		}
	}
	
	if( $this->qeseo_check_woo_exists() ){
		if( is_product_category() || is_product_tag() ){
			$qeseo_meta = $this->qeseo_woo_cat_tag_options();		
			$title_output = trim(stripslashes(esc_html($qeseo_meta['qeseo_woocat_title'])));
			if( !isset( $title_output ) || empty( $title_output ) ){
				$title_output = single_cat_title('',false);
			}
		}
	}
		return esc_html($title_output);
	}
	
		
	/* Add custom title tag */
	public function qeseo_add_custom_title_tag( $title ){
		$qeseo_custom_title = '';
		$qeseo_remove_sitetitle = 0;
		/* Remove site title from title tag */
		if( isset($this->qeseo_options[ 'qeseo_remove_sitename_option' ]) && !empty($this->qeseo_options[ 'qeseo_remove_sitename_option' ])){
			$qeseo_remove_sitetitle = $this->qeseo_options[ 'qeseo_remove_sitename_option' ];	
		}		
		if( $qeseo_remove_sitetitle ){
			$title['site'] = '';
		}
		
		if( is_home() || is_front_page() ){
			$title_output = $this->qeseo_title_output();
			if( isset( $title_output ) && !empty( $title_output ) ){
				$title['title'] = $this->qeseo_title_output();
				$title['tagline'] = '';				
			}			
		}	
		
		if( is_singular() && !is_home() && !is_front_page() ){
			global $post;
			$qeseo_custom_title = get_post_meta( $post->ID, '_qeseo_title_tag_meta_value', true  );
			$qeseo_custom_title = trim(esc_html(strip_tags($qeseo_custom_title)));
			if( ! isset( $qeseo_custom_title ) || empty( $qeseo_custom_title ) ) return $title;
			$title['title'] = trim(stripslashes(esc_html(strip_tags($qeseo_custom_title))));			
			}
		
		if( $this->qeseo_check_woo_exists() ){
			if( is_product_category() || is_product_tag() ){
				$qeseo_meta = $this->qeseo_woo_cat_tag_options();
				$qeseo_custom_title = trim(stripslashes(esc_html($qeseo_meta['qeseo_woocat_title'])));
				if( ! isset( $qeseo_custom_title ) || empty( $qeseo_custom_title ) ) return $title;
				$title['title'] = trim(stripslashes(esc_html(strip_tags($qeseo_custom_title))));			
				}
				
			if( is_shop()){
				$shop_id = get_option( 'woocommerce_shop_page_id' );
				$shop_title = get_post_meta( $shop_id, '_qeseo_title_tag_meta_value', true );
					if( !isset($shop_title) || empty($shop_title) ){
						$shop_title = get_the_title($shop_id);
					}
				if( isset($shop_title) && !empty($shop_title) ){
					$title['title'] = trim(stripslashes(esc_html($shop_title)));
				}else{
					return $title;
				}
			} 
		}	
		return $title;
		}
	
	/* Generate meta description from content */	
	public function qeseo_generate_metades_content(){
		global $post;
		$qeseo_custom_metades = '';
		$qeseo_custom_metades = stripslashes(esc_html(strip_shortcodes(strip_tags($post->post_content))));
		$qeseo_custom_metades = str_replace(array("\n", "\r", "\t"), ' ', $qeseo_custom_metades);
		$qeseo_custom_metades = substr($qeseo_custom_metades, 0, 155);
		/* Trim content only if it is longer than 154 characters */
		if( strlen($qeseo_custom_metades) > 154 ){
			$qeseo_lastpos = strrpos($qeseo_custom_metades, ' ');
			$qeseo_custom_metades = substr($qeseo_custom_metades, 0, $qeseo_lastpos);
		    $qeseo_custom_metades = $qeseo_custom_metades . '..';
		}		
		return trim(esc_html($qeseo_custom_metades));
	}
	
	/* Meta description output for homepage */
	public function qeseo_hp_meta_des_output(){
		$qeseo_og_des = '';
		$hp_display = get_option( 'show_on_front' );
		if( $hp_display === 'posts' ){
			if( isset($this->qeseo_options['qeseo_hp_metades']) && !empty($this->qeseo_options['qeseo_hp_metades'])){
				$qeseo_og_des = trim(esc_attr($this->qeseo_options['qeseo_hp_metades']));	
			}			
		}
		/* If it's a static page */
		if( $hp_display === 'page' ){
			if( is_front_page() ){
				if( isset($this->qeseo_options['qeseo_hp_metades']) && !empty($this->qeseo_options['qeseo_hp_metades'])){
				$qeseo_og_des = trim(esc_attr($this->qeseo_options['qeseo_hp_metades']));
				}else{
					$qeseo_og_des = get_post_meta( get_the_ID(), '_qeseo_metades_meta_value', true );
				}
			}else{
				$spid = get_option( 'page_for_posts' );
				$qeseo_og_des = get_post_meta( $spid, '_qeseo_metades_meta_value', true );
			}
		}			
			return $qeseo_og_des;
	}
	
	
	/* Add meta description all pages except homepage output */
	public function qeseo_metades_func(){
		$qeseo_custom_metades = trim(get_post_meta( get_the_id(), '_qeseo_metades_meta_value', true  ));
		if( !isset($qeseo_custom_metades) || empty( $qeseo_custom_metades ) ){
			$qeseo_custom_metades = '';
			if( is_single() || is_page() )
			$qeseo_custom_metades = $this->qeseo_generate_metades_content();
		}else{
			$qeseo_custom_metades = trim(stripslashes(esc_html(strip_tags( $qeseo_custom_metades ))));	
			}
		return $qeseo_custom_metades;	
	}
	
	/* Add custom Meta description tag */
	public function qeseo_add_custom_metades_tag(){
		$qeseo_custom_metades = '';

		$qpage = 0;
		$qpage = get_query_var( 'paged' );
		
		if( !isset( $qpage ) || empty( $qpage ) ){
			$qpage = 0;
		}
		
		/* Return if page 2 or above */
		if( $qpage >= 2 ) return;
		
		if( is_home() || is_front_page() ){
			$qeseo_hp_metades = '';
			$qeseo_hp_metades = $this->qeseo_hp_meta_des_output();
			if( isset($qeseo_hp_metades) && !empty($qeseo_hp_metades) ){			
				$qeseo_custom_metades = "<meta name='description' content='$qeseo_hp_metades'/> \n";
				return $qeseo_custom_metades;
			}			
		}
		/* Output custom meta description for single posts/pages/custom-post-types if set */
		if( is_singular() && !is_front_page() && !is_home() ){
			$qeseo_custom_metades = $this->qeseo_metades_func();
			if( isset($qeseo_custom_metades) && !empty($qeseo_custom_metades)){
				if( str_word_count( $qeseo_custom_metades  ) > 2 ){
				    $qeseo_custom_metades = "<meta name='description' content='$qeseo_custom_metades'/> \n";
					return $qeseo_custom_metades;
				}				
			}
		}
		/* Meta description for Woocomerce product category and tag pages */
		if( $this->qeseo_check_woo_exists() ){
			if( is_product_category() || is_product_tag() ){
				$qeseo_meta = $this->qeseo_woo_cat_tag_options();
				$qeseo_custom_metades = trim($qeseo_meta['qeseo_woocat_metades']);
									
				if( ! isset( $qeseo_custom_metades ) || empty( $qeseo_custom_metades ) ) {
					$qeseo_query_obj = get_queried_object();
					$qeseo_term_id = $qeseo_query_obj->term_id;	
					$qeseo_custom_metades = trim(term_description( $qeseo_term_id ));
				}
				$qeseo_custom_metades = trim(stripslashes(esc_html(strip_tags( $qeseo_custom_metades ))));
				
				if( isset($qeseo_custom_metades) && !empty($qeseo_custom_metades)){
				$qeseo_custom_metades = "<meta name='description' content='$qeseo_custom_metades'/> \n";	
				return $qeseo_custom_metades . "\n";
				}
			}
		}	
		/* Meta description for woocommerce shop page */  
		if( $this->qeseo_check_woo_exists() ){
			if( is_shop() ){
				$shop_id = get_option( 'woocommerce_shop_page_id' );
				$shop_metades = get_post_meta( $shop_id, '_qeseo_metades_meta_value', true );
					if( isset($shop_metades) && !empty($shop_metades) ){
					$qeseo_custom_metades = "<meta name='description' content='$shop_metades'/> \n";	
					return $qeseo_custom_metades . "\n";
				}
			}
		}	
	}
	/* Add Meta description tag for category/tag pages if present */
	
	public function qeseo_meta_des_archives(){
		if( $this->qeseo_check_woo_exists() ){
			if( is_product_category() || is_product_tag() ) return;	
		}
		
		$qpage = 0;
		$qpage = get_query_var( 'paged' );
		
		if( !isset( $qpage ) || empty( $qpage ) ){
			$qpage = 0;
		}
		
		/* Return if page 2 or above */
		if( $qpage >= 2 ) return;
		
		
		/* Output meta description for category/tag pages */
		if( is_category() || is_tag() || is_tax() ){
			$qeseo_term_id = get_queried_object_id();
			$qeseo_term_des = term_description( $qeseo_term_id );
			
			if( isset($qeseo_term_des) && !empty( $qeseo_term_des ) ){
				$qeseo_term_des = trim(stripslashes(esc_attr(strip_tags($qeseo_term_des))));
				$qeseo_term_meta_des = "<meta name='description' content='$qeseo_term_des' /> ";
				return $qeseo_term_meta_des . "\n";
			}
		}
	}
	
	/* Add og prefix if not present */
	public function qeseo_fb_og_prefix($tagdata) {
		if(is_home() || is_front_page() || is_singular() || is_archive() ){
		$qeseo_ogtags_option = 0;	
		if( isset($this->qeseo_options['qeseo_add_ogtag_field']) && !empty($this->qeseo_options['qeseo_add_ogtag_field']) ){
			$qeseo_ogtags_option = $this->qeseo_options['qeseo_add_ogtag_field'];	
		}	
			if( ! $qeseo_ogtags_option ) return $tagdata;
					
		if( (strpos( $tagdata, '//ogp.me/ns#'  ) !== false) ) return $tagdata;
		$tagdata .= " prefix='og: http://ogp.me/ns#' ";
		return $tagdata;
		}
		
		return $tagdata;
	}
	/* Add select og tags for single posts, pages and post formats */
	public function qeseo_add_og_tags(){
	if( !isset($this->qeseo_options['qeseo_add_ogtag_field']) || empty($this->qeseo_options['qeseo_add_ogtag_field']) ) return;
	global $post;
	$qeseo_og_output = '';
	
	/* Set og locale */
	$qeseo_og_locale = '';
	$qeseo_og_locale = esc_attr(get_locale());
	if( isset($qeseo_og_locale) && !empty($qeseo_og_locale) ){
		$qeseo_og_output .= "<meta property='og:locale' content='$qeseo_og_locale'/> \n";
	}
	
	/* Set og sitename */
	$qeseo_og_sitename = '';
	$qeseo_og_sitename = esc_attr(get_bloginfo( 'name' ));
	if(!empty($qeseo_og_sitename)){
		$qeseo_og_output .= "<meta property='og:site_name' content='$qeseo_og_sitename'/> \n";
	}
		
		if( is_home() || is_front_page() ){
			#$qeseo_og_des = '';
			$qeseo_og_title = '';
			#$qeseo_og_url = '';
			
			#$qeseo_og_des = $this->qeseo_hp_meta_des_output();
			$qeseo_og_title = $this->qeseo_title_output();
			if( !isset($qeseo_og_title) || empty($qeseo_og_title) ){
				$qeseo_og_title = $qeseo_og_sitename;
			}
			/*if( is_front_page() ){
				$qeseo_og_url = esc_url( get_home_url() );
			}else{
				$qeseo_og_url = esc_url(get_permalink( get_option( 'page_for_posts' ) ));
			}*/			
			
			/*Output*/
			/*if( isset( $qeseo_og_url ) && !empty( $qeseo_og_url ) ){
				$qeseo_og_output .= "<meta property='og:url' content='$qeseo_og_url'/> \n";	
			}*/			
			if( isset($qeseo_og_title) && !empty($qeseo_og_title) ){
				$qeseo_og_output .= "<meta property='og:title' content='$qeseo_og_title'/> \n";	
			}			
			/*if( isset($qeseo_og_des) && !empty($qeseo_og_des) ){
				$qeseo_og_output .= "<meta property='og:description' content='$qeseo_og_des'/> \n";	
			}*/
		}
		
		/* OG Image for frontpage */
		if( is_front_page() || is_home() ){
			if( has_post_thumbnail( get_the_id() ) ){
				/* check if featured image is present */
				$qeseo_hp_img = wp_get_attachment_image_src(get_post_thumbnail_id( get_the_id() ), 'full', false); 
				$qeseo_hp_img = trim(esc_url( $qeseo_hp_img[0] ));				
			}else{
				/* Check fallback image if featured image is absent */
				if( isset($this->qeseo_options['qeseo_hp_ogimg_option']) && !empty($this->qeseo_options['qeseo_hp_ogimg_option']) ){
					$qeseo_hp_img = trim(esc_url($this->qeseo_options['qeseo_hp_ogimg_option']));	
				}				
			}			
			if( isset( $qeseo_hp_img ) && !empty( $qeseo_hp_img ) ){
				$qeseo_og_output .= "<meta property='og:image' content='$qeseo_hp_img'/> \n";
			}			
		}
		
		/* Featured image for woocommerce shop page*/
		if( $this->qeseo_check_woo_exists() ){
			if( is_shop() ){
				$shop_id = get_option( 'woocommerce_shop_page_id' );
				$qeseo_og_img = wp_get_attachment_image_src(get_post_thumbnail_id( $shop_id ), 'full', false);
				$qeseo_og_img = esc_url($qeseo_og_img[0]);
				
				if( isset($qeseo_og_img) && !empty($qeseo_og_img) ){
					$qeseo_og_output .= "<meta property='og:image' content='$qeseo_og_img'/> \n";
				}
				
				}
			}	
				
		/* Tags for posts, pages, post formats */
		if( is_singular() ){
			if( ! is_home() && ! is_front_page() ){
				$qeseo_og_img = '';
				/* section working on*/
				$qeseo_og_title = '';
				$qeseo_og_title = $this->qeseo_title_output();
				#$qeseo_og_url = esc_url( get_permalink() );
				#$qeseo_og_des = $this->qeseo_metades_func();
				
				if( has_post_thumbnail($post->ID) ){
					/* check if featured image is present */
					$qeseo_og_img = wp_get_attachment_image_src(get_post_thumbnail_id( $post->ID ), 'full', false);
					$qeseo_og_img = esc_url($qeseo_og_img[0]);
					
				}else{
					/* Check fallback image if featured image is absent */				
					if( isset($this->qeseo_options['qeseo_fb_ogimg']) && !empty($this->qeseo_options['qeseo_fb_ogimg']) ){
						$qeseo_og_img = trim(esc_url($this->qeseo_options['qeseo_fb_ogimg']));	
					}				
				}
				/* Og tags output */
				/*if( isset($qeseo_og_url) && !empty($qeseo_og_url) ){
					$qeseo_og_output .= "<meta property='og:url' content='$qeseo_og_url'/> \n";	
				}*/
				
				if( is_single() || is_page() ){
					global $post;
					
					$qeseo_og_output .= "<meta property='og:type' content='article' /> \n";
					
					$qeseo_og_datepub = '';
					$qeseo_og_datepub = get_the_date('c');
					if( isset($qeseo_og_datepub) && !empty($qeseo_og_datepub) ){
						$qeseo_og_output .= "<meta property='article:published_time' content='$qeseo_og_datepub' /> \n";
					}
					
					$qeseo_og_datemod = '';
					$qeseo_og_datemod = get_the_modified_date('c');
					if( isset($qeseo_og_datemod) && !empty($qeseo_og_datemod) ){
						$qeseo_og_output .= "<meta property='article:modified_time' content='$qeseo_og_datemod' /> \n";
					}					
				}
				
				if( isset($qeseo_og_title) && !empty($qeseo_og_title) ){
					$qeseo_og_output .= "<meta property='og:title' content='$qeseo_og_title'/> \n";
				}
				/*if( isset($qeseo_og_des) && !empty($qeseo_og_des) ){
					$qeseo_og_output .= "<meta property='og:description' content='$qeseo_og_des'/> \n";
				}*/
				if( isset($qeseo_og_img) && !empty($qeseo_og_img) ){
					$qeseo_og_output .= "<meta property='og:image' content='$qeseo_og_img'/> \n";
				}
		}}
		
		if( isset($this->qeseo_options['qeseo_fb_appid_option']) && !empty($this->qeseo_options['qeseo_fb_appid_option']) ){
			$qeseo_fb_appid = trim(intval($this->qeseo_options['qeseo_fb_appid_option']));	
		}
		
		if( isset($this->qeseo_options['qeseo_fb_appid_option']) && !empty($this->qeseo_options['qeseo_fb_appid_option']) ){
			$qeseo_fb_admin = trim(intval($this->qeseo_options['qeseo_fb_admin_option']));	
		}
		
		
		if( isset( $qeseo_fb_appid ) && !empty( $qeseo_fb_appid ) ){
			$qeseo_og_output .= "<meta property='fb:app_id' content='$qeseo_fb_appid'/> \n";
		}
		
		if( isset( $qeseo_fb_admin ) && !empty( $qeseo_fb_admin ) ){
			$qeseo_og_output .= "<meta property='fb:admins' content='$qeseo_fb_admin'/> \n";
		}
		
		return "<!-- OG Tags -->\n" . $qeseo_og_output;
	}
	

	/* Remove unwanted tags */
	public function qeseo_remove_unwated_head_tags(){
		
		if( $this->qeseo_options['qeseo_remove_shortlink_tag'] ){
			remove_action('wp_head', 'wp_shortlink_wp_head');
		}
		
		if( $this->qeseo_options['qeseo_remove_nextprev_tag'] ){
			remove_action('wp_head', 'adjacent_posts_rel_link_wp_head');
		}
		
		if( $this->qeseo_options['qeseo_remove_wlwmanifest_tag'] ){
				remove_action('wp_head', 'wlwmanifest_link');
			}
		
		if( $this->qeseo_options['qeseo_remove_wp_generator_tag'] ){
				remove_action('wp_head', 'wp_generator');
			}

		if( $this->qeseo_options['qeseo_remove_feed_links_tag'] ){
					remove_action('wp_head', 'feed_links', 2);
					remove_action('wp_head', 'feed_links_extra', 3);
				}

		if( $this->qeseo_options['qeseo_remove_xml_rpc_tag'] ){
					remove_action('wp_head', 'rsd_link');
				}
				
		remove_action('wp_head', 'rel_canonical');		
	}

	/* Add Next and Prev rel tags to archive pages */
	
	public function qeseo_add_prev_next_archives(){
		$qeseo_nextprev_archives_option = 0;
		if( isset($this->qeseo_options['qeseo_add_nextprev_archives_field']) && !empty($this->qeseo_options['qeseo_add_nextprev_archives_field']) ){
			$qeseo_nextprev_archives_option = $this->qeseo_options['qeseo_add_nextprev_archives_field'];	
		}
		
		
		if( $qeseo_nextprev_archives_option ){
			
			if( is_singular() || is_archive() || is_home() || is_front_page() ){
			global $wp_rewrite, $wp_query;
			
			/*Get pagination base for the permalink*/
			$basepaginate = esc_html( trailingslashit($wp_rewrite->pagination_base) );
			
			/*Get current page number*/
			$pnumber = intval( get_query_var( 'paged' ) );
			
			if( is_single() ){
				$basepaginate = '';
				$pnumber = get_query_var('page');
			}
			
			if($pnumber == 0){ $pnumber = 1; }
			$qeseo_perm_link = '';
			
			/*Get permalink for homepage*/
			if( is_home() || is_front_page() ){
				$hp_display = get_option( 'show_on_front' );
				/* Generate separate URL if posts page is different from homepage */
				if( $hp_display === 'page' ){
					$qeseo_perm_link = esc_url(get_home_url());
					if( ! is_front_page() ){
						$qeseo_perm_link = get_permalink( get_option( 'page_for_posts' ) );
					}
				}else{
					$qeseo_perm_link = esc_url(get_home_url());
				}
			}
			
			if( is_single() || is_page() ){
				$qeseo_perm_link = get_permalink();
			}
			
			/*Get permalink for category, tag and custom taxonomies (eg: Woocommerce category/tag archives) */
			if( is_category() || is_tag() || is_tax() ){
				$qeseo_tax_value_array = get_queried_object();
				$qeseo_tax_value = $qeseo_tax_value_array->taxonomy;
				$qeseo_tax_value = sanitize_text_field( $qeseo_tax_value );
				$qeseo_term_id = get_queried_object_id();
				$qeseo_perm_link = get_term_link( $qeseo_term_id, $qeseo_tax_value );
			}
			
			/* Get permalink for post type archive eg: Woocommerce shop pages */
			if( is_post_type_archive() ){
				$qeseo_post_type_object = get_queried_object();
				$qeseo_perm_link = sanitize_text_field( $qeseo_perm_link );
				$qeseo_perm_link = get_post_type_archive_link( $qeseo_post_type_object->name );
			}
			
			/*Get permalink for author archives*/
			if ( is_author() ){
				$qeseo_perm_link = get_author_posts_url( get_the_author_meta( 'ID' ) );
			}
			/*Get permalinks for date archives*/
			if(is_date()){
				if( is_day() ){
				$qeseo_perm_link =  get_day_link( get_post_time('Y'), get_post_time('m'), get_post_time('j') );
				}elseif( is_month() ){
				$qeseo_perm_link =  get_month_link( get_post_time('Y'), get_post_time('m') );
				}elseif( is_year() ){
				$qeseo_perm_link =  get_year_link( get_post_time('Y') );
				}
			}
			
			$qeseo_perm_link = esc_url( $qeseo_perm_link );	
			
			/*Construct the next and prev links*/
			if( ! empty( $qeseo_perm_link ) ){
					$qeseo_nxt_url = '';
					$qeseo_prev_url = '';
					$qeseo_canonical_link = '';
					
					if( $pnumber == 1  ){
						$qeseo_canonical_link = user_trailingslashit(trailingslashit($qeseo_perm_link));
						$qeseo_canonical_link = esc_url($qeseo_canonical_link);
						$qeseo_canonical_link = "<link rel='canonical' href='$qeseo_canonical_link'/>\n";
					}else{
						$qeseo_canonical_link = user_trailingslashit(trailingslashit($qeseo_perm_link) . $basepaginate . $pnumber);
						$qeseo_canonical_link = esc_url($qeseo_canonical_link);
						$qeseo_canonical_link = "<link rel='canonical' href='$qeseo_canonical_link'/>\n";
					}
					
					
					# Add rel="next" tag.
					if(get_next_posts_link() == true){
						$nxt_page_num = ($pnumber + 1);
						$qeseo_nxt_url = user_trailingslashit(trailingslashit($qeseo_perm_link) . $basepaginate . $nxt_page_num);
						$qeseo_nxt_url = esc_url($qeseo_nxt_url);
						$qeseo_nxt_url = "<link rel='next' href='$qeseo_nxt_url' /> \n";
						}
					# Add rel="prev" tag.	
					if(get_previous_posts_link() == true){
						$prev_page_num = ($pnumber - 1);
						if($pnumber == 2){$prev_page_num = ''; $basepaginate = '';}
						$qeseo_prev_url = user_trailingslashit(trailingslashit($qeseo_perm_link) . $basepaginate . $prev_page_num);
						$qeseo_prev_url = esc_url($qeseo_prev_url);
						$qeseo_prev_url = "<link rel='prev' href='$qeseo_prev_url' /> \n";
						}
						
					return $qeseo_canonical_link . $qeseo_nxt_url . $qeseo_prev_url;   	
					}
				}
			}else{
				return;
			}
		}
		
	/* Add prev and next tag to paginated single posts */		
	public function qeseo_prev_next_rel_paginated_posts(){
		
		if( ! is_single() ) return;
		$qeseo_nextprev_singlepages_option = 0;
		if( isset($this->qeseo_options['qeseo_add_nextprev_singlepages_field']) && !empty($this->qeseo_options['qeseo_add_nextprev_singlepages_field']) ){
			$qeseo_nextprev_singlepages_option = $this->qeseo_options['qeseo_add_nextprev_singlepages_field'];	
		} 
		
		
		if( $qeseo_nextprev_singlepages_option ){
			
			global $post;
						
			# 1: Count number of paginated pages.
			if( isset( $post->post_content ) ){
				$num_pages = intval ( ( substr_count( $post->post_content, '<!--nextpage-->' ) ) + 1 );
				} 

			# 2: Check if pagination exists on a single post.
			if($num_pages > 1){
				$qeseo_perm_link = esc_url( get_permalink() );
				$qeseo_prev_url = '';
				$qeseo_nxt_url = '';
				$qeseo_post_page_num = get_query_var('page'); 
				$qeseo_post_page_num = intval( $qeseo_post_page_num );
				if( $qeseo_post_page_num == 0 ){ $qeseo_post_page_num = 1; }
				
				# 3: Add rel="prev" meta tag.
				if( ($qeseo_post_page_num > 1) && ($qeseo_post_page_num <= $num_pages) ){
					$prev_page_num = ($qeseo_post_page_num - 1);
					if($qeseo_post_page_num == 2){$prev_page_num = '';}
					$qeseo_prev_url = user_trailingslashit(trailingslashit($qeseo_perm_link) . $prev_page_num);
					$qeseo_prev_url = esc_url($qeseo_prev_url);
					$qeseo_prev_url = "<link rel='prev' href='$qeseo_prev_url' /> \n";
					}
				
				# 4: Add rel="next" meta tag.
				if( ($qeseo_post_page_num >= 1) && ($qeseo_post_page_num < $num_pages) ){
					$nxt_page_num = ($qeseo_post_page_num + 1);
					$qeseo_nxt_url = user_trailingslashit(trailingslashit($qeseo_perm_link) . $nxt_page_num);
					$qeseo_nxt_url = esc_url($qeseo_nxt_url);
					$qeseo_nxt_url = "<link rel='next' href='$qeseo_nxt_url' /> \n";
					}
					
				return $qeseo_prev_url . $qeseo_nxt_url;
			}
		}
	}
	
	public function qeseo_pre_output_store(){
		/* Output robots meta tags for search/404/attachment pages */
		if( $this->qeseo_add_robots_tags() ){
			echo $this->qeseo_add_robots_tags();
			echo "\n";
		}
		
		/* Output robots meta tags for archive pages */
		if( $this->qeseo_robots_tag_archives() ){
			echo $this->qeseo_robots_tag_archives();
			echo "\n";
		}		
		
		/* Output custom meta description tag */
		if( $this->qeseo_add_custom_metades_tag() ){
			echo $this->qeseo_add_custom_metades_tag();
		}
		
		/* Output meta description tag for archives */
		if( $this->qeseo_meta_des_archives() ){
			echo $this->qeseo_meta_des_archives();
		}
		
		/* Output next/prev tag for paginated archives */
		if( $this->qeseo_add_prev_next_archives() ){
			echo $this->qeseo_add_prev_next_archives();
		}
			
		/* Output next/prev tag for paginated single post pages */
		if( $this->qeseo_prev_next_rel_paginated_posts() ){
			echo $this->qeseo_prev_next_rel_paginated_posts();
		}
		
		/* Output og tags */
		if( $this->qeseo_add_og_tags() ){
			echo $this->qeseo_add_og_tags();
		}
	}
	
	
	
	/* Main Output */
	public function qeseo_print_all_values(){
	 /* Check if output is present */
		ob_start();
			$this->qeseo_pre_output_store();
			$qeseo_output = ob_get_contents();
		ob_end_clean();
		
		/* Output data with comments */
		if( !empty( $qeseo_output ) ){
			echo "\n <!--Added by Quick and Easy SEO --> \n";
			$qeseo_output = preg_replace("/[\r\n]+/", "\n", $qeseo_output); 
			#$qeseo_output = preg_replace("/([\r\n]{4,}|[\n]{2,}|[\r]{2,})/", "\n", $qeseo_output); #remove double line breaks
			#$qeseo_output = explode( '>', $qeseo_output );
			
			/* Create og url from canonical tag if it exists */
			$cano_pos = strpos($qeseo_output, 'canonical');
			$ogtag_pos = stripos($qeseo_output, 'OG TAGS');
			if( $cano_pos > 0 && $ogtag_pos > 0 ){
				$http_pos = strpos($qeseo_output, 'http', $cano_pos);
				$arrow_pos = strpos($qeseo_output, '>', $http_pos);
				$qeseo_ogurl = substr($qeseo_output, $http_pos, ($arrow_pos - $http_pos) - 2 );
				#$ogurl_end = strpos($ogurl2, '\'');
				#$qeseo_ogurl = esc_url(substr($ogurl2, 0, $ogurl_end));
				$qeseo_ogurl = "<meta property='og:url' content='$qeseo_ogurl'/> \n";
				$qeseo_output .= $qeseo_ogurl;
			}
			
			/* Create og description from meta description tag if it exists */
			$des_pos = strpos($qeseo_output, 'description');
			if( $des_pos > 0 && $ogtag_pos > 0 ){
				$con_pos = strpos($qeseo_output, 'content', $des_pos);
				$arrow_pos = strpos($qeseo_output, '>', $con_pos);
				$ogdes = substr($qeseo_output, $con_pos, ($arrow_pos - $con_pos) - 1 );
				$qeseo_ogdes = "<meta property='og:description' $ogdes/> \n";
				$qeseo_output .= $qeseo_ogdes;
			}
			echo $qeseo_output;
			echo "<!--Added by Quick and Easy SEO --> \n\n";
		}		
	}
	
}
