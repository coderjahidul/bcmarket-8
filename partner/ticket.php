<?php 
if(is_user_logged_in() && !current_user_has_approve_bid()){
    wp_safe_redirect( home_url('/partner/offers') );
}else if(!is_user_logged_in()){
    wp_safe_redirect( home_url('/my/') );
}

if(isset($_GET['id']) && !empty($_GET['id'])) : 

	$args = array(
        'post_type' => 'tickets', // change this to your post type
        'meta_query' => array(
            array(
                'key' => 'ticket_id', 
                'value' => $_GET['id']
            )
        )
    );

    $query = new WP_Query($args);

    if ($query->have_posts()) {
        $query->the_post();
        $ticket_id = get_the_ID();
    } wp_reset_postdata();


get_header(); ?>
<link rel="stylesheet" href="<?php echo get_template_directory_uri(); ?>/css/ticket.css">

<section class="soc-category" id="content">
    <div class="wrap-breadcrumbs">
        <div class="container">
            <div class="flex">
                <div class="block" itemscope="" itemtype="http://schema.org/BreadcrumbList" id="breadcrumbs">
                    <div itemprop="itemListElement" itemscope="" itemtype="http://schema.org/ListItem">
                        <a href="/" itemprop="item">
                            <span itemprop="name">Home</span>
                            <meta itemprop="position" content="0" />
                        </a>
                        <span class="divider">/</span>
                    </div>
                    <div itemprop="itemListElement" itemscope="" itemtype="http://schema.org/ListItem">
                        <span class="current" itemprop="name">Partner interface</span>
                        <meta itemprop="position" content="1" />
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="container">
        <div class="flex">
        	<div class="chat-section">
		        <div data-current-chat="">
		            <div class="ticket-chat">


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
				                        <span class="author">Support</span>
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
		    </div>
        </div>
    </div>
</section>

<script>
	jQuery(document).ready(function($){

		$('.chat-section > div').animate({
            scrollTop: $('.chat-section > div').get(0).scrollHeight
        }, 1000);

	});
</script>

<?php endif; ?>

<?php get_footer() ?>