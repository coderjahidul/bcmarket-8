<?php
get_template_part('emails/email', 'header');

$post_id = $args['post_id'] ?? 0;
$product_id = $post_id ? get_post_meta($post_id, 'custom_product_id', true) : '';
?>

<p style="text-align:center;">
	<span style="font-size:18px;">
		Accounts for request <?php echo esc_html($product_id); ?> are uploaded.
	</span>
</p>

<p style="text-align:center;">
	<span style="font-size:18px;">
		Upload: <?php echo esc_html( total_uploaded_accounts_by_id($post_id) ); ?> pcs.
	</span>
</p>

<p style="text-align:center;">
	<span style="font-size:18px;">
		Repeats:
		<?php
			$repeat_account = get_option('repeat_account', 0);
			echo esc_html($repeat_account);
		?>
		pcs.
	</span>
</p>

<?php
get_template_part('emails/email', 'footer');
