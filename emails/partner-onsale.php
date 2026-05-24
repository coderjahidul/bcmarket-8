<?php get_template_part('emails/email', 'header');
$product_id = get_post_meta($args['post_id'], 'custom_product_id', true);
 ?> 
<p style="text-align:center">
	<span style="font-size:22px">Request <?php echo $product_id; ?> is approved!</span>
</p>
<p style="text-align:center">
	<span style="font-size:18px">Accounts have already been uploaded to the store and are on sale.</span>
</p>
<?php get_template_part('emails/email', 'footer'); ?> 