<?php 

// AJAX Login form processing 
function login_form_callback() {
 
  // Verify nonce
	check_ajax_referer( 'bcmarket_login_nonce', 'security' );


    $info = array();
    $info['user_login'] = $_POST['email'];
    $info['user_password'] = $_POST['password'];
    $info['remember'] = true;

    $user_signon = wp_signon( $info, false );
    if ( is_wp_error($user_signon) ){
        echo json_encode(array('loggedin'=>false, 'message'=>__($user_signon->get_error_message(), 'tirisi')));
    } else {

    	$user = get_userdata( $user_signon->ID );
		$user_roles = $user->roles;

    	if ( in_array( 'partner', $user_roles ) ) {
	        echo json_encode(array('loggedin'=>true, 'link' => home_url('/partner'), 'message'=>__('Login successful, redirecting...', 'tirisi')));
	    } else {
	         echo json_encode(array('loggedin'=>true, 'link' => wc_get_account_endpoint_url('dashboard'), 'message'=>__('Login successful, redirecting...', 'tirisi')));
	    }
        
    }


  die();
  	
}
 
add_action('wp_ajax_login_form', 'login_form_callback');
add_action('wp_ajax_nopriv_login_form', 'login_form_callback');



// AJAX Registration form processing 
function registration_form_callback() {
 
  // Verify nonce
	check_ajax_referer( 'bcmarket_register_nonce', 'security' );

  	// Post values
    $username = sanitize_user($_POST['email']);
    $password = $_POST['password'];
    $confirm_password = $_POST['password2'];
    $email    = $_POST['email'];
    $conditions_reg    = $_POST['conditions_reg'];

    $errors = array();
    $success = array();
    
    if ( username_exists( $username ) ) {
	    $errors['user'] = __('Username is already exists!', 'bcmarket');
	}

	/*if ( validate_username( $username ) ) {
	    die("Please Enter a valid username");
	}*/

	if(empty($conditions_reg)){
		$errors['term'] = __('Please accept the public offer and terms of use', 'bcmarket');
	}
 
    if(!is_email($email)){
    	$errors['email'] = __('Please enter a valid email address!', 'bcmarket');
    }
    if ( strcmp( $password, $confirm_password ) !== 0 ) {
    	$errors['password'] = __('Password and Confirm Password Fields should be same!', 'bcmarket');
    }


    if(count($errors) < 1){
    	$userdata = array(
	        'user_login' => $username,
	        'user_pass'  => $password,
	        'user_email' => $email,
	        'role' => 'customer',
	    );
	 
	    $user_id = wp_insert_user( wp_slash($userdata) ) ;

	    // Return
	    if( !is_wp_error($user_id) ) {
	        $success['success'] = 1;
	        $success['message'] = __('Thanks for registering with us!', 'bcmarket');

	        wp_set_current_user($user_id);
    		wp_set_auth_cookie($user_id);

    		die(json_encode($success));
	    } else {
	        $errors['message'] = $user_id->get_error_message();
	        $errors['error'] = 1;
    		die(json_encode($errors));
	    }
    }else{
    	$errors['error'] = 1;
    	die(json_encode($errors));
    }

  	
}


// Registration form page processing
add_action('wp_ajax_registration_form', 'registration_form_callback');
add_action('wp_ajax_nopriv_registration_form', 'registration_form_callback');



// AJAX Registration form page processing 
function registration_form_page_callback() {
 
  // Verify nonce
	check_ajax_referer( 'bcmarket_register_page_nonce', 'security' );

  	// Post values
    $username = sanitize_user($_POST['email']);
    $password = $_POST['password'];
    $confirm_password = $_POST['password2'];
    $email    = $_POST['email'];
    $conditions_reg    = $_POST['conditions_reg'];

    $errors = array();
    $success = array();
    
    if ( username_exists( $username ) ) {
	    $errors['user'] = __('Username is already exists!', 'bcmarket');
	}

	/*if ( validate_username( $username ) ) {
	    die("Please Enter a valid username");
	}*/

	if(empty($conditions_reg)){
		$errors['term'] = __('Please accept the public offer and terms of use', 'bcmarket');
	}
 
    if(!is_email($email)){
    	$errors['email'] = __('Please enter a valid email address!', 'bcmarket');
    }
    if ( strcmp( $password, $confirm_password ) !== 0 ) {
    	$errors['password'] = __('Password and Confirm Password Fields should be same!', 'bcmarket');
    }


    if(count($errors) < 1){
    	$userdata = array(
	        'user_login' => $username,
	        'user_pass'  => $password,
	        'user_email' => $email,
	        'role' => 'customer',
	    );
	 
	    $user_id = wp_insert_user( wp_slash($userdata) ) ;

	    // Return
	    if( !is_wp_error($user_id) ) {
	        $success['success'] = 1;
	        $success['message'] = __('Thanks for registering with us!', 'bcmarket');

	        wp_set_current_user($user_id);
    		wp_set_auth_cookie($user_id);

    		die(json_encode($success));
	    } else {
	        $errors['message'] = $user_id->get_error_message();
	        $errors['error'] = 1;
    		die(json_encode($errors));
	    }
    }else{
    	$errors['error'] = 1;
    	die(json_encode($errors));
    }

  	
}

add_action('wp_ajax_registration_form_page', 'registration_form_page_callback');
add_action('wp_ajax_nopriv_registration_form_page', 'registration_form_page_callback');



// AJAX Registration form page processing 
function partner_registration_callback() {
 
  // Verify nonce
	check_ajax_referer( 'bcmarket_register_partner_nonce', 'security' );

  	// Post values
    $username = sanitize_user($_POST['email']);
    $password = $_POST['password'];
    $confirm_password = $_POST['password2'];
    $email    = $_POST['email'];
    $conditions_reg    = $_POST['conditions_reg'];

    $errors = array();
    $success = array();
    
    if ( username_exists( $username ) ) {
	    $errors['user'] = __('Username is already exists!', 'bcmarket');
	}

	/*if ( validate_username( $username ) ) {
	    die("Please Enter a valid username");
	}*/

	if(empty($conditions_reg)){
		$errors['term'] = __('Please accept the public offer and terms of use', 'bcmarket');
	}
 
    if(!is_email($email)){
    	$errors['email'] = __('Please enter a valid email address!', 'bcmarket');
    }
    if ( strcmp( $password, $confirm_password ) !== 0 ) {
    	$errors['password'] = __('Password and Confirm Password Fields should be same!', 'bcmarket');
    }


    if(count($errors) < 1){
    	$userdata = array(
	        'user_login' => $username,
	        'user_pass'  => $password,
	        'user_email' => $email,
	        'role' => 'partner',
	    );
	 
	    $user_id = wp_insert_user( wp_slash($userdata) ) ;

	    // Return
	    if( !is_wp_error($user_id) ) {
	        $success['success'] = 1;
	        $success['message'] = __('Thanks for registering with us!', 'bcmarket');

	        wp_set_current_user($user_id);
    			wp_set_auth_cookie($user_id);

    			do_action('send_partner_registration_email', $user_id);

    			die(json_encode($success));
	    } else {
	        $errors['message'] = $user_id->get_error_message();
	        $errors['error'] = 1;
    		die(json_encode($errors));
	    }
    }else{
    	$errors['error'] = 1;
    	die(json_encode($errors));
    }

  	
}

add_action('wp_ajax_partner_registration', 'partner_registration_callback');
add_action('wp_ajax_nopriv_partner_registration', 'partner_registration_callback');


function send_partner_registration_email_callback($user_id){

	$to =  get_the_author_meta('user_email', $user_id, );
	$site_name = get_bloginfo('name');
	$domain_name = parse_url( get_site_url(), PHP_URL_HOST );

	$subject = 'Registration in the affiliate cabinet - ' . $site_name;

	ob_start(); 

		get_template_part('emails/partner', 'registration');

	$body = ob_get_clean(); 

	

	$headers = array('Content-Type: text/html; charset=UTF-8','From: '. $site_name .' <noreply@'. $domain_name .'>');

	wp_mail( $to, $subject, $body, $headers );

}
add_action('send_partner_registration_email', 'send_partner_registration_email_callback');


add_filter( 'wp_authenticate_user', 'disallow_login_based_on_user_meta', 10, 2 );
function disallow_login_based_on_user_meta( $user, $password ) {
    $user_id = $user->ID;
    $user_meta_value = get_user_meta( $user_id, 'account_status', true );
 
    if ( 'rejected' === $user_meta_value ) {
        return new WP_Error( 'disallowed_user', 'Sorry, Your account is banned.' );
    }
 
    return $user;
}