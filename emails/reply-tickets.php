<?php get_template_part('emails/email', 'header');

$unique_hash =  get_post_meta($args['post_id'], 'unique_hash', true);
$ticket_id = $args['post_id'];
$reply_message = isset($args['reply_message']) ? $args['reply_message'] : '';
$user_message = isset($args['user_message']) ? $args['user_message'] : '';
?> 
<p style="text-align:center">
	<span style="font-size:22px">You have a new reply to your ticket!</span>
</p>

<?php if(!empty($reply_message)): ?>
<div style="background-color: #f9f9f9; padding: 15px; border-left: 4px solid #0073aa; margin: 20px 0; text-align: left;">
    <strong>Support Reply:</strong><br>
    <?php echo nl2br(esc_html($reply_message)); ?>
</div>
<?php endif; ?>

<?php if(!empty($user_message)): ?>
<div style="background-color: #f0f0f0; padding: 15px; margin: 20px 0; text-align: left; color: #666;">
    <strong>Ticket Message:</strong><br>
    <?php echo nl2br(esc_html($user_message)); ?>
</div>
<?php endif; ?>

<p style="text-align:center">
	<span style="font-size:18px">Follow the link to view the ticket and reply: <a href="<?php echo get_permalink($ticket_id) . '?hash=' . $unique_hash; ?>"><?php echo get_permalink($ticket_id) . '?hash=' . $unique_hash; ?></a></span>
</p>
<?php get_template_part('emails/email', 'footer'); ?> 
