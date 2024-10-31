<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @since      1.0.0
 *
 * @package    Quick_And_Easy_Seo
 * @subpackage Quick_And_Easy_Seo/admin
 */



class Quick_And_Easy_Seo_Admin {

	private $plugin_name;
	private $version;
	private $qeseo_options;


	/* Initialize the class and set its properties. */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;

	}
	
	/*
	Use this to enqueue javascript
	public function enqueue_scripts() {

		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/qeseo-plugin-admin.js', array( 'jquery' ), $this->version, false );

	}*/
	
	/* Add settings link */
	public function qeseo_plug_settings_link( $links ){
		
		$setpath = admin_url( 'options-general.php?page=' . $this->plugin_name );
		$set_link = array( "<a href='$setpath'>Settings</a>" );
		return array_merge( $set_link, $links );		
		
	}
	
	/* Add sidebar menu link */
	public function qeseo_add_sb_menu_link(){
		
		$page_title = 'Quick and Easy SEO Tool Settings';
		$menu_title = 'Quick and Easy SEO';
		$capability = 'manage_options';
		$menu_slug = $this->plugin_name;
		add_options_page( $page_title, $menu_title, $capability, $menu_slug, array( $this, 'qeseo_settings_page_html' )  );
	}
	
	/* Add page menu callback function */
	public function qeseo_settings_page_html(){
		
		?>
		
		<h2>Quick And Easy SEO Settings Page</h2>
		This plugin is pre-configured, but if you want, you can make custom changes here.
		
		<form action="options.php" method="POST" name="add_robots_protocol_form" >
			
			<?php
			
			$this->qeseo_options = get_option( $this->plugin_name );
			
			settings_fields( $this->plugin_name );
			do_settings_sections( $this->plugin_name );
			
			submit_button() ?>
		
		</form>
		<?php		
		
	}
	
	/* Register settings, sections and fields */
	public function qeseo_settings_form_fields(){
		
		register_setting( $this->plugin_name, $this->plugin_name, array( $this, 'qeseo_validate_entries' )   );
		
		
		/*section 1 for homepage title tag*/
		add_settings_section( $this->plugin_name.'hometitle_section', 'Homepage Title & Meta Description Tag (optional)', array( $this, 'qeseo_hp_titlemeta_section' ), $this->plugin_name );
		add_settings_field( $this->plugin_name.'hp_titlemeta_tag', 'Homepage Title Tag', array( $this, 'qeseo_hp_custom_title_tag' ), $this->plugin_name, $this->plugin_name.'hometitle_section' );
		add_settings_field( $this->plugin_name.'hp_metades_tag', 'Homepage Meta Description', array( $this, 'qeseo_hp_metades_tag' ), $this->plugin_name, $this->plugin_name.'hometitle_section' );
		
		/*section 2 for title tag separator*/
		add_settings_section( $this->plugin_name.'titleseparator_section', 'Title Tag Formatting (optional)', array( $this, 'qeseo_titleseparator_section' ), $this->plugin_name );
		add_settings_field( $this->plugin_name.'titleseparator', 'Title Tag Separator', array( $this, 'qeseo_title_tag_separator' ), $this->plugin_name, $this->plugin_name.'titleseparator_section' );
		add_settings_field( $this->plugin_name.'remove_sitename_title', 'Remove sitename from title tag', array( $this, 'qeseo_remove_sitename' ), $this->plugin_name, $this->plugin_name.'titleseparator_section' );
		
		
		/*section 3 for robots tag*/
		add_settings_section( $this->plugin_name.'robots_section', 'Robots Meta Tag Settings', array( $this, 'qeseo_robots_meta_tag_form_section' ), $this->plugin_name );
		add_settings_field( $this->plugin_name.'search_robots_tag', 'Search Pages', array( $this, 'qeseo_search_meta_tag' ), $this->plugin_name, $this->plugin_name.'robots_section' );
		add_settings_field( $this->plugin_name.'404_robots_tag', '404 Pages', array( $this, 'qeseo_404_meta_tag' ), $this->plugin_name, $this->plugin_name.'robots_section' );
		add_settings_field( $this->plugin_name.'attachments_robots_tag', 'Media Attachment Pages', array( $this, 'qeseo_attachment_meta_tag'), $this->plugin_name, $this->plugin_name.'robots_section');
		add_settings_field( $this->plugin_name.'date_archives_robots_tag', 'Date Archives', array( $this, 'qeseo_date_archives_meta_tag' ), $this->plugin_name, $this->plugin_name.'robots_section' );
		add_settings_field( $this->plugin_name.'author_archives_robots_tag', 'Author Archives', array( $this, 'qeseo_author_archives_meta_tag' ), $this->plugin_name, $this->plugin_name.'robots_section' );
		add_settings_field( $this->plugin_name.'category_archives_robots_tag', 'Category Archives', array( $this, 'qeseo_category_archives_meta_tag' ), $this->plugin_name, $this->plugin_name.'robots_section' );
		add_settings_field( $this->plugin_name.'tag_archives_robots_tag', 'Tag Archives', array( $this, 'qeseo_tag_archives_meta_tag' ), $this->plugin_name, $this->plugin_name.'robots_section' );
		add_settings_field( $this->plugin_name.'homepage_archives_robots_tag', 'Homepage Paginated Pages', array( $this, 'qeseo_homepage_archives_meta_tag' ), $this->plugin_name, $this->plugin_name.'robots_section' );
		
		/*section 4 for custom title tag meta box*/
		add_settings_section( $this->plugin_name.'meta_box_section', 'Add Custom Title Tag & Meta Description Meta Box', array( $this, 'qeseo_meta_box_section_cb' ), $this->plugin_name );
		add_settings_field( $this->plugin_name.'title_tag_meta_box', 'Add custom title tags & meta description meta box to:', array( $this, 'qeseo_title_tag_meta_box_field' ), $this->plugin_name, $this->plugin_name.'meta_box_section' );
		#add_settings_field( $this->plugin_name.'metades_meta_box', 'Add custom meta description meta box to:', array( $this, 'qeseo_metades_meta_box_field' ), $this->plugin_name, $this->plugin_name.'meta_box_section' );
		
		/*section 5 to remove unwated head tags*/
		add_settings_section( $this->plugin_name.'remove_tags_section', 'Remove Unwanted Tags From Wordpress Head Section', array( $this, 'qeseo_remove_tags_section_cb' ), $this->plugin_name );
		add_settings_field( $this->plugin_name.'remove_nextprev_tag', 'Remove Next/Prev tags from post pages:', array( $this, 'qeseo_remove_next_prev_field' ), $this->plugin_name, $this->plugin_name.'remove_tags_section' );
		add_settings_field( $this->plugin_name.'remove_shortlink_tag', 'Remove Shortlink tag:', array( $this, 'qeseo_remove_shortlink_field' ), $this->plugin_name, $this->plugin_name.'remove_tags_section' );		
		add_settings_field( $this->plugin_name.'remove_wlwmanifest_tag', 'Remove WLW (Windows live writer) tag:', array( $this, 'qeseo_remove_wlwmanifest_field' ), $this->plugin_name, $this->plugin_name.'remove_tags_section' );
		add_settings_field( $this->plugin_name.'remove_generator_tag', 'Remove Wp Generator tag:', array( $this, 'qeseo_remove_wp_generator_field' ), $this->plugin_name, $this->plugin_name.'remove_tags_section' );
		add_settings_field( $this->plugin_name.'remove_feed_tag', 'Remove feed tags:', array( $this, 'qeseo_remove_feed_links_field' ), $this->plugin_name, $this->plugin_name.'remove_tags_section' );
		add_settings_field( $this->plugin_name.'remove_xmlrpc_tag', 'Remove XMLRPC tag:', array( $this, 'qeseo_remove_xml_rpc_field' ), $this->plugin_name, $this->plugin_name.'remove_tags_section' );
		
		/*section 6 to add next and prev tags*/
		add_settings_section( $this->plugin_name.'add_nextprev_tag_section', 'Add Next/Prev Rel Tags to Archive Pages', array( $this, 'qeseo_prevnext_rel_tags_cb' ), $this->plugin_name );
		add_settings_field( $this->plugin_name.'add_archives_nextprev_rel_tag', 'Add Next/Prev rel tags to all archive pages:', array( $this, 'qeseo_add_nextprev_archives_field' ), $this->plugin_name, $this->plugin_name.'add_nextprev_tag_section' );
		add_settings_field( $this->plugin_name.'add_single_nextprev_rel_tag', 'Add Next/Prev rel tags to paginated single post pages:', array( $this, 'qeseo_add_nextprev_singleposts_field' ), $this->plugin_name, $this->plugin_name.'add_nextprev_tag_section' );
		
		/*section 7 to add og tags*/
		add_settings_section( $this->plugin_name.'add_og_tag_section', 'Add OG (Open Graph) Tags', array( $this, 'qeseo_og_tags_cb' ), $this->plugin_name );
		add_settings_field( $this->plugin_name.'add_fb_og_tags', 'Add OG tags:', array( $this, 'qeseo_add_ogtags_field' ), $this->plugin_name, $this->plugin_name.'add_og_tag_section' );
		add_settings_field( $this->plugin_name.'add_hp_ogimg_tags', 'Homepage OG Image URL (optional):', array( $this, 'qeseo_add_hp_ogimg_field' ), $this->plugin_name, $this->plugin_name.'add_og_tag_section', array( 'class' => 'qeseo_hp_ogimg_class' ) );
		add_settings_field( $this->plugin_name.'add_fallback_ogimg_tags', 'Fallback OG Image URL (optional):', array( $this, 'qeseo_add_fb_ogimg_field' ), $this->plugin_name, $this->plugin_name.'add_og_tag_section', array( 'class' => 'qeseo_fb_ogimg_class' ) );
		add_settings_field( $this->plugin_name.'add_fb_appid_tag', 'Facebook App ID (optional):', array( $this, 'qeseo_add_fb_appid_field' ), $this->plugin_name, $this->plugin_name.'add_og_tag_section', array( 'class' => 'qeseo_fb_appid_class' ) );
		add_settings_field( $this->plugin_name.'add_fb_admin_tag', 'Facebook Admin ID (optional):', array( $this, 'qeseo_add_fb_admin_field' ), $this->plugin_name, $this->plugin_name.'add_og_tag_section', array( 'class' => 'qeseo_fb_admin_class' ) );
		
		
		}
		
	/* Form section 1 callback */	
	public function qeseo_hp_titlemeta_section(){
		echo 'If you leave this empty, default title tags will be used for homepage.';
	}	
	
	/* Form section 2 callback */
	public function qeseo_robots_meta_tag_form_section(){
		echo 'Add robots meta tag to disallow indexing/crawling of archive pages and other low value pages.';		
	}
		
	/* Form section 3 callback */	
	public function qeseo_titleseparator_section(){
		echo 'Settings for title tag.';
	}
	
	/* Form section 4 callback */
	public function qeseo_meta_box_section_cb(){		
		echo 'Setting to display custom title tag and meta description tag, meta box in your wordpress admin panel.';
	}
	
	/* Form section 5 callback */
	public function qeseo_remove_tags_section_cb(){		
		echo 'Remove unwanted meta tags from WP head section.';		
	}
	
	/* Form section 6 callback */
	public function qeseo_prevnext_rel_tags_cb(){		
		echo 'Add Next/Prev rel tags to all archive pages and to single post pages that have pagination.';
	}
	
	/* Form section 7 callback */
	public function qeseo_og_tags_cb(){
		echo 'Add og tags to single posts, pages, post formats and homepage.';
	}
	
	/* Form HP title tag field callback */
	public function qeseo_hp_custom_title_tag(){
		$qeseo_custom_hp_title = '';
		if( isset( $this->qeseo_options['qeseo_hp_title'] ) || ! empty ( $this->qeseo_options['qeseo_hp_title'] )){
			$qeseo_custom_hp_title = trim(stripslashes(esc_html($this->qeseo_options['qeseo_hp_title'])));	
		}
		
		?>
		<input type="text" name="qeseo_hp_title" id="qeseo_hp_title" value="<?php echo $qeseo_custom_hp_title ? $qeseo_custom_hp_title : ''; ?>">
		<?php
	}
	
	
	/* Form field HP meta description callback */
	public function qeseo_hp_metades_tag(){
		$qeseo_hp_metades = '';
		if( isset( $this->qeseo_options['qeseo_hp_metades'] ) && !empty( $this->qeseo_options['qeseo_hp_metades'] ) ){
			$qeseo_hp_metades = trim(stripslashes(esc_html($this->qeseo_options['qeseo_hp_metades'])));	
		}
		
		?>		
		<textarea name="qeseo_hp_metades" id="qeseo_hp_metades" rows="2" cols="55" > <?php echo $qeseo_hp_metades ? $qeseo_hp_metades : ''; ?>  </textarea>
		<?php
	}
	
	/* Form field title tag separator callback */
	
	public function qeseo_title_tag_separator(){
		$qeseo_separator_option = '-';
		if( isset( $this->qeseo_options['qeseo_title_separator'] ) && !empty( $this->qeseo_options['qeseo_title_separator'] ) ){
			$qeseo_separator_option = $this->qeseo_options['qeseo_title_separator'];	
		}
		?>
		<select name="qeseo_title_separator" id="qeseo_title_separator">
			<option value="-" <?php selected( $qeseo_separator_option,'-' ) ?> >-</option>
			<option value="bdash" <?php selected( $qeseo_separator_option,'bdash'  ) ?> >&#8212;</option>
			<option value="|" <?php selected( $qeseo_separator_option,'|' ) ?> >|</option>
			<option value="*" <?php selected( $qeseo_separator_option,'*' ) ?> >*</option>
			<option value="~" <?php selected( $qeseo_separator_option,'~' ) ?> >~</option>
			<option value=":" <?php selected( $qeseo_separator_option,':'  ) ?> >:</option>			
			<option value="::" <?php selected( $qeseo_separator_option,'::'  ) ?> >::</option>			
			<option value=">" <?php selected( $qeseo_separator_option,'>'  ) ?> >></option>
			<option value="." <?php selected( $qeseo_separator_option,'.'  ) ?> >.</option>
			<option value="darrow" <?php selected( $qeseo_separator_option,'darrow'  ) ?> >&#187;</option>
			<option value="bdot" <?php selected( $qeseo_separator_option,'bdot'  ) ?> >&#8226;</option>
			<option value="diamond" <?php selected( $qeseo_separator_option,'diamond'  ) ?> >&#9830;</option>
			<option value="hearts" <?php selected( $qeseo_separator_option,'hearts'  ) ?> >&#9829;</option>
		</select>
		<?php		
	}
	
	/* Form field 1 callback */
	public function qeseo_search_meta_tag(){		
		?>
		<select name="qeseo_search" id="ow_field_1">
				<option value="ow_follow" <?php selected( $this->qeseo_options['qeseo_search'], 'ow_follow')  ?>  > NoIndex/Follow  </option>
				<option value="ow_nofollow" <?php selected( $this->qeseo_options['qeseo_search'], 'ow_nofollow')  ?> > NoIndex/NoFollow </option>
				<option value="ow_none" <?php selected( $this->qeseo_options['qeseo_search'], 'ow_none')  ?> > None </option>
		</select>
		<?php		
	}
	
	
	/* Form field 2 callback */
	public function qeseo_404_meta_tag(){		
		?>
		<select name="qeseo_404" id="ow_field_2">
				<option value="ow_follow" <?php selected( $this->qeseo_options['qeseo_404'], 'ow_follow')  ?>  > NoIndex/Follow  </option>
				<option value="ow_nofollow" <?php selected( $this->qeseo_options['qeseo_404'], 'ow_nofollow')  ?> > NoIndex/NoFollow </option>
				<option value="ow_none" <?php selected( $this->qeseo_options['qeseo_404'], 'ow_none')  ?> > None </option>
		</select>
		<?php		
	}
	
	/* Form field 3 callback */
	public function qeseo_attachment_meta_tag(){		
		?>
		<select name="qeseo_attachments" id="ow_field_3">
				<option value="ow_follow" <?php selected( $this->qeseo_options['qeseo_attachments'], 'ow_follow')  ?>  > NoIndex/Follow  </option>
				<option value="ow_nofollow" <?php selected( $this->qeseo_options['qeseo_attachments'], 'ow_nofollow')  ?> > NoIndex/NoFollow </option>
				<option value="ow_none" <?php selected( $this->qeseo_options['qeseo_attachments'], 'ow_none')  ?> > None </option>
		</select>
		<?php		
	}
	
	/* Form field 4 callback */
	public function qeseo_date_archives_meta_tag(){
		$qeseo_date = $this->qeseo_options['qeseo_date'];
		?>
		<select name="qeseo_date" id="ow_field_4">
			<option value="ow_follow" <?php selected( $qeseo_date,'ow_follow'  ) ?> >NoIndex/Follow</option>
			<option value="ow_nofollow" <?php selected( $qeseo_date,'ow_nofollow'  ) ?> >NoIndex/NoFollow</option>
			<option value="ow_follow_2nd_page" <?php selected( $qeseo_date,'ow_follow_2nd_page'  ) ?> >NoIndex/Follow (2nd page onward)</option>
			<option value="ow_nofollow_2nd_page" <?php selected( $qeseo_date,'ow_nofollow_2nd_page'  ) ?>>NoIndex/NoFollow (2nd page onward)</option>
			<option value="ow_none" <?php selected( $qeseo_date,'ow_none'  ) ?> >None</option>
		</select>
		<?php		
	}
	
	
	/* Form field 5 callback */
	public function qeseo_author_archives_meta_tag(){
		$qeseo_author = $this->qeseo_options['qeseo_author'];
		?>
		<select name="qeseo_author" id="ow_field_5">
			<option value="ow_follow" <?php selected( $qeseo_author,'ow_follow'  ) ?> >NoIndex/Follow</option>
			<option value="ow_nofollow" <?php selected( $qeseo_author,'ow_nofollow'  ) ?> >NoIndex/NoFollow</option>
			<option value="ow_follow_2nd_page" <?php selected( $qeseo_author,'ow_follow_2nd_page'  ) ?> >NoIndex/Follow (2nd page onward)</option>
			<option value="ow_nofollow_2nd_page" <?php selected( $qeseo_author,'ow_nofollow_2nd_page'  ) ?>>NoIndex/NoFollow (2nd page onward)</option>
			<option value="ow_none" <?php selected( $qeseo_author,'ow_none'  ) ?> >None</option>
		</select>
		<?php		
	}
	
	
	
	/* Form field 6 callback */
	public function qeseo_category_archives_meta_tag(){
		$qeseo_category = $this->qeseo_options['qeseo_category'];
		?>
		<select name="qeseo_category" id="ow_field_6">
			<option value="ow_follow" <?php selected( $qeseo_category,'ow_follow'  ) ?> >NoIndex/Follow</option>
			<option value="ow_nofollow" <?php selected( $qeseo_category,'ow_nofollow'  ) ?> >NoIndex/NoFollow</option>
			<option value="ow_follow_2nd_page" <?php selected( $qeseo_category,'ow_follow_2nd_page'  ) ?> >NoIndex/Follow (2nd page onward)</option>
			<option value="ow_nofollow_2nd_page" <?php selected( $qeseo_category,'ow_nofollow_2nd_page'  ) ?>>NoIndex/NoFollow (2nd page onward)</option>
			<option value="ow_none" <?php selected( $qeseo_category,'ow_none'  ) ?> >None</option>
		</select>
		<?php		
	}
	
	/* Form field 7 callback */
	public function qeseo_tag_archives_meta_tag(){
			$qeseo_tag = $this->qeseo_options['qeseo_tag'];
		?>
		<select name="qeseo_tag" id="ow_field_7">
			<option value="ow_follow" <?php selected( $qeseo_tag,'ow_follow'  ) ?> >NoIndex/Follow</option>
			<option value="ow_nofollow" <?php selected( $qeseo_tag,'ow_nofollow'  ) ?> >NoIndex/NoFollow</option>
			<option value="ow_follow_2nd_page" <?php selected( $qeseo_tag,'ow_follow_2nd_page'  ) ?> >NoIndex/Follow (2nd page onward)</option>
			<option value="ow_nofollow_2nd_page" <?php selected( $qeseo_tag,'ow_nofollow_2nd_page'  ) ?>>NoIndex/NoFollow (2nd page onward)</option>
			<option value="ow_none" <?php selected( $qeseo_tag,'ow_none'  ) ?> >None</option>
		</select>
		<?php		
	}
	
	/* Form field 8 callback */
	public function qeseo_homepage_archives_meta_tag(){
			
			$qeseo_homepage = $this->qeseo_options['qeseo_homepage'];
		?>
		<select name="qeseo_homepage" id="ow_field_8">
			<option value="ow_follow_2nd_page" <?php selected( $qeseo_homepage,'ow_follow_2nd_page'  ) ?> >NoIndex/Follow (2nd page onward)</option>
			<option value="ow_nofollow_2nd_page" <?php selected( $qeseo_homepage,'ow_nofollow_2nd_page'  ) ?>>NoIndex/NoFollow (2nd page onward)</option>
			<option value="ow_none" <?php selected( $qeseo_homepage,'ow_none'  ) ?> >None</option>
		</select>
		<?php		
	}
	
	/* Form field 9 callback */
	public function qeseo_title_tag_meta_box_field(){
		$qeseo_meta_add_option = $this->qeseo_options['qeseo_title_meta_field'];
		?>
		<select name="qeseo_title_meta_field" id="ow_field_9">
			<option value="ow_posts_pages" <?php selected( $qeseo_meta_add_option,'ow_posts_pages'  ) ?> >Only posts & pages</option>
			<option value="ow_posts_pages_formats" <?php selected( $qeseo_meta_add_option,'ow_posts_pages_formats'  ) ?>>All post types</option>
			<option value="ow_no_meta_box" <?php selected( $qeseo_meta_add_option,'ow_no_meta_box'  ) ?> >None</option>
		</select>
		<p class="description">Select 'none' if you don't want to use custom title tag/meta tag.</p>
		<?php
	}
	
	/* Form field 10 callback 
	public function qeseo_metades_meta_box_field(){
		$qeseo_metades_add_option = $this->qeseo_options['qeseo_custom_metades_field'];
		?>
		<select name="qeseo_custom_metades_field" id="ow_field_10">
			<option value="ow_posts_pages" <?php selected( $qeseo_metades_add_option,'ow_posts_pages'  ) ?> >Only posts & pages</option>
			<option value="ow_posts_pages_formats" <?php selected( $qeseo_metades_add_option,'ow_posts_pages_formats'  ) ?>>All post types</option>
			<option value="ow_no_meta_box" <?php selected( $qeseo_metades_add_option,'ow_no_meta_box'  ) ?> >None</option>
		</select>
		<?php
	} */
	
	/* Form field 11 callback */
	public function qeseo_remove_shortlink_field(){
		$qeseo_remove_shorlink_tag = 0;
		if( isset( $this->qeseo_options['qeseo_remove_shortlink_tag'] ) && !empty($this->qeseo_options['qeseo_remove_shortlink_tag']) ){
			$qeseo_remove_shorlink_tag = $this->qeseo_options['qeseo_remove_shortlink_tag'];	
		}		
		?>
		<input type="checkbox" name="qeseo_remove_shortlink_tag" id="qeseo_remove_shortlink_tag" <?php checked( $qeseo_remove_shorlink_tag, 1 ) ?> >
		<?php
	}
	
	/* Form field 12 callback */
	public function qeseo_remove_next_prev_field(){
		$qeseo_remove_nextprev_tag = 0;
		if( isset( $this->qeseo_options['qeseo_remove_nextprev_tag'] ) && !empty( $this->qeseo_options['qeseo_remove_nextprev_tag'] ) ){
			$qeseo_remove_nextprev_tag = $this->qeseo_options['qeseo_remove_nextprev_tag'];	
		}
		
		?>
		<input type="checkbox" name="qeseo_remove_nextprev_tag" id="qeseo_remove_nextprev_tag" <?php checked( $qeseo_remove_nextprev_tag, 1 ) ?> >
		<?php
	}
	
	/* Form field 13 callback */
	public function qeseo_remove_wlwmanifest_field(){
		$qeseo_remove_wlwmanifest_tag = 0;
		if( isset( $this->qeseo_options['qeseo_remove_wlwmanifest_tag'] ) && !empty( $this->qeseo_options['qeseo_remove_wlwmanifest_tag'] ) ){
			$qeseo_remove_wlwmanifest_tag = $this->qeseo_options['qeseo_remove_wlwmanifest_tag'];	
		}		
		?>
		<input type="checkbox" name="qeseo_remove_wlwmanifest_tag" id="qeseo_remove_wlwmanifest_tag" <?php checked( $qeseo_remove_wlwmanifest_tag, 1 ) ?> >
		<?php
	}
	
	/* Form field 14 callback */
	public function qeseo_remove_wp_generator_field(){
		$qeseo_remove_wp_generator_tag = 0;
		if( isset( $this->qeseo_options['qeseo_remove_wp_generator_tag'] ) && !empty( $this->qeseo_options['qeseo_remove_wp_generator_tag'] )){
			$qeseo_remove_wp_generator_tag = $this->qeseo_options['qeseo_remove_wp_generator_tag'];	
		}
		
		?>
		<input type="checkbox" name="qeseo_remove_wp_generator_tag" id="qeseo_remove_wp_generator_tag" <?php checked( $qeseo_remove_wp_generator_tag, 1 ) ?> >
		<?php
	}
	
	/* Form field 15 callback */
	public function qeseo_remove_feed_links_field(){
		$qeseo_remove_feed_links_tag = 0;
		if( isset($this->qeseo_options['qeseo_remove_feed_links_tag']) && !empty($this->qeseo_options['qeseo_remove_feed_links_tag']) ){
			$qeseo_remove_feed_links_tag = $this->qeseo_options['qeseo_remove_feed_links_tag'];	
		}		
		?>
		<input type="checkbox" name="qeseo_remove_feed_links_tag" id="qeseo_remove_feed_links_tag" <?php checked( $qeseo_remove_feed_links_tag, 1 ) ?> >
		<?php
	}
	
	/* Form field 16 callback */
	public function qeseo_remove_xml_rpc_field(){
		$qeseo_remove_xml_rpc_tag = 0;
		if( isset($this->qeseo_options['qeseo_remove_xml_rpc_tag']) && !empty($this->qeseo_options['qeseo_remove_xml_rpc_tag']) ){
			$qeseo_remove_xml_rpc_tag = $this->qeseo_options['qeseo_remove_xml_rpc_tag'];	
		}
		
		?>
		<input type="checkbox" value="1" name="qeseo_remove_xml_rpc_tag" id="qeseo_remove_xml_rpc_tag" <?php checked( $qeseo_remove_xml_rpc_tag, 1 ) ?> >
		<?php
	}
	
	/* Form field 17 callback */	
	public function qeseo_add_nextprev_archives_field(){
		$qeseo_nextprev_archives_option = 0;
		if( isset($this->qeseo_options['qeseo_add_nextprev_archives_field']) && !empty($this->qeseo_options['qeseo_add_nextprev_archives_field']) ){
			$qeseo_nextprev_archives_option = $this->qeseo_options['qeseo_add_nextprev_archives_field'];	
		}
		
		?>
		<input type="checkbox" name="qeseo_add_nextprev_archives_field" id="qeseo_add_nextprev_archives_field" <?php checked( $qeseo_nextprev_archives_option, 1 ) ?> >
		
		<?php
	}
	
	/* Form field 18 callback */
	public function qeseo_add_nextprev_singleposts_field(){
		$qeseo_nextprev_singlepages_option = 0;
		if( isset($this->qeseo_options['qeseo_add_nextprev_singlepages_field']) && !empty($this->qeseo_options['qeseo_add_nextprev_singlepages_field']) ){
			$qeseo_nextprev_singlepages_option = $this->qeseo_options['qeseo_add_nextprev_singlepages_field'];	
		}
		
		?>
		<input type="checkbox" name="qeseo_add_nextprev_singlepages_field" id="qeseo_add_nextprev_singlepages_field" <?php checked( $qeseo_nextprev_singlepages_option, 1 ) ?> >
		<?php		
	}
	
	/* Form field 19 callback */
	public function qeseo_add_ogtags_field(){
		$qeseo_ogtags_option = 0;
		if( isset( $this->qeseo_options['qeseo_add_ogtag_field'] ) && !empty( $this->qeseo_options['qeseo_add_ogtag_field'] ) ){
			$qeseo_ogtags_option = $this->qeseo_options['qeseo_add_ogtag_field'];	
		}
		?>
		<input type="checkbox" name="qeseo_add_ogtag_field" id="qeseo_add_ogtag_field" <?php checked( $qeseo_ogtags_option, 1 ) ?> >
		<p class="description">If you are using other plugins to add OG Tags, uncheck this checkbox.</p>
		<?php		
	}
	
	/* Form field 20 callback */
	public function qeseo_add_hp_ogimg_field(){
		$qeseo_hp_ogimg_option = '';
		if( isset( $this->qeseo_options['qeseo_hp_ogimg_option'] ) && !empty( $this->qeseo_options['qeseo_hp_ogimg_option'] ) ){
			$qeseo_hp_ogimg_option = trim(esc_url($this->qeseo_options['qeseo_hp_ogimg_option']));	
		}		
		?>
		<input type="url" name="qeseo_hp_ogimg" id="qeseo_hp_ogimg" value="<?php echo $qeseo_hp_ogimg_option ? $qeseo_hp_ogimg_option : ''; ?>">
		<p class="description"><?php _e('eg: http://example.com/wp_content/uploads/image.jpg', 'text_domain'); ?></p>
		<?php		
	}
	
	/* Form field 21 callback */
	public function qeseo_add_fb_ogimg_field(){
		$qeseo_fb_ogimg_option = '';
		if( isset( $this->qeseo_options['qeseo_fb_ogimg_option'] ) && !empty( $this->qeseo_options['qeseo_fb_ogimg_option'] ) ){
			$qeseo_fb_ogimg_option = trim(esc_url($this->qeseo_options['qeseo_fb_ogimg_option']));
		}
		?>
		<input type="url" name="qeseo_fb_ogimg" id="qeseo_fb_ogimg" value="<?php echo $qeseo_fb_ogimg_option ? $qeseo_fb_ogimg_option : ''; ?>">
		<p class="description"><?php _e('eg: http://example.com/wp_content/uploads/image.jpg (this will be used in case a featured image is missing for single posts/pages)', 'text_domain'); ?></p>
		<?php	
	}
	
	/* Form field 22 callback */
	public function qeseo_add_fb_appid_field(){
		$qeseo_fb_appid_option = '';
		if( isset( $this->qeseo_options['qeseo_fb_appid_option'] ) && !empty( $this->qeseo_options['qeseo_fb_appid_option'] ) ){
			$qeseo_fb_appid_option = trim(intval($this->qeseo_options['qeseo_fb_appid_option']));
		}
		?>
		<input type="number" name="qeseo_fb_appid" id="qeseo_fb_appid" value="<?php echo $qeseo_fb_appid_option ? $qeseo_fb_appid_option : ''; ?>">
		<?php	
	}
	
	/* Form field 23 callback */
	public function qeseo_add_fb_admin_field(){
		$qeseo_fb_admin_option = '';
		if( isset( $this->qeseo_options['qeseo_fb_admin_option'] ) && !empty( $this->qeseo_options['qeseo_fb_admin_option'] ) ){
			$qeseo_fb_admin_option = trim(intval($this->qeseo_options['qeseo_fb_admin_option']));
		}
		?>
		<input type="number" name="qeseo_fb_admin" id="qeseo_fb_admin" value="<?php echo $qeseo_fb_admin_option ? $qeseo_fb_admin_option : ''; ?>">
		<?php	
	}
	
	/* Form field 24 callback */
	public function qeseo_remove_sitename(){
		$qeseo_remove_sitename_option = 0;
		if( isset($this->qeseo_options['qeseo_remove_sitename_option']) && !empty($this->qeseo_options['qeseo_remove_sitename_option']) ){
			$qeseo_remove_sitename_option = $this->qeseo_options['qeseo_remove_sitename_option'];	
		}		
		?>
		<input type="checkbox" name="qeseo_remove_sitename_title" id="qeseo_remove_sitename_title" <?php checked( $qeseo_remove_sitename_option, 1 ) ?> >
		<p class="description">Eg: "Page Title | sitename.com" will become "Page Title"</p>
		<?php	
	}
	
	
	
	
	/* Validate form input data */
	
	public function qeseo_validate_entries(){
		
		$valid = array();
		
		$valid['qeseo_hp_title'] = (isset( $_POST[ "qeseo_hp_title" ] ) && !empty( $_POST[ "qeseo_hp_title" ] )) ? sanitize_text_field($_POST[ "qeseo_hp_title" ]) : '';
		
		$valid['qeseo_hp_metades'] = (isset( $_POST["qeseo_hp_metades"] ) && !empty( $_POST["qeseo_hp_metades"] )) ? sanitize_text_field($_POST["qeseo_hp_metades"]) : '';
		
		$valid['qeseo_search'] = (isset( $_POST[ "qeseo_search" ] ) && !empty( $_POST[ "qeseo_search" ] )) ? sanitize_text_field($_POST[ "qeseo_search" ]) : '';
		
		$valid['qeseo_404'] = (isset( $_POST[ "qeseo_404" ] ) && !empty( $_POST[ "qeseo_404" ] )) ? sanitize_text_field($_POST[ "qeseo_404" ]) : '';
		
		$valid['qeseo_attachments'] = (isset( $_POST[ "qeseo_attachments" ] ) && !empty( $_POST[ "qeseo_attachments" ] )) ? sanitize_text_field($_POST[ "qeseo_attachments" ]) : '';
		
		$valid['qeseo_date'] = (isset( $_POST[ "qeseo_date" ] ) && !empty( $_POST[ "qeseo_date" ] )) ? sanitize_text_field($_POST[ "qeseo_date" ]) : '';
		
		$valid['qeseo_author'] = (isset( $_POST[ "qeseo_author" ] ) && !empty( $_POST[ "qeseo_author" ] )) ? sanitize_text_field($_POST[ "qeseo_author" ]) : '';
		
		$valid['qeseo_category'] = (isset( $_POST[ "qeseo_category" ] ) && !empty( $_POST[ "qeseo_category" ] )) ? sanitize_text_field($_POST[ "qeseo_category" ]) : '';
		
		$valid['qeseo_tag'] = (isset( $_POST[ "qeseo_tag" ] ) && !empty( $_POST[ "qeseo_tag" ] )) ? sanitize_text_field($_POST[ "qeseo_tag" ]) : '';
		
		$valid['qeseo_homepage'] = (isset( $_POST[ "qeseo_homepage" ] ) && !empty( $_POST[ "qeseo_homepage" ] )) ? sanitize_text_field($_POST[ "qeseo_homepage" ]) : '';
		
		$valid['qeseo_title_meta_field'] = (isset( $_POST[ "qeseo_title_meta_field" ] ) && !empty( $_POST[ "qeseo_title_meta_field" ] )) ? sanitize_text_field($_POST[ "qeseo_title_meta_field" ]) : '';
		
		#$valid['qeseo_custom_metades_field'] = (isset( $_POST[ "qeseo_custom_metades_field" ] ) && !empty( $_POST[ "qeseo_custom_metades_field" ] )) ? $_POST[ "qeseo_custom_metades_field" ] : '';
		
		$valid['qeseo_remove_shortlink_tag'] = (isset( $_POST[ "qeseo_remove_shortlink_tag" ] ) && !empty( $_POST[ "qeseo_remove_shortlink_tag" ] )) ? 1 : '';
		
		$valid['qeseo_remove_nextprev_tag'] = (isset( $_POST[ "qeseo_remove_nextprev_tag" ] ) && !empty( $_POST[ "qeseo_remove_nextprev_tag" ] )) ? 1 : '';
	
		$valid['qeseo_remove_wlwmanifest_tag'] = (isset( $_POST[ "qeseo_remove_wlwmanifest_tag" ] ) && !empty( $_POST[ "qeseo_remove_wlwmanifest_tag" ] )) ? 1 : '';
		
		$valid['qeseo_remove_wp_generator_tag'] = (isset( $_POST[ "qeseo_remove_wp_generator_tag" ] ) && !empty( $_POST[ "qeseo_remove_wp_generator_tag" ] )) ? 1 : '';
		
		$valid['qeseo_remove_feed_links_tag'] = (isset( $_POST[ "qeseo_remove_feed_links_tag" ] ) && !empty( $_POST[ "qeseo_remove_feed_links_tag" ] )) ? 1 : '';
		
		$valid['qeseo_remove_xml_rpc_tag'] = (isset( $_POST[ "qeseo_remove_xml_rpc_tag" ] ) && !empty( $_POST[ "qeseo_remove_xml_rpc_tag" ] )) ? 1 : '';
		
		$valid['qeseo_add_nextprev_archives_field'] = (isset( $_POST[ "qeseo_add_nextprev_archives_field" ] ) && !empty( $_POST[ "qeseo_add_nextprev_archives_field" ] )) ? 1 : '';
		
		$valid['qeseo_add_nextprev_singlepages_field'] = (isset( $_POST[ "qeseo_add_nextprev_singlepages_field" ] ) && !empty( $_POST[ "qeseo_add_nextprev_singlepages_field" ] )) ? 1 : '';
		
		$valid['qeseo_add_ogtag_field'] = (isset( $_POST[ "qeseo_add_ogtag_field" ] ) && !empty( $_POST[ "qeseo_add_ogtag_field" ] )) ? 1 : '';
		
		$valid['qeseo_hp_ogimg_option'] = (isset( $_POST[ "qeseo_hp_ogimg" ] ) && !empty( $_POST[ "qeseo_hp_ogimg" ] )) ? trim(esc_url($_POST[ "qeseo_hp_ogimg" ])) : '';
		
		$valid['qeseo_fb_ogimg_option'] = (isset( $_POST[ "qeseo_fb_ogimg" ] ) && !empty( $_POST[ "qeseo_fb_ogimg" ] )) ? trim(esc_url($_POST[ "qeseo_fb_ogimg" ])) : '';
		
		$valid['qeseo_fb_appid_option'] = (isset( $_POST[ "qeseo_fb_appid" ] ) && !empty( $_POST[ "qeseo_fb_appid" ] )) ? intval($_POST[ "qeseo_fb_appid" ]) : '';
		
		$valid['qeseo_fb_admin_option'] = (isset( $_POST[ "qeseo_fb_admin" ] ) && !empty( $_POST[ "qeseo_fb_admin" ] )) ? intval($_POST[ "qeseo_fb_admin" ]) : '';
		
		$valid['qeseo_title_separator'] = (isset( $_POST[ "qeseo_title_separator" ] ) && !empty( $_POST[ "qeseo_title_separator" ] )) ? $_POST[ "qeseo_title_separator" ] : '';
		
		$valid['qeseo_remove_sitename_option'] = (isset( $_POST[ "qeseo_remove_sitename_title" ] ) && !empty( $_POST[ "qeseo_remove_sitename_title" ] )) ? 1 : '';
		
		
		return $valid;
		
	}
	
	/* Add title tag meta box */
	
	public function qeseo_add_title_tag_metabox(){
		$ow_title_options = get_option( $this->plugin_name );
		$qeseo_meta_box_add_option_check = $ow_title_options[ 'qeseo_title_meta_field' ];
		
		if( $qeseo_meta_box_add_option_check == 'ow_posts_pages_formats' ){
			$qeseo_all_pub_post_types = get_post_types( array( 'public'=>true ) );
			#$qeseo_main_pub_post_types = array_diff( $qeseo_all_pub_post_types, array( 'attachment' )  );
			$qeseo_pub_pt_keys = array_keys( $qeseo_all_pub_post_types );
				
		foreach( $qeseo_pub_pt_keys as $qeseo_pub_pt_key  ){
			add_meta_box( 'qeseo_title_tag_meta_box', 'Custom Title Tag', array( $this, 'qeseo_add_custom_title_tag_cb'), $qeseo_pub_pt_key , 'normal', 'high');
			}
			
		}elseif( $qeseo_meta_box_add_option_check == 'ow_posts_pages' ){
			
			add_meta_box( 'qeseo_title_tag_meta_box', 'Custom Title Tag', array( $this, 'qeseo_add_custom_title_tag_cb'), array( 'post', 'page' ), 'normal', 'high');	
			
		}else{
			return;
		}
	}
	
	/* Title tag metabox callback function */
	public function qeseo_add_custom_title_tag_cb( $post ){
		
		wp_nonce_field( 'qeseo_title_tag_data', 'qeseo_custom_title_tag_nonce' );
		$title_value = get_post_meta( get_the_ID(), '_qeseo_title_tag_meta_value', true );
		?>
		<input type="text" name="qeseo_custom_title_tag_field" id="qeseo_custom_title_tag_field" style="width:100%" value="<?php echo esc_attr( $title_value ) ?>"  />
		<p class="description"><?php _e('Added by Quick & Easy SEO', 'text_domain'); ?></p>
		<?php
		
	}
	
	/* Check, sanitize and update title tag meta box data */
	public function qeseo_title_tag_data( $post_id ){
		
		if( ! isset( $_POST['qeseo_custom_title_tag_nonce'] ) ){
			return;
		}
		
		if( ! wp_verify_nonce( $_POST['qeseo_custom_title_tag_nonce'], 'qeseo_title_tag_data' ) ){
			return;
		}
		
		if( defined('DOING_AUTOSAVE') && DOING_AUTOSAVE ){
			return;			
		}
		
		if ( isset( $_POST['post_type'] ) && 'page' == $_POST['post_type'] ) {
				if ( ! current_user_can( 'edit_page', $post_id ) ) {
					return;
			}
		}
		
		if( ! current_user_can( 'edit_post', $post_id ) ){
			return;
		}
		
		
		if( ! isset( $_POST['qeseo_custom_title_tag_field'] ) ){
			return;
		}
		
		$qeseo_title_value = sanitize_text_field( $_POST['qeseo_custom_title_tag_field'] );
		
		update_post_meta( $post_id, '_qeseo_title_tag_meta_value', $qeseo_title_value );
		
		
	}
	
	
	/* Add Meta description meta box */	
	public function qeseo_add_metades_tag_metabox(){
		$ow_title_options = get_option( $this->plugin_name );
		$qeseo_meta_box_add_option_check = $ow_title_options[ 'qeseo_title_meta_field' ];
		
		if( $qeseo_meta_box_add_option_check == 'ow_posts_pages_formats' ){
			$qeseo_all_pub_post_types = get_post_types( array( 'public'=>true ) );
			#$qeseo_main_pub_post_types = array_diff( $qeseo_all_pub_post_types, array( 'attachment' )  );
			$qeseo_pub_pt_keys = array_keys( $qeseo_all_pub_post_types );
				
		foreach( $qeseo_pub_pt_keys as $qeseo_pub_pt_key  ){
			add_meta_box( 'qeseo_metades_meta_box', 'Custom Meta Description Tag', array( $this, 'qeseo_add_custom_metades_tag_cb'), $qeseo_pub_pt_key , 'normal', 'high');
			}
			
		}elseif( $qeseo_meta_box_add_option_check == 'ow_posts_pages' ){
			
			add_meta_box( 'qeseo_metades_meta_box', 'Custom Meta Description Tag', array( $this, 'qeseo_add_custom_metades_tag_cb'), array('post','page') , 'normal', 'high');
			
		}else{
			return;
		}		
	}
	
	/* Meta description meta box call back function */
	public function qeseo_add_custom_metades_tag_cb( $post ){
		
		wp_nonce_field( 'qeseo_metades_data', 'qeseo_metades_tag_nonce' );
		$metades_value = get_post_meta( get_the_ID(), '_qeseo_metades_meta_value', true );
		?>
		<textarea name="qeseo_custom_metades_field" id="qeseo_custom_metades_field" cols="40" rows="5" style="width:100%"><?php echo esc_attr( $metades_value ) ?></textarea>
		<p class="description"><?php _e('Added by Quick & Easy SEO', 'text_domain'); ?></p>
		<?php
		
	}
	
	/* Check, sanitize and update meta description meta box data */
	public function qeseo_metades_data( $post_id ){
		
		if( ! isset( $_POST['qeseo_metades_tag_nonce'] ) ){
			return;
		}
		
		if( ! wp_verify_nonce( $_POST['qeseo_metades_tag_nonce'], 'qeseo_metades_data' ) ){
			return;
		}
		
		if( defined('DOING_AUTOSAVE') && DOING_AUTOSAVE ){
			return;			
		}
		
		if ( isset( $_POST['post_type'] ) && 'page' == $_POST['post_type'] ) {
				if ( ! current_user_can( 'edit_page', $post_id ) ) {
					return;
			}
		}
		
		if( ! current_user_can( 'edit_post', $post_id ) ){
			return;
		}
		
		
		if( ! isset( $_POST['qeseo_custom_metades_field'] ) ){
			return;
		}
		
		$qeseo_metades_value = sanitize_text_field( $_POST['qeseo_custom_metades_field'] );
		
		update_post_meta( $post_id, '_qeseo_metades_meta_value', $qeseo_metades_value );
		
		
	}
	
	
	
	/* Add title/meta-description metabox to woocommerce product category and tag pages */
	
	public function qeseo_woo_metades_title_form_fields($term){

		//getting term ID
		$qeseo_term_id = $term->term_id;
		wp_nonce_field( 'qeseo_woo_metades_data', 'qeseo_woo_metades_cate_nonce' );
		// retrieve the existing value(s) for this meta field. This returns an array
		$qeseo_meta = get_option("qeseo_to_" . $qeseo_term_id);
		$qeseo_title_val = esc_attr(stripslashes($qeseo_meta['qeseo_woocat_title']));
		$qeseo_metades_val = esc_attr(stripslashes($qeseo_meta['qeseo_woocat_metades']));
		?>
		<tr class="form-field">
			<th scope="row" valign="top"><label for="qeseo_meta[qeseo_woocat_title]"><?php _e('Custom Title Tag', 'text_domain'); ?></label></th>
			<td>
				<input type="text" name="qeseo_meta[qeseo_woocat_title]" id="qeseo_meta[qeseo_woocat_title]" value="<?php echo $qeseo_title_val ? $qeseo_title_val : ''; ?>">
				<p class="description"><?php _e('Added by Quick & Easy SEO', 'text_domain'); ?></p>
			</td>
		</tr>
		<tr class="form-field">
			<th scope="row" valign="top"><label for="qeseo_meta[qeseo_woocat_metades]"><?php _e('Meta Description Tag', 'text_domain'); ?></label></th>
			<td>
				<textarea name="qeseo_meta[qeseo_woocat_metades]" id="qeseo_meta[qeseo_woocat_metades]"><?php echo $qeseo_metades_val ? $qeseo_metades_val : ''; ?></textarea>
				<p class="description"><?php _e('Added by Quick & Easy SEO', 'text_domain'); ?></p>
			</td>
		</tr>
		<?php
	}

	/* Callback to save data */
	public function qeseo_woo_metades_title_update($term_id) {
		
		if( ! isset($_POST['qeseo_woo_metades_cate_nonce']  ) ) return;
			
		if( ! wp_verify_nonce( $_POST['qeseo_woo_metades_cate_nonce'], 'qeseo_woo_metades_data' ) ) return;
			
		if( ! current_user_can( 'manage_options' ) )return;
		
		if (isset($_POST['qeseo_meta'])) {
			$qeseo_meta = get_option("qeseo_to_" . $term_id);
			$qeseo_keys = array_keys($_POST['qeseo_meta']);
			foreach ($qeseo_keys as $qeseokey) {
				if (isset($_POST['qeseo_meta'][$qeseokey])) {
					$qeseo_meta[$qeseokey] = sanitize_text_field($_POST['qeseo_meta'][$qeseokey]);
				}
			}
			// Save the option array.
			update_option("qeseo_to_" . $term_id, $qeseo_meta);
		}else{
			return;
		}
	}
	
	

}
