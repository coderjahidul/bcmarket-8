<?php
/**
 * Email Footer
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/emails/email-footer.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see https://woo.com/document/template-structure/
 * @package WooCommerce\Templates\Emails
 * @version 7.4.0
 */

 defined( 'ABSPATH' ) || exit;
?>
<p style="text-align:center">
	<span style="font-size:15px">______________________________<wbr>____________________</span>
</p>
<p style="text-align:center">
	<span style="font-size:13px">
		<a href="<?php echo esc_url(home_url('/')); ?>" target="_blank">Back to store</a>&nbsp;|&nbsp;
		<a href="<?php echo esc_url(home_url('/tickets/new')); ?>" target="_blank" >Ask a question</a>&nbsp;|&nbsp;
		<a href="<?php echo esc_url(home_url('/tickets/new')); ?>" target="_blank">&nbsp;Problems with the order</a>&nbsp;|&nbsp;
		<a href="https://t.me/pvamarkets" target="_blank">5% discount on&nbsp;Telegram</a>&nbsp;
	</span>
</p>


<p style="text-align:center"><span style="color:#999999;font-size:12px">The message was created automatically and it does not require a reply</span></p>
<p style="text-align:center"><span style="color:#999999;font-size:12px">Copyright ©&nbsp; <?php echo get_bloginfo('name'); ?> <?php echo date('Y'); ?>.</span></p>
<p style="text-align:center">&nbsp;</p>