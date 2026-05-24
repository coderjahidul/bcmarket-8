
<?php 
add_action('wp_ajax_new_tickets', 'new_tickets_callback');
add_action('wp_ajax_nopriv_new_tickets', 'new_tickets_callback');
function new_tickets_callback(){

	$subject_id = $_POST['subject_id'];
	$user_id = $_POST['user_id'];
	$order_id_client = $_POST['order_id_client'];
	$message = $_POST['message'];
	$proxy = $_POST['field1'];
	$purchase_account = $_POST['field2'];
	$email = $_POST['email'];
	$json = array();

	if($user_id != 0){
		$user_data = get_userdata($user_id);
		$email = $user_data->user_email;
	}



	if($subject_id != 3 && empty($order_id_client)){
		$json['err'] = 'Pleae enter order id.';
		die(json_encode($json));
	}

	if($subject_id == 1 && empty($proxy)){
		$json['err'] = 'Pleae enter the proxy.';
		die(json_encode($json));
	}

	if($subject_id == 1 && empty($purchase_account)){
		$json['err'] = 'How did you check your purchased accounts is required';
		die(json_encode($json));
	}

	if($subject_id == 1 && empty($message)){
		$json['err'] = 'Message is required';
		die(json_encode($json));
	}

	if($subject_id == 2 && empty($message)){
		$json['err'] = 'Message is required';
		die(json_encode($json));
	}

	if($subject_id == 3 && empty($message)){
		$json['err'] = 'Message is required';
		die(json_encode($json));
	}

	/*if($user_id == 0 && empty($order_id_client)){
		$json['err'] = 'Pleae enter email address.';
		die(json_encode($json));
	}*/ 

	$argsss = array(
	    'post_type' => 'tickets',
	    'posts_per_page' => 1,
	    'orderby' => 'ID',
	    'order' => 'DESC'
	);
	$latest_postsss = new WP_Query( $argsss );
	if ( $latest_postsss->have_posts() ) {

		while($latest_postsss->have_posts()){
			$latest_postsss->the_post();
			$latest_sequential_number = intval(get_post_meta( get_the_ID(), 'ticket_id', true ));
		} wp_reset_postdata();
	    
	}

	if ( $latest_sequential_number ) {
	    $new_sequential_number = $latest_sequential_number + 1;
	} else {
	    $new_sequential_number = 1;
	}

	$my_ticket = array(
	  'post_title'    => 'Tickets ',
	  'post_status'   => 'publish',
	  'post_type' => 'tickets'
	);

	// Insert the post into the database
	$ticket_id = wp_insert_post( $my_ticket );
	$unique_hash = uniqid() . mt_rand(1000,10000)  . $ticket_id;


	if($ticket_id){
		wp_update_post( array(
	        'ID' => $ticket_id,
	        'post_name' => $ticket_id, 
	        'post_title' => 'Tickets #' . $new_sequential_number
	    ));


	    update_post_meta($ticket_id, 'subject_id', $subject_id);
	    update_post_meta( $ticket_id, 'ticket_id', $new_sequential_number );

	    if($subject_id == 1){
	    	update_post_meta($ticket_id, 'subject_title', 'I have problems with the product');
	    }
	    if($subject_id == 2){
	    	update_post_meta($ticket_id, 'subject_title', 'I did not receive the product automatically');
	    }
	    if($subject_id == 3){
	    	update_post_meta($ticket_id, 'subject_title', 'I have a simple question/Need consult');
	    }

	    update_post_meta($ticket_id, 'order_id_client', $order_id_client);
	    update_post_meta($ticket_id, 'message', $message);
	    update_post_meta($ticket_id, 'proxy', $proxy);
	    update_post_meta($ticket_id, 'purchase_account', $purchase_account);
	    update_post_meta($ticket_id, 'email', $email);
	    update_post_meta($ticket_id, 'unique_hash', $unique_hash);
	    update_post_meta($ticket_id, 'user_id', $user_id);
	    update_post_meta($ticket_id, 'unread', 0);
		update_post_meta($ticket_id, '_solved_unsolved', 'unsolved');
	}

	if($user_id == 0){
		$json['redirect'] = get_permalink($ticket_id) . '?hash=' . $unique_hash;
	}else{
		$json['redirect'] = home_url('/your-account/tickets/') . '?id=' . $ticket_id;
	}

	do_action('send_new_ticket_email', $ticket_id);  

	
	die(json_encode($json));

}


function send_new_ticket_email_callback($post_id){


	$to =  get_post_meta($post_id, 'email', true);
	$site_name = get_bloginfo('name');
	$domain_name = parse_url( get_site_url(), PHP_URL_HOST );

	$subject = 'Ticket #' . get_post_meta($post_id, 'ticket_id', true) . ' was created';

	$args = array(
		'post_id' => $post_id
	);

	ob_start(); 

		get_template_part('emails/new', 'tickets', $args);

	$body = ob_get_clean(); 

	

	$headers = array('Content-Type: text/html; charset=UTF-8','From: '. $site_name .' <noreply@'. $domain_name .'>');

	wp_mail( $to, $subject, $body, $headers );


}
add_action('send_new_ticket_email', 'send_new_ticket_email_callback');

function send_reply_ticket_email_callback($post_id, $reply_message = ''){

	global $wpdb;
	
	// Get the LAST message from the Partner/User
	$table = $wpdb->prefix.'ticket_chats';
	$last_user_msg = $wpdb->get_row( "SELECT message FROM $table WHERE ticket_id = $post_id AND from_user = 0 ORDER BY id DESC LIMIT 1" );
	
	if ($last_user_msg) {
		$user_message = $last_user_msg->message;
	} else {
		// Fallback to the initial ticket message if no chats found
		$user_message = get_post_meta($post_id, 'message', true);
	}

	$to =  get_post_meta($post_id, 'email', true);
	$site_name = get_bloginfo('name');
	$domain_name = parse_url( get_site_url(), PHP_URL_HOST );

	$subject = 'New reply to Ticket #' . get_post_meta($post_id, 'ticket_id', true);

	$args = array(
		'post_id' => $post_id,
		'reply_message' => $reply_message,
		'user_message' => $user_message // This is now the Last Partner Message
	);

	ob_start(); 

		get_template_part('emails/reply', 'tickets', $args);

	$body = ob_get_clean(); 

	

	$headers = array('Content-Type: text/html; charset=UTF-8','From: '. $site_name .' <noreply@'. $domain_name .'>');

	wp_mail( $to, $subject, $body, $headers );


}
add_action('send_reply_ticket_email', 'send_reply_ticket_email_callback', 10, 2);

function bcmarket_create_tickets_table() {
 
    global $wpdb;
 
    $table_name = $wpdb->prefix . "ticket_chats";
 
    $charset_collate = $wpdb->get_charset_collate();
 
    $sql = "CREATE TABLE IF NOT EXISTS $table_name (
      id bigint(20) NOT NULL AUTO_INCREMENT,
      ticket_id bigint(20) UNSIGNED NOT NULL,
      from_user bigint(20) UNSIGNED NOT NULL,
      message TEXT,
      attach_ids varchar(255),
      created_at datetime NOT NULL,
      PRIMARY KEY id (id)
    ) $charset_collate;";
 
    require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
    dbDelta($sql);
}    
 
add_action('init', 'bcmarket_create_tickets_table');


add_action('wp_ajax_add_ticket_message', 'add_ticket_message_callback');
add_action('wp_ajax_nopriv_add_ticket_message', 'add_ticket_message_callback');
function add_ticket_message_callback(){

	global $wpdb;

	$json = array();
	$message = $_POST['message'];
	$from_user = $_POST['from_user'];
	$ticket_id = $_POST['ticket_id'];
	$attach_files = $_POST['attach_files'];

	$attach_ids_arr = array();
	$attach_save_data = '';

	if($attach_files){
		foreach($attach_files as $attach_file){
			$attach_ids_arr[] = $attach_file;
		}

		$attach_save_data = implode(',', $attach_ids_arr);
	}



	if(!empty($ticket_id)){
		$data['ticket_id'] = $ticket_id;
		$data['message'] = $message;
		$data['from_user'] = $from_user;
		$data['attach_ids'] = $attach_save_data;

		$my_post = array(
		    'ID'            => $ticket_id,
		);
		wp_update_post( $my_post );

		$table = $wpdb->prefix.'ticket_chats';

		$format = array('%s','%d');
		$wpdb->insert($table,$data);
		$my_id = $wpdb->insert_id;
       update_post_meta($ticket_id, 'unread', 0);

       // Send email notification for the reply
       if ($from_user == 1) { // If message is from support (admin)
           do_action('send_reply_ticket_email', $ticket_id, $message);
       }
       
		ob_start(); 

            	$table = $wpdb->prefix.'ticket_chats';
				$results = $wpdb->get_results( "SELECT * FROM $table WHERE ticket_id = $ticket_id AND id = $my_id" );

				if($results) : foreach($results as $result) :  

				$is_admin_sender = ($result->from_user == 1);
				$is_me = ($from_user == $result->from_user);
				
				// In public view (no logged in user or user_id=0 context), we need to ensure correct "You" labeling
				// If $from_user is 0 (guest/partner) and message is from 0, it is "You".
				// If $from_user is 1 (admin) and message is from 1, it is "You".
				// However, if we are in admin panel, $from_user passed via AJAX is 1.
				// If we are in partner panel/public view, $from_user passed via AJAX is 0.
				// The logic $is_me = ($from_user == $result->from_user) handles this correctly for the *sender*.
				
				// BUT, we also need to handle the label text correctly.
				// If I am Admin ($from_user=1) -> Sender 1 is "You", Sender 0 is "Client".
				// If I am Partner ($from_user=0) -> Sender 0 is "You", Sender 1 is "Support".
				
				$author_label = 'You';
				if (!$is_me) {
					if ($from_user == 1) {
						// I am Admin, message is not from me (so from Client)
						$author_label = 'Client';
					} else {
						// I am Partner, message is not from me (so from Support)
						$author_label = 'Support';
					}
				}
				?>
				<div id="m<?php echo $result->id; ?>" class="message-msg <?php echo ($is_me) ? 'self' : 'voice'; ?>">
		            <div class="msg-section-author">
		                <span class="author"><?php echo $author_label; ?></span>
		                <span class="date-msg"><?php echo $result->created_at; ?></span>
		                <span class="status-msg read" data-message-id-is-readable="<?php echo $result->id; ?>"></span>
		                <?php if($is_admin_sender && $is_me) : ?>
		                <span class="edit-msg-icon" data-id="<?php echo $result->id; ?>" style="cursor:pointer; margin-left:5px; color:#6e5b5b; font-weight:bold; background-color: #000; padding: 2px 5px; border-radius: 4px;" title="Edit Message"><i class="fa-solid fa-pencil" aria-hidden="true"></i></span>
		                <?php endif; ?>
		            </div>
		            <section class="section-msg">
                        <span class="ip"></span>
                        <div class="m">
                            <span class="msg-content" id="msg-content-<?php echo $result->id; ?>" style="white-space: break-spaces;"><?php echo processMessage($result->message); ?></span>
                            <div class="cb"></div>
                            <div class="attachments">
                            	<?php 
                            		if(!empty($result->attach_ids)){
                            			$attachment_ids = explode(',', $result->attach_ids);

                            			if($attachment_ids){
                            				foreach($attachment_ids as $attach_id){
                            					$parsed = wp_get_attachment_url( $attach_id );

                            					if(!empty($parsed)) : 
                            					$filename_only = basename( get_attached_file( $attach_id ) ); ?>
                            						<div>
					                                    <a href="<?php echo $parsed; ?>" target="_blank"><?php echo $filename_only; ?></a>
					                                </div>
                            					<?php endif;
                            				}
                            			}
                            		}
                            	?>
                            </div>
                        </div>
                    </section>
                </div>
            <?php endforeach; endif;

		$chat_html = ob_get_clean();

		if($my_id){
			$json['success'] = 1;
			$data['chat_html'] = $chat_html;
			$json['data'] = $data;
		}
	}

	
	die(json_encode($json));

}

add_action('wp_ajax_upload_attachments', 'upload_attachments_callback');
add_action('wp_ajax_nopriv_upload_attachments', 'upload_attachments_callback'); // Allow front-end submission
function upload_attachments_callback(){
    

    $valid_formats = array("jpg", "png", "gif", "bmp", "jpeg"); // Supported file types
    $max_file_size = 1024 * 500; // in kb
    $max_image_upload = 10; // Define how many images can be uploaded to the current post
    
    // Force uploads into /uploads/ticket.
    $wp_upload_dir = wp_upload_dir();
    // $path = $wp_upload_dir['path'] . '/';
    $year  = date( 'Y' );
    $month = date( 'm' );
    $ticket_dir = trailingslashit( $wp_upload_dir['basedir'] ) . 'ticket/' . $year . '/' . $month;
    $ticket_url = trailingslashit( $wp_upload_dir['baseurl'] ) . 'ticket/' . $year . '/' . $month;
    if ( ! file_exists( $ticket_dir ) ) {
        wp_mkdir_p( $ticket_dir );
    }

    // Helper to adjust upload target directory.
    $upload_dir_filter = function( $dirs ) use ( $ticket_dir, $ticket_url ) {
        $dirs['path']   = $ticket_dir;
        $dirs['url']    = $ticket_url;
        $dirs['subdir'] = str_replace( $dirs['basedir'], '', $ticket_dir );
        return $dirs;
    };
    $count = 0;

   
    $attach_ids = array();

  	if(  count( $_FILES['files']['name'] )  < $max_image_upload ) {

  		foreach ( $_FILES['files']['name'] as $f => $name ) {

  			  

  			$picture['tmp_name'] =   $_FILES['files']['tmp_name'][$f];      

		    $picture['name'] = preg_replace( '/[^0-9a-zA-Z.]/', '', basename( $_FILES['files']['name'][$f] ) );
		    $picture['type'] = $_FILES['files']['type'][$f];
		    $picture['size'] = $_FILES['files']['size'][$f];
		    $picture['error'] = $_FILES['files']['error'][$f];


		    //$upload_result = wp_handle_upload($picture);
		  //  $upload_result = wp_upload_bits($_FILES["files"]["name"][$f], null, file_get_contents($_FILES["files"]["tmp_name"][$f]));
		    
		    // Upload into the ticket folder.
            add_filter( 'upload_dir', $upload_dir_filter );
            $upload_result = wp_handle_upload( $picture, array( 'test_form' => false ) );
            remove_filter( 'upload_dir', $upload_dir_filter );


		    if( ! isset( $upload_result['error'] ) && ! empty( $upload_result['url'] ) ) {

		            $attachment = array(
		                'guid'           => $upload_result['url'], 
		                'post_mime_type' => $_FILES['files']['type'][$f],
		                'post_title'     => $_FILES['files']['name'][$f],
		                'post_content'   => '',
		                'post_status'    => 'inherit'
		            );


		            $attach_id = wp_insert_attachment( $attachment, $upload_result['file'] );

		            $attach_ids[] = $attach_id;

		            $filename_only = basename( get_attached_file( $attach_id ) );

		            ?>

		            	<div>
		            		<div class="close"></div>
		            		<input type="hidden" name="attach_files[]" value="<?php echo $attach_id; ?>"><?php echo $filename_only; ?>
		            	</div>

		            <?php 

		    }

  		}

  	}


  	wp_die();
       

}


add_action('wp_ajax_show_ticket_chat_list', 'show_ticket_chat_list_callback');
add_action('wp_ajax_nopriv_show_ticket_chat_list', 'show_ticket_chat_list_callback'); // Allow front-end submission
function show_ticket_chat_list_callback(){

	global $wpdb;
    $table = $wpdb->prefix.'ticket_chats';

	$is_admin_chat = '';

	$ticket_id = $_POST['id'];
	$is_admin_chat = $_POST['admin_chat'];
	$json = array(); 

	if($is_admin_chat == 1){
		$results = $wpdb->get_results( "SELECT * FROM $table WHERE ticket_id = $ticket_id AND from_user = 0" );
	}else{
		$results = $wpdb->get_results( "SELECT * FROM $table WHERE ticket_id = $ticket_id AND from_user = 1" );
	}
	

    if($results){
        foreach($results as $item){
            $id = $item->id;
            $wpdb->update(
                $table,
                array( 'chat_read' => 1 ),
                array( 'id' => $id ),
                array( '%d' ),
                array( '%d' )
            );

        }
    }

    update_post_meta($ticket_id, 'unread', 1);



	ob_start();
	?>
    <?php
    $initial_msg_class = ($is_admin_chat == 1) ? 'voice' : 'self';
    $initial_msg_author = ($is_admin_chat == 1) ? 'Client' : 'You';
    ?>
    <div id="m823580" class="message-msg <?php echo $initial_msg_class; ?>">
        <div class="msg-section-author">
            <span class="author"><?php echo $initial_msg_author; ?></span>
            <span class="date-msg"><?php echo get_the_date('', $ticket_id); echo ' '; echo get_the_time('', $ticket_id); ?></span>
            <span class="status-msg read" data-message-id-is-readable="823580"></span>
        </div>
        <section class="section-msg">
            <span class="ip"></span>
            <div class="m">
                <span class="msg-content" id="msg-content-823580" style="white-space: break-spaces;" <?php if($initial_msg_class == 'voice'){ echo 'ondblclick="tickets.textarea_dialog(823580)"'; } ?>>
                	<?php 
						$message = get_post_meta($ticket_id, 'message', true); 
						echo processMessage($message);
					
					?>
                </span>
                <div class="cb"></div>
                <div class="attachments"></div>
            </div>
        </section>
    </div>

    <?php 

	global $wpdb;
	$table = $wpdb->prefix.'ticket_chats';
	$results = $wpdb->get_results( "SELECT * FROM $table WHERE ticket_id = $ticket_id" );

	if($results) : foreach($results as $result) : 
		
		// Determine if the message is from the current viewer ("You")
		$is_me = false;
		if ($is_admin_chat == 1 && $result->from_user == 1) {
			$is_me = true;
		} elseif ($is_admin_chat != 1 && $result->from_user == 0) {
			$is_me = true;
		}

		// Handle labels for "Other" person
		$author_label = 'Support'; // Default for partner viewing admin msg
		if ($is_admin_chat == 1) {
			$author_label = 'Client'; // Admin viewing client msg
		}

		if($is_me) : ?>

		<div id="m<?php echo $result->id; ?>" class="message-msg self">
                <div class="msg-section-author">
                    <span class="author">You</span>
                    <span class="date-msg"><?php echo $result->created_at; ?></span>
                    <span class="status-msg read" data-message-id-is-readable="<?php echo $result->id; ?>"></span>
                    <?php if($is_admin_chat == 1) : // Only Admin can edit their own messages ?>
                    <span class="edit-msg-icon" data-id="<?php echo $result->id; ?>" style="cursor:pointer; margin-left:5px; color:#6e5b5b; font-weight:bold; background-color: #000; padding: 2px 5px; border-radius: 4px;" title="Edit Message"><i class="fa-solid fa-pencil" aria-hidden="true"></i></span>
                    <?php endif; ?>
                </div>
                <section class="section-msg">
                    <span class="ip"></span>
                    <div class="m">
                        <span class="msg-content" id="msg-content-<?php echo $result->id; ?>" style="white-space: break-spaces;"><?php echo processMessage($result->message); ?></span>
                        <div class="cb"></div>
                    <div class="attachments">
                    	<?php 
                    		if(!empty($result->attach_ids)){
                    			$attachment_ids = explode(',', $result->attach_ids);

                    			if($attachment_ids){
                    				foreach($attachment_ids as $attach_id){
                    					$parsed = wp_get_attachment_url( $attach_id );

                    					if(!empty($parsed)) : 
                    					$filename_only = basename( get_attached_file( $attach_id ) ); ?>
                    						<div>
			                                    <a href="<?php echo $parsed; ?>" target="_blank"><?php echo $filename_only; ?></a>
			                                </div>
                    					<?php endif;
                    				}
                    			}
                    		}
                    	?>
                    </div>
                </div>
            </section>
        </div>
        <?php else : ?>
        	<div id="m<?php echo $result->id; ?>" class="message-msg voice">
		                    <div class="msg-section-author">
		                        <span class="author"><?php echo $author_label; ?></span>
		                        <span class="date-msg"><?php echo $result->created_at; ?></span>
		                        <span class="status-msg read" data-message-id-is-readable="<?php echo $result->id; ?>"></span>
		                    </div>
		                    <section class="section-msg">
		                        <span class="ip"></span>
		                        <div class="m">
		                            <span class="msg-content" id="msg-content-<?php echo $result->id; ?>" style="white-space: break-spaces;" <?php if($is_admin_chat != 1) { echo 'ondblclick="tickets.textarea_dialog('.$result->id.')"'; } ?>><?php echo processMessage($result->message); ?></span>
		                            <div class="cb"></div>
                        <div class="attachments">
                        	<?php 
                        		if(!empty($result->attach_ids)){
                        			$attachment_ids = explode(',', $result->attach_ids);

                        			if($attachment_ids){
                        				foreach($attachment_ids as $attach_id){
                        					$parsed = wp_get_attachment_url( $attach_id );

                        					if(!empty($parsed)) : 
                        					$filename_only = basename( get_attached_file( $attach_id ) ); ?>
                        						<div>
				                                    <a href="<?php echo $parsed; ?>" target="_blank"><?php echo $filename_only; ?></a>
				                                </div>
                        					<?php endif;
                        				}
                        			}
                        		}
                        	?>
                        </div>
                    </div>
                </section>
            </div>
    <?php endif; endforeach; endif; 

    $json['chat_list_html'] = ob_get_clean();

    ob_start(); ?>
		
		<?php if(current_user_can('administrator') || current_user_can('wpseo_manager')){?>
		<div class="solved-unsolved-section">
			<?php
			
			// Retrieve and display the initial state
			$meta_value = get_post_meta($ticket_id, '_solved_unsolved', true);
		
			// Check if the meta value exists
			if ($meta_value == "solved") {
				?>
				<div id="solved" style="display: block;">
					<p style="color: green">The problem is solved!😊</p>
				</div>
			<?php
			} else {
				?>
				<div id="unsolved" style="display: block;">
					<p style="color: red">This is a problem that needs solving!😭</p>
				</div>
			<?php
			}
			?>
		
			<div class="solved_unsolved_button_section">
				<?php
				// Assuming $meta_value and $ticket_id are defined somewhere in your code
				if ($meta_value == "unsolved" || $meta_value == NULL) {
				?>
					<button class="solved-btn" type="button">Solved</button>
				<?php } else { ?>
					<button class="unsolved-btn" type="button">Unsolved</button>
				<?php } ?>
			</div>
			<script>
				jQuery(document).ready(function($){
					$(".solved-btn").click(function(){
						let ticket_id = <?php echo $ticket_id; ?>;
						$.ajax({
							type: "POST",
							url: "<?php echo admin_url('admin-ajax.php'); ?>",
							data: {
								action: 'ticket_solved_function',
								ticket_id: ticket_id
							},
							success: function(data){
								location.reload();
							},
							error: function(xhr, status, error) {
								console.log(xhr.responseText);
							}
							
						});
					});
					$(".unsolved-btn").click(function(){
						let ticket_id = <?php echo $ticket_id; ?>;
						$.ajax({
							type: "POST",
							url: "<?php echo admin_url('admin-ajax.php'); ?>",
							data: {
								action: 'ticket_unsolved_function',
								ticket_id: ticket_id
							},
							success: function(data){
								location.reload();
							},
							error: function(xhr, status, error) {
								console.log(xhr.responseText);
							}
						});
					});
				});
			</script>

		</div>
		<?php } ?>
		

    <form id="form-ticket-send-msg-js" method="post" enctype="multipart/form-data">
	    <div class="ticket-input-section">
	        <textarea name="message" id="message-input" autocomplete="off" placeholder="Write a message" class="ui-autocomplete-input" required="required" ></textarea>
	        <div class="file-input-wrapper">
	            <input type="file" name="file" id="file_trigger" multiple="" />
	            <label for="file_trigger"></label>
	        </div>
	        <button type="submit">Send</button>
	    </div>
	    <input type="hidden" name="ticket_id" id="ticket_id" value="<?php echo $ticket_id; ?>" />

	    <?php if($is_admin_chat == 1) : ?>
	    	<input type="hidden" name="from_user" value="1">
	    <?php else : ?>
		    <input type="hidden" name="from_user" value="0">
		<?php endif; ?>
	    <input type="hidden" name="action" value="add_ticket_message" />
	    <div class="attachments editable" id="attachments"></div>
	</form>
	<?php $json['chat_form_html'] = ob_get_clean();


	die(json_encode($json));

}


add_action('wp_ajax_show_ticket_list_search', 'show_ticket_list_search_callback');
add_action('wp_ajax_nopriv_show_ticket_list_search', 'show_ticket_list_search_callback');
function show_ticket_list_search_callback(){

	global $wpdb;

	$admin_chat = '';

	$keyword = $_POST['keyword'];
	$admin_chat = $_POST['admin_chat'];

	if($admin_chat == 1){
		$tickets_query_key = new WP_Query(array(
			'post_type' => 'tickets', 
			'posts_per_Page' => -1,
			'meta_query' => array(
				array(
					'key' => 'ticket_id', 
					'value' => $keyword
				)
			)
		));

		$tickets_query = new WP_Query(array(
			'post_type' => 'tickets', 
			'posts_per_Page' => -1, 
		));
	}else{

		$tickets_query_key = new WP_Query(array(
			'post_type' => 'tickets', 
			'posts_per_Page' => -1, 
			'meta_query' => array(
				array(
					'key' => 'user_id', 
					'value' => get_current_user_id()
				), 
				array(
					'key' => 'ticket_id', 
					'value' => $keyword
				)
			), 
		));

		$tickets_query = new WP_Query(array(
			'post_type' => 'tickets', 
			'posts_per_Page' => -1, 
			'meta_query' => array(
				array(
					'key' => 'user_id', 
					'value' => get_current_user_id()
				)
			)
		));

	}

	


	if($tickets_query_key->have_posts()){

		while($tickets_query_key->have_posts()) : $tickets_query_key->the_post(); 

			if($admin_chat == 1) : 
				$active_class = '';
        		if(isset($_GET['id']) && !empty($_GET['id']) && $_GET['id'] == get_the_ID()){
        			$active_class = 'active';
        		}

        		$user_id = get_post_meta(get_the_ID(), 'user_id', true); 
        		$listing_id = get_post_meta(get_the_ID(), 'listing_id', true); 
        		$user = get_userdata( $user_id );
        		$ticket_id = get_the_ID();
        		$chat_read = 1; 
        		$last_message = ''; 
        		$last_time = ''; 

        		$results = $wpdb->get_results( "SELECT * FROM $table WHERE ticket_id = $ticket_id  AND from_user = 1 ORDER BY id DESC LIMIT 1" );
        		$all_result = $wpdb->get_results( "SELECT * FROM $table WHERE ticket_id = $ticket_id  ORDER BY id DESC LIMIT 1" );

        		if($results){
        			foreach($results as $item){
        				if($item->chat_read != 1){
        					$chat_read = 0;
        				}
        			}
        		}else{
        			$chat_read = get_post_meta(get_the_ID(), 'unread', true);
        		}

        		if($all_result){
        			foreach($all_result as $item){
        				$last_message = $item->message;
        				$last_time = $item->created_at;
        			}
        		}


        	 	?>
                <div class="<?php echo $active_class; ?> ticket_list_item <?php if($chat_read == 0){echo 'has_unread_chat';} ?>" data-id="<?php echo get_the_ID(); ?>" data-admin="1">
                    <p class="ticket-list-email">
                        <span class="ticket-list-item-ticket-id">#<?php echo get_post_meta(get_the_ID(), 'ticket_id', true); ?></span>
                        <span class="ticket-list-item-email">
                            <a href="#" title="<?php echo get_post_meta(get_the_ID(), 'subject_title', true); ?>">
                            	<?php echo get_post_meta(get_the_ID(), 'subject_title', true); ?>
                            </a>
                        </span>
                        &nbsp;
                        <span data-item-client-unread-count-js="" style="display: none;"> </span>
                    </p>
                    <p class="ticket-list-last-time">
                        <span data-item-last-message-datetime-js=""><?php echo get_the_date(); ?> <?php echo get_the_time(); ?></span>
                    </p>
                    <p class="ticket-list-message">
                        <span data-item-message-js="">
                        	<?php echo $last_message; ?> 
                        </span>
                    </p>
                    <p class="ticket_extra_info">
                    	<?php if(get_post_meta(get_the_ID(), 'order_id_client', true)) : ?>
                    		Order Id: <?php echo get_post_meta(get_the_ID(), 'order_id_client', true); ?>
                    	<?php endif;  ?>
                    	<?php if(get_post_meta(get_the_ID(), 'order_id_client', true)) : ?>
                    		Partner Id: 
                    		<?php 
                    			$order_id =  get_post_meta(get_the_ID(), 'order_id_client', true);

                    			$order = wc_get_order( $order_id );

                    			if($order){
                    				foreach ( $order->get_items() as $item_id => $item ) {
									   $product_id = $item->get_product_id();
									   echo get_post_field( 'post_author', $product_id );
									}
                    			}

                    			
                    		 ?>
                    	<?php endif;  ?>
                    	<?php if(get_post_meta(get_the_ID(), 'user_id', true)) : ?>
                    		User Id: <?php echo get_post_meta(get_the_ID(), 'user_id', true); ?>
                    	<?php endif;  ?> <br>
                    	<?php if(get_post_meta(get_the_ID(), 'proxy', true)) : ?>
                    		Proxy: <?php echo get_post_meta(get_the_ID(), 'proxy', true); ?>
                    	<?php endif;  ?>
                    	<?php if(get_post_meta(get_the_ID(), 'purchase_account', true)) : ?>
                    		Purchased accounts: <?php echo get_post_meta(get_the_ID(), 'purchase_account', true); ?>
                    	<?php endif;  ?>
                    </p>
                </div>

			<?php 
		else : 
			$active_class = '';
    		if(isset($_GET['id']) && !empty($_GET['id']) && $_GET['id'] == get_the_ID()){
    			$active_class = 'active';
    		}

    		$user_id = get_post_meta(get_the_ID(), 'user_id', true); 
    		$listing_id = get_post_meta(get_the_ID(), 'listing_id', true); 
    		$user = get_userdata( $user_id );
    		$ticket_id = get_the_ID();
    		$chat_read = 1; 
    		$last_message = ''; 
    		$last_time = ''; 

    		$results = $wpdb->get_results( "SELECT * FROM $table WHERE ticket_id = $ticket_id  AND from_user = 0 ORDER BY id DESC LIMIT 1" );
    		$all_result = $wpdb->get_results( "SELECT * FROM $table WHERE ticket_id = $ticket_id  ORDER BY id DESC LIMIT 1" );

    		if($results){
    			foreach($results as $item){
    				if($item->chat_read != 1){
    					$chat_read = 0;
    				}
    			}
    		}

    		if($all_result){
    			foreach($all_result as $item){
    				$last_message = $item->message;
    				$last_time = $item->created_at;
    			}
    		}

        ?>
            <div class="<?php echo $active_class; ?> ticket_list_item <?php if($chat_read == 0){echo 'has_unread_chat';} ?>" data-id="<?php echo get_the_ID(); ?>" data-time="">
                <p class="ticket-list-email">
                    <span class="ticket-list-item-ticket-id">
                    	Ticket#<?php echo get_post_meta(get_the_ID(),'ticket_id', true ); ?> | 
                    	Order#<?php echo get_post_meta(get_the_ID(), 'order_id_client', true); ?>
                    </span>
                </p>
                <p class="ticket-list-last-time">
                    <span data-item-last-message-datetime-js=""><?php echo get_the_date(); ?> <?php echo get_the_time(); ?></span>
                </p>
                <p class="ticket-list-message">
                    <span data-item-message-js="" title="H"><?php echo get_post_meta(get_the_ID(), 'subject_title', true); ?></span>
                </p>
            </div> <?php 
		endif;

		endwhile; wp_reset_postdata();

	}else{


		while($tickets_query->have_posts()) : $tickets_query->the_post(); 

			if($admin_chat == 1) : 
				$active_class = '';
        		if(isset($_GET['id']) && !empty($_GET['id']) && $_GET['id'] == get_the_ID()){
        			$active_class = 'active';
        		}

        		$user_id = get_post_meta(get_the_ID(), 'user_id', true); 
        		$listing_id = get_post_meta(get_the_ID(), 'listing_id', true); 
        		$user = get_userdata( $user_id );
        		$ticket_id = get_the_ID();
        		$chat_read = 1; 
        		$last_message = ''; 
        		$last_time = ''; 

        		$results = $wpdb->get_results( "SELECT * FROM $table WHERE ticket_id = $ticket_id  AND from_user = 1 ORDER BY id DESC LIMIT 1" );
        		$all_result = $wpdb->get_results( "SELECT * FROM $table WHERE ticket_id = $ticket_id  ORDER BY id DESC LIMIT 1" );

        		if($results){
        			foreach($results as $item){
        				if($item->chat_read != 1){
        					$chat_read = 0;
        				}
        			}
        		}else{
        			$chat_read = get_post_meta(get_the_ID(), 'unread', true);
        		}

        		if($all_result){
        			foreach($all_result as $item){
        				$last_message = $item->message;
        				$last_time = $item->created_at;
        			}
        		}


        	 	?>
                <div class="<?php echo $active_class; ?> ticket_list_item <?php if($chat_read == 0){echo 'has_unread_chat';} ?>" data-id="<?php echo get_the_ID(); ?>" data-admin="1">
                    <p class="ticket-list-email">
                        <span class="ticket-list-item-ticket-id">#<?php echo get_post_meta(get_the_ID(), 'ticket_id', true); ?></span>
                        <span class="ticket-list-item-email">
                            <a href="#" title="<?php echo get_post_meta(get_the_ID(), 'subject_title', true); ?>">
                            	<?php echo get_post_meta(get_the_ID(), 'subject_title', true); ?>
                            </a>
                        </span>
                        &nbsp;
                        <span data-item-client-unread-count-js="" style="display: none;"> </span>
                    </p>
                    <p class="ticket-list-last-time">
                        <span data-item-last-message-datetime-js=""><?php echo get_the_date(); ?> <?php echo get_the_time(); ?></span>
                    </p>
                    <p class="ticket-list-message">
                        <span data-item-message-js="">
                        	<?php echo $last_message; ?> 
                        </span>
                    </p>
                    <p class="ticket_extra_info">
                    	<?php if(get_post_meta(get_the_ID(), 'order_id_client', true)) : ?>
                    		Order Id: <?php echo get_post_meta(get_the_ID(), 'order_id_client', true); ?>
                    	<?php endif;  ?>
                    	<?php if(get_post_meta(get_the_ID(), 'order_id_client', true)) : ?>
                    		Partner Id: 
                    		<?php 
                    			$order_id =  get_post_meta(get_the_ID(), 'order_id_client', true);

                    			$order = wc_get_order( $order_id );

                    			if($order){
                    				foreach ( $order->get_items() as $item_id => $item ) {
									   $product_id = $item->get_product_id();
									   echo get_post_field( 'post_author', $product_id );
									}
                    			}

                    			
                    		 ?>
                    	<?php endif;  ?>
                    	<?php if(get_post_meta(get_the_ID(), 'user_id', true)) : ?>
                    		User Id: <?php echo get_post_meta(get_the_ID(), 'user_id', true); ?>
                    	<?php endif;  ?> <br>
                    	<?php if(get_post_meta(get_the_ID(), 'proxy', true)) : ?>
                    		Proxy: <?php echo get_post_meta(get_the_ID(), 'proxy', true); ?>
                    	<?php endif;  ?>
                    	<?php if(get_post_meta(get_the_ID(), 'purchase_account', true)) : ?>
                    		Purchased accounts: <?php echo get_post_meta(get_the_ID(), 'purchase_account', true); ?>
                    	<?php endif;  ?>
                    </p>
                </div>

			<?php 
		else : 
			if(isset($_GET['id']) && !empty($_GET['id']) && $_GET['id'] == get_the_ID()){
    			$active_class = 'active';
    		}

    		$user_id = get_post_meta(get_the_ID(), 'user_id', true); 
    		$listing_id = get_post_meta(get_the_ID(), 'listing_id', true); 
    		$user = get_userdata( $user_id );
    		$ticket_id = get_the_ID();
    		$chat_read = 1; 
    		$last_message = ''; 
    		$last_time = ''; 

    		$results = $wpdb->get_results( "SELECT * FROM $table WHERE ticket_id = $ticket_id  AND from_user = 0 ORDER BY id DESC LIMIT 1" );
    		$all_result = $wpdb->get_results( "SELECT * FROM $table WHERE ticket_id = $ticket_id  ORDER BY id DESC LIMIT 1" );

    		if($results){
    			foreach($results as $item){
    				if($item->chat_read != 1){
    					$chat_read = 0;
    				}
    			}
    		}

    		if($all_result){
    			foreach($all_result as $item){
    				$last_message = $item->message;
    				$last_time = $item->created_at;
    			}
    		}

        ?>
            <div class="<?php echo $active_class; ?> ticket_list_item <?php if($chat_read == 0){echo 'has_unread_chat';} ?>" data-id="<?php echo get_the_ID(); ?>" data-time="">
                <p class="ticket-list-email">
                    <span class="ticket-list-item-ticket-id">
                    	Ticket#<?php echo get_post_meta(get_the_ID(),'ticket_id', true ); ?> | 
                    	Order#<?php echo get_post_meta(get_the_ID(), 'order_id_client', true); ?>
                    </span>
                </p>
                <p class="ticket-list-last-time">
                    <span data-item-last-message-datetime-js=""><?php echo get_the_date(); ?> <?php echo get_the_time(); ?></span>
                </p>
                <p class="ticket-list-message">
                    <span data-item-message-js="" title="H"><?php echo get_post_meta(get_the_ID(), 'subject_title', true); ?></span>
                </p>
            </div> <?php 
		endif;

		endwhile; wp_reset_postdata();

	}

	die();

}
?>
