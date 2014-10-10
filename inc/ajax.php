<?php
add_action('wp_ajax_payline_get_price','payline_get_price');
function payline_get_price(){
    $vip_id=$_POST['cid'];
   	switch($vip_id) {
    	case "30":		
        	die( get_option('PG-V_One_Month',5000) );
        	break;
    	case "90":
        	die( get_option('PG-V_Three_Months',5000) );
			break;
    	case "180":
        	die( get_option('PG-V_Six_Months',5000) );
			break;					
    	case "360":
			die( get_option('PG-V_Twelve_Months',5000) );
			break;					
    	default:		
	} 
}