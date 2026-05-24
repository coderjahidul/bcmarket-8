<link rel="stylesheet" type="text/css" href="<?php echo get_template_directory_uri(); ?>/css/ticket.css?v=1.1" />
<?php $is_ticket_open = (isset($_GET['id']) && !empty($_GET['id'])); ?>
<div class="flex-container ticket-section <?php echo $is_ticket_open ? 'ticket-details-open' : ''; ?>">
	<?php
	

	$current_url = esc_url_raw($_SERVER['REQUEST_URI']);
	$page_number = intval(preg_replace('/[^0-9]+/', '', $current_url), 10);

	$paged = $page_number == 0 ? 1 : $page_number;


	$args = array(
		'post_type' => 'tickets', 
		'posts_per_page' => -1,
		'paged' => $paged,
		'orderby' => 'modified',
		'meta_query' => array(
			array(
				'key' => 'user_id', 
				'value' => get_current_user_id()
			)
		)
	);
	
	
	$tickets_query = new WP_Query($args);


	global $wpdb;
    $table = $wpdb->prefix.'ticket_chats';

   
	if($tickets_query->have_posts()) : ?>

    <button class="folding"></button>
    <div class="ticket-list-user">
        <div class="chats-list-section-js">
            <section>
                <section class="" data-my-chats-js="">

                	<?php 
                	
                	while($tickets_query->have_posts()) : $tickets_query->the_post();
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
	                    </div>
                	<?php endwhile; wp_reset_postdata(); ?>
					<div class="pager_wrap" style="display: flex; justify-content: center; padding-bottom: 40px;">
						<?php if ($tickets_query->max_num_pages > 1) {
							$pagination_args = array(
								'base' => add_query_arg('page_number', '%#%'),
                            	'format' => '',
								'current' => $paged,
								'total' => $tickets_query->max_num_pages,
								'prev_text' => __('&laquo; Prev'),
								'next_text' => __('Next &raquo;'),
							);

							echo '<div class="pagination">';
							echo paginate_links($pagination_args);
							echo '</div>';
						}?>
					</div>

                </section>
            </section>
        </div>
        <div class="ticket-list-user-down" data-my-chats-search-js="">
            <span role="status" aria-live="polite" class="ui-helper-hidden-accessible"></span>
            <input type="search" name="search" id="search-ticket-list" placeholder="# by ticket number" autocomplete="off" class="ui-autocomplete-input ui-autocomplete-loading" />
        </div>
    </div>
    <div class="chat-section">
        <div class="mobile-back-btn-wrapper">
            <a href="<?php echo remove_query_arg('id'); ?>" class="mobile-back-btn">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" style="vertical-align: middle;">
                    <path d="M19 12H5" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                    <path d="M12 19L5 12L12 5" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                </svg>
                Back to List
            </a>
        </div>
        <div data-current-chat="">
            <div class="ticket-chat">

            	<?php if(isset($_GET['id']) && !empty($_GET['id'])) :
            		$ticket_id = $_GET['id'];


            	 ?>

                <div id="m823580" class="message-msg self">
                    <div class="msg-section-author">
                        <span class="author">You</span>
                        <span class="date-msg"><?php echo get_the_date('', $ticket_id); echo ' '; echo get_the_time('', $ticket_id); ?></span>
                        <span class="status-msg read" data-message-id-is-readable="823580"></span>
                    </div>
                    <section class="section-msg">
                        <span class="ip"></span>
                        <div class="m">
                            <span id="mess823580" ondblclick="tickets.textarea_dialog(823580)">
                            	<?php echo get_post_meta($ticket_id, 'message', true); ?>
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

						// In Partner Panel (tickets.php), "I" am the Partner (from_user=0).
						// So if message is from_user=0, it's ME ("You", self).
						// If message is from_user=1, it's SUPPORT ("Support", voice).
						
						if($result->from_user == 0) : // Message from Partner (Me)
						?>
						<div id="m<?php echo $result->id; ?>" class="message-msg self">
		                    <div class="msg-section-author">
		                        <span class="author">You</span>
		                        <span class="date-msg"><?php echo $result->created_at; ?></span>
		                        <span class="status-msg read" data-message-id-is-readable="<?php echo $result->id; ?>"></span>
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
		            <?php else : // Message from Admin (Support) ?>
		            	<div id="m<?php echo $result->id; ?>" class="message-msg voice">
		                    <div class="msg-section-author">
		                        <span class="author">Support</span>
		                        <span class="date-msg"><?php echo $result->created_at; ?></span>
		                        <span class="status-msg read" data-message-id-is-readable="<?php echo $result->id; ?>"></span>
		                    </div>
		                    <section class="section-msg">
		                        <span class="ip"></span>
		                        <div class="m">
		                            <span class="msg-content" id="msg-content-<?php echo $result->id; ?>" style="white-space: break-spaces;" ><?php echo processMessage($result->message); ?></span>
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
		             <?php endif; ?>
		        <?php endforeach; ?>
		        <?php endif; ?>
            <?php endif; ?>

            </div>
        </div>
        <div id="ticket_form" class="_list.php">
        	<?php if(isset($_GET['id']) && !empty($_GET['id'])) : ?>
	            <form id="form-ticket-send-msg-js" method="post" enctype="multipart/form-data">
	                <div class="ticket-input-section">
	                   <textarea name="message" id="message-input" autocomplete="off" placeholder="Write a message" class="ui-autocomplete-input" required="required" ></textarea>
	                    <div class="file-input-wrapper">
	                        <input type="file" name="file" id="file_trigger" multiple="" />
	                        <label for="file_trigger"></label>
	                    </div>
	                    <button type="submit">Send</button>
	                </div>
	                <input type="hidden" name="ticket_id" id="ticket_id" value="<?php if(isset($_GET['id'])){echo $_GET['id'];} ?>" />
	                <input type="hidden" name="from_user" value="0">
	                <input type="hidden" name="action" value="add_ticket_message" />
	                <div class="attachments editable" id="attachments"></div>
	            </form>
	        <?php endif; ?>
        </div>
		
		
    </div>

<?php else : ?>

	<div class="chat-section-empty">
		<a href="<?php echo esc_url(home_url('/tickets/new/')); ?>">New ticket / Ask a question</a>
	</div>

<?php endif; ?>

</div>

<script>
jQuery(document).ready(function($) {
    // Handle ticket click to show chat on mobile
    $(document).on('click', '.ticket_list_item', function() {
        if ($(window).width() <= 768) {
            $('.ticket-section').addClass('ticket-details-open');
            // Scroll to top to see the chat
            $('html, body').animate({ scrollTop: 0 }, 'fast');
        }
    });

    // Handle back button click
    $(document).on('click', '.mobile-back-btn', function(e) {
        // If the URL does NOT have ?id= (AJAX case), we must manually reset the view.
        // If it DOES have ?id=, the link will reload the page (which is fine/safer).
        if (window.location.search.indexOf('id=') === -1) {
            e.preventDefault();
            $('.ticket-section').removeClass('ticket-details-open');
        }
    });
    
    // Check if we need to open the view on load (e.g. back button history)
    if (window.location.search.indexOf('id=') !== -1 && $(window).width() <= 768) {
        $('.ticket-section').addClass('ticket-details-open');
    }
});
</script>
