<?php
function PGVinit_message_admin_pages(){
	// if ( current_user_can('read') && !current_user_can('manage_network')) {
    $pgv_admin_hook=add_menu_page(__('VIP','PG-VIP'),__('VIP','PG-VIP'),'manage_options','pgv_admin','pgv_admin_dashboard',pgv_IMG_URL.'menu.png');
	
    $pgv_mainpage_hook=  add_submenu_page('pgv_admin', __('Main Page','PG-VIP'), __('Main Page','PG-VIP'), 
            'manage_options', 'pgv_admin');
    
    $pgv_vipusers_hook= add_submenu_page('pgv_admin', __('VIP Users','PG-VIP'), __('VIP Users','PG-VIP'),
            'manage_options','pgv_vipusers','pgv_vipusers_page');
    
    $pgv_protectedfiles_hook= add_submenu_page('pgv_admin', __('Protected Files','PG-VIP'), __('Protected Files','PG-VIP'),
            'manage_options','pgv_protectedfiles','pgv_protectedfiles_page');
	
	$pgv_paymentslist_hook= add_submenu_page('pgv_admin', __('Payments List','PG-VIP'), __('Payments List','PG-VIP'),
            'manage_options','pgv_paymentslist','pgv_paymentslist');
    
	$pgv_settings_hook= add_submenu_page('pgv_admin', __('Settings','PG-VIP'), __('Settings','PG-VIP'),
            'manage_options','pgv_Settings','pgv_Settings_page');
	
	$pgv_About_hook= add_submenu_page('pgv_admin', __('About Plugin','PG-VIP'), __('About plugin','PG-VIP'),
            'manage_options','pgv_About','pgv_About_page');
	
	if (PGVget_user_role()=='PG_Ads_User'):
		$pgv_member_hook=add_menu_page(__('Ads','PG-VIP'),__('Ads','PG-VIP'),'read','pgv_member','pgv_member_dashboard',pgv_IMG_URL.'menu.png');
		$pgv_About_hook= add_submenu_page('pgv_member', __('About Plugin','PG-VIP'), __('About Plugin','PG-VIP'),
            'manage_options','pgv_About','pgv_About_page');
	endif;
	// }
    add_action("load-$pgv_admin_hook",'PGV_Register_Script');
	add_action("load-$pgv_mainpage_hook",'PGV_Register_Script');
	add_action("load-$pgv_vipusers_hook",'PGV_Register_Script');
	add_action("load-$pgv_protectedfiles_hook",'PGV_Register_Script');
	add_action("load-$pgv_paymentslist_hook",'PGV_Register_Script');
	add_action("load-$pgv_settings_hook",'PGV_Register_Script');
	add_action("load-$pgv_About_hook",'PGV_Register_Script');
}


function PGV_Register_Script(){
	wp_enqueue_media();
    wp_register_script('pgvin-script',pgv_JS_URL.'scripts.js',array('jquery'));
    wp_enqueue_script('pgvin-script');
    wp_localize_script('pgvin-script', 'pgvajax', array('ajaxurl' => admin_url('admin-ajax.php', (is_ssl() ? 'https' : 'http'))));
    wp_register_style('pgvin-style',pgv_CSS_URL.'pstyle.css');
    wp_enqueue_style('pgvin-style');
}
function PGVget_user_role() { // returns current user's role
	global $current_user;
	$user_roles = $current_user->roles;
	$user_role = array_shift($user_roles);
	return $user_role; // return translate_user_role( $user_role );
}

function bytesToSize($bytes, $precision = 2){  
    $kilobyte = 1024;
    $megabyte = $kilobyte * 1024;
    $gigabyte = $megabyte * 1024;
    $terabyte = $gigabyte * 1024;
   
    if (($bytes >= 0) && ($bytes < $kilobyte)) {
        return $bytes . ' B';
 
    } elseif (($bytes >= $kilobyte) && ($bytes < $megabyte)) {
        return round($bytes / $kilobyte, $precision) . ' KB';
 
    } elseif (($bytes >= $megabyte) && ($bytes < $gigabyte)) {
        return round($bytes / $megabyte, $precision) . ' MB';
 
    } elseif (($bytes >= $gigabyte) && ($bytes < $terabyte)) {
        return round($bytes / $gigabyte, $precision) . ' GB';
 
    } elseif ($bytes >= $terabyte) {
        return round($bytes / $terabyte, $precision) . ' TB';
    } else {
        return $bytes . ' B';
    }
}

function AddFilesToDbase($FilesData){
	
	    global $wpdb;
        $sql="INSERT INTO {$wpdb->prefix}pgv_files (file_name,encypted_name,file_size,file_type,upload_date)
        VALUES ('{$FilesData['file_name']}','{$FilesData['encypted_name']}',{$FilesData['file_size']},'{$FilesData['file_type']}'
		,'{$FilesData['upload_date']}') ";     
		echo $sql;
	        $wpdb->query($sql);
}

function encrypt($pure_string, $encryption_key) {
    $iv_size = mcrypt_get_iv_size(MCRYPT_BLOWFISH, MCRYPT_MODE_ECB);
    $iv = mcrypt_create_iv($iv_size, MCRYPT_RAND);
    $encrypted_string = mcrypt_encrypt(MCRYPT_BLOWFISH, $encryption_key, utf8_encode($pure_string), MCRYPT_MODE_ECB, $iv);
    return $encrypted_string;
}

function decrypt($encrypted_string, $encryption_key) {
    $iv_size = mcrypt_get_iv_size(MCRYPT_BLOWFISH, MCRYPT_MODE_ECB);
    $iv = mcrypt_create_iv($iv_size, MCRYPT_RAND);
    $decrypted_string = mcrypt_decrypt(MCRYPT_BLOWFISH, $encryption_key, $encrypted_string, MCRYPT_MODE_ECB, $iv);
    return $decrypted_string;
}
function PGV_CreatePageOrPost($type,$content,$parent,$title){
	global $user_ID;
	$page['post_type']    = $type; //post or page
	$page['post_content'] = $content;//'Put your page content here'
	$page['post_parent']  = $parent;
	$page['post_author']  = $user_ID;
	$page['post_status']  = 'publish';
	$page['post_title']   = $title;//'Your Page Title';
	$page = apply_filters('yourplugin_add_new_page', $page, 'teams');
	$pageid = wp_insert_post ($page);
	return $pageid;	
}
