<?php
function PGVTimeStampToShamsi($date){
	return jdate("y-m-d",$date);
}
function PGVSendTimeStamp($date){
	date_default_timezone_set('Asia/Tehran');
	$ndate = new DateTime($date);
	return $ndate->getTimestamp();
}
function PGVgetcurrentdate(){
	date_default_timezone_set('Asia/Tehran');
	return $date=strftime("%Y-%m-%d %H:%M:%S",time());
}
function PGVRemainingdays($savedate,$CountOfShow){
	date_default_timezone_set('Asia/Tehran');
	$expiredate=$savedate+($CountOfShow*24*60*60);
    $datediff = $expiredate-$savedate;
    $Rdays= floor($datediff/(60*60*24));
	$Rtime=strftime("%H:%M:%S",$datediff/(60*60*24));
	return array("days"=>$Rdays,"times"=>$Rtime);
}

function PGVUpdateField($FeildName,$FeildValue,$ID){
	    global $wpdb;
		$sql="UPDATE {$wpdb->prefix}pgv_ads SET `".$FeildName."` = ".$FeildValue." WHERE {$wpdb->prefix}pgv_ads.id ="
		.$ID;
		$output = "<script>console.log( 'Debug Objects: " . $sql . "' );</script>";
		echo  $output;
	    $res=$wpdb->query($sql);
		return $res;	
}

function PGVGetArchivedAds(){
	global $wpdb;
	$myads=$wpdb->get_results("SELECT * FROM {$wpdb->prefix}pgv_ads WHERE status =". 3);
	return $myads;	
}

	



function PGVUpdateCredit($TypeOfContract,$CreditField,$AdsID){
	switch ($TypeOfContract) {
    	case 1:
			PGVUpdateField("showed",$CreditField+1,$AdsID);
        	break;
    	case 2:
			PGVUpdateField("clicked",$CreditField+1,$AdsID);
        	break;
	}
}
	function PGVregister_scripts3(){
    	wp_register_style('pgv-style3',pgv_CSS_URL.'tstyle.css');
    	wp_enqueue_style('pgv-style3');
	}
function GetVipUserList(){
	global $wpdb;
	$myvipuser=$wpdb->get_results("SELECT * FROM {$wpdb->prefix}pgv_members");
	return $myvipuser;	
}
function GetVipFileList(){
	global $wpdb;
	$myvipfiles=$wpdb->get_results("SELECT * FROM {$wpdb->prefix}pgv_files");
	return $myvipfiles;	
}
function GetVipFilename($id){
	global $wpdb;
	$myfile = $wpdb->get_row("SELECT * FROM {$wpdb->prefix}pgv_files WHERE ID =". $id);
	return $myfile; 
}
function PGVCheckCredit($UserID){
	global $wpdb;
	$VipUser = $wpdb->get_row("SELECT * FROM {$wpdb->prefix}pgv_members WHERE user_ID =". $UserID);
	$Rdays=PGVRemainingdays(PGVSendTimeStamp($VipUser->start_date),$VipUser->expire_date);
    	if ($Rdays["days"]==0):
			//PGVUpdateField("status",2,$AdsID);
			return false;
		else:
			return true; 
		endif;
}
function PGVSendToLog($LValue){
	$output = "<script>console.log( 'Debug Objects: " . $LValue . "' );</script>";
	echo  $output;
}
function Alert($AValue){
	echo "<script type='text/javascript'>alert('$AValue');</script>";
}
function PGVIsOk($acceptable_domain){
	if(!in_array($_SERVER['SERVER_NAME'], $acceptable_domain, 1)):
    	return false;
	else:
		return true;
	endif;
}




function GetUserNameByID($id){
 $user_info = get_userdata(1);
      return $user_info->user_login;
}



function pgvdownload(){
	if (isset($_GET['efid'])){
		if (is_user_logged_in()){
			if (PGVCheckCredit(get_current_user_id())){
				require_once(pgv_INC_DIR.'http.class.php');
				$pgv_http = new av_httpdownload;
				$filename=GetVipFilename($_GET['efid']);
				$pgv_http->set_byfile( pgv_upload_DIR.$filename->file_name ); 
				$pgv_http->use_resume = false;
				$pgv_http->speed =  100; 
				$pgv_http->download( $filename->file_name );
			}
			else{
				Alert("شما کاربر ویژه نیستید.");
			}
		}
		else{
			Alert("لطفا به سیستم وارد شوید");
		}
	}
}
add_action('init', 'pgvdownload');
///////////////////////////////////////////////////
function PGUpdateField($FeildName,$FeildValue,$ID){
	    global $wpdb;
		$sql="UPDATE {$wpdb->prefix}pgv_members SET `".$FeildName."` = ".$FeildValue." WHERE 
		{$wpdb->prefix}pgv_members.user_ID =".$ID;
	    $res=$wpdb->query($sql);
		return $res;	
}
function PGgetcurrentdate(){
	date_default_timezone_set('Asia/Tehran');
	return $date=strftime("%Y-%m-%d %H:%M:%S",time());
}
function AddNewUserToDbase($UserData){
	global $wpdb;
	$sql="INSERT INTO {$wpdb->prefix}pgv_members (user_ID,start_date,expire_date)
        VALUES ({$UserData['user_ID']},'{$UserData['start_date']}',{$UserData['expire_date']}) ";
	        $wpdb->query($sql);
}
function AddVipCredit($pric,$dismember,$hdays){
	
	global $user_ID;
	switch($dismember) {
    	case true:
			//تعداد روز و تاریخ آغاز عضویت ویژه
			$vipDateDays=GetExpireDay($user_ID);
			//روز باقیمانده از عضویت ویژه
			$Rdays=PGRemainingdays(PGSendTimeStamp($vipDateDays['sdate']),$vipDateDays['edays']);
			if ($Rdays['days']==0){
				PGUpdateField("start_date",PGgetcurrentdate(),$user_ID);
				PGUpdateField("expire_date",$hdays,$user_ID);
			}
			else{
				PGUpdateField("expire_date",$hdays+$Rdays['days'],$user_ID);
			}
        	break;
    	case false:
			$UserData=array("user_ID"=>$user_ID,"start_date"=>PGgetcurrentdate(),"expire_date"=>$hdays,);
			AddNewUserToDbase($UserData);
			break;					
    	default:		
	}
}
function GetExpireDay($id){
	global $wpdb;
	$member = $wpdb->get_row("SELECT * FROM {$wpdb->prefix}pgv_members WHERE user_ID =". $id);
	$exdate= $member->expire_date; 
	$stdate=$member->start_date;
	return array("edays"=>$exdate,"sdate"=>$stdate);		
}
function PGSendTimeStamp($date){
	date_default_timezone_set('Asia/Tehran');
	$ndate = new DateTime($date);
	return $ndate->getTimestamp();
}
function PGRemainingdays($savedate,$CountOfShow){
	date_default_timezone_set('Asia/Tehran');
	$expiredate=$savedate+($CountOfShow*24*60*60);
    $datediff = $expiredate-$savedate;
    $Rdays= floor($datediff/(60*60*24));
	$Rtime=strftime("%H:%M:%S",$datediff/(60*60*24));
	return array("days"=>$Rdays,"times"=>$Rtime);
}	

function GetVipPrice($id){
	global $wpdb;
	$jahanpay_table_name = jahanpay_get_table_name();
	$myprice = $wpdb->get_row("SELECT * FROM $jahanpay_table_name WHERE orderid =". $id);
	return $myprice->price; 
}
function VIPIsMember($id){
	global $wpdb;
	$member = $wpdb->get_row("SELECT * FROM {$wpdb->prefix}pgv_members WHERE user_ID =". $id);
	if (count($member)>0){
		return true;
	}
	else{
		return false;
	}
}
function GetVipPlan($price){
	switch($price) {
    	case 	get_option( "PG-V_One_Month"):		
        	return 31;
        	break;
    	case 	get_option( "PG-V_Three_Months"):
        	return 93;
			break;
    	case 	get_option( "PG-V_Six_Months"):
        	return 186;
			break;					
    	case 	get_option( "PG-V_Twelve_Months"):
        	return 365;
			break;					
    	default:		
	}
	
}
function GetVipType($type){
	   	switch($type) {
    	case "30":		
        	return "عضویت ویژه یک ماهه";
        	break;
    	case "90":
        	return "عضویت ویژه سه ماهه";
			break;
    	case "180":
        	return "عضویت ویژه شش ماهه";
			break;					
    	case "360":
        	return "عضویت ویژه دوازده ماهه";
			break;					
    	default:		
	}
}

function get_the_user_ip() {
	if ( ! empty( $_SERVER['HTTP_CLIENT_IP'] ) ) {
		//check ip from share internet
		$ip = $_SERVER['HTTP_CLIENT_IP'];
	} 
	elseif ( ! empty( $_SERVER['HTTP_X_FORWARDED_FOR'] ) ) {
		//to check ip is pass from proxy
		$ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
	} 
	else {
		$ip = $_SERVER['REMOTE_ADDR'];
	}
	return apply_filters( 'wpb_get_ip', $ip );
}
function PGV_User_Style(){
		wp_enqueue_media();
    wp_register_script('pgvin-script',pgv_JS_URL.'scripts.js',array('jquery'));
    wp_enqueue_script('pgvin-script');
    wp_localize_script('pgvin-script', 'pgvajax', array('ajaxurl' => admin_url('admin-ajax.php', (is_ssl() ? 'https' : 'http'))));
	wp_register_style('pgv-userstyle',pgv_CSS_URL.'tstyle.css');
    wp_enqueue_style('pgv-userstyle');

}
add_action('init', 'PGV_User_Style');
