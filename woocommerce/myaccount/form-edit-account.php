<?php
/**
 * Edit account form
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/myaccount/form-edit-account.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see https://docs.woocommerce.com/document/template-structure/
 * @package WooCommerce\Templates
 * @version 7.0.1
 */

defined( 'ABSPATH' ) || exit;

do_action( 'woocommerce_before_edit_account_form' ); ?>

<form class="woocommerce-EditAccountForm edit-account" action="" method="post" <?php do_action( 'woocommerce_edit_account_form_tag' ); ?> >

	<?php 
	
	$wallets = get_user_meta($user->ID, 'wallets', true);

	
	do_action( 'woocommerce_edit_account_form_start' ); ?>
	
	<div class="row mb-5">
		<div class="col-xs-12 col-lg-6 p0">
			<h4 class="profile_title">Your profile</h4>
			<div class="row">
				<div class="col-xs-12 col-sm-2 col-lg-3">First Name:</div>
				<div class="col-xs-12 col-sm-6 col-lg-9">
					<input type="text" name="account_first_name" id="account_first_name" autocomplete="given-name" value="<?php echo esc_attr( $user->first_name ); ?>" /></div>
			</div>
			<div class="row">
				<div class="col-xs-12 col-sm-2 col-lg-3">Last Name:</div>
				<div class="col-xs-12 col-sm-6 col-lg-9">
					<input type="text" name="account_last_name" id="account_last_name" autocomplete="family-name" value="<?php echo esc_attr( $user->first_name ); ?>" /></div>
			</div>
			<div class="row">
				<div class="col-xs-12 col-sm-2 col-lg-3">Email: <span class="red">*</span></div>
				<div class="col-xs-12 col-sm-6 col-lg-9">
					<input type="email" name="account_email" id="account_email" autocomplete="email" readonly="" value="<?php echo esc_attr( $user->user_email ); ?>" />
				</div>
			</div>
		</div>
		<div class="col-xs-12 col-lg-6 p0">
			<h4 class="profile_title">Change password</h4>
			<div class="row">
				<div class="col-xs-12 col-sm-3 col-lg-4">Old password: <span class="red">*</span></div>
				<div class="col-xs-12 col-sm-5 col-lg-8">
					<input type="password" name="password_current" id="password_current" autocomplete="off" />
				</div>
			</div>
			<div class="row">
				<div class="col-xs-12 col-sm-3 col-lg-4">New password:</div>
				<div class="col-xs-12 col-sm-5 col-lg-8">
					<input type="password"  name="password_1" id="password_1" autocomplete="off" />
				</div>
			</div>
			<div class="row">
				<div class="col-xs-12 col-sm-3 col-lg-4">Confirm new password:</div>
				<div class="col-xs-12 col-sm-5 col-lg-8">
					<input type="password"  name="password_2" id="password_2" autocomplete="off" />
				</div>
			</div>
		</div>
	</div>
	<!--
	<div class="row">
		<div class="col-xs-12 p0"><h4 class="profile_title">eWallets for funds withdrawals</h4></div>
		<div class="col-xs-12 col-lg-6 p0">
			<div class="row">
				<div class="col-xs-12 col-sm-4 col-lg-6">USDT(ERC20) (min. $1000):</div>
				<div class="col-xs-12 col-sm-4 col-lg-6"><input type="text" name="wallets[73]" value="<?php if(isset($wallets[73])){echo $wallets[73];} ?>" autocomplete="off" /></div>
			</div>
		</div>
		<div class="col-xs-12 col-lg-6 p0">
			<div class="row">
				<div class="col-xs-12 col-sm-4 col-lg-6">Dash (DASH):</div>
				<div class="col-xs-12 col-sm-4 col-lg-6"><input type="text" name="wallets[51]" value="<?php if(isset($wallets[51])){echo $wallets[51];} ?>" autocomplete="off" /></div>
			</div>
		</div>
		<div class="col-xs-12 col-lg-6 p0">
			<div class="row">
				<div class="col-xs-12 col-sm-4 col-lg-6">Webmoney WMZ:</div>
				<div class="col-xs-12 col-sm-4 col-lg-6"><input type="text" name="wallets[85]" value="<?php if(isset($wallets[85])){echo $wallets[85];} ?>" autocomplete="off" /></div>
			</div>
		</div>
		<div class="col-xs-12 col-lg-6 p0">
			<div class="row">
				<div class="col-xs-12 col-sm-4 col-lg-6">AdvCash USD:</div>
				<div class="col-xs-12 col-sm-4 col-lg-6"><input type="text" name="wallets[20]" value="<?php if(isset($wallets[20])){echo $wallets[20];} ?>" autocomplete="off" /></div>
			</div>
		</div>
		<div class="col-xs-12 col-lg-6 p0">
			<div class="row">
				<div class="col-xs-12 col-sm-4 col-lg-6">Bitcoin (BTC) (min. $500):</div>
				<div class="col-xs-12 col-sm-4 col-lg-6"><input type="text" name="wallets[11]" value="<?php if(isset($wallets[11])){echo $wallets[11];} ?>" autocomplete="off" /></div>
			</div>
		</div>
		<div class="col-xs-12 col-lg-6 p0">
			<div class="row">
				<div class="col-xs-12 col-sm-4 col-lg-6">Litecoin (LTC):</div>
				<div class="col-xs-12 col-sm-4 col-lg-6"><input type="text" name="wallets[52]" value="<?php if(isset($wallets[52])){echo $wallets[52];} ?>" autocomplete="off" /></div>
			</div>
		</div>
		<div class="col-xs-12 col-lg-6 p0">
			<div class="row">
				<div class="col-xs-12 col-sm-4 col-lg-6">Zec (ZEC):</div>
				<div class="col-xs-12 col-sm-4 col-lg-6"><input type="text" name="wallets[87]" value="<?php if(isset($wallets[87])){echo $wallets[87];} ?>" autocomplete="off" /></div>
			</div>
		</div>
		<div class="col-xs-12 col-lg-6 p0">
			<div class="row">
				<div class="col-xs-12 col-sm-4 col-lg-6">Balance:</div>
				<div class="col-xs-12 col-sm-4 col-lg-6"><input type="text" name="wallets[34]" value="<?php if(isset($wallets[34])){echo $wallets[34];} ?>" autocomplete="off" /></div>
			</div>
		</div>
		<div class="col-xs-12 col-lg-6 p0">
			<div class="row">
				<div class="col-xs-12 col-sm-4 col-lg-6">USDT(TRC20) (min. $50):</div>
				<div class="col-xs-12 col-sm-4 col-lg-6"><input type="text" name="wallets[74]" value="<?php if(isset($wallets[74])){echo $wallets[74];} ?>" autocomplete="off" /></div>
			</div>
		</div>
		<div class="col-xs-12 col-lg-6 p0">
			<div class="row">
				<div class="col-xs-12 col-sm-4 col-lg-6">Perfect Money USD:</div>
				<div class="col-xs-12 col-sm-4 col-lg-6"><input type="text" name="wallets[14]" value="<?php if(isset($wallets[14])){echo $wallets[14];} ?>" autocomplete="off" /></div>
			</div>
		</div>
		<div class="col-xs-12 col-lg-6 p0">
			<div class="row">
				<div class="col-xs-12 col-sm-4 col-lg-6">Etherium (ETH) (min. $500):</div>
				<div class="col-xs-12 col-sm-4 col-lg-6"><input type="text" name="wallets[60]" value="<?php if(isset($wallets[60])){echo $wallets[60];} ?>" autocomplete="off" /></div>
			</div>
		</div>
		<div class="col-xs-12 col-lg-6 p0">
			<div class="row">
				<div class="col-xs-12 col-sm-4 col-lg-6">Qiwi:</div>
				<div class="col-xs-12 col-sm-4 col-lg-6"><input type="text" name="wallets[5]" value="<?php if(isset($wallets[5])){echo $wallets[5];} ?>" autocomplete="off" /></div>
			</div>
		</div>
	</div> -->
	
	<span class="red">*</span>
	 - Required fields.



	<?php do_action( 'woocommerce_edit_account_form' ); ?>

		<?php wp_nonce_field( 'save_account_details', 'save-account-details-nonce' ); ?>
	<button type="submit" class="lk-form-button" name="save_account_details" value="<?php esc_attr_e( 'Save changes', 'woocommerce' ); ?>"><?php esc_html_e( 'Save changes', 'woocommerce' ); ?></button>
	<input type="hidden" name="action" value="save_account_details" />
	<input type="hidden" name="account_display_name" id="account_display_name" value="<?php echo esc_attr( $user->display_name ); ?>" />
	

	<?php do_action( 'woocommerce_edit_account_form_end' ); ?>
</form>

<?php do_action( 'woocommerce_after_edit_account_form' ); ?>
