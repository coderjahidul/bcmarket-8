<?php get_template_part('emails/email', 'header');
$reason = $args['reason'];
 ?> 
<p style="text-align:center">
	<span style="font-size:22px">Your acccount is banned!</span>
</p>
<?php if(!empty($reason)) : ?>
<p style="text-align:center">
	<span style="font-size:22px">Reason: <?php echo $reason; ?></span>
</p>
<?php endif; ?>
<p style="text-align:center">
	<span style="font-size:18px">You can read the brief instruction on the use of the partner cabinet in the section&nbsp;<a href="<?php echo esc_url(home_url('/partner/pfaq')); ?>" target="_blank">Partner FAQ</a>.
	</span>
</p>
<?php get_template_part('emails/email', 'footer'); ?> 