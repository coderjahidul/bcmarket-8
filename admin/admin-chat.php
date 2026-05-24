<?php
if (is_user_logged_in()) {
	$current_user = wp_get_current_user();
	$roles = array('administrator', 'employee', 'wpseo_manager');
	$found = 0;
	foreach ($roles as $role) {
		if (in_array($role, (array) $current_user->roles)) {
			$found = 1;

		}
	}

	if ($found == 0) {
		wp_redirect(home_url('/my/'));
		exit();
	}

} else {
	wp_redirect(home_url('/my/'));
	exit();
}
get_header(); ?>
<section class="soc-category" id="content">

	<?php get_template_part('template-parts/admin', 'breadcrumb'); ?>

	<div class="container">
		<div class="flex">
			<h1><?php the_title(); ?></h1>

			<?php get_template_part('admin/admin', 'menu'); ?>
			<div class="body partner_cabinet">
				<link rel="stylesheet" type="text/css"
					href="<?php echo get_template_directory_uri(); ?>/css/ticket.css?v=1.0" />
				<div class="flex-container ticket-section">
					<?php

					$unreadChatDivs = array();
					$otherDivs = array();
					$unreadTicketIds = array();
					$current_url = esc_url_raw($_SERVER['REQUEST_URI']);
					$page_number = intval(preg_replace('/[^0-9]+/', '', $current_url), 10);

					$paged = $page_number == 0 ? 1 : $page_number;


					$args = array(
						'post_type' => 'tickets',
						'posts_per_page' => 100,
						'paged' => $paged,
						'orderby' => 'modified'


					);
					$newargs = array(
						'post_type' => 'tickets',
						'posts_per_page' => 100,
						'orderby' => 'modified',
						'paged' => $paged

					);


					$tickets_query = new WP_Query($args);
					$newtickets_query = new WP_Query($newargs);

					global $wpdb;
					$table = $wpdb->prefix . 'ticket_chats';


					if ($newtickets_query->have_posts()): ?>

						<button class="folding"></button>
						<div class="ticket-list-user">
							<div class="chats-list-section-js">
								<section>
									<section class="" data-my-chats-js="">

										<?php
										$countBadge = 0;
										$totalUnread = array();




										while ($tickets_query->have_posts()):
											$tickets_query->the_post();
											$active_class = '';
											if (isset($_GET['id']) && !empty($_GET['id']) && $_GET['id'] == get_the_ID()) {
												$active_class = 'active';
											}

											$user_id = get_post_meta(get_the_ID(), 'user_id', true);
											$listing_id = get_post_meta(get_the_ID(), 'listing_id', true);
											$user = get_userdata($user_id);
											$ticket_id = get_the_ID();
											$chat_read = 1;
											$last_message = '';
											$last_time = '';

											$results = $wpdb->get_results("SELECT * FROM $table WHERE ticket_id = $ticket_id  AND from_user = 0 ORDER BY id DESC LIMIT 1");
											$all_result = $wpdb->get_results("SELECT * FROM $table WHERE ticket_id = $ticket_id  ORDER BY id DESC LIMIT 1");

											if ($results) {
												foreach ($results as $item) {
													if ($item->chat_read != 1) {
														$chat_read = 0;
													}
												}
											} else {
												$chat_read = get_post_meta(get_the_ID(), 'unread', true);
											}

											if ($all_result) {
												foreach ($all_result as $item) {
													$last_message = $item->message;
													$last_time = $item->created_at;
												}
											}

											ob_start();
											?>

											<div class="<?php echo $active_class; ?> ticket_list_item <?php
												if ($chat_read == 0) {
													echo 'has_unread_chat';
													$countBadge++;
												}
												?>" data-id="<?php echo get_the_ID(); ?>" data-admin="1">
												<p class="ticket-list-email">
													<span
														class="ticket-list-item-ticket-id">#<?php echo get_post_meta(get_the_ID(), 'ticket_id', true); ?></span>
													<span class="ticket-list-item-email">
														<a href="#"
															title="<?php echo get_post_meta(get_the_ID(), 'subject_title', true); ?>">
															<?php echo get_post_meta(get_the_ID(), 'subject_title', true); ?>
														</a>
													</span>
													&nbsp;
													<span data-item-client-unread-count-js="" style="display: none;"> </span>
												</p>
												<p class="ticket-list-last-time">
													<span data-item-last-message-datetime-js=""><?php echo get_the_date(); ?>
														<?php echo get_the_time(); ?></span>
												</p>
												<p class="ticket-list-message">
													<span data-item-message-js="">
														<?php echo $last_message; ?>
													</span>
												</p>
												<p class="ticket_extra_info">
													<?php if (get_post_meta(get_the_ID(), 'order_id_client', true)): ?>
														Order Id:
														<?php echo get_post_meta(get_the_ID(), 'order_id_client', true); ?>
													<?php endif; ?>
													<?php if (get_post_meta(get_the_ID(), 'order_id_client', true)): ?>
														Partner Id:
														<?php
														$order_id = get_post_meta(get_the_ID(), 'order_id_client', true);


														$pro_query = new WP_Query(
															array(
																'post_type' => 'shop_order',
																'post_status' => array_keys(wc_get_order_statuses()),
																'meta_key' => '_order_number',
																'meta_value' => $order_id
															)
														);

														while ($pro_query->have_posts()) {
															$pro_query->the_post();
															$order_id = get_the_ID();
														}
														wp_reset_postdata();


														$order = wc_get_order($order_id);

														if ($order) {
															foreach ($order->get_items() as $item_id => $item) {
																$product_id = $item->get_product_id();
																echo get_post_field('post_author', $product_id);
															}
														}


														?>
													<?php endif; ?>
													<?php if (get_post_meta(get_the_ID(), 'user_id', true)): ?>
														User Id: <?php echo get_post_meta(get_the_ID(), 'user_id', true); ?>
													<?php endif; ?> <br>
													<?php if (get_post_meta(get_the_ID(), 'proxy', true)): ?>
														Proxy: <?php echo get_post_meta(get_the_ID(), 'proxy', true); ?>
													<?php endif; ?>
													<?php if (get_post_meta(get_the_ID(), 'purchase_account', true)): ?>
														Purchased accounts:
														<?php echo get_post_meta(get_the_ID(), 'purchase_account', true); ?>
													<?php endif; ?>
												</p>
											</div>
											<?php
											$divContent = ob_get_clean();

											if ($chat_read == 0) {
												$unreadTicketIds[$ticket_id] = true;
												array_unshift($unreadChatDivs, $divContent);


											}



										endwhile;
										wp_reset_postdata();

										while ($newtickets_query->have_posts()):
											$newtickets_query->the_post();
											$active_class = '';
											if (isset($_GET['id']) && !empty($_GET['id']) && $_GET['id'] == get_the_ID()) {
												$active_class = 'active';
											}

											$user_id = get_post_meta(get_the_ID(), 'user_id', true);
											$listing_id = get_post_meta(get_the_ID(), 'listing_id', true);
											$user = get_userdata($user_id);
											$ticket_id = get_the_ID();
											$chat_read = 1;
											$last_message = '';
											$last_time = '';

											$results = $wpdb->get_results("SELECT * FROM $table WHERE ticket_id = $ticket_id  AND from_user = 1 ORDER BY id DESC LIMIT 1");
											$all_result = $wpdb->get_results("SELECT * FROM $table WHERE ticket_id = $ticket_id  ORDER BY id DESC LIMIT 1");

											if ($results) {
												foreach ($results as $item) {
													if ($item->chat_read != 1) {
														$chat_read = 0;
													}
												}
											} else {
												$chat_read = get_post_meta(get_the_ID(), 'unread', true);
											}

											$list_client_unread = 1;
											$client_last_rows = $wpdb->get_results("SELECT * FROM $table WHERE ticket_id = $ticket_id AND from_user = 0 ORDER BY id DESC LIMIT 1");
											if ($client_last_rows) {
												foreach ($client_last_rows as $item) {
													if ($item->chat_read != 1) {
														$list_client_unread = 0;
													}
												}
											} else {
												$list_client_unread = get_post_meta(get_the_ID(), 'unread', true);
											}

											if ($all_result) {
												foreach ($all_result as $item) {
													$last_message = $item->message;
													$last_time = $item->created_at;
												}
											}

											ob_start();
											?>

											<?php 
												$get_solved_unsolved = get_post_meta($ticket_id, '_solved_unsolved', true);
												if($get_solved_unsolved == "solved"){?>
													<div class="<?php echo $active_class; ?> ticket_list_item<?php if ($list_client_unread == 0) { echo ' has_unread_chat'; } ?>" data-id="<?php echo get_the_ID(); ?>" data-admin="1">
														<p class="ticket-list-email">
															<span
																class="ticket-list-item-ticket-id">#<?php echo get_post_meta(get_the_ID(), 'ticket_id', true); ?></span>
															<span class="ticket-list-item-email">
																<a href="#"
																	title="<?php echo get_post_meta(get_the_ID(), 'subject_title', true); ?>">
																	<?php echo get_post_meta(get_the_ID(), 'subject_title', true); ?>
																</a>
															</span>
															&nbsp;
															<span data-item-client-unread-count-js="" style="display: none;"> </span>
														</p>
														<p class="ticket-list-last-time">
															<span data-item-last-message-datetime-js=""><?php echo get_the_date(); ?>
																<?php echo get_the_time(); ?></span>
														</p>
														<p class="ticket-list-message">
															<span data-item-message-js="">
																<?php echo $last_message; ?>
															</span>
														</p>
														<p class="ticket_extra_info">
															<?php if (get_post_meta(get_the_ID(), 'order_id_client', true)): ?>
																Order Id:
																<?php echo get_post_meta(get_the_ID(), 'order_id_client', true); ?>
															<?php endif; ?>
															<?php if (get_post_meta(get_the_ID(), 'order_id_client', true)): ?>
																Partner Id:
																<?php
																$order_id = get_post_meta(get_the_ID(), 'order_id_client', true);


																$pro_query = new WP_Query(
																	array(
																		'post_type' => 'shop_order',
																		'post_status' => array_keys(wc_get_order_statuses()),
																		'meta_key' => '_order_number',
																		'meta_value' => $order_id
																	)
																);

																while ($pro_query->have_posts()) {
																	$pro_query->the_post();
																	$order_id = get_the_ID();
																}
																wp_reset_postdata();


																$order = wc_get_order($order_id);

																if ($order) {
																	foreach ($order->get_items() as $item_id => $item) {
																		$product_id = $item->get_product_id();
																		echo get_post_field('post_author', $product_id);
																	}
																}


																?>
															<?php endif; ?>
															<?php if (get_post_meta(get_the_ID(), 'user_id', true)): ?>
																User Id: <?php echo get_post_meta(get_the_ID(), 'user_id', true); ?>
															<?php endif; ?> <br>
															<?php if (get_post_meta(get_the_ID(), 'proxy', true)): ?>
																Proxy: <?php echo get_post_meta(get_the_ID(), 'proxy', true); ?>
															<?php endif; ?>
															<?php if (get_post_meta(get_the_ID(), 'purchase_account', true)): ?>
																Purchased accounts:
																<?php echo get_post_meta(get_the_ID(), 'purchase_account', true); ?>
															<?php endif; ?>
														</p>
													</div><?php
												}else{?>
													<div style="background: #ff4d4d;" class="<?php echo $active_class; ?> ticket_list_item<?php if ($list_client_unread == 0) { echo ' has_unread_chat'; } ?>" data-id="<?php echo get_the_ID(); ?>" data-admin="1">
														<p class="ticket-list-email">
															<span
																class="ticket-list-item-ticket-id">#<?php echo get_post_meta(get_the_ID(), 'ticket_id', true); ?></span>
															<span class="ticket-list-item-email">
																<a href="#"
																	title="<?php echo get_post_meta(get_the_ID(), 'subject_title', true); ?>">
																	<?php echo get_post_meta(get_the_ID(), 'subject_title', true); ?>
																</a>
															</span>
															&nbsp;
															<span data-item-client-unread-count-js="" style="display: none;"> </span>
														</p>
														<p class="ticket-list-last-time">
															<span data-item-last-message-datetime-js=""><?php echo get_the_date(); ?>
																<?php echo get_the_time(); ?></span>
														</p>
														<p class="ticket-list-message">
															<span data-item-message-js="">
																<?php echo $last_message; ?>
															</span>
														</p>
														<p class="ticket_extra_info">
															<?php if (get_post_meta(get_the_ID(), 'order_id_client', true)): ?>
																Order Id:
																<?php echo get_post_meta(get_the_ID(), 'order_id_client', true); ?>
															<?php endif; ?>
															<?php if (get_post_meta(get_the_ID(), 'order_id_client', true)): ?>
																Partner Id:
																<?php
																$order_id = get_post_meta(get_the_ID(), 'order_id_client', true);


																$pro_query = new WP_Query(
																	array(
																		'post_type' => 'shop_order',
																		'post_status' => array_keys(wc_get_order_statuses()),
																		'meta_key' => '_order_number',
																		'meta_value' => $order_id
																	)
																);

																while ($pro_query->have_posts()) {
																	$pro_query->the_post();
																	$order_id = get_the_ID();
																}
																wp_reset_postdata();


																$order = wc_get_order($order_id);

																if ($order) {
																	foreach ($order->get_items() as $item_id => $item) {
																		$product_id = $item->get_product_id();
																		echo get_post_field('post_author', $product_id);
																	}
																}


																?>
															<?php endif; ?>
															<?php if (get_post_meta(get_the_ID(), 'user_id', true)): ?>
																User Id: <?php echo get_post_meta(get_the_ID(), 'user_id', true); ?>
															<?php endif; ?> <br>
															<?php if (get_post_meta(get_the_ID(), 'proxy', true)): ?>
																Proxy: <?php echo get_post_meta(get_the_ID(), 'proxy', true); ?>
															<?php endif; ?>
															<?php if (get_post_meta(get_the_ID(), 'purchase_account', true)): ?>
																Purchased accounts:
																<?php echo get_post_meta(get_the_ID(), 'purchase_account', true); ?>
															<?php endif; ?>
														</p>
													</div>
													<?php
												}?>
											<?php
											$divContent = ob_get_clean();

											// Keep all non-client-unread tickets visible in the secondary list.
											// Otherwise some replied tickets can disappear when chat_read state is mixed.
											if (!isset($unreadTicketIds[$ticket_id])) {
												$otherDivs[] = $divContent;


											}




										endwhile;



										foreach ($unreadChatDivs as $unreadChatDiv) {
											echo $unreadChatDiv;
										}

										foreach ($otherDivs as $otherDiv) {
											echo $otherDiv;
										}




										update_option('totalTicket', $countBadge);

										if ($newtickets_query->max_num_pages > 1) {
											echo '<div class="pagination">';
											$pagination_args = array(
												'base' => add_query_arg('page_number', '%#%'),
												'format' => '',
												'current' => $paged,
												'total' => $newtickets_query->max_num_pages,
												'prev_text' => __('&laquo; Previous'),
												'next_text' => __('Next &raquo;'),
											);
											echo paginate_links($pagination_args);
											echo '</div>';
										}






										wp_reset_postdata(); ?>

									</section>
								</section>
							</div>
							<div class="ticket-list-user-down" data-my-chats-search-js="" style="position:relative;">
								<span role="status" aria-live="polite" class="ui-helper-hidden-accessible"></span>
								<input type="search" name="search" id="search-ticket-list" placeholder="# by ticket number"
									autocomplete="off" class="ui-autocomplete-input ui-autocomplete-loading"
									data-admin="1" />
							</div>
						</div>
						<div class="chat-section">
							<div data-current-chat="">
								<div class="ticket-chat">

									<?php if (isset($_GET['id']) && !empty($_GET['id'])):
										$ticket_id = $_GET['id'];


										?>

										<div id="m823580" class="message-msg self">
											<div class="msg-section-author">
												<span class="author">You</span>
												<span
													class="date-msg"><?php echo get_the_date('', $ticket_id);
													echo ' ';
													echo get_the_time('', $ticket_id); ?></span>
												<span class="status-msg read" data-message-id-is-readable="823580"></span>
                                                <span class="edit-msg-icon" data-id="823580" style="cursor:pointer; margin-left:5px; color:#007cba; display: inline-block !important; visibility: visible !important; opacity: 1 !important;" title="Edit Message"><i class="fa fa-pencil" aria-hidden="true"></i></span>
											</div>
											<section class="section-msg">
												<span class="ip"></span>
												<div class="m">
													<span class="msg-content" id="msg-content-823580" style="white-space: break-spaces;"
														ondblclick="tickets.textarea_dialog(823580)">
														<?php echo get_post_meta($ticket_id, 'message', true); ?>
													</span>
													<div class="cb"></div>
													<div class="attachments"></div>
												</div>
											</section>
										</div>

										<?php

										global $wpdb;
										$table = $wpdb->prefix . 'ticket_chats';
										$results = $wpdb->get_results("SELECT * FROM $table WHERE ticket_id = $ticket_id");

										if ($results) {
												// Mark Client/Partner messages as read when Admin opens the ticket
												foreach($results as $result){
													if($result->from_user == 0 && $result->chat_read == 0) {
														$wpdb->update(
															$table,
															array('chat_read' => 1),
															array('id' => $result->id)
														);
														// Update ticket meta to reflect no unread messages for admin if needed
														update_post_meta($ticket_id, 'unread', 0); // This usually tracks user unread, check logic
													}
												}
											}

										if ($results):
											foreach ($results as $result):

												if ($result->from_user == 1):
													?>
													<div id="m<?php echo $result->id; ?>" class="message-msg self">
														<div class="msg-section-author">
															<span class="author">You</span>
															<span class="date-msg"><?php echo $result->created_at; ?></span>
															<span class="status-msg read"
																data-message-id-is-readable="<?php echo $result->id; ?>"></span>
                                                            <span class="edit-msg-icon" data-id="<?php echo $result->id; ?>" style="cursor:pointer; margin-left:5px; color:#007cba; display: inline-block !important; visibility: visible !important; opacity: 1 !important;" title="Edit Message"><i class="fa fa-pencil" aria-hidden="true"></i></span>
														</div>
														<section class="section-msg">
															<span class="ip"></span>
															<div class="m">
																<span class="msg-content" id="msg-content-<?php echo $result->id; ?>"
																	style="white-space: break-spaces;"><?php echo $result->message; ?></span>
																<div class="cb"></div>
																<div class="attachments">
																	<?php
																	if (!empty($result->attach_ids)) {
																		$attachment_ids = explode(',', $result->attach_ids);

																		if ($attachment_ids) {
																			foreach ($attachment_ids as $attach_id) {
																				$parsed = wp_get_attachment_url($attach_id);

																				if (!empty($parsed)):
																					$filename_only = basename(get_attached_file($attach_id)); ?>
																					<div>
																						<a href="<?php echo $parsed; ?>"
																							target="_blank"><?php echo $filename_only; ?></a>
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
												<?php else: ?>
													<div id="m<?php echo $result->id; ?>" class="message-msg voice">
														<div class="msg-section-author">
															<span class="author">Support</span>
															<span class="date-msg"><?php echo $result->created_at; ?></span>
															<span class="status-msg read"
																data-message-id-is-readable="<?php echo $result->id; ?>"></span>
															<span class="edit-msg-icon" data-id="<?php echo $result->id; ?>" style="cursor:pointer; margin-left:5px; color:#007cba; display: inline-block !important; visibility: visible !important; opacity: 1 !important;" title="Edit Message"><i class="fa fa-pencil" aria-hidden="true"></i></span>
														</div>
														<section class="section-msg">
															<span class="ip"></span>
															<div class="m">
																<span class="msg-content" id="msg-content-<?php echo $result->id; ?>"
																	style="white-space: break-spaces;"><?php echo $result->message; ?></span>
																<div class="cb"></div>
																<div class="attachments"></div>
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
								<?php if (isset($_GET['id']) && !empty($_GET['id'])): ?>
									<form id="form-ticket-send-msg-js" method="post" enctype="multipart/form-data">

										<div class="ticket-input-section">
											<textarea name="message" id="message-input" autocomplete="off"
												placeholder="Write a message" class="ui-autocomplete-input"
												required="required"></textarea>
											<div class="file-input-wrapper">
												<input type="file" name="file" id="file_trigger" multiple="" />
												<label for="file_trigger"></label>
											</div>
											<button type="submit">Send</button>
										</div>
										<input type="hidden" name="ticket_id" id="ticket_id"
											value="<?php if (isset($_GET['id'])) {
												echo $_GET['id'];
											} ?>" />
										<input type="hidden" name="from_user" value="1">
										<input type="hidden" name="action" value="add_ticket_message" />
										<div class="attachments editable" id="attachments"></div>
									</form>
								<?php endif; ?>
							</div>

						</div>

					<?php else: ?>

						<div class="chat-section-empty">
							<a href="<?php echo esc_url(home_url('/tickets/new/')); ?>">New ticket / Ask a question</a>
						</div>

					<?php endif; ?>

				</div>

			</div>
		</div>
	</div>
</section>

<script>
jQuery(document).ready(function($) {
    // Edit Message Click
    $(document).on('click', '.edit-msg-icon', function() {
        var msgId = $(this).data('id');
        var contentSpan = $('#msg-content-' + msgId);
        
        if (contentSpan.find('textarea').length > 0) return; // Already editing

        var currentText = contentSpan.text();
        
        // Create edit interface
        var textarea = $('<textarea class="edit-msg-textarea" style="width:100%; min-height:60px; margin-top:5px; border:1px solid #ddd; padding:5px;"></textarea>').val(currentText);
        var btnContainer = $('<div style="margin-top:5px; text-align:right;"></div>');
        var saveBtn = $('<button type="button" class="button button-primary button-small save-msg-btn" data-id="' + msgId + '">Save</button>');
        var cancelBtn = $('<button type="button" class="button button-secondary button-small cancel-msg-btn" style="margin-left:5px;">Cancel</button>');
        
        btnContainer.append(saveBtn).append(cancelBtn);
        
        contentSpan.data('original-text', currentText);
        contentSpan.empty().append(textarea).append(btnContainer);
        
        // Focus textarea
        textarea.focus();
    });

    // Cancel Edit
    $(document).on('click', '.cancel-msg-btn', function() {
        var contentSpan = $(this).closest('.msg-content');
        var originalText = contentSpan.data('original-text');
        contentSpan.text(originalText);
    });

    // Save Edit
    $(document).on('click', '.save-msg-btn', function() {
        var btn = $(this);
        var msgId = btn.data('id');
        var contentSpan = btn.closest('.msg-content');
        var newText = contentSpan.find('textarea').val();

        // Basic Validation
        if (!newText.trim()) {
            alert('Message cannot be empty.');
            return;
        }

        btn.text('Saving...').prop('disabled', true);
        
        // Use ajaxurl if available, otherwise try to deduce it or use default WP path
        var ajax_url = (typeof ajaxurl !== 'undefined') ? ajaxurl : '<?php echo admin_url('admin-ajax.php'); ?>';

        $.ajax({
            url: ajax_url,
            type: 'POST',
            data: {
                action: 'edit_ticket_message',
                message_id: msgId,
                message: newText
            },
            success: function(response) {
                if (response !== 'error') {
                    contentSpan.text(response);
                } else {
                    alert('Error updating message. Please try again.');
                    btn.text('Save').prop('disabled', false);
                }
            },
            error: function() {
                alert('Error updating message. Network error.');
                btn.text('Save').prop('disabled', false);
            }
        });
    });
});
</script>
<?php get_footer(); ?>