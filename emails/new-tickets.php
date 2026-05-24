<?php get_template_part('emails/email', 'header');

$unique_hash =  get_post_meta($args['post_id'], 'unique_hash', true);
$ticket_id = $args['post_id'];
 ?> 
<p style="text-align:center">
	<span style="font-size:22px">Your ticket has been created!</span>
</p>
<p style="text-align:center">
	<span style="font-size:18px">Follow the link to view the ticket : <a href="<?php echo get_permalink($ticket_id) . '?hash=' . $unique_hash; ?>"><?php echo get_permalink($ticket_id) . '?hash=' . $unique_hash; ?></a></span>
</p>
<?php get_template_part('emails/email', 'footer'); ?> 