<?php
function pgvdlink_func( $atts ) {
	global $user_ID;
    $a = shortcode_atts( array(
        'id' => 'something',
    ), $atts );	
		if (is_user_logged_in()){
			if (PGVCheckCredit($user_ID)){
    			return "<a href=".site_url()."/?efid={$a['id']}> از اینجا دانلود نمایید</a>";
			}
			else{
				$link="<a class='btn-success btn'
				 href='".get_permalink(get_option("pgv_paypage"))."' target='_blank'>فعال سازی یا ارتقا عضویت ویژه</a>";
				$IText='<div class="pgv-alert-box">
				<p>.:این محتوا مخصوص کاربران ویژه سایت می باشد:.</p>
				<p style="text-align:center">'.$link.'</p></div>';
				echo $IText;
			}
		}
		else{
			echo ("لطفا جهت بهره مندی از کلیه امکانات سایت به سیستم وارد شوید.");
		}
	
	
}

function member_check_shortcode($atts, $content = null) {
	global $user_ID;
	if (is_user_logged_in() && !is_null($content) && !is_feed()) {
		if (PGVCheckCredit($user_ID)){
			return $content;
		}
		else{
				$link="<a class='btn-success btn'
				 href='".get_permalink(get_option("pgv_paypage"))."' target='_blank'>فعال سازی یا ارتقا عضویت ویژه</a>";
				$IText='<div class="pgv-alert-box">
				<p>.:این محتوا مخصوص کاربران ویژه سایت می باشد:.</p>
				<p style="text-align:center">'.$link.'</p></div>';
				echo $IText;
		}
	} 
	else {
		return 'متاسفیم! نمایش این مطلب برای کاربران عضو امکان پذیر می باشد';
	}
}

function PGV_payline_form() {
	global $current_user, $PGV_payline;
	include_once(pgv_INC_DIR.'PGV_PayLineform.php');
	if($_POST['submit_payment']) {
		if($_POST['payer_name'] && $_POST['payer_price'] && $_POST['payline_tell'] && $_POST['payline_email']
		&& $_POST['payline_des']) {
			$PGV_payline->Price = $_POST['payer_price'];
			$PGV_payline->ReturnPath = urlencode('http://' . $_SERVER['SERVER_NAME'] . $_SERVER['REQUEST_URI']);
			if($PGV_payline->Request()) {
				switch($PGV_payline->Request()) {
					case '-1':
						echo '<p class="error-payment">' . __('api ارسالی با نوع api تعریف شده در payline سازگار نیست.', 'PG-VIP') . '</p>';
						break;
					case '-2':
						echo '<p class="error-payment">' . __('مقدار amount داده عددی نمی باشد.', 'PG-VIP') . '</p>';
						break;
					case '-3':
						echo '<p class="error-payment">' . __('مقدار redirect رشته null است.', 'PG-VIP') . '</p>';
						break;
					case '-4':
						echo '<p class="error-payment">' . __('درگاهی با اطلاعات ارسالی شما یافت نشده و یا در حالت انتظار می باشد.', 'PG-VIP') . '</p>';
						break;
				}
			} 
			else {
delete_option('user_price_' . $current_user->ID);
				add_option('user_price_' . $current_user->ID, $_POST['payer_price']);
				update_option('user_price_' . $current_user->ID, $_POST['payer_price']);
			}
		} 
		else {
			echo '<p class="error-payment">' . __('لطفا تمام فيلد هارا کامل کنيد', 'PG-VIP') . '</p>';
		}
	}

	$PGV_payline->Price = get_option('user_price_' . $current_user->ID);
    if($_POST['trans_id']){
		switch($PGV_payline->Verify()) {
			case '-1':
				echo '<p class="error-payment">' . __('api ارسالی با نوع api تعریف شده در payline سازگار نیست.', 'PG-VIP') . '</p>';
				continue;
			case '-2':
				echo '<p class="error-payment">' . __('trans_id ارسال شده معتبر نمی باشد.', 'PG-VIP') . '</p>';
				continue;
			case '-3':
				echo '<p class="error-payment">' . __('id_get ارسالی معتبر نمی باشد.', 'PG-VIP') . '</p>';
				continue;
			case '-4':
				echo '<p class="error-payment">' . __('چنین تراکنشی در سیستم وجود ندارد و یا موفقیت آمیز نبوده است.', 'PG-VIP') . '</p>';
				continue;
			case 1:
				echo '<p class="success-payment">' . sprintf(__('تراکنش موفقيت آميز بود. <br /> شماره رهگيري: %s ', 'PG-VIP'), $PGV_payline->RefNumber) . '</p>';
				/////////////////////////////////
				//vip pluguns
				//کاربر فعلی
				global $user_ID;
				//مبلغ پرداخت شده
				$vippaid=get_option('user_price_' . $current_user->ID);
				//وجود کاربر در جدول vip
				$vip_ismember=VIPIsMember($user_ID);
				//تعداد روزی که باید اضافه شود بر اساس مبلغ پرداختی
				$vipdays=GetVipPlan($vippaid);
				AddVipCredit($pric,$vip_ismember,$vipdays);
				//send emails
				$email=$current_user->user_email;
				$admin_email = get_option('admin_email');
				$headers = "From: $admin_email \r\n";
				$headers .= "Content-type: text/html\r\n";
	$body = "با عرض سلام<br/>تراکنش شما با موفقیت ثبت شد<br/>کد رهگیری شما: " . $PGV_payline->RefNumber .  get_bloginfo('name');
	$body2 = "با عرض سلام<br/>تراکنش جدیدی در سایت شما با موفقیت ثبت شد<br/>کد رهگیری: " . $PGV_payline->RefNumber . "<br/>باتشکر/" . get_bloginfo('name');
	wp_mail($email, 'تراکنش شما ثبت شد.', $body, $headers);
	wp_mail($admin_email, 'تراکنش جدیدی ثبت شد.', $body2, $headers);
				
				
				
				
				
				
				/////////////////////////////
				continue;
		}
	}

		//	delete_option('user_price_' . $current_user->ID);
}
	
function test(){
					//کاربر فعلی
				global $user_ID;
				//مبلغ پرداخت شده
				$vippaid=1000;
				//وجود کاربر در جدول vip
				$vip_ismember=VIPIsMember($user_ID);
				//تعداد روزی که باید اضافه شود بر اساس مبلغ پرداختی
				$vipdays=GetVipPlan($vippaid);
				AddVipCredit($pric,$vip_ismember,$vipdays);

	
}
function register_shortcodes(){
	add_shortcode( 'pgvdlink', 'pgvdlink_func' );
		add_shortcode( 'pgvtest', 'test' );

	add_shortcode('pgvmember', 'member_check_shortcode');
	add_shortcode('pgvpayline', 'PGV_payline_form');
	add_filter('widget_text', 'do_shortcode');
}
add_action( 'init', 'register_shortcodes');



?>