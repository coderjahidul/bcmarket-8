<?php get_template_part('emails/email', 'header');
$product_id = get_post_meta($args['post_id'], 'custom_product_id', true);
 ?> 
<p style="text-align:center">
	<span style="font-size:22px">Accounts for request <?php echo $product_id; ?> is declined.</span>
</p>
<?php if(isset($args['note']) && !empty($args['note']) ) : ?>
<p style="text-align:center">
	<span style="font-size:18px">Reason : <?php echo $args['note']; ?></span>
</p>
<p style="text-align:center">
	<span style="font-size:18px">If you have additional questions, please send them to partner@pvamarkets.com</span>
</p>
<?php endif; ?>
<?php get_template_part('emails/email', 'footer'); ?> 