<?php
add_action('register_form','pgv_register_form');
function pgv_register_form (){
$first_name = ( isset( $_POST['first_name'] ) ) ? $_POST['first_name']: '';
$last_name = ( isset( $_POST['last_name'] ) ) ? $_POST['last_name']: '';
$mobileno = ( isset( $_POST['mobileno'] ) ) ? $_POST['mobileno']: '';

?>
<p>
	<label for="first_name">نام<br>
		<input style="background-image: url(&quot;data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAABAAAAAQCAYAAAAf8/9hAAABHklEQVQ4EaVTO26DQBD1ohQWaS2lg9JybZ+AK7hNwx2oIoVf4UPQ0Lj1FdKktevIpel8AKNUkDcWMxpgSaIEaTVv3sx7uztiTdu2s/98DywOw3Dued4Who/M2aIx5lZV1aEsy0+qiwHELyi+Ytl0PQ69SxAxkWIA4RMRTdNsKE59juMcuZd6xIAFeZ6fGCdJ8kY4y7KAuTRNGd7jyEBXsdOPE3a0QGPsniOnnYMO67LgSQN9T41F2QGrQRRFCwyzoIF2qyBuKKbcOgPXdVeY9rMWgNsjf9ccYesJhk3f5dYT1HX9gR0LLQR30TnjkUEcx2uIuS4RnI+aj6sJR0AM8AaumPaM/rRehyWhXqbFAA9kh3/8/NvHxAYGAsZ/il8IalkCLBfNVAAAAABJRU5ErkJggg==&quot;); background-repeat: no-repeat; background-attachment: scroll; background-position: right center;" name="first_name" id="first_name" class="input" value="" size="20" type="text">
    </label>
</p>

<p>
	<label for="first_name">نام و نام خانوادگی<br>
		<input style="background-image: url(&quot;data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAABAAAAAQCAYAAAAf8/9hAAABHklEQVQ4EaVTO26DQBD1ohQWaS2lg9JybZ+AK7hNwx2oIoVf4UPQ0Lj1FdKktevIpel8AKNUkDcWMxpgSaIEaTVv3sx7uztiTdu2s/98DywOw3Dued4Who/M2aIx5lZV1aEsy0+qiwHELyi+Ytl0PQ69SxAxkWIA4RMRTdNsKE59juMcuZd6xIAFeZ6fGCdJ8kY4y7KAuTRNGd7jyEBXsdOPE3a0QGPsniOnnYMO67LgSQN9T41F2QGrQRRFCwyzoIF2qyBuKKbcOgPXdVeY9rMWgNsjf9ccYesJhk3f5dYT1HX9gR0LLQR30TnjkUEcx2uIuS4RnI+aj6sJR0AM8AaumPaM/rRehyWhXqbFAA9kh3/8/NvHxAYGAsZ/il8IalkCLBfNVAAAAABJRU5ErkJggg==&quot;); background-repeat: no-repeat; background-attachment: scroll; background-position: right center;" name="last_name" id="last_name" class="input" value="" size="20" type="text">
    </label>
</p>
    
<p>
	<label for="mobileno">شماره همراه<br>
		<input style="background-image: url(&quot;data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAABAAAAAQCAYAAAAf8/9hAAABHklEQVQ4EaVTO26DQBD1ohQWaS2lg9JybZ+AK7hNwx2oIoVf4UPQ0Lj1FdKktevIpel8AKNUkDcWMxpgSaIEaTVv3sx7uztiTdu2s/98DywOw3Dued4Who/M2aIx5lZV1aEsy0+qiwHELyi+Ytl0PQ69SxAxkWIA4RMRTdNsKE59juMcuZd6xIAFeZ6fGCdJ8kY4y7KAuTRNGd7jyEBXsdOPE3a0QGPsniOnnYMO67LgSQN9T41F2QGrQRRFCwyzoIF2qyBuKKbcOgPXdVeY9rMWgNsjf9ccYesJhk3f5dYT1HX9gR0LLQR30TnjkUEcx2uIuS4RnI+aj6sJR0AM8AaumPaM/rRehyWhXqbFAA9kh3/8/NvHxAYGAsZ/il8IalkCLBfNVAAAAABJRU5ErkJggg==&quot;); background-repeat: no-repeat; background-attachment: scroll; background-position: right center;" name="mobileno" id="mobileno" class="input" value="" size="20" type="text">
    </label>
</p>    
<?php
}

//2. Add validation. In this case, we make sure first_name is required.
add_filter('registration_errors', 'pgv_registration_errors', 10, 3);
function pgv_registration_errors ($errors, $sanitized_user_login, $user_email) {
	if ( empty( $_POST['first_name']  ) ){
		$errors->add( 'first_name_error', __('<strong>ERROR</strong>: You must include a first name.','PG-VIP') );
	}
		if ( empty( $_POST['last_name']  ) ){
		$errors->add( 'last_name_error', __('<strong>ERROR</strong>: You must include a last name.','PG-VIP') );
	}
	if (  empty( $_POST['mobileno'] ) ){
		$errors->add( 'mobileno_error', __('<strong>ERROR</strong>: You must include a mobile number.','PG-VIP') );
	}
	return $errors;
}

//3. Finally, save our extra registration user meta.
add_action('user_register', 'pgv_user_register');
function pgv_user_register ($user_id) {
	if ( isset( $_POST['first_name'] ) ){
		update_user_meta($user_id, 'first_name', $_POST['first_name']);
	}
	if ( isset( $_POST['last_name'] ) ){
		update_user_meta($user_id, 'last_name', $_POST['last_name']);
	}
	if ( isset( $_POST['mobileno'] ) ){
		update_user_meta($user_id, 'mobileno', $_POST['mobileno']);}
	}
?>