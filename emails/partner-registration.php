<?php get_template_part('emails/email', 'header'); ?> 
<p style="text-align:center">
	<span style="font-size:22px">You registered at <a href="<?php echo esc_url(home_url('/')); ?>"><?php echo parse_url( get_site_url(), PHP_URL_HOST ); ?>!</a></span>
</p>
<p style="text-align:center">
	<span style="font-size:18px">Fill out&nbsp;
		<a href="<?php echo esc_url(home_url('/registration/query')); ?>" target="_blank">this form</a> with the accounts that you want to ship.</span>
	</p>
<?php get_template_part('emails/email', 'footer'); ?> 