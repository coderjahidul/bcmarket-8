<?php get_template_part('emails/email', 'header');
$product_id = get_post_meta($args['post_id'], 'custom_product_id', true);
 ?> 
<p style="text-align:center">
	<span style="font-size:22px">Accounts for request <?php echo $product_id; ?> sold out.</span>
</p>
<p style="text-align:center">
	<span style="font-size:18px">Upload new accounts.</span>
</p>
<?php get_template_part('emails/email', 'footer'); ?> 