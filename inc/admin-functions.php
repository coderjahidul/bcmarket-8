<?php 
function change_item_status_callback() {
 
	$status = $_POST['status'];
	$id = $_POST['product_id'];
	$note = $_POST['note'];

	if(!empty($id)){
	
		update_post_meta($id, 'bid_status', $status);

		if($status == 'onsale' || $status == 'soldout'){

				$my_post = array();
		    $my_post['ID'] = $id;
		    $my_post['post_status'] = 'publish';

		    $post_id = wp_update_post( $my_post );

		    if(get_post_meta($post_id, 'bid_status', true) == 'onsale'){
		    	do_action('send_onsale_email', $post_id);
		    } 
		    if(get_post_meta($post_id, 'bid_status', true) == 'soldout'){
		    	$my_post = array();
			    $my_post['ID'] = $id;
			    $my_post['post_status'] = 'pending';

			    $post_id = wp_update_post( $my_post );
		    	do_action('send_soldout_email', $post_id);
		    }
		


		}elseif($status === 'declined'){
            update_post_meta($id, 'note', $note);
			do_action('send_declined_email', $id, $note);
			die('dcelnied email setn');

		}else{

			$my_post = array();
		    $my_post['ID'] = $id;
		    $my_post['post_status'] = 'pending';

		    wp_update_post( $my_post );

		}

		

		die('yes');

	}else{

		die('no');

	}
	
  	
}
 
add_action('wp_ajax_change_item_status', 'change_item_status_callback');


function select_term_child_callback() {
 
	$cat = $_POST['cat'];
	$id = $_POST['id'];
	$parent = $_POST['parent'];

	if(!empty($id) && !empty($cat)){

		 $termchildren = get_term_children( $parent, 'product_cat' );


    if($termchildren){
    	foreach($termchildren as $child){
    		wp_remove_object_terms( $id, $child, 'product_cat');
    	}
    }

		$cat_item = array( $cat );
	
		wp_set_post_terms( $id, $cat_item, 'product_cat', true );

		die('yes');

	}else{

		die('no');

	}
	
  	
}
 
add_action('wp_ajax_select_term_child', 'select_term_child_callback');



function approve_account_application_callback() {
 
	$id = $_POST['id'];

	if(!empty($id)){


		
		update_post_meta($id, 'offer_status', 'approved');
		$author_id = get_post_field( 'post_author', $id );

		$user_data = array(
		  'ID' => $author_id,
		  'role' => 'partner'
		);
		wp_update_user( $user_data );


		do_action('send_approve_email', $id);

		
		die('yes');

	}else{

		die('no');

	}
	
  	
}
 
add_action('wp_ajax_approve_account_application', 'approve_account_application_callback');


function reject_account_application_callback() {
 
	$id = $_POST['id'];
	$reason = $_POST['reason'];

	if(!empty($id)){
	
		update_post_meta($id, 'offer_status', 'rejected');
		update_post_meta($id, 'reject_reason', $reason);

		do_action('send_reject_email', $id, $reason);
		

		
		die('yes');

	}else{

		die('no');

	}

}
 
add_action('wp_ajax_reject_account_application', 'reject_account_application_callback');

// Ban Account AJAX
function ban_account_callback() {
 
	$id = $_POST['id'];
	$reason = $_POST['reason'];
	$ban_datetime = $_POST['datetime'];

	if(!empty($id)){
	
		update_user_meta($id, 'account_status', 'rejected');
		update_user_meta($id, 'account_status_datetime', $ban_datetime);
		add_user_meta($id, 'ban_reason', $reason);
		add_user_meta($id, 'ban_history', 'ban_partner_account');
        add_user_meta($id, 'unban_date', $ban_datetime);

		$current_datetimes = current_time('mysql'); // Get the current datetime in MySQL format
        $current_datetime = date('Y-m-d', strtotime($current_datetimes));
        // ban current date 
        add_user_meta($id, 'ban_current_date', $current_datetime);

		$args = array(
		    'post_type' => 'product',
		    'author' => $id,
		    'posts_per_page' => -1
		);
		$products = new WP_Query( $args );

		if ( $products->have_posts() ) {
		    while ( $products->have_posts() ) {
		        $products->the_post();
		        
		        $product_id = get_the_ID();
						
						update_post_meta($product_id, 'bid_status', 'declined');
		    }
		    wp_reset_postdata();
		}

		do_action('send_ban_email', $id, $reason, $current_datetime, $ban_datetime);
		

		
		die('yes');

	}else{

		die('no');

	}
	
}
 
add_action('wp_ajax_ban_account', 'ban_account_callback');


// Reactivate Ban Account AJAX
function rec_partner_account_callback() {
 
	$id = $_POST['id'];

	if(!empty($id)){
	
		update_user_meta($id, 'account_status', '');
		update_user_meta($id, 'account_status_datetime', '');

		do_action('send_reactivate_email', $id);
		
		
		die('yes');

	}else{

		die('no');

	}
	
}
 
add_action('wp_ajax_rec_partner_account', 'rec_partner_account_callback');


function send_reactivate_email_callback($user_id){


	$user = get_userdata( $user_id );
	$to = $user->user_email;

	$subject = 'Your account is activated.';

	ob_start(); 
		get_template_part('emails/account', 'reactivate');

	$body = ob_get_clean(); 

	$site_name = get_bloginfo('name');
	$domain_name = parse_url( get_site_url(), PHP_URL_HOST );

	$headers = array('Content-Type: text/html; charset=UTF-8','From: '. $site_name .' <noreply@'. $domain_name .'>');

	wp_mail( $to, $subject, $body, $headers );


}
add_action('send_reactivate_email', 'send_reactivate_email_callback', 10, 1);

function send_ban_email_callback($user_id, $reason, $current_datetime, $ban_datetime){


	$user = get_userdata( $user_id );
	$to = $user->user_email;

	$subject = 'Your account is banned.';

	ob_start(); 
		$args = array('reason' => $reason);
		get_template_part('emails/account', 'banned', $args);

	$body = ob_get_clean(); 

	$site_name = get_bloginfo('name');
	$domain_name = parse_url( get_site_url(), PHP_URL_HOST );

	$headers = array('Content-Type: text/html; charset=UTF-8','From: '. $site_name .' <noreply@'. $domain_name .'>');

	wp_mail( $to, $subject, $body, $headers );


}
add_action('send_ban_email', 'send_ban_email_callback', 10, 2);


function send_reject_email_callback($post_id, $reason){


	$author_id = get_post_field ('post_author', $post_id);
	$to =  get_the_author_meta('user_email', $author_id );

	$subject = 'Your registration is rejceted.';

	ob_start(); 
		$args = array('reason' => $reason);
		get_template_part('emails/application', 'rejected', $args);

	$body = ob_get_clean(); 

	$site_name = get_bloginfo('name');
	$domain_name = parse_url( get_site_url(), PHP_URL_HOST );

	$headers = array('Content-Type: text/html; charset=UTF-8','From: '. $site_name .' <noreply@'. $domain_name .'>');

	wp_mail( $to, $subject, $body, $headers );


}
add_action('send_reject_email', 'send_reject_email_callback', 10, 2);


function send_approve_email_callback($post_id){


	$author_id = get_post_field ('post_author', $post_id);
	$to =  get_the_author_meta('user_email', $author_id);
	$subject = 'Your registration is approved.';

	ob_start(); 

		get_template_part('emails/application', 'approved');

	$body = ob_get_clean(); 

	$site_name = get_bloginfo('name');
	$domain_name = parse_url( get_site_url(), PHP_URL_HOST );

	$headers = array('Content-Type: text/html; charset=UTF-8','From: '. $site_name .' <noreply@'. $domain_name .'>');

	wp_mail( $to, $subject, $body, $headers );


}
add_action('send_approve_email', 'send_approve_email_callback');


// Send Onsale email notification 
function send_onsale_email_callback($post_id){


	$author_id = get_post_field ('post_author', $post_id);
	$to =  get_the_author_meta('user_email', $author_id );
	$site_name = get_bloginfo('name');
	$domain_name = parse_url( get_site_url(), PHP_URL_HOST );

	$product_id = get_post_meta($post_id, 'custom_product_id', true);

	$subject = 'Bid №'. $product_id .' is approved - ' . $domain_name;

	ob_start(); 

		$args = array('post_id' => $post_id);

		get_template_part('emails/partner', 'onsale', $args);

	$body = ob_get_clean(); 

	

	$headers = array('Content-Type: text/html; charset=UTF-8','From: '. $site_name .' <noreply@'. $domain_name .'>');

	wp_mail( $to, $subject, $body, $headers );


}
add_action('send_onsale_email', 'send_onsale_email_callback');

// Send Declined email notification 
function send_declined_email_callback($post_id, $note){

	$author_id = get_post_field ('post_author', $post_id);
	$to =  get_the_author_meta('user_email', $author_id );
	$site_name = get_bloginfo('name');
	$domain_name = parse_url( get_site_url(), PHP_URL_HOST );

	$product_id = get_post_meta($post_id, 'custom_product_id', true);

	$subject = 'Bid №'. $product_id .' is Declined - ' . $domain_name;

	ob_start(); 

		$args = array('post_id' => $post_id, 'note' => $note);

		get_template_part('emails/partner', 'declined', $args);

	$body = ob_get_clean(); 

	

	$headers = array('Content-Type: text/html; charset=UTF-8','From: '. $site_name .' <noreply@'. $domain_name .'>');

	wp_mail( $to, $subject, $body, $headers );


}
add_action('send_declined_email', 'send_declined_email_callback',10, 2);


function check_onsale_product_callback(){

	if(isset($_REQUEST['onsale']) && $_REQUEST['onsale'] == 'checking'){

		$onsale_query = new WP_Query(array(
			'post_type' => 'product', 
			'posts_per_page' => 50,
			'meta_query' => array(
				array(
					'key' => 'bid_status', 
					'value' => 'onsale'
				)
			)
		));

		if($onsale_query->have_posts()){

			while($onsale_query->have_posts()){
				$onsale_query->the_post();

				echo  get_post_meta(get_the_ID(), 'bid_status', true);
				echo total_free_accounts_by_id(get_the_ID());

				if(total_free_accounts_by_id(get_the_ID()) == 0 ){
					update_post_meta(get_the_ID(), 'bid_status', 'soldout');
					$my_post = array();
			    $my_post['ID'] = get_the_ID();
			    $my_post['post_status'] = 'pending';

			    $post_id = wp_update_post( $my_post );
					do_action('send_soldout_email', get_the_ID());
				}

				echo '<br>';

			} wp_reset_postdata(); 

		}


		die();

	}

}
add_action('init', 'check_onsale_product_callback');



// Send Soldout email notification 
function send_soldout_email_callback($post_id){


	$author_id = get_post_field ('post_author', $post_id);
	$to =  get_the_author_meta('user_email', $author_id );
	$site_name = get_bloginfo('name');
	$domain_name = parse_url( get_site_url(), PHP_URL_HOST );

	$product_id = get_post_meta($post_id, 'custom_product_id', true);

	$subject = 'Accounts sold out (№'. $product_id .')';

	ob_start(); 

		$args = array('post_id' => $post_id);

		get_template_part('emails/partner', 'soldout', $args);

	$body = ob_get_clean(); 

	

	$headers = array('Content-Type: text/html; charset=UTF-8','From: '. $site_name .' <noreply@'. $domain_name .'>');

	wp_mail( $to, $subject, $body, $headers );


}
add_action('send_soldout_email', 'send_soldout_email_callback');


function bcmarket_admin_menus( $theme_location ) {
  if ( ($theme_location) && ($locations = get_nav_menu_locations()) && isset($locations[$theme_location]) ) {
    $menu = get_term( $locations[$theme_location], 'nav_menu' );
    $menu_items = wp_get_nav_menu_items($menu->term_id);

    $menu_list = '';
    $active_class = '';

    $menu_list .= '<ul class="partner-menu-items partner-menu-mobile-hide">' ."\n";

    
    foreach( $menu_items as $menu_item ) {  

        if(get_the_ID() == $menu_item->object_id){
            $active_class = 'partner-menu__active';
        }else{
            $active_class = '';
        }
      
      $link = $menu_item->url;
      $title = $menu_item->title;
      
      if ( !$menu_item->menu_item_parent ) {
        $parent_id = $menu_item->ID;
        
        $menu_list .= '<li class="partner-menu-item">' ."\n";
        $menu_list .= '<a href="'.$link.'" class="'. $active_class .'">'.$title.'</a>' ."\n";
        $menu_list .= '</li>' ."\n"; 
      }

    }
    
    $menu_list .= '</ul>' ."\n";

  } else {
    $menu_list = '<!-- no menu defined in location "'.$theme_location.'" -->';
  }
  echo $menu_list;
}



function mark_as_paid_callback(){


	$payment_id = $_POST['payment_id'];
	$partner_id = $_POST['partner_id'];

	update_post_meta($payment_id, 'payment_status', 'paid');

	$order_ids = explode(',', get_post_meta($payment_id, 'order_ids', true));

	if($order_ids){

			foreach($order_ids as $order_id){

				if(get_post_meta($order_id, 'partner_payment_status', true) == 2){
					update_post_meta($order_id, 'partner_payment_status', 3);
				}

				
			}

		}

	$user = get_userdata( $partner_id );
	$to = $user->user_email;

  $site_name = get_bloginfo('name');
  $domain_name = parse_url( get_site_url(), PHP_URL_HOST );

  $subject = 'The payment is completed https://pvamarkets.com/';

  ob_start(); 

  		$args = array('payment_id' => $payment_id);

      get_template_part('emails/payment', 'completed', $args);

  $body = ob_get_clean(); 

  
  $headers = array('Content-Type: text/html; charset=UTF-8','From: '. $site_name .' <noreply@'. $domain_name .'>');

  wp_mail( $to, $subject, $body, $headers );


	die($payment_id);


}
add_action('wp_ajax_mark_as_paid', 'mark_as_paid_callback');

function update_payment_comment_callback(){


	$payment_id = $_POST['payment_id'];
	$payment_comment = $_POST['payment_comment'];

	update_post_meta($payment_id, 'payment_comment', $payment_comment);

	die($payment_comment);


}
add_action('wp_ajax_update_payment_comment', 'update_payment_comment_callback');

function update_product_details_callback(){


	$product_id = $_POST['product_id'];
	$product_detail = $_POST['product_detail'];

  $my_post = array(
      'ID'           => $product_id,
      'post_content' => $product_detail
  );

	// Update the post into the database
  wp_update_post( $my_post );

	die($product_detail);


}
add_action('wp_ajax_update_product_details', 'update_product_details_callback');


function connect_similar_item_callback(){


	$product_id = $_POST['product_id'];
	$similar_product_id = $_POST['similar_product_id'];

  update_post_meta($product_id, 'similar_product_id', $similar_product_id);

	die($similar_product_id);


}
add_action('wp_ajax_connect_similar_item', 'connect_similar_item_callback');


function similar_pro_find_callback(){


	$keyword = $_POST['keyword'];
	
	$search_query = new WP_Query(array(
		'post_type' => 'product', 
		'posts_per_page' => 10, 
		'post_status' => array('publish', 'pending'),
		's' => $keyword
	));

	if($search_query->have_posts()){

		echo '<ul>';

		while($search_query->have_posts()){
			$search_query->the_post();

			?>
				<li data-id="<?php echo get_the_ID(); ?>"><?php the_title(); ?></li>
			<?php 

		} wp_reset_postdata();

		echo '</ul>';

	}else{
		echo '<p>No product Found</p>';
	}

	die();


}
add_action('wp_ajax_similar_pro_find', 'similar_pro_find_callback');

function send_subscription_emails($to, $product_url){

	$subject = 'Get new Accounts from Pvamarkets';
	  $site_name = get_bloginfo('name');
      $domain_name = parse_url( get_site_url(), PHP_URL_HOST );
	  $unsubscribe_link = home_url() . '/unsubscribe?email=' . urlencode($to);
	ob_start();
	?>
		<p style='text-align:center'>
		<?php the_custom_logo(); ?>
		</p>
		<p>Hi ,</p>
		<p>Just to let you know — Some New Accounts are published in Pvamarkets.com.</p>


		<h2 style="color: #7f54b3; display: block; font-family: &quot;Helvetica Neue&quot;,Helvetica,Roboto,Arial,sans-serif; font-size: 18px; font-weight: bold; line-height: 130%; margin: 0 0 18px; text-align: left;">
			<a href="<?php echo $product_url; ?>" target="_blank">Get New Accounts Here</a>
		</h2>

		<div style="margin-bottom: 40px;">
			
			<p>Thanks for using Pvamarkets!</p>
			<p>If you no longer wish to receive these emails, you can <a href='<?php echo $unsubscribe_link;?>'>unsubscribe</a>.</p>
			<p style="text-align: center;" align="center">Please read these articles to avoid problems when working with accounts</p>
			<p style="text-align: center;" align="center">
				<a target="_blank" rel="noopener noreferrer" href="<?php echo home_url(); ?>/accounts-guidelines/" style="color: #7f54b3; font-weight: normal; text-decoration: underline;">Recommendations for working with any accounts</a>
			</p>
			<p style="text-align: center;" align="center">
				<a target="_blank" rel="noopener noreferrer" href="<?php echo home_url(); ?>/faq" style="color: #7f54b3; font-weight: normal; text-decoration: underline;">FAQ(frequently asked questions)</a>
			</p>
			<p style="text-align: center;" align="center">
				<span style="font-size: 15px;">______________________________<wbr>____________________</span>
			</p>
		<p style="text-align: center;" align="center">
			<span style="font-size: 13px;">
				<a target="_blank" rel="noopener noreferrer" href="<?php echo home_url(); ?>/" style="color: #7f54b3; font-weight: normal; text-decoration: underline;">Back to store</a>&nbsp;|&nbsp;
				<a target="_blank" rel="noopener noreferrer" href="<?php echo home_url(); ?>/tickets/new" style="color: #7f54b3; font-weight: normal; text-decoration: underline;">Ask a question</a>&nbsp;|&nbsp;
				<a target="_blank" rel="noopener noreferrer" href="<?php echo home_url(); ?>/tickets/new" style="color: #7f54b3; font-weight: normal; text-decoration: underline;">&nbsp;Problems with the order</a>&nbsp;|&nbsp;
				<a target="_blank" rel="noopener noreferrer" href="https://t.me/pvamarkets" style="color: #7f54b3; font-weight: normal; text-decoration: underline;">5% discount on&nbsp;Telegram</a>&nbsp;
			</span>
		</p>


		<p style="text-align: center;" align="center"><span style="color: #999999; font-size: 12px;">The message was created automatically and it does not require a reply</span></p>
		<p style="text-align: center;" align="center"><span style="color: #999999; font-size: 12px;">Copyright ©&nbsp; Pvamarkets 2023.</span></p>
		<p style="text-align: center;" align="center">&nbsp;</p>

	<?php $body = ob_get_clean();
	error_log($body); // Add this line to log the email content
	$headers = array('Content-Type: text/html; charset=UTF-8','From: '. $site_name .' <noreply@'. $domain_name .'>');

	wp_mail( $to, $subject, $body, $headers );
}


add_action('wp_ajax_connect_item', 'connect_item_callback');
function connect_item_callback(){
	global $wpdb;
	$item_id = $_POST['item_id'];
	$partner_id=$_POST['partner_id'];
	$product_id = $_POST['product_id'];
	$item_partner_price = $_POST['item_partner_price'];
	$admin_percentage = get_theme_mod('admin_percentage');
	$new_price = $item_partner_price + (($admin_percentage / 100) * $item_partner_price);

// 	checking 


	  
	update_post_meta($product_id, 'item_id', $item_id);
	
		$item_pro_query = new WP_Query(array(
						'posts_per_page' => -1, 
						'post_type' => 'product',
						'post_status' => 'publish',
						'meta_query' => array(
							array(
								'key' => 'item_id', 
								'value' => $item_id
							)
						)
					));
					$itemIDbasedprice = [];
					while($item_pro_query->have_posts()){
						$item_pro_query->the_post(); 
						
						$product_id = get_the_ID();
						$product = wc_get_product( $product_id );
						$regular_price = $product->get_regular_price();
						
						$itemIDbasedprice[]= $regular_price ;
					}
					
					$minimumPrice = min($itemIDbasedprice);
				  
	    update_post_meta($item_id, 'item_price', $minimumPrice);
    
    // if(empty($price)){
    //     update_post_meta($item_id, 'item_price', $new_price);
    // }else{
    //   if( $price > $new_price){
    //       update_post_meta($item_id, 'item_price', $new_price);
    //   }else{
    //       update_post_meta($item_id, 'item_price', $price);
    //   }
    // }

	// Send emails to the filtered list
	$table_name = $wpdb->prefix . "subscribe_emails";
        
	// Fetch all subscriber emails from the database
	$get_subscriber = $wpdb->get_results("SELECT email FROM $table_name WHERE email != '' ORDER BY id DESC");
	
	// table truncate
	$wpdb->query("TRUNCATE TABLE {$wpdb->prefix}subscriber_pending_email");
	// Send emails to the filtered list
	foreach ($get_subscriber as $subscriber) {
		// send_subscription_emails_in_smtp($subscriber->email, get_permalink($item_id));
		// Insert subscriber_pending_email table
		$wpdb->insert(
			$wpdb->prefix . 'subscriber_pending_email', // Table name
			array(
				'email' => sanitize_email($subscriber->email), // Sanitize email
				'product_url' => esc_url(get_permalink($item_id)), // Sanitize URL
			),
			array('%s', '%s') // Data types for prepared statement
		);
	}

	// Terminate the script
	die();
	

}

// Program Logs
function put_program_logs( $data ) {

	// Ensure the directory for logs exists
	$directory = __DIR__ . '/program_logs/';
	if ( !file_exists( $directory ) ) {
		mkdir( $directory, 0777, true );
	}

	// Construct the log file path
	$file_name = $directory . 'program_logs.log';

	// Append the current datetime to the log entry
	$current_datetime = date( 'Y-m-d H:i:s' );
	$data             = $data . ' - ' . $current_datetime;

	// Write the log entry to the file
	if ( file_put_contents( $file_name, $data . "\n\n", FILE_APPEND | LOCK_EX ) !== false ) {
		return "Data appended to file successfully.";
	} else {
		return "Failed to append data to file.";
	}
}
add_action('wp_ajax_update_invalid_item', 'update_invalid_item_callback');
function update_invalid_item_callback(){
	global $wpdb;

	$table_name = $wpdb->prefix . "wallet_minus";

	$invalid_item = $_POST['invalid_item'];
	$order_item_id = $_POST['order_id'];
	$partner_id = $_POST['partner_id'];
	// $pre_qty_price = $_POST['pre_qty_price'];
	$pre_qty_partner_price = $_POST['pre_qty_partner_price'];
	$cost = $invalid_item * $pre_qty_partner_price;

	update_metadata('order_item', $order_item_id, 'invalid_items', $invalid_item);

	$data = array(
		'order_id' => $order_item_id,
		'partner_id' => $partner_id,
		'amount' => $cost,
		'status' => '1',
	);
	// Check if the record already exists
	$exists = $wpdb->get_var("SELECT COUNT(*) FROM $table_name WHERE order_id = $order_item_id");
	if ($exists > 0) {
		$wpdb->update($table_name, $data, array('order_id' => $order_item_id));
	}else{
		$wpdb->insert($table_name, $data);
	}

	echo $invalid_item;

	die();

}

function add_employee_roles_bcmarket() {
   add_role( 'employee', 'Employee', array( 'read' => true ) );
}
add_action('init', 'add_employee_roles_bcmarket');


add_action('wp_ajax_change_pp_price', 'change_pp_price_callback');
function change_pp_price_callback(){

		$price = $_POST['price'];
		$product_id = $_POST['product_id'];
		$item_id = $_POST['item_id'];
		
	
	    

		$admin_percentage = get_theme_mod('admin_percentage');
		$new_price = $price + (($admin_percentage / 100) * $price);

		$product = wc_get_product( $product_id );
		$product->set_regular_price( $new_price );
		$product->update_meta_data('partner_price', $price);

		$product->save();
		
		
			
			$item_p_query = new WP_Query(array(
						'posts_per_page' => -1, 
						'post_type' => 'product',
						'post_status' => 'publish',
						'meta_query' => array(
							array(
								'key' => 'item_id', 
								'value' => $item_id
							)
						)
					));
					$itemIDbasedprice = [];
					while($item_p_query->have_posts()){
						$item_p_query->the_post(); 
						
						$product_id = get_the_ID();
						$product = wc_get_product( $product_id );
						$regular_price = $product->get_regular_price();
						
						$itemIDbasedprice[]= $regular_price ;
					}
					
					$minimumPrice = min($itemIDbasedprice);
				  
	    update_post_meta($item_id, 'item_price', $minimumPrice);

		die();

}

function custom_product_filters() {
    ?>
    <input type="text" name="custom_product_id" placeholder="Search by Product Id">
    <?php
}
add_action( 'woocommerce_product_filters', 'custom_product_filters' );

function custom_product_search_meta_query( $query ) {
    global $pagenow, $wpdb;

    // Check if we're on the Products admin page and a search has been performed
    if ( $pagenow === 'edit.php' && isset( $_GET['s'] ) ) {

        // Get the search value from the custom meta field
        $search_value = isset( $_GET['custom_product_id'] ) ? sanitize_text_field( $_GET['custom_product_id'] ) : '';

        // If a search value is entered, add a meta query to search by the specified meta key
        if ( ! empty( $search_value ) ) {
            $meta_query_args = array(
                'relation' => 'OR',
                array(
                    'key'     => 'custom_product_id',
                    'value'   => $search_value,
                    'compare' => '=',
                ),
            );
            $query->set( 'meta_query', $meta_query_args );
        }
    }
}
add_action( 'pre_get_posts', 'custom_product_search_meta_query' );

add_action('wp_ajax_edit_ticket_message', 'edit_ticket_message_callback');
function edit_ticket_message_callback() {
    global $wpdb;
    $message_id = intval($_POST['message_id']);
    $new_message = stripslashes($_POST['message']); // Handle quotes
    $table = $wpdb->prefix . 'ticket_chats';

    if (!empty($message_id) && !empty($new_message)) {
        $updated = $wpdb->update(
            $table,
            array('message' => $new_message),
            array('id' => $message_id),
            array('%s'),
            array('%d')
        );
        
        if ($updated !== false) {
            echo $new_message;
        } else {
            echo 'error';
        }
    } else {
        echo 'error';
    }
    die();
}
