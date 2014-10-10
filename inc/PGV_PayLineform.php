<link rel="stylesheet" href="<?php echo plugin_dir_url(__FILE__); ?>style.css" type="text/css" />
<?php
	global $user_ID;
	$current_user = wp_get_current_user();
	$mail=$current_user->user_email;
 	$fname= get_the_author_meta( "first_name", $user_ID);
 	$lname= get_the_author_meta( "last_name", $user_ID);
 	$mobilen= get_the_author_meta( "mobileno", $user_ID);
?>
<div class="payment-form">
	<form action="" method="post">
		<table width="100%">
        	<tr>
				<td>نوع عضویت:</td>
				<td> 
					<select name="viptype" class="jinput" id="idviptype">
  						<option value="30">عضویت ویژه یک ماهه</option>
  						<option value="90">عضویت ویژه سه ماهه</option>
  						<option value="180">عضویت ویژه شش ماهه</option>
  						<option value="360">عضویت ویژه دوازده ماهه</option>
					</select> 
				</td>
			</tr>
            <tr>
				<td width="110px">نام و نام خانوادگی:</td>
				<td>
					<input class="payline-name jinput" maxlength="250" type="text" name="payer_name" 
					value="<?php echo $fname.' '.$lname; ?>"/>
                    <span class="description-require">*</span>
				</td>
			</tr>
			<tr>
				<td width="110px">شماره همراه:</td>
				<td>
					<input class="payline_tell jinput" maxlength="50" type="text" name="payline_tell" dir="ltr"
					value="<?php echo $mobilen; ?>"/>
                    <span class="description-require">*</span>
				</td>
			</tr>
            <tr>
				<td width="110px">ایمیل:</td>
				<td>
					<input class="payline_email jinput" maxlength="250" type="text" name="payline_email" dir="ltr"
					value="<?php echo $mail; ?>"/>
                    <span class="description-require">*</span>
				</td>
			</tr>			<tr>
				<td><?php _e('مبلغ', 'PG-VIP'); ?></td>
				<td>
					<input type="text" name="payer_price" dir="ltr" readonly class="payline-price jinput"/>
					<span class="description-require">*</span>
					<br /><span class="description-field"><?php _e('به ريال وارد کنيد', 'PG-VIP'); ?></span>
				</td>
			</tr>
			<tr>
				<td width="110px">توضیحات:</td>
				<td>
					<textarea rows="10" cols=\'60\' class="payline_des jinput" readonly  type="text" name="payline_des"><?php $des ?></textarea>
                </td>
			</tr>			
    		<tr>
				<td colspan="2">
					<input type="submit" name="submit_payment" value="<?php _e('پرداخت', 'PG-VIP'); ?>" class="payline-submit"/>
				</td>
			</tr>
		</table>
	</form>
</div>