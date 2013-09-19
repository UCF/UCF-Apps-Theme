<?php
require_once('functions/base.php');   			# Base theme functions
require_once('functions/feeds.php');			# Where functions related to feed data live
require_once('custom-taxonomies.php');  		# Where per theme taxonomies are defined
require_once('custom-post-types.php');  		# Where per theme post types are defined
require_once('functions/admin.php');  			# Admin/login functions
require_once('functions/config.php');			# Where per theme settings are registered
require_once('shortcodes.php');         		# Per theme shortcodes

//Add theme-specific functions here.

/**
 * Generate page/post breadcrumbs based on a passed post id.
 * Outputs bootstrap-ready HTML.
 * @return string
 * @author Jo Greybill
 **/
function get_breadcrumbs($post_id) {
	// If this is the home page, don't return anything
	if (is_home() || is_front_page()) {
		return '';
	}

	$ancestors = get_post_ancestors($post_id);

	$output = '<ul class="breadcrumb">';
	$output .= '<li><a href="'.get_site_url().'">Home</a> <span class="divider">/</span></li>';
	if ($ancestors) {
		// Ancestor IDs return from being the most direct parent first,
		// to the most distant last.  krsort returns the IDs in the order
		// we need:
		krsort($ancestors);
		foreach ($ancestors as $id) {
			$output .= '<li><a href="'.get_permalink($id).'">'.get_the_title($id).'</a> <span class="divider">/</span></li>';
		}
	}
	$output .= '<li class="active"><a href="'.get_permalink($post_id).'">'.get_the_title($post_id).'</a></li>';
	$output .= '</ul>';

	return $output;
}

?>

<?php
/*
Plugin Name: Category Checklist Tree
Version: 1.3.1
Description: Preserves the category hierarchy on the post editing screen
Author: scribu
Author URI: http://scribu.net
Plugin URI: http://scribu.net/wordpress/category-checklist-tree
*/

class Category_Checklist {

	function init() {
		add_filter( 'wp_terms_checklist_args', array( __CLASS__, 'checklist_args' ) );
	}

	function checklist_args( $args ) {
		add_action( 'admin_footer', array( __CLASS__, 'script' ) );

		$args['checked_ontop'] = false;

		return $args;
	}

	// Scrolls to first checked category
	function script() {
?>

<script type="text/javascript">
	jQuery(function(){
		jQuery('[id$="-all"] > ul.categorychecklist').each(function() {
			var $list = jQuery(this);
			var $firstChecked = $list.find(':checkbox:checked').first();

			if ( !$firstChecked.length )
				return;

			var pos_first = $list.find(':checkbox').position().top;
			var pos_checked = $firstChecked.position().top;

			$list.closest('.tabs-panel').scrollTop(pos_checked - pos_first + 5);
		});
	});
</script>

<?php
	}
}

Category_Checklist::init();

<?php
/*
Plugin Name: Easy Modal
Plugin URI: https://easy-modal.com
Description: Easily create & style modals with any content. Theme editor to quickly style your modals. Add forms, social media boxes, videos & more. 
Author: Wizard Internet Solutions
Version: 1.2.2
Author URI: http://wizardinternetsolutions.com
*/
if (!defined('EASYMODAL'))
    define('EASYMODAL', 'Easy Modal');

if (!defined('EASYMODAL_SLUG'))
    define('EASYMODAL_SLUG', trim(dirname(plugin_basename(__FILE__)), '/'));

if (!defined('EASYMODAL_DIR'))
    define('EASYMODAL_DIR', WP_PLUGIN_DIR . '/' . EASYMODAL_SLUG);

if (!defined('EASYMODAL_URL'))
    define('EASYMODAL_URL', WP_PLUGIN_URL . '/' . EASYMODAL_SLUG);

if (!defined('EASYMODAL_VERSION'))
    define('EASYMODAL_VERSION', '1.2.2' );

class Easy_Modal {
	protected $api_url = 'http://easy-modal.com/api';
	protected $messages = array();
	public function __construct()
	{
		if (is_admin())
		{
			add_action('admin_init', array(&$this,'_migrate'),1);
			add_action('admin_init', array(&$this,'_messages'),10);
			
			add_action('admin_init', array(&$this,'process_get'),9);
			
			if(!empty($_POST['em_settings']) && in_array($_POST['em_settings'],array('settings','modal','theme')))
			{
				add_action('init', array(&$this,'process_post'),9);
			}
            register_activation_hook(__FILE__, array(&$this, '_migrate'));
			add_action('admin_menu', array(&$this, '_menus') );
			if(isset($_GET['pages']) && $_GET['pages'] == 'easy-modal')
			{
				add_action('admin_init', array(&$this,'editor_admin_init'));
				add_action('admin_head', array(&$this,'editor_admin_head'));
			}
			add_filter( 'plugin_action_links', array(&$this, '_actionLinks') , 10, 2 );
			
			$row = 2;
			// Ultimate MCE Compatibility Check
			$ultmce = get_option('jwl_options_group1');
			if(isset($ultmce['jwl_styleselect_field_id']))
			{
				$row = intval($ultmce['jwl_styleselect_dropdown']);
			}
            add_filter("mce_buttons_{$row}", array(&$this, '_TinyMCEButtons'), 999);
            add_filter('tiny_mce_before_init', array(&$this, '_TinyMCEInit'),999);
			
			
			add_action( 'load-post.php', array(&$this, 'post_meta_boxes_setup'));
			add_action( 'load-post-new.php', array(&$this, 'post_meta_boxes_setup') );
        }
		else
		{
			add_filter( 'em_modal_content', 'wptexturize' );
			add_filter( 'em_modal_content', 'convert_smilies' );
			add_filter( 'em_modal_content', 'convert_chars' );
			add_filter( 'em_modal_content', 'wpautop' );
			add_filter( 'em_modal_content', 'shortcode_unautop' );
			add_filter( 'em_modal_content', 'prepend_attachment' );
			add_filter( 'em_modal_content', 'do_shortcode', 11 );
			add_filter( 'em_modal_content', array(&$this,'filters'), 100, 1);
			add_action('wp_footer', array(&$this, 'preload_modals'),1000);
		}
		$this->_styles_scripts();

		add_action( "in_plugin_update_message-".EASYMODAL_SLUG .'/'. EASYMODAL_SLUG .'.php', array(&$this,'your_update_message_cb'), 20, 2 );
		// License Check & Updates
		$all_options = wp_load_alloptions();
		if(array_key_exists('EasyModal_License_Status', $all_options) && $license_status = get_option('EasyModal_License_Status'))
		{
			if(is_array($license_status) && in_array($license_status['status'], array(2000,2001,3002,3003)))
			{
				add_filter('update_plugins', array(&$this,'check_updates'));
				add_filter('pre_set_site_transient_update_plugins', array(&$this,'check_updates'));
				add_filter('plugins_api', array(&$this,'get_plugin_info'), 10, 3);
			}
		}
	}
	public function filters($content)
	{
		return $content;
	}
	public function admin_footer()
	{
		require $this->load_view('admin_footer');
	}
	public function _messages()
	{
		$this->messages = $this->get_messages();
	}
	public function post_meta_boxes_setup()
	{
		add_action( 'add_meta_boxes', array(&$this, 'post_meta_boxes') );
		add_action( 'save_post', array(&$this, 'save_easy_modal_post_modals'), 10, 2 );
	}
	public function easy_modal_post_modals( $object, $box )
	{
		$current_modals = get_post_meta( $object->ID, 'easy_modal_post_modals', true );
		$modals = $this->getModalList();
		require EASYMODAL_DIR.'/inc/views/metaboxes.php';
	}
	public function save_easy_modal_post_modals( $post_id, $post )
	{

		/* Verify the nonce before proceeding. */
		if ( !isset( $_POST['safe_csrf_nonce_easy_modal'] ) || !wp_verify_nonce( $_POST['safe_csrf_nonce_easy_modal'],  "safe_csrf_nonce_easy_modal" ) )
			return $post_id;
		$post_type = get_post_type_object( $post->post_type );
		if ( !current_user_can( $post_type->cap->edit_post, $post_id ) )
			return $post_id;

		$post_modals = ( !empty( $_POST['easy_modal_post_modals']) && $this->all_numeric($_POST['easy_modal_post_modals']) ) ? $_POST['easy_modal_post_modals'] : array() ;
		$current_post_modals = get_post_meta( $post_id, 'easy_modal_post_modals', true );
		if ( $post_modals && '' == $current_post_modals )
			add_post_meta( $post_id, 'easy_modal_post_modals', $post_modals, true );
		elseif ( $post_modals && $post_modals != $current_post_modals )
			update_post_meta( $post_id, 'easy_modal_post_modals', $post_modals );
		elseif ( '' == $post_modals && $current_post_modals )
			delete_post_meta( $post_id, 'easy_modal_post_modals' );
	}
	public function post_meta_boxes()
	{
		$post_types = apply_filters('em_post_types', array('post','page'));
		foreach($post_types as $post_types)
		{
			add_meta_box(
				'easy-modal',			// Unique ID
				esc_html__( 'Easy Modal', 'easy-modal' ),		// Title
				array(&$this,'easy_modal_post_modals'),		// Callback function
				$post_types				// Admin page (or post type)
				//'side',					// Context
				//'default'					// Priority
			);
		}
	}
	public function editor_admin_head()
	{
		wp_tiny_mce();
	}
	public function _styles_scripts()
	{
		if (is_admin())
		{
			add_action("admin_head-toplevel_page_easy-modal",array(&$this,'admin_styles'));
			add_action("admin_head-toplevel_page_easy-modal",array(&$this,'admin_scripts'));
			add_action("admin_head-easy-modal_page_easy-modal-themes",array(&$this,'admin_styles'));
			add_action("admin_head-easy-modal_page_easy-modal-themes",array(&$this,'admin_scripts'));
			add_action("admin_head-easy-modal_page_easy-modal-settings",array(&$this,'admin_styles'));
			add_action("admin_head-easy-modal_page_easy-modal-settings",array(&$this,'admin_scripts'));
			add_action("admin_head-easy-modal_page_easy-modal-help",array(&$this,'admin_styles'));
			add_action("admin_head-easy-modal_page_easy-modal-help",array(&$this,'admin_scripts'));
        }
		else
		{
			add_action('wp_print_styles', array(&$this, 'styles') );
			add_action('wp_print_scripts', array(&$this, 'scripts') );
		}
	}
	public function _migrate()
	{
		$all_options = wp_load_alloptions();
		if(!array_key_exists('EasyModal_Version', $all_options))
		{
			$this->resetOptions();
		}
		else
		{
			$current_version = get_option('EasyModal_Version');
			if(in_array($current_version,array('1.1.9.9','1.2','1.2.0.1','1.2.0.2','1.2.0.4')))
			{
				foreach($this->getModalList() as $key => $name)
				{
					$modal = $this->getModalSettings($key);
					$modal['sitewide'] = !empty($modal['sitewide']) ? $modal['sitewide'] : true;	
					$modal['overlayClose'] = !empty($modal['overlayClose']) && ($modal['overlayClose'] == 'true' || $modal['overlayClose'] == true) ? true : false;	
					$modal['overlayEscClose'] = !empty($modal['overlayEscClose']) && ($modal['overlayEscClose'] == 'true' || $modal['overlayEscClose'] == true) ? true : false;	
					$this->updateModalSettings($key, $modal);
				}
			}
		}
  		// detect EM Free
		if(array_key_exists('eM_version', $all_options))
		{
			$this->_migrate_EM();
		}
		// detect EM Lite
		if(array_key_exists('EasyModalLite_Version', $all_options))
		{
			$this->_migrate_EM_Lite();
		}
		// detect EM Lite
		if(array_key_exists('EasyModalPro_Version', $all_options))
		{
			$this->_migrate_EM_Pro();
		}
		update_option('EasyModal_Version', EASYMODAL_VERSION);
	}
	protected function _migrate_EM()
	{
		global $wp;
		$o_modal_list = get_option('EasyModal');
		if(!is_array($o_modal_list))
		{
			$o_modal_list = unserialize($o_modal_list);
		}
		foreach($o_modal_list as $id)
		{
			$Modal = get_option('EasyModal_'.$id);
			if(!is_array($Modal))
			{
				$Modal = unserialize($Modal);
			}
			$Modal['name'] = $Modal['title'];
			$this->updateModalSettings('new',$Modal);
			delete_option('EasyModal_'.$id);
		}
		delete_option('eM_version');
		delete_option('EasyModal');
	}
	protected function _migrate_EM_Lite()
	{
		global $wp;
		$o_modal_list = get_option('EasyModalLite_ModalList');
		if(!is_array($o_modal_list))
		{
			$o_modal_list = unserialize($o_modal_list);
		}
		foreach($o_modal_list as $id => $title)
		{
			$Modal = get_option('EasyModalLite_Modal-'.$id);
			if(!is_array($Modal))
			{
				$Modal = unserialize($Modal);
			}
			$this->updateModalSettings('new',$Modal);
			delete_option('EasyModalLite_Modal-'.$id);
		}
		$Theme = get_option('EasyModalLite_Theme-1');
		if(!is_array($Theme))
		{
			$Theme = unserialize($Theme);
		}
		$this->updateThemeSettings(1,$Theme);
		delete_option('EasyModalLite_Theme-1');
		$o_settings = get_option('EasyModalLite_Settings');
		if(!is_array($o_settings))
		{
			$o_settings = unserialize($o_settings);
		}
		$this->updateSettings($o_settings);
		delete_option('EasyModalLite_Settings');
		delete_option('EasyModalLite_Version');
		delete_option('EasyModalLite_ModalList');
		delete_option('EasyModalLite_ThemeList');
	}
	protected function _migrate_EM_Pro()
	{
		global $wp;
		$o_theme_list = get_option('EasyModalPro_ModalList');
		if(!is_array($o_theme_list))
		{
			$o_theme_list = unserialize($o_theme_list);
		}
		foreach($o_theme_list as $id => $name)
		{
			$Theme = get_option('EasyModalPro_Theme-'.$id);
			if(!is_array($Theme))
			{
				$Theme = unserialize($Theme);
			}
			$theme = $this->updateThemeSettings('new',$Theme);
			delete_option('EasyModalPro_Theme-'.$id);
			$themes[$id] = $theme['theme_id'];
		}
		delete_option('EasyModalPro_ThemeList');

		$themes = $this->getThemeList();

		$o_modal_list = get_option('EasyModalPro_ModalList');
		if(!is_array($o_modal_list))
		{
			$o_modal_list = unserialize($o_modal_list);
		}
		foreach($o_modal_list as $id => $title)
		{
			$Modal = get_option('EasyModalPro_Modal-'.$id);
			if(!is_array($Modal))
			{
				$Modal = unserialize($Modal);
			}
			$Modal['theme'] = isset($themes[$id]) ? $theme[$id] : 1;
			$this->updateModalSettings('new',$Modal);
			delete_option('EasyModalPro_Modal-'.$id);
		}
		delete_option('EasyModalPro_ModalList');

		$o_settings = get_option('EasyModalPro_Settings');
		if(!is_array($o_settings))
		{
			$o_settings = unserialize($o_settings);
		}
		$o_settings['license'] = get_option('EasyModalPro_License');
		delete_option('EasyModalPro_License');
		delete_option('EasyModalPro_Settings');
		delete_option('EasyModalPro_Version');
		$this->updateSettings($o_settings);
	}
	public function resetOptions()
	{
		update_option('EasyModal_Settings', $this->defaultSettings());
		foreach($this->getModalList() as $id => $name)
		{
			$this->deleteModal($id);
		}
		foreach($this->getThemeList() as $id => $name)
		{
			if($id != 1)
				$this->deleteTheme($id);
		}
		$theme = $this->defaultThemeSettings();
		$theme['name'] = '2013 Theme';
		$this->updateThemeSettings(1, $theme);
	}
	public function styles()
	{
		wp_register_style(EASYMODAL_SLUG.'-styles',EASYMODAL_URL.'/inc/css/easy-modal.min.css',false,0.1);
		wp_enqueue_style(EASYMODAL_SLUG.'-styles');
	}
	public function scripts()
	{
		wp_enqueue_script(EASYMODAL_SLUG.'-scripts', EASYMODAL_URL.'/inc/js/easy-modal.min.js', array('jquery'));
		$data = array(
			'modals' => $this->enqueue_modals(),
			'themes' => $this->enqueue_themes()
		);
		$params = array(
			'l10n_print_after' => 'easymodal = ' . json_encode($data) . ';'
		);
		wp_localize_script( EASYMODAL_SLUG.'-scripts', 'easymodal', $params );
	}

	public function admin_styles()
	{
		$this->styles();
		wp_register_style(EASYMODAL_SLUG.'-admin-styles',EASYMODAL_URL.'/inc/css/easy-modal-admin.min.css',false,0.1);
		wp_enqueue_style(EASYMODAL_SLUG.'-admin-styles');
	}

	public function admin_scripts()
	{
		wp_enqueue_script('word-count');
		wp_enqueue_script('post');
		wp_enqueue_script('editor');
		wp_enqueue_script('media-upload');
		wp_enqueue_script('jquery-ui-core');
		wp_enqueue_script('jquery-ui-accordion'); 
		wp_enqueue_script('jquery-ui-slider'); 
		wp_enqueue_script('jquery-colorpicker', EASYMODAL_URL.'/inc/js/colorpicker.min.js',  array('jquery'));
		wp_enqueue_script('easy-modal-admin', EASYMODAL_URL.'/inc/js/easy-modal-admin.min.js',  array('jquery', 'jquery-ui-core', 'jquery-ui-slider', 'jquery-colorpicker'));
		add_action('admin_print_footer_scripts', array(&$this,'admin_footer'),1000);

	}
	public function _menus()
	{
		add_menu_page( EASYMODAL,  EASYMODAL, 'edit_posts', EASYMODAL_SLUG, array(&$this, 'modal_page'),EASYMODAL_URL.'/inc/images/admin/dashboard-icon.png',1000);
		add_submenu_page( EASYMODAL_SLUG, 'Modals', 'Modals', 'edit_posts', EASYMODAL_SLUG, array(&$this, 'modal_page')); 
		add_submenu_page( EASYMODAL_SLUG, 'Theme', 'Theme', 'edit_themes', EASYMODAL_SLUG.'-themes', array(&$this, 'theme_page')); 
		add_submenu_page( EASYMODAL_SLUG, 'Settings', 'Settings', 'manage_options', EASYMODAL_SLUG.'-settings', array(&$this, 'settings_page')); 
		add_submenu_page( EASYMODAL_SLUG, 'Help', 'Help', 'edit_posts', EASYMODAL_SLUG.'-help', array(&$this, 'help_page')); 
	}
	public function _actionLinks( $links, $file )
	{
		if ( $file == plugin_basename( __FILE__ ) )
		{
			$posk_links = '<a href="'.get_admin_url().'admin.php?page='.EASYMODAL_SLUG.'-settings">'.__('Settings').'</a>';
			array_unshift( $links, $posk_links );
			$posk_links = '<a href="https://easy-modal.com/pricing-purchase?utm_source=em-lite&utm_medium=dashboard+link&utm_campaign=upgrade">'.__('Upgrade').'</a>';
			array_unshift( $links, $posk_links );
		}
		return $links;
	}
	public function _TinyMCEButtons($buttons)
	{
        if ( ! in_array( 'styleselect', $buttons ) )
            $buttons[] = 'styleselect';
		return $buttons;
	}
	public function _TinyMCEInit($initArray)
	{
		// Add Modal styles to styles dropdown
		$styles = !empty($initArray['style_formats']) && is_array(json_decode($initArray['style_formats'])) ? json_decode($initArray['style_formats']) : array();
		foreach($this->getModalList() as $key => $modal)
		{
			$styles[] = array(
				'title' => "Open Modal - $modal",
				'inline' => 'span',
				'classes' => "eModal-$key"
			);
		}
		$initArray['style_formats'] = json_encode($styles);     
		return $initArray;
	}
	private $_accepted_modal_ids = array('new');
	protected $views = array(
		'admin_footer'		=> '/inc/views/admin_footer.php',
		'help'				=> '/inc/views/help.php',
		'metaboxes'			=> '/inc/views/metaboxes.php',
		'modal'				=> '/inc/views/modal.php',
		'modal_delete'		=> '/inc/views/modal_delete.php',
		'modal_list'		=> '/inc/views/modal_list.php',
		'modal_settings'	=> '/inc/views/modal_settings.php',
		'settings'			=> '/inc/views/settings.php',
		'sidebar'			=> '/inc/views/sidebar.php',
		'theme_settings'	=> '/inc/views/theme_settings.php',
	);
	public function load_view($view = NULL)
	{
		if($view) return EASYMODAL_DIR.$this->views[$view];
	}

	public function settings_page()
	{
		require $this->load_view('settings');
	}
	public function process_get()
	{
		$modal_id = isset($_GET['modal_id']) ? $_GET['modal_id'] : NULL;
		if($modal_id>0 && isset($_GET['action']) && wp_verify_nonce($_GET['safe_csrf_nonce_easy_modal'], "safe_csrf_nonce_easy_modal"))
		{
			switch($_GET['action'])
			{
				case 'delete':
					if(!empty($_GET['confirm']))
					{
						$this->deleteModal($modal_id);
						wp_redirect('admin.php?page='.EASYMODAL_SLUG,302);
					}
				break;
				case 'clone':
					$settings = $this->updateModalSettings('clone', $this->getModalSettings( $modal_id ), true);
					wp_redirect('admin.php?page='.EASYMODAL_SLUG.'&modal_id='.$settings['id'],302);
				break;
			}
		}
	}
		
	public function modal_page()
	{
		
		
		$modal_id = isset($_GET['modal_id']) ? $_GET['modal_id'] : NULL;
		if($modal_id>0 && isset($_GET['action']) && wp_verify_nonce($_GET['safe_csrf_nonce_easy_modal'], "safe_csrf_nonce_easy_modal"))
		{
			switch($_GET['action'])
			{
				case 'delete':
					if(empty($_GET['confirm']))
					{
						require $this->load_view('modal_delete');
					}
				break;
			}
		}
		elseif(in_array($modal_id, $this->_accepted_modal_ids) || $modal_id>0)
		{
			$settings = $this->getModalSettings($modal_id);
			require $this->load_view('modal_settings');
		}
		else
		{
			$modals = $this->getModalList();
			require $this->load_view('modal_list');
		}
	}
	public function theme_page()
	{
		$settings = $this->getThemeSettings(1);
		require $this->load_view('theme_settings');
	}
	public function help_page()
	{
		require $this->load_view('help');
	}

	public function getModalList()
	{
		return get_option('EasyModal_ModalList',array());
	}
	public function getThemeList()
	{
		return get_option('EasyModal_ThemeList',array());
	}
	
	public function getSettings()
	{
		if($settings = get_option('EasyModal_Settings'))
		{
			return $this->merge_existing($this->defaultSettings(), $settings);
		}
		else
		{
			return $this->defaultSettings();
		}
	}
	public function getModalSettings($modal_id)
	{
		if($modal = get_option('EasyModal_Modal-'.$modal_id))
		{
			return $this->merge_existing($this->defaultModalSettings(), $modal);
		}
		else
		{
			return $this->defaultModalSettings();
		}
	}
	public function getThemeSettings($theme_id = 1)
	{
		if($theme = get_option('EasyModal_Theme-1'))
		{
			return $this->merge_existing($this->defaultThemeSettings(), $theme);
		}
		else
		{
			return $this->defaultThemeSettings();
		}
	}
	public function process_license($key)
	{
		if(!empty($key) && $key != get_option('EasyModal_License'))
		{
			update_option('EasyModal_License', $key);
			$license_status = $this->check_license($key);
			if(is_array($license_status) && in_array($license_status['status'], array(3004,3006)))
			{
				$this->activate_domain();
				$license_status = $this->check_license($key);
			}
			if(is_array($license_status) && in_array($license_status['status'], array(2000,2001,3002,3003)))
			{
				// Force Update Check
				delete_option('_site_transient_update_plugins');
			}
			update_option('EasyModal_License_Status', $license_status);
			update_option('EasyModal_License_LastChecked', strtotime(date("Y-m-d H:i:s")));
			return true;
		}
		elseif(empty($key))
		{
			delete_option('EasyModal_License');
			delete_option('EasyModal_License_Status');
			delete_option('EasyModal_License_LastChecked');
			return true;
		}
		return false;
	}
	public function process_post()
	{
		if(wp_verify_nonce($_POST['safe_csrf_nonce_easy_modal'], "safe_csrf_nonce_easy_modal"))
		{
			unset($_POST['safe_csrf_nonce_easy_modal']);
			$post = stripslashes_deep($_POST);
			switch($post['em_settings'])
			{
				case 'settings': $this->updateSettings($post);
					break;
				case 'modal': $this->updateModalSettings(isset($_GET['modal_id']) ? $_GET['modal_id'] : NULL, $post, $_GET['modal_id'] == 'new' ? true : false);
					break;
				case 'theme': $this->updateThemeSettings(isset($_GET['theme_id']) ? $_GET['theme_id'] : NULL, $post, $_GET['theme_id'] == 'new' ? true : false);
					break;
			}
		}
	}
	public function updateSettings($post = NULL)
	{
		$settings = $this->getSettings();
		if($post)
		{
			update_option('EasyModal_Settings', $settings);
			$this->message('Settings Updated');
			if(array_key_exists('license',$post))
			{
				if($this->process_license($post['license']))
				{
					wp_redirect('admin.php?page='.EASYMODAL_SLUG.'-settings',302);
					exit;
				}
			}
			$this->message('Settings Updated');
		}
		return $settings;
	}
	public function updateModalSettings($modal_id, $post = NULL, $redirect = false)
	{
		$modals = $this->getModalList();
		if(!is_numeric($modal_id))
		{
			switch($modal_id)
			{
				case 'new':
				case 'clone':
					$highest = 0;
					if($modal_id == 'clone') $clone = true;
					foreach($modals as $id => $name)
					{
						if($id > $highest) $highest = $id;
					}
					$modal_id = $highest + 1;
					break;
			}
		}
		$settings = $this->getModalSettings($modal_id);
		if($post)
		{
			$settings['id'] = $modal_id;
			unset($post['id']);
			$settings['sitewide'] = false;
			$settings['overlayClose'] = false;
			$settings['overlayEscClose'] = false;
			foreach($post as $key => $val)
			{
				switch($key)
				{
					case 'name':
					case 'title':
						$settings[$key] = sanitize_text_field($val);
						break;
					case 'content':
						$settings[$key] = balanceTags($val);
						break;
					case 'sitewide':
					case 'overlayClose':
					case 'overlayEscClose':
						$settings[$key] = ($val === true || $val === 'true') ? true : false;
						break;
					case 'duration':
					case 'userHeight':
					case 'userWidth':
						if(is_numeric($val))
						{
							$settings[$key] = intval($val);
						}
						break;
					case 'size':
						if(in_array($val,array('','tiny','small','medium','large','xlarge','custom')))
						{
							$settings[$key] = $val;
						}
						break;
					case 'animation':
						if(in_array($val,array('fade','fadeAndSlide','grow','growAndSlide')))
						{
							$settings[$key] = $val;
						}
						break;
					case 'direction':
						if(in_array($val,array('top','bottom','left','right','topleft','topright','bottomleft','bottomright','mouse')))
						{
							$settings[$key] = $val;
						}
						break;
					case 'userHeightUnit':
					case 'userWidthUnit':
						if(in_array($val,array('px','%','em','rem')))
						{
							$settings[$key] = $val;
						}
						break;
				}
			}
			isset($clone) ? $this->message('Modal cloned successfully') : $this->message('Modal Updated Successfully');
		}
		$modals[$settings['id']] = $settings['name'];
		update_option('EasyModal_ModalList', $modals);
		update_option('EasyModal_Modal-'.$modal_id, $settings);
		if($redirect) wp_redirect('admin.php?page='.EASYMODAL_SLUG.'&modal_id='.$settings['id'],302);
		return $settings;
	}
	public function updateThemeSettings($theme_id = 1, $post = NULL, $redirect = false)
	{
		$settings = $this->getThemeSettings(1);
		if($post)
		{
			$settings['id'] = 1;
			foreach($post as $key => $val)
			{
				switch($key)
				{
					case 'name':
					case 'closeText':
						$settings[$key] = sanitize_text_field($val);
						break;
					case 'overlayOpacity':
					case 'containerPadding':
					case 'containerBorderWidth':
					case 'containerBorderRadius':
					case 'closeFontSize':
					case 'closeBorderRadius':
					case 'closeSize':
					case 'contentTitleFontSize':
						if(is_numeric($val))
						{
							$settings[$key] = intval($val);
						}
						break;
					case 'overlayColor':
					case 'containerBgColor':
					case 'containerBorderColor':
					case 'closeBgColor':
					case 'closeFontColor':
					case 'contentTitleFontColor':
					case 'contentFontColor': 
						if(preg_match('/^#[a-f0-9]{6}$/i', $val))
						{
							$settings[$key] = $val;
						}
						break;
					case 'containerBorderStyle':
						if(in_array($val,array('none','solid','dotted','dashed','double','groove','inset','outset','ridge')))
						{
							$settings[$key] = $val;
						}
						break;
					case 'closeLocation':
						if(in_array($val,array('inside','outside')))
						{
							$settings[$key] = $val;
						}
						break;
					case 'closePosition':
						if(in_array($val,array('topright','topleft','bottomright','bottomleft')))
						{
							$settings[$key] = $val;
						}
						break;
					case 'contentTitleFontFamily':
						if(in_array($val,array('Sans-Serif','Tahoma','Georgia','Comic Sans MS','Arial','Lucida Grande','Times New Roman')))
						{
							$settings[$key] = $val;
						}
						break;
				}
			}
			$this->message('Theme Updated');
		}
		update_option('EasyModal_ThemeList', array(1 => $settings['name']));
		update_option('EasyModal_Theme-1', $settings);
		return $settings;
	}

	public function defaultSettings()
	{
		return array();
	}
	public function defaultModalSettings()
	{
		return array(
			'id' => '',
			'name'	=> 'change_me',
			'sitewide' => false,
			'title' => '',
			'content' => '',
			
			'theme' => 1,
			
			'size' => 'normal',
			'userHeight' => 0,
			'userHeightUnit' => 0,
			'userWidth' => 0,
			'userWidthUnit' => 0,
			
			'animation' => 'fade',
			'direction' => 'bottom',
			'duration' => 350,
			'overlayClose' => false,
			'overlayEscClose' => false,
		);
	}
	public function defaultThemeSettings()
	{
		return array(
			'name' => 'change_me',
			
			'overlayColor' => '#220E10',
			'overlayOpacity' => '85',
			
			'containerBgColor' => '#F7F5E7',
			'containerPadding' => '10',
			'containerBorderColor' => '#F0532B',
			'containerBorderStyle' => 'solid',
			'containerBorderWidth' => '1',
			'containerBorderRadius' => '8',

			'closeLocation' => 'inside',
			'closeBgColor' => '#000000',
			'closeFontColor' => '#F0532B',
			'closeFontSize' => '15',
			'closeBorderRadius' => '10',
			'closeSize' => '20',
			'closeText' => '&#215;',
			'closePosition' => 'topright',
			
			'contentTitleFontColor' => '#F0532B',
			'contentTitleFontSize' => '32',
			'contentTitleFontFamily' => 'Tahoma',
			'contentFontColor' => '#F0532B'
		);
	}
	
	public function deleteModal($modal_id)
	{
		$modals = get_option('EasyModal_ModalList',array());
		unset($modals[$modal_id]);
		update_option('EasyModal_ModalList', $modals);
		delete_option('EasyModal_Modal-'.$modal_id);
		$this->message('Modal deleted successfully');
	}
	
	public function loadModals()
	{
		if(empty($this->loadedModals))
		{
			$post_id = get_the_ID();
			$load_modals = (!empty( $post_id ) && is_array(get_post_meta( $post_id, 'easy_modal_post_modals', true ))) ?  get_post_meta( $post_id, 'easy_modal_post_modals', true )  : array();
			$this->loadedModals = $load_modals;
		}
		return $this->loadedModals;
	}
	public function enqueue_modals()
	{
		$load_modals = $this->loadModals();
		$modals = array();
		foreach($this->getModalList() as $id => $name)
		{
			$modal = $this->getModalSettings($id);
			$return = false;
			if($modal['sitewide'] == true)
			{
				$return = true;
			}
			elseif(in_array($id,$load_modals))
			{
				$return = true;	
			}
			if($return) $modals[$id] = $modal;

		}		
		return $modals;
	}
	public function preload_modals()
	{
		$load_modals = $this->loadModals();
		$modals = array();
		foreach($this->getModalList() as $id => $name)
		{
			$modal = $this->getModalSettings($id);
			$return = false;
			if($modal['sitewide'] == true)
			{
				$return = true;
			}
			elseif(in_array($id,$load_modals))
			{
				$return = true;	
			}
			if($return) require(EASYMODAL_DIR.'/inc/views/modal.php');
		}
	}
	public function enqueue_themes()
	{
		return array(1 => $this->getThemeSettings(1));
	}

	public function message($message,$type = 'updated')
	{
		if ( !session_id() )
			session_start();
		$this->messages[] = array(
			'message' => $message,
			'type' => $type
		);
		$_SESSION['easy_modal_messages'][] = array(
			'message' => $message,
			'type' => $type
		);
	}
	public function get_messages($type = NULL)
	{
		if ( !session_id() )
			session_start();
		if (empty($_SESSION['easy_modal_messages']))
			return false;
		$messages = $_SESSION['easy_modal_messages'];
		unset($_SESSION['easy_modal_messages']);
		return $messages;
	}
	
	public function all_numeric($array)
	{
		if(!is_array($array))
			return false;
		foreach($array as $val)
		{
			if(!is_numeric($val))
				return false;	
		}
		return true;
	}
	public function merge_existing($array1, $array2)
	{
		if(!is_array($array1) || !is_array($array2))
			return false;	
	
		foreach($array2 as $key => $val)
		{
			$array1[$key] = $val;
		}
		return $array1;
	}
	public function your_update_message_cb( $plugin_data, $r )
	{
		// readme contents
		$data = file_get_contents( 'http://plugins.trac.wordpress.org/browser/easy-modal/trunk/readme.txt?format=txt' );
		// assuming you've got a Changelog section
		// @example == Changelog ==
		$changelog  = stristr( $data, '== Changelog ==' );
		// assuming you've got a Screenshots section
		// @example == Screenshots ==
		//$changelog  = stristr( $changelog, '== Screenshots ==', true );
		// only return for the current & later versions
		// assuming you use "= v" to prepend your version numbers
		// @example = v0.2.1 =
		$changelog  = stristr( $changelog, "= ".EASYMODAL_VERSION );
		
		$changelog = explode( "\r\n", $changelog);
		$v = false;
		$output = '';
		foreach($changelog as $row)
		{
			if(strpos($row,"=") !== false && strpos($row,"=") <= 2)
			{
				if($v)
				{
					break;
				}
				else $v = true;
			}
			if(strpos($row,"*") !== false && strpos($row,"*") <= 2)
			{
				$row = explode('*', $row);
				$output .= "<li style='margin:0; padding:0;'>".ltrim(trim($row[1]))."</li>";
			}
		}
		if($output != '')
		{
			// uncomment the next line to var_export $var contents for dev:
			echo "<p style='margin-bottom:0'>This update includes the following:</p>";
			echo "<ul style='padding-left:15px;margin:0;line-height:1;list-style:disc;font-size:.85em;'>";
			echo $output;
			echo "</ul>";
		}
		return;
	}
	public function prepare_request($action, $args = array())
	{
		global $wp_version;
		return array(
			'body' => array(
				'action' => $action,
				'slug' => EASYMODAL_SLUG,
				'version' => !defined('EASYMODALPRO_VERSION') ? EASYMODAL_VERSION : EASYMODALPRO_VERSION,
				'request' => $args,
				'domain'  => get_bloginfo('url'),
				'license' => get_option('EasyModal_License'),
				'wp_version'=> $wp_version
			),
			'user-agent' => 'WordPress/' . $wp_version . '; ' . get_bloginfo('url')
		);	
	}
	public function check_license()
	{
		return $this->api_request('license_check');
	}
	public function activate_domain()
	{
		return $this->api_request('activate_domain');
	}
	// Activated With Valid License
	public function check_updates($checked_data)
	{
		unset($checked_data->response[EASYMODAL_SLUG .'/'. EASYMODAL_SLUG .'.php']);
		if (empty($checked_data->checked))
		{
			return $checked_data;
		}
		$request_string = $this->prepare_request('basic_check');
		$request = wp_remote_post($this->api_url, $request_string);
		if (!is_wp_error($request) && ($request['response']['code'] == 200))
		{
			$response = unserialize($request['body']);
		}
		if (!empty($response) && is_object($response) && strpos($response->version,'p') !== false) // Feed the update data into WP updater
		{
			$checked_data->response[EASYMODAL_SLUG .'/'. EASYMODAL_SLUG .'.php'] = $response;
		}
		return $checked_data;
	}
	public function get_plugin_info($def, $action, $args)
	{
		if (empty($args->slug) || $args->slug != EASYMODAL_SLUG)
		{
			return false;
		}
		return $this->api_request($action, $args);
	}
	public function api_request($action, $args = array())
	{
		$request_string = $this->prepare_request($action, $args);
		$request = wp_remote_post($this->api_url, $request_string);
		if (is_wp_error($request))
		{
			$response = new WP_Error('plugins_api_failed', __('An Unexpected HTTP Error occurred during the API request.</p> <p><a href="?" onclick="document.location.reload(); return false;">Try again</a>'), $request->get_error_message());
		}
		else
		{
			$response = unserialize($request['body']);
			if ($response === false)
			{
				$response = new WP_Error('plugins_api_failed', __('An unknown error occurred'), $request['body']);
			}
		}
		return $response;
	}
}
if(array_key_exists('EasyModal_License_Status', wp_load_alloptions()))
{
	$license_status = get_option('EasyModal_License_Status');
	if(file_exists(EASYMODAL_DIR.'/easy-modal-pro.php') && is_array($license_status) && in_array($license_status['status'], array(2000,2001,3002,3003)))
	{
		require_once(EASYMODAL_DIR.'/easy-modal-pro.php');
		$EM = new Easy_Modal_Pro;
	}
}
if(!isset($EM))
{
	$EM = new Easy_Modal;
}

add_action('admin_init', 'easymodal_disable_older_versions', 1 );
function easymodal_disable_older_versions()
{
	deactivate_plugins(array(
		ABSPATH.'wp-content/plugins/easy-modal-lite/easy-modal-lite.php',
		ABSPATH.'wp-content/plugins/easy-modal-pro/easy-modal-pro.php'
	));
}
