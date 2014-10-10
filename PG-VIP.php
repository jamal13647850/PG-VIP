<?php
/*
  Plugin Name:PG-VIP
  Plugin URI:http://www.pgsavis.com
  Description: عضویت ویژه کاربران
  Version: 1.0
  Author:سید جمال قاسمی
  Author URI:http://www.pgsavis.com
  License:pay
 */
load_plugin_textdomain( 'PG-VIP', false, dirname( plugin_basename( __FILE__ ) ) . '/langs/' );

////////////////////////////////////////////////////
/*
 add_action('login_form','redirect_wp_admin');

function redirect_wp_admin(){
$redirect_to = $_SERVER['REQUEST_URI'];

if(count($_REQUEST)> 0 && array_key_exists('redirect_to', $_REQUEST)){
$redirect_to = $_REQUEST['redirect_to'];
$check_wp_admin = stristr($redirect_to, 'wp-admin');
if($check_wp_admin){
wp_safe_redirect( '404.php' );
}
}
} */
 ///////////////////////////////////////
defined( 'ABSPATH' ) || exit;

$upload_dir = wp_upload_dir();
define( 'pgv_upload_DIR', $upload_dir['basedir'].'/pfiles/' ); 
define( 'pgv_DIR', plugin_dir_path( __FILE__ ) );
define( 'pgv_URL', plugin_dir_url( __FILE__ ) );
define( 'pgv_INC_DIR', trailingslashit( pgv_DIR . 'inc' ) );
define( 'pgv_INC_URL', trailingslashit( pgv_URL . 'inc' ) );
define( 'pgv_CSS_URL', trailingslashit( pgv_URL . 'css' ) );
define( 'pgv_JS_URL', trailingslashit( pgv_URL . 'js' ) );
define( 'pgv_IMG_URL', trailingslashit( pgv_URL . 'img' ) );
define('ENCRYPTION_KEY', '!@#$%^&*');
	
	include_once(pgv_INC_DIR.'PGV_payline.class.php');
	$PGV_payline = new PGV_Payline;
	$PGV_payline->api="d5502-92da9-b06dd-11aef-abbf3c780c750270ea7353d88748";
require_once pgv_INC_DIR.'user_functions.php';
require_once pgv_INC_DIR.'Consts.php';
require_once pgv_INC_DIR.'ajax.php';
require_once pgv_INC_DIR.'shortcodes.php';
require_once pgv_INC_DIR.'customregister.php';
if(is_admin()  ){
	if (!function_exists("jdate")){
    //	require_once pgv_INC_DIR.'jdf.php';
	}
    require_once pgv_INC_DIR.'admin_functions.php';
    require_once pgv_INC_DIR.'pages.php';
	require_once pgv_INC_DIR.'ecrypt.class.php';
	function jahan_register(){
	wp_enqueue_media();
    wp_register_script('jahan-script',plugin_dir_url(__FILE__) . 'includes/script/script.js',array('jquery'));
    wp_enqueue_script('jahan-script');
    wp_localize_script('jahan-script', 'jahanajax', array('ajaxurl' => admin_url('admin-ajax.php', (is_ssl() ? 'https' : 'http'))));
	wp_register_style('pgv-style',plugin_dir_url(__FILE__) . 'includes/css/style.css');
    wp_enqueue_style('pgv-style');

}
add_action('init', 'jahan_register');
	global $PGVDM;
if (PGVIsOk($PGVDM)):
    
    add_action('admin_menu','PGVinit_message_admin_pages');
endif;
}

register_activation_hook(__FILE__ ,'pgv_activation');
function pgv_activation(){
	global $pgv_plugin_options;
	foreach ($pgv_plugin_options as $optins):
		add_option($optins["id"],$optins["std"]);
	endforeach;
   global $table_prefix;

$pgvmember_table='CREATE TABLE IF NOT EXISTS `'.$table_prefix.'pgv_members` (
  `ID` int(10) NOT NULL AUTO_INCREMENT,
  `user_ID` int(10) NOT NULL,
  `start_date` timestamp NOT NULL,
  `expire_date` int(11) NOT NULL,
  `phone` varchar(16) DEFAULT NULL,
  PRIMARY KEY (`ID`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1' ;
$pgvfiles_table='CREATE TABLE IF NOT EXISTS `'.$table_prefix.'pgv_files` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `file_name` text NOT NULL,
  `encypted_name` text NOT NULL,
  `file_size` int(20) NOT NULL,
  `file_type` varchar(6) NOT NULL,
  `upload_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`ID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1' ;
$pgvpayments_table='CREATE TABLE IF NOT EXISTS `'.$table_prefix.'pgv_payments` (
  `ID` int(10) NOT NULL AUTO_INCREMENT,
  `user_ID` int(10) NOT NULL,
  `paymenter_ip` varchar(50) NOT NULL,
  `payment_date` timestamp NOT NULL,
  `payment_cost` int(15) NOT NULL,
  `refNumber` int(15) NOT NULL,
  `payment_agancy` varchar(20) DEFAULT NULL,
  PRIMARY KEY (`ID`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1' ;

    require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
 
    dbDelta($pgvmember_table);
	dbDelta($pgvfiles_table);
	
	$pageid=PGV_CreatePageOrPost("page","[jahanpay]",0,"پرداخت عضویت ویژه");

		add_option("pgv_paypage",$pageid);

    
}
register_deactivation_hook(__FILE__ ,'pgv_deactivation');
function pgv_deactivation(){
}

?>