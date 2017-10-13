<?php
/**
 * Plugin Name: Form Maker
 * Plugin URI: https://web-dorado.com/products/form-maker-wordpress.html
 * Description: This plugin is a modern and advanced tool for easy and fast creating of a WordPress Form. The backend interface is intuitive and user friendly which allows users far from scripting and programming to create WordPress Forms.
 * Version: 1.11.6
 * Author: WebDorado Form Builder Team
 * Author URI: https://web-dorado.com/
 * License: GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 */
define('WD_FM_DIR', WP_PLUGIN_DIR . "/" . plugin_basename(dirname(__FILE__)));
define('WD_FM_URL', plugins_url(plugin_basename(dirname(__FILE__))));
define('WD_MAIN_FILE', plugin_basename(__FILE__));
define('WD_FM_VERSION', '1.11.6');
// Plugin menu.
function form_maker_options_panel() {
  $parent_slug = null;
  if( get_option( "fm_subscribe_done" ) == 1 ) {
    add_menu_page('Form Maker', 'Form Maker', 'manage_options', 'manage_fm', 'form_maker', WD_FM_URL . '/images/FormMakerLogo-16.png', 105.105);
    $parent_slug = "manage_fm";
  }

  add_menu_page('Form Maker Add-ons', 'Form Maker &nbsp;&nbsp;&nbsp;&nbsp; Add-ons', 'manage_options', 'extensions_fm', 'fm_extensions', WD_FM_URL . '/assets/add-ons-icon.png');

  $manage_page = add_submenu_page($parent_slug, 'Manager', 'Manager', 'manage_options', 'manage_fm', 'form_maker');
  add_action('admin_print_styles-' . $manage_page, 'form_maker_manage_styles');
  add_action('admin_print_scripts-' . $manage_page, 'form_maker_manage_scripts');

  $submissions_page = add_submenu_page($parent_slug, 'Submissions', 'Submissions', 'manage_options', 'submissions_fm', 'form_maker');
  add_action('admin_print_styles-' . $submissions_page, 'form_maker_submissions_styles');
  add_action('admin_print_scripts-' . $submissions_page, 'form_maker_submissions_scripts');

  if (defined('WD_FM_SAVE_PROG') && is_plugin_active(constant('WD_FM_SAVE_PROG'))) {
	$saved_entries_page = add_submenu_page($parent_slug, 'Saved Entries', 'Saved Entries', 'manage_options', 'saved_entries', 'fm_saved_entries');
	add_action('admin_print_styles-' . $saved_entries_page, 'form_maker_submissions_styles');
    add_action('admin_print_scripts-' . $saved_entries_page, 'form_maker_submissions_scripts');
  }
  
  $blocked_ips_page = add_submenu_page($parent_slug, 'Blocked IPs', 'Blocked IPs', 'manage_options', 'blocked_ips_fm', 'form_maker');
  add_action('admin_print_styles-' . $blocked_ips_page, 'form_maker_manage_styles');
  add_action('admin_print_scripts-' . $blocked_ips_page, 'form_maker_manage_scripts');

  $themes_page = add_submenu_page($parent_slug, 'Themes', 'Themes', 'manage_options', 'themes_fm', 'form_maker');
  add_action('admin_print_styles-' . $themes_page, 'form_maker_manage_styles');
  add_action('admin_print_scripts-' . $themes_page, 'form_maker_manage_scripts');

  $global_options_page = add_submenu_page($parent_slug, 'Global Options', 'Global Options', 'manage_options', 'goptions_fm', 'form_maker');
  add_action('admin_print_styles-' . $global_options_page, 'form_maker_manage_styles');
  add_action('admin_print_scripts-' . $global_options_page, 'form_maker_manage_scripts');

  $licensing_plugins_page = add_submenu_page($parent_slug, 'Pro Version', 'Pro Version', 'manage_options', 'licensing_fm', 'form_maker');

  $uninstall_page = add_submenu_page($parent_slug, 'Uninstall', 'Uninstall', 'manage_options', 'uninstall_fm', 'form_maker');
  add_action('admin_print_styles-' . $uninstall_page, 'form_maker_styles');
  add_action('admin_print_scripts-' . $uninstall_page, 'form_maker_scripts');
}
add_action('admin_menu', 'form_maker_options_panel');

function form_maker() {
  if (function_exists('current_user_can')) {
    if (!current_user_can('manage_options')) {
      die('Access Denied');
    }
  }
  else {
    die('Access Denied');
  }
  require_once(WD_FM_DIR . '/framework/WDW_FM_Library.php');
  $page = WDW_FM_Library::get('page');
  if (($page != '') && (($page == 'manage_fm') || ($page == 'goptions_fm') || ($page == 'submissions_fm') || ($page == 'blocked_ips_fm') || ($page == 'themes_fm') || ($page == 'uninstall_fm') || ($page == 'formmakerwindow') || ($page == 'extensions_fm') || ($page == 'licensing_fm'))) {
    require_once (WD_FM_DIR . '/admin/controllers/FMController' . ucfirst(strtolower($page)) . '.php');
    $controller_class = 'FMController' . ucfirst(strtolower($page));
    $controller = new $controller_class();
    $controller->execute();
  }
}


function fm_extensions() {
  if (function_exists('current_user_can')) {
    if (!current_user_can('manage_options')) {
      die('Access Denied');
    }
  }
  else {
    die('Access Denied');
  }
  require_once(WD_FM_DIR . '/featured/featured.php');
  wp_register_style('fm_featured', WD_FM_URL . '/featured/style.css', array(), WD_FM_VERSION);
  wp_print_styles('fm_featured');
  fm_extensions_page('form-maker');
}

add_action('wp_ajax_get_stats', 'form_maker'); //Show statistics
add_action('wp_ajax_generete_csv', 'form_maker_ajax'); // Export csv.
add_action('wp_ajax_generete_xml', 'form_maker_ajax'); // Export xml.
add_action('wp_ajax_FormMakerPreview', 'form_maker_ajax');
add_action('wp_ajax_formmakerwdcaptcha', 'form_maker_ajax'); // Generete captcha image and save it code in session.
add_action('wp_ajax_nopriv_formmakerwdcaptcha', 'form_maker_ajax'); // Generete captcha image and save it code in session for all users.
add_action('wp_ajax_formmakerwdmathcaptcha', 'form_maker_ajax'); // Generete math captcha image and save it code in session.
add_action('wp_ajax_nopriv_formmakerwdmathcaptcha', 'form_maker_ajax'); // Generete math captcha image and save it code in session for all users.
add_action('wp_ajax_fromeditcountryinpopup', 'form_maker_ajax'); // Open country list.
add_action('wp_ajax_product_option', 'form_maker_ajax'); // Open product options on add paypal field.
add_action('wp_ajax_frommapeditinpopup', 'form_maker_ajax'); // Open map in submissions.
add_action('wp_ajax_fromipinfoinpopup', 'form_maker_ajax'); // Open ip in submissions.
add_action('wp_ajax_show_matrix', 'form_maker_ajax'); // Edit matrix in submissions.
add_action('wp_ajax_FormMakerSubmits', 'form_maker_ajax'); // Open submissions in submissions.
add_action('wp_ajax_FormMakerSQLMapping', 'form_maker_ajax'); // Add/Edit SQLMaping from form options.

add_action('wp_ajax_select_data_from_db', 'form_maker_ajax'); // select data from db.
add_action('wp_ajax_manage_fm', 'form_maker_ajax'); //Show statistics

if ( is_admin() && ( ! defined( 'DOING_AJAX' ) || ! DOING_AJAX ) ) {
	require_once( 'fm_admin_class.php' );
	add_action( 'plugins_loaded', array( 'FM_Admin', 'get_instance' ) );
}

function form_maker_ajax() {
  require_once(WD_FM_DIR . '/framework/WDW_FM_Library.php');
  $page = WDW_FM_Library::get('action');
  if ($page != 'formmakerwdcaptcha' && $page != 'formmakerwdmathcaptcha') {
    if (function_exists('current_user_can')) {
      if (!current_user_can('manage_options')) {
        die('Access Denied');
      }
    }
    else {
      die('Access Denied');
    }
  }
  if ($page != '') {
    require_once (WD_FM_DIR . '/admin/controllers/FMController' . ucfirst($page) . '.php');
    $controller_class = 'FMController' . ucfirst($page);
    $controller = new $controller_class();
    $controller->execute();
  }
}

// Add the Form Maker button.
function form_maker_add_button($buttons) {
  array_push($buttons, "Form_Maker_mce");
  return $buttons;
}

// Register Form Maker button.
function form_maker_register($plugin_array) {
  $url = WD_FM_URL . '/js/form_maker_editor_button.js';
  $plugin_array["Form_Maker_mce"] = $url;
  return $plugin_array;
}

function form_maker_admin_ajax() {
  ?>
  <script>
    var form_maker_admin_ajax = '<?php echo add_query_arg(array('action' => 'formmakerwindow'), admin_url('admin-ajax.php')); ?>';
    var plugin_url = '<?php echo WD_FM_URL; ?>';
    var content_url = '<?php echo content_url() ?>';
    var admin_url = '<?php echo admin_url('admin.php'); ?>';
    var nonce_fm = '<?php echo wp_create_nonce('nonce_fm') ?>';
  </script>
  <?php
}
add_action('admin_head', 'form_maker_admin_ajax');

function fm_output_buffer() {
  ob_start();
}
add_action('init', 'fm_output_buffer');
 
add_shortcode('Form', 'fm_shortcode');

function fm_shortcode($attrs) {
  $fm_settings = get_option('fm_settings');
  $fm_shortcode = isset($fm_settings['fm_shortcode']) ? $fm_settings['fm_shortcode'] : '';
  if ($fm_shortcode) {
    $new_shortcode = '[Form';
    foreach ($attrs as $key => $value) {
      $new_shortcode .= ' ' . $key . '="' . $value . '"';
    }
    $new_shortcode .= ']';
    return $new_shortcode;
  }
  else {
    ob_start();
    FM_front_end_main($attrs, 'embedded');
    return str_replace(array("\r\n", "\n", "\r"), '', ob_get_clean());
  }
}
if (!is_admin() && !in_array($GLOBALS['pagenow'], array('wp-login.php', 'wp-register.php'))) {
	add_action('wp_footer', 'FM_front_end_main');
	add_action('wp_enqueue_scripts', 'form_maker_front_end_scripts');
}
function FM_front_end_main($params = array(), $type = '') {
	if(!isset($params['type'])){
		$form_id =  isset($params['id']) ? (int)$params['id'] : 0;
    wd_form_maker($form_id, $type);
	}

	return;
}

add_shortcode('email_verification', 'fm_email_verification_shortcode');
function fm_email_verification_shortcode() {
	require_once(WD_FM_DIR . '/framework/WDW_FM_Library.php');
	require_once(WD_FM_DIR . '/frontend/controllers/FMControllerVerify_email.php');
  $controller_class = 'FMControllerVerify_email';
  $controller = new $controller_class();
  $controller->execute();
}

function wd_form_maker($id, $type = 'embedded') {
  require_once (WD_FM_DIR . '/frontend/controllers/FMControllerForm_maker.php');
  $controller = new FMControllerForm_maker();
  $form = $controller->execute($id, $type);
  echo $form;
}

function Form_maker_fornt_end_main($content) {
  global $form_maker_generate_action;
  if ($form_maker_generate_action) {
    $pattern = '[\[Form id="([0-9]*)"\]]';
    $count_forms_in_post = preg_match_all($pattern, $content, $matches_form);
    if ($count_forms_in_post) {
      require_once (WD_FM_DIR . '/frontend/controllers/FMControllerForm_maker.php');
      $controller = new FMControllerForm_maker();
      for ($jj = 0; $jj < $count_forms_in_post; $jj++) {
        $padron = $matches_form[0][$jj];
        $replacment = $controller->execute($matches_form[1][$jj]);
        $content = str_replace($padron, $replacment, $content);
      }
    }
  }
  return $content;
}

$fm_settings = get_option('fm_settings');
if(isset($fm_settings['fm_shortcode']) && $fm_settings['fm_shortcode']!= '')
	add_filter('the_content', 'Form_maker_fornt_end_main', 5000);

// Add the Form Maker button to editor.
add_action('wp_ajax_formmakerwindow', 'form_maker_ajax');
add_filter('mce_external_plugins', 'form_maker_register');
add_filter('mce_buttons', 'form_maker_add_button', 0);

// Form Maker Widget.
if (class_exists('WP_Widget')) {
  require_once(WD_FM_DIR . '/admin/controllers/FMControllerWidget.php');
  add_action('widgets_init', create_function('', 'return register_widget("FMControllerWidget");'));
}

// Register fmemailverification post type
add_action('init', 'register_fmemailverification_cpt');
function register_fmemailverification_cpt(){
	$args = array(
	  'public' => true,
	  'label'  => 'FM Email Verification'
	);
	
	register_post_type( 'fmemailverification', $args );
	if(!get_option('fm_emailverification')) {	
		flush_rewrite_rules();
		add_option('fm_emailverification', true);
	}
}

// Activate plugin.
function form_maker_activate() {
	$version = get_option("wd_form_maker_version");
	$new_version = substr_replace(WD_FM_VERSION, '1.', 0, 2);

	global $wpdb;
	if (!$version) {
    add_option("wd_form_maker_version", $new_version, '', 'no');
    if ($wpdb->get_var("SHOW TABLES LIKE '" . $wpdb->prefix . "formmaker'") == $wpdb->prefix . "formmaker") {
      require_once WD_FM_DIR . "/form_maker_update.php";
      $recaptcha_keys = $wpdb->get_row('SELECT `public_key`, `private_key` FROM ' . $wpdb->prefix . 'formmaker WHERE public_key!="" and private_key!=""', ARRAY_A);
      $public_key = isset($recaptcha_keys['public_key']) ? $recaptcha_keys['public_key'] : '';
      $private_key = isset($recaptcha_keys['private_key']) ? $recaptcha_keys['private_key'] : '';
      if (FALSE === $fm_settings = get_option('fm_settings')) {
        add_option('fm_settings', array('public_key' => $public_key, 'private_key' => $private_key, 'csv_delimiter' => ',', 'map_key' => ''));
      }
      form_maker_update_until_mvc();
      form_maker_update_contact();
      form_maker_update('');
    }
    else {
      require_once WD_FM_DIR . "/form_maker_insert.php";
      from_maker_insert();
      $email_verification_post = array(
        'post_title' => 'Email Verification',
        'post_content' => '[email_verification]',
        'post_status' => 'publish',
        'post_author' => 1,
        'post_type' => 'fmemailverification',
      );
      $mail_verification_post_id = wp_insert_post($email_verification_post);

      add_option('fm_settings', array('public_key' => '', 'private_key' => '', 'csv_delimiter' => ',', 'map_key' => ''));
      $wpdb->update($wpdb->prefix . "formmaker", array(
        'mail_verification_post_id' => $mail_verification_post_id,
      ), array('id' => 1), array(
        '%d',
      ), array('%d'));
    }
  }
	elseif (version_compare($version, $new_version, '<')) {
    $version = substr_replace($version, '1.', 0, 2);
		require_once WD_FM_DIR . "/form_maker_update.php";
		$mail_verification_post_ids = $wpdb->get_results($wpdb->prepare('SELECT mail_verification_post_id FROM ' . $wpdb->prefix . 'formmaker WHERE mail_verification_post_id!="%d"',0));
		if($mail_verification_post_ids)
			foreach($mail_verification_post_ids as $mail_verification_post_id) {
				 $update_email_ver_post_type = array(
				  'ID'           => (int)$mail_verification_post_id->mail_verification_post_id,
				  'post_type'   => 'fmemailverification',
				);

				wp_update_post( $update_email_ver_post_type ); 
			}
		form_maker_update($version);
		update_option("wd_form_maker_version", $new_version);
		
		$recaptcha_keys = $wpdb->get_row('SELECT `public_key`, `private_key` FROM ' . $wpdb->prefix . 'formmaker WHERE public_key!="" and private_key!=""', ARRAY_A);
		$public_key = isset($recaptcha_keys['public_key']) ? $recaptcha_keys['public_key'] : '';
		$private_key = isset($recaptcha_keys['private_key']) ? $recaptcha_keys['private_key'] : '';
		if (FALSE === $fm_settings = get_option('fm_settings')) {
			add_option('fm_settings', array('public_key' => $public_key, 'private_key' => $private_key, 'csv_delimiter' => ',', 'map_key' => ''));	
		}
	}
}
register_activation_hook(__FILE__, 'form_maker_activate');

if ((!isset($_GET['action']) || $_GET['action'] != 'deactivate') && (!isset($_GET['page']) || $_GET['page'] != 'uninstall_fm')) {
  add_action('admin_init', 'form_maker_activate');
}

// Form Maker manage page styles.
function form_maker_manage_styles() {
  wp_admin_css('thickbox');
  wp_enqueue_style('form_maker_tables', WD_FM_URL . '/css/form_maker_tables.css', array(), WD_FM_VERSION);
  wp_enqueue_style('form_maker_first', WD_FM_URL . '/css/form_maker_first.css', array(), WD_FM_VERSION);
  wp_enqueue_style('form_maker_calendar-jos', WD_FM_URL . '/css/calendar-jos.css', array(), WD_FM_VERSION);
  wp_enqueue_style('phone_field_css', WD_FM_URL . '/css/intlTelInput.css', array(), WD_FM_VERSION);
  wp_enqueue_style('jquery-ui', WD_FM_URL . '/css/jquery-ui-1.10.3.custom.css', array(), WD_FM_VERSION);
  wp_enqueue_style('jquery-ui-spinner', WD_FM_URL . '/css/jquery-ui-spinner.css', array(), WD_FM_VERSION);
  wp_enqueue_style('form_maker_style', WD_FM_URL . '/css/style.css', array(), WD_FM_VERSION);
  wp_enqueue_style('form_maker_codemirror', WD_FM_URL . '/css/codemirror.css', array(), WD_FM_VERSION);
  wp_enqueue_style('form_maker_layout', WD_FM_URL . '/css/form_maker_layout.css', array(), WD_FM_VERSION);
  wp_enqueue_style('fm-bootstrap', WD_FM_URL . '/css/fm-bootstrap.css', array(), WD_FM_VERSION);
  wp_enqueue_style('fm-colorpicker', WD_FM_URL . '/css/spectrum.css', array(), WD_FM_VERSION);
  wp_enqueue_style('fm-font-awesome', WD_FM_URL . '/css/frontend/font-awesome/font-awesome.css', array(), WD_FM_VERSION);
}

// Form Maker manage page scripts.
function form_maker_manage_scripts() {
  wp_enqueue_script('thickbox');
  $fm_settings = get_option('fm_settings');
  $map_key = isset($fm_settings['map_key']) ? $fm_settings['map_key'] : '';

  wp_enqueue_script('jquery');
  wp_enqueue_script('jquery-ui-sortable');
  wp_enqueue_script('jquery-ui-widget');
  wp_enqueue_script('jquery-ui-slider');
  wp_enqueue_script('jquery-ui-spinner');
  wp_enqueue_script('jquery-ui-datepicker');
  wp_enqueue_media();

  // wp_enqueue_script('mootools', WD_FM_URL . '/js/mootools.js', array(), '1.12');
  if($_GET['page'] == 'manage_fm'){
    wp_enqueue_script('google-maps', 'https://maps.google.com/maps/api/js?v=3.exp&key='.$map_key);
  }
  wp_enqueue_script('gmap_form', WD_FM_URL . '/js/if_gmap_back_end.js', array(), WD_FM_VERSION);
  wp_enqueue_script('phone_field', WD_FM_URL . '/js/intlTelInput.js', array(), '11.0.0');

  wp_enqueue_script('form_maker_admin', WD_FM_URL . '/js/form_maker_admin.js', array(), WD_FM_VERSION);
  wp_enqueue_script('form_maker_manage', WD_FM_URL . '/js/form_maker_manage.js', array(), WD_FM_VERSION);
  wp_enqueue_script('formmaker_div', WD_FM_URL . '/js/formmaker_div.js', array(), WD_FM_VERSION);

  wp_enqueue_script('form_maker_codemirror', WD_FM_URL . '/js/layout/codemirror.js', array(), '2.3');
  wp_enqueue_script('form_maker_clike', WD_FM_URL . '/js/layout/clike.js', array(), '1.0.0');
  wp_enqueue_script('form_maker_formatting', WD_FM_URL . '/js/layout/formatting.js', array(), '1.0.0');
  wp_enqueue_script('form_maker_css', WD_FM_URL . '/js/layout/css.js', array(), '1.0.0');
  wp_enqueue_script('form_maker_javascript', WD_FM_URL . '/js/layout/javascript.js', array(), '1.0.0');
  wp_enqueue_script('form_maker_xml', WD_FM_URL . '/js/layout/xml.js', array(), '1.0.0');
  wp_enqueue_script('form_maker_php', WD_FM_URL . '/js/layout/php.js', array(), '1.0.0');
  wp_enqueue_script('form_maker_htmlmixed', WD_FM_URL . '/js/layout/htmlmixed.js', array(), '1.0.0');

  wp_enqueue_script('Calendar', WD_FM_URL . '/js/calendar/calendar.js', array(), '1.0');
  wp_enqueue_script('calendar_function', WD_FM_URL . '/js/calendar/calendar_function.js', array(), WD_FM_VERSION);

  // wp_enqueue_script('form_maker_calendar_setup', WD_FM_URL . '/js/calendar/calendar-setup.js');
  wp_enqueue_script('fm-colorpicker', WD_FM_URL . '/js/spectrum.js', array(), WD_FM_VERSION);
}

// Form Maker submissions page styles.
function form_maker_submissions_styles() {
  wp_admin_css('thickbox');
  wp_enqueue_style('form_maker_tables', WD_FM_URL . '/css/form_maker_tables.css', array(), WD_FM_VERSION);
  wp_enqueue_style('form_maker_calendar-jos', WD_FM_URL . '/css/calendar-jos.css', array(), WD_FM_VERSION);

  wp_enqueue_style('jquery-ui', WD_FM_URL . '/css/jquery-ui-1.10.3.custom.css', array(), '1.10.3');
  wp_enqueue_style('jquery-ui-spinner', WD_FM_URL . '/css/jquery-ui-spinner.css', array(), '1.10.3');
  wp_enqueue_style('form_maker_style', WD_FM_URL . '/css/style.css', array(), WD_FM_VERSION);
}
// Form Maker submissions page scripts.
function form_maker_submissions_scripts() {
  wp_enqueue_script('thickbox');
  wp_enqueue_script('jquery');
  wp_enqueue_script( 'jquery-ui-progressbar' ); 
  wp_enqueue_script('jquery-ui-sortable');
  wp_enqueue_script('jquery-ui-widget');
  wp_enqueue_script('jquery-ui-slider');
  wp_enqueue_script('jquery-ui-spinner');
  wp_enqueue_script('jquery-ui-mouse');
  wp_enqueue_script('jquery-ui-core');
  wp_enqueue_script('jquery-ui-datepicker');

  // wp_enqueue_script('mootools', WD_FM_URL . '/js/mootools.js', array(), '1.12');

  wp_enqueue_script('form_maker_admin', WD_FM_URL . '/js/form_maker_admin.js', array(), WD_FM_VERSION);
  wp_enqueue_script('form_maker_manage', WD_FM_URL . '/js/form_maker_manage.js', array(), WD_FM_VERSION);
  wp_enqueue_script('form_maker_submissions', WD_FM_URL . '/js/form_maker_submissions.js', array(), WD_FM_VERSION);

  wp_enqueue_script('main_div_front_end', WD_FM_URL . '/js/main_div_front_end.js', array(), WD_FM_VERSION);

  wp_enqueue_script('Calendar', WD_FM_URL . '/js/calendar/calendar.js', array(), '1.0');
  wp_enqueue_script('calendar_function', WD_FM_URL . '/js/calendar/calendar_function.js', array(), WD_FM_VERSION);

  // wp_enqueue_script('form_maker_calendar_setup', WD_FM_URL . '/js/calendar/calendar-setup.js');

  wp_localize_script('main_div_front_end', 'fm_objectL10n', array(
    'plugin_url' => WD_FM_URL
  ));
}

function form_maker_styles() {
  wp_enqueue_style('form_maker_tables', WD_FM_URL . '/css/form_maker_tables.css', array(), WD_FM_VERSION);
  wp_enqueue_style('fm_deactivate-css',  WD_FM_URL . '/wd/assets/css/deactivate_popup.css', array(), WD_FM_VERSION);
}
function form_maker_scripts() {
  wp_enqueue_script('form_maker_admin', WD_FM_URL . '/js/form_maker_admin.js', array(), WD_FM_VERSION);
  wp_enqueue_script('fm-deactivate-popup', WD_FM_URL . '/wd/assets/js/deactivate_popup.js', array(), WD_FM_VERSION, true );
  $admin_data = wp_get_current_user();
  wp_localize_script( 'fm-deactivate-popup', 'fmWDDeactivateVars', array(
    "prefix" => "fm" ,
    "deactivate_class" => 'fm_deactivate_link',
    "email" => $admin_data->data->user_email,
    "plugin_wd_url" => "https://web-dorado.com/products/wordpress-form.html",
  ));
}

$form_maker_generate_action = 0;
function form_maker_generate_action() {
  global $form_maker_generate_action;
  $form_maker_generate_action = 1;
}
add_filter('wp_head', 'form_maker_generate_action', 10000);

function form_maker_front_end_scripts() {
  wp_enqueue_script('jquery');
  wp_enqueue_script('jquery-ui-widget');
  wp_enqueue_script('jquery-ui-slider');
  wp_enqueue_script('jquery-ui-spinner');
  wp_enqueue_script('jquery-effects-shake');
  wp_enqueue_script('jquery-ui-datepicker');

  wp_register_style('fm-jquery-ui', WD_FM_URL . '/css/jquery-ui-1.10.3.custom.css', array(), WD_FM_VERSION);
  wp_enqueue_style('fm-jquery-ui');
  wp_register_style('fm-jquery-ui-spinner', WD_FM_URL . '/css/jquery-ui-spinner.css', array(), WD_FM_VERSION);
  wp_enqueue_style('fm-jquery-ui-spinner');

  wp_register_script('gmap_form', WD_FM_URL . '/js/if_gmap_front_end.js', array(), WD_FM_VERSION);
  wp_enqueue_script('gmap_form');
  wp_register_script('phone_field', WD_FM_URL . '/js/intlTelInput.js', array(), WD_FM_VERSION);
  wp_enqueue_script('phone_field');

  wp_register_script('fm-Calendar', WD_FM_URL . '/js/calendar/calendar.js', array(), WD_FM_VERSION);
  wp_enqueue_script('fm-Calendar');
  wp_register_script('calendar_function', WD_FM_URL . '/js/calendar/calendar_function.js', array(), WD_FM_VERSION);
  wp_enqueue_script('calendar_function');

  wp_register_style('form_maker_calendar-jos', WD_FM_URL . '/css/calendar-jos.css', array(), WD_FM_VERSION);
  wp_enqueue_style('form_maker_calendar-jos');
  wp_register_style('phone_field_css', WD_FM_URL . '/css/intlTelInput.css', array(), WD_FM_VERSION);
  wp_enqueue_style('phone_field_css');
  wp_register_style('form_maker_frontend', WD_FM_URL . '/css/form_maker_frontend.css', array(), WD_FM_VERSION);
  wp_enqueue_style('form_maker_frontend');

  wp_register_script('main_div_front_end', WD_FM_URL . '/js/main_div_front_end.js', array(), WD_FM_VERSION);
  wp_enqueue_script('main_div_front_end');
  wp_localize_script('main_div_front_end', 'fm_objectL10n', array(
    'plugin_url' => WD_FM_URL,
    'fm_file_type_error' => addslashes(__('Can not upload this type of file', 'form_maker')),
    'fm_field_is_required' => addslashes(__('Field is required', 'form_maker')),
    'fm_min_max_check_1' => addslashes((__('The ', 'form_maker'))),
    'fm_min_max_check_2' => addslashes((__(' value must be between ', 'form_maker'))),
    'fm_spinner_check' => addslashes((__('Value must be between ', 'form_maker'))),
  ));

  require_once(WD_FM_DIR . '/framework/WDW_FM_Library.php');
	$google_fonts = WDW_FM_Library::get_google_fonts();
	$fonts = implode("|", str_replace(' ', '+', $google_fonts));
	wp_register_style('fm_googlefonts', 'https://fonts.googleapis.com/css?family=' . $fonts . '&subset=greek,latin,greek-ext,vietnamese,cyrillic-ext,latin-ext,cyrillic', null, null);
	wp_enqueue_style('fm_googlefonts');

  wp_register_style('fm-animate', WD_FM_URL . '/css/frontend/fm-animate.css', array(), WD_FM_VERSION);
  wp_enqueue_style('fm-animate');
  wp_register_style('fm-font-awesome', WD_FM_URL . '/css/frontend/font-awesome/font-awesome.css', array(), WD_FM_VERSION);
  wp_enqueue_style('fm-font-awesome');
}
// add_action('wp_enqueue_scripts', 'form_maker_front_end_scripts');

// Languages localization.
function form_maker_language_load() {
  load_plugin_textdomain('form_maker', FALSE, basename(dirname(__FILE__)) . '/languages');
}
add_action('plugins_loaded', 'form_maker_language_load');

function fm_topic() {
  $page = isset($_GET['page']) ? $_GET['page'] : '';
  $task = isset($_REQUEST['task']) ? $_REQUEST['task'] : '';
  $user_guide_link = 'https://web-dorado.com/wordpress-form-maker/';
  $support_forum_link = 'https://wordpress.org/support/plugin/form-maker';
  $pro_icon = WD_FM_URL . '/images/wd_logo.png';
  $pro_link = 'https://web-dorado.com/files/fromFormMaker.php';
  $support_icon = WD_FM_URL . '/images/support.png';
  $prefix = 'form_maker';
  $is_free = TRUE;
  switch ($page) {
    case 'blocked_ips_fm': {
      $help_text = 'block IPs';
      $user_guide_link .= 'blocking-ips.html';
      break;
    }
    case 'goptions_fm': {
      $help_text = 'edit form settings';
      $user_guide_link .= 'configuring-form-options.html';
      break;
    }
    case 'licensing_fm': {
      $help_text = '';
      $user_guide_link .= '';
      break;
    }
    case 'manage_fm': {
      switch ($task) {
        case 'edit':
        case 'edit_old': {
          $help_text = 'add fields to your form';
          $user_guide_link .= 'description-of-form-fields.html';
          break;
        }
        case 'form_options':
        case 'form_options_old': {
          $help_text = 'edit form options';
          $user_guide_link .= 'configuring-form-options.html';
          break;
        }
        default: {
          $help_text = 'create, edit forms';
          $user_guide_link .= 'creating-form.html';
        }
      }
      break;
    }
    case 'submissions_fm': {
      $help_text = 'view and manage form submissions';
      $user_guide_link .= 'managing-submissions.html';
      break;
    }
    case 'themes_fm': {
      $help_text = 'create, edit form themes';
      $user_guide_link .= 'creating-form.html';
      break;
    }
    default: {
      return '';
    }
  }
  ob_start();
  ?>
  <style>
    .wd_topic {
      background-color: #ffffff;
      border: none;
      box-sizing: border-box;
      clear: both;
      color: #6e7990;
      font-size: 14px;
      font-weight: bold;
      line-height: 44px;
      padding: 0 0 0 15px;
      vertical-align: middle;
      width: 98%;
    }
    .wd_topic .wd_help_topic {
      float: left;
    }
    .wd_topic .wd_help_topic a {
      color: #0073aa;
    }
    .wd_topic .wd_help_topic a:hover {
      color: #00A0D2;
    }
    .wd_topic .wd_support {
      float: right;
      margin: 0 10px;
    }
    .wd_topic .wd_support img {
      vertical-align: middle;
    }
    .wd_topic .wd_support a {
      text-decoration: none;
      color: #6E7990;
    }
    .wd_topic .wd_pro {
      float: right;
      padding: 0;
    }
    .wd_topic .wd_pro a {
      border: none;
      box-shadow: none !important;
      text-decoration: none;
    }
    .wd_topic .wd_pro img {
      border: none;
      display: inline-block;
      vertical-align: middle;
    }
    .wd_topic .wd_pro a,
    .wd_topic .wd_pro a:active,
    .wd_topic .wd_pro a:visited,
    .wd_topic .wd_pro a:hover {
      background-color: #D8D8D8;
      color: #175c8b;
      display: inline-block;
      font-size: 11px;
      font-weight: bold;
      padding: 0 10px;
      vertical-align: middle;
    }
  </style>
  <div class="update-nag wd_topic">
    <?php
    if ($help_text) {
      ?>
      <span class="wd_help_topic">
      <?php echo sprintf(__('This section allows you to %s.', $prefix), $help_text); ?>
        <a target="_blank" href="<?php echo $user_guide_link; ?>">
        <?php _e('Read More in User Manual', $prefix); ?>
      </a>
    </span>
      <?php
    }
    if ($is_free) {
      $text = strtoupper(__('Upgrade to paid version', $prefix));
      ?>
      <div class="wd_pro">
        <a target="_blank" href="<?php echo $pro_link; ?>">
          <img alt="web-dorado.com" title="<?php echo $text; ?>" src="<?php echo $pro_icon; ?>" />
          <span><?php echo $text; ?></span>
        </a>
      </div>
      <?php
    }
    if (FALSE) {
      ?>
      <span class="wd_support">
      <a target="_blank" href="<?php echo $support_forum_link; ?>">
        <img src="<?php echo $support_icon; ?>" />
        <?php _e('Support Forum', $prefix); ?>
      </a>
    </span>
      <?php
    }
    ?>
  </div>
  <?php
  echo ob_get_clean();
}

add_action('admin_notices', 'fm_topic', 11);

function fm_overview() {
  if (is_admin() && !isset($_REQUEST['ajax'])) {
    if (!class_exists("DoradoWeb")) {
      require_once(WD_FM_DIR . '/wd/start.php');
    }
    global $fm_options;
    $fm_options = array(
      "prefix" => "fm",
      "wd_plugin_id" => 31,
      "plugin_title" => "Form Maker",
      "plugin_wordpress_slug" => "form-maker",
      "plugin_dir" => WD_FM_DIR,
      "plugin_main_file" => __FILE__,
      "description" => __('Form Maker plugin is a modern and advanced tool for easy and fast creating of a WordPress Form. The backend interface is intuitive and user friendly which allows users far from scripting and programming to create WordPress Forms.', 'form_maker'),
      // from web-dorado.com
      "plugin_features" => array(
        0 => array(
          "title" => __("Easy to Use", "form_maker"),
          "description" => __("This responsive form maker plugin is one of the most easy-to-use form builder solutions available on the market. Simple, yet powerful plugin allows you to quickly and easily build any complex forms.", "form_maker"),
        ),
        1 => array(
          "title" => __("Customizable Fields", "form_maker"),
          "description" => __("All the fields of Form Maker plugin are highly customizable, which allows you to change almost every detail in the form and make it look exactly like you want it to be.", "form_maker"),
        ),
        2 => array(
          "title" => __("Submissions", "form_maker"),
          "description" => __("You can view the submissions for each form you have. The plugin allows to view submissions statistics, filter submission data and export in csv or xml formats.", "form_maker"),
        ),
        3 => array(
          "title" => __("Multi-Page Forms", "form_maker"),
          "description" => __("With the form builder plugin you can create muilti-page forms. Simply use the page break field to separate the pages in your forms.", "form_maker"),
        ),
        4 => array(
          "title" => __("Themes", "form_maker"),
          "description" => __("The WordPress Form Maker plugin comes with a wide range of customizable themes. You can choose from a list of existing themes or simply create the one that better fits your brand and website.", "form_maker"),
        )
      ),
      // user guide from web-dorado.com
      "user_guide" => array(
        0 => array(
          "main_title" => __("Installing", "form_maker"),
          "url" => "https://web-dorado.com/wordpress-form-maker/installing.html",
          "titles" => array()
        ),
        1 => array(
          "main_title" => __("Creating a new Form", "form_maker"),
          "url" => "https://web-dorado.com/wordpress-form-maker/creating-form.html",
          "titles" => array()
        ),
        2 => array(
          "main_title" => __("Configuring Form Options", "form_maker"),
          "url" => "https://web-dorado.com/wordpress-form-maker/configuring-form-options.html",
          "titles" => array()
        ),
        3 => array(
          "main_title" => __("Description of The Form Fields", "form_maker"),
          "url" => "https://web-dorado.com/wordpress-form-maker/description-of-form-fields.html",
          "titles" => array(
            array(
              "title" => __("Selecting Options from Database", "form_maker"),
              "url" => "https://web-dorado.com/wordpress-form-maker/description-of-form-fields/selecting-options-from-database.html",
            ),
          )
        ),
        4 => array(
          "main_title" => __("Publishing the Created Form", "form_maker"),
          "url" => "https://web-dorado.com/wordpress-form-maker/publishing-form.html",
          "titles" => array()
        ),
        5 => array(
          "main_title" => __("Blocking IPs", "form_maker"),
          "url" => "https://web-dorado.com/wordpress-form-maker/blocking-ips.html",
          "titles" => array()
        ),
        6 => array(
          "main_title" => __("Managing Submissions", "form_maker"),
          "url" => "https://web-dorado.com/wordpress-form-maker/managing-submissions.html",
          "titles" => array()
        ),
        7 => array(
          "main_title" => __("Publishing Submissions", "form_maker"),
          "url" => "https://web-dorado.com/wordpress-form-maker/publishing-submissions.html",
          "titles" => array()
        ),
      ),
      "video_youtube_id" => "tN3_c6MhqFk",  // e.g. https://www.youtube.com/watch?v=acaexefeP7o youtube id is the acaexefeP7o
      "plugin_wd_url" => "https://web-dorado.com/products/wordpress-form.html",
      "plugin_wd_demo_link" => "http://wpdemo.web-dorado.com",
      "plugin_wd_addons_link" => "https://web-dorado.com/products/wordpress-form/add-ons.html",
      "after_subscribe" => admin_url('admin.php?page=overview_fm'), // this can be plagin overview page or set up page
      "plugin_wizard_link" => '',
      "plugin_menu_title" => "Form Maker",
      "plugin_menu_icon" => WD_FM_URL . '/images/FormMakerLogo-16.png',
      "deactivate" => true,
      "subscribe" => true,
      "custom_post" => 'manage_fm',
      "menu_position" => null,
    );

    dorado_web_init($fm_options);
  }
}
add_action('init', 'fm_overview');
