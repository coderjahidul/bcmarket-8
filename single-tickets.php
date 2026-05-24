<?php 
$hash = '';

if(isset($_GET['hash'])){
	$hash = $_GET['hash'];
}

if(!empty($hash) && $hash !== get_post_meta(get_the_ID(), 'unique_hash', true)){
	wp_redirect( home_url('/tickets/new/') );
	die();
}elseif(empty($hash)){
	wp_redirect( home_url('/tickets/new/') );
	die();
}


get_header();



?>
<section class="soc-category" id="content">
    
    <<div class="wrap-breadcrumbs">
	    <div class="container">
	        <div class="flex">
	            <div class="block" itemscope="" itemtype="http://schema.org/BreadcrumbList" id="breadcrumbs">
	                <div itemprop="itemListElement" itemscope="" itemtype="http://schema.org/ListItem">
	                    <a href="/" itemprop="item">
	                        <span itemprop="name">Home</span>
	                        <meta itemprop="position" content="0">
	                    </a>
	                    <span class="divider">/</span>
	                </div>
	                <div itemprop="itemListElement" itemscope="" itemtype="http://schema.org/ListItem">
	                    <span class="current" itemprop="name">Ticket</span>
	                    <meta itemprop="position" content="1">
	                </div>
	            </div>
	        </div>
	    </div>
	</div>

    <div class="container">
        <div class="flex">
            <div class="body partner_cabinet">
		<link rel="stylesheet" type="text/css" href="<?php echo get_template_directory_uri(); ?>/css/ticket.css?v=1.0" />
		<div class="flex-container ticket-section">
			
		    <div class="chat-section">
		        <div data-current-chat="">
		            <div class="ticket-chat">

		            	<?php 

		            		$ticket_id = get_the_ID();


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

								if($result->from_user == 1) : 
								?>
								<div id="m<?php echo $result->id; ?>" class="message-msg voice">
				                    <div class="msg-section-author">
				                        <span class="author">Support</span>
				                        <span class="date-msg"><?php echo $result->created_at; ?></span>
				                        <span class="status-msg read" data-message-id-is-readable="<?php echo $result->id; ?>"></span>
				                    </div>
				                    <section class="section-msg">
				                        <span class="ip"></span>
				                        <div class="m">
				                            <span><?php echo $result->message; ?></span>
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
				            	<div id="m<?php echo $result->id; ?>" class="message-msg self">
				                    <div class="msg-section-author">
				                        <span class="author">You</span>
				                        <span class="date-msg"><?php echo $result->created_at; ?></span>
				                        <span class="status-msg read" data-message-id-is-readable="<?php echo $result->id; ?>"></span>
				                    </div>
				                    <section class="section-msg">
				                        <span class="ip"></span>
				                        <div class="m">
				                            <span><?php echo $result->message; ?></span>
				                            <div class="cb"></div>
				                            <div class="attachments"></div>
				                        </div>
				                    </section>
				                </div>
				             <?php endif; ?>
				        <?php endforeach; ?>
				        <?php endif; ?>

		            </div>
		        </div>
		        <div id="ticket_form" class="_list.php">
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
			                <input type="hidden" name="from_user" value="0">
			                <input type="hidden" name="action" value="add_ticket_message" />
			                <div class="attachments editable" id="attachments"></div>
			            </form>
		        </div>
		    </div>


		</div>

</div>
        </div>
    </div>
</section>

<?php get_footer(); ?>