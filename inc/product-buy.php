<?php 
add_action('wp_ajax_buy_product', 'buy_product_callback');
add_action('wp_ajax_nopriv_buy_product', 'buy_product_callback');
function buy_product_callback(){


	$item_id = $_POST['item_id'];
	$product = wc_get_product( $product_id );

	?>
	<style>
		#subscribe_email {
			display: none;
		}
		#subscribe {
			margin-left: 0px;
		}
		#conditions {
			margin-left: 0px;
		}
	</style>
	<form action="#" class="submit_buy" onsubmit="setWooWalletHideCookie();">
	    <input type="hidden" name="item_id" id="item_id" value="" />
	    <input type="hidden" name="price" id="item_price" value="" />
		<input type="hidden" name="partner_id" id="item_partners_id" value="" />
	    <input type="hidden" name="user_id" value="<?php echo get_current_user_id(); ?>" /> 
	    <input type="hidden" name="action" value="create_order" />
	    <input type="hidden" name="max_qty" value="">

	    <div id="item_name"><?php echo get_the_title($item_id); ?></div>
	    <div class="buy_steps">
	        <div class="step_1">
	            <div class="step_in_process">Step 1: Select a vendor</div>
	            <div class="step_done" style="display: none;">
	                <svg width="18" height="18" viewBox="0 0 18 18" fill="none" xmlns="http://www.w3.org/2000/svg">
	                    <path
	                        fill-rule="evenodd"
	                        clip-rule="evenodd"
	                        d="M11.7473 2.83009C10.4177 2.23767 8.93223 2.09091 7.51243 2.41169C6.09263 2.73248 4.81456 3.50362 3.86884 4.61012C2.92312 5.71661 2.36041 7.09917 2.26463 8.5516C2.16886 10.004 2.54516 11.4485 3.3374 12.6696C4.12964 13.8907 5.29539 14.823 6.66077 15.3274C8.02615 15.8319 9.51802 15.8814 10.9139 15.4687C12.3097 15.056 13.5348 14.2031 14.4063 13.0373C15.2778 11.8715 15.7492 10.4552 15.75 8.99959V8.31002C15.75 7.89581 16.0858 7.56002 16.5 7.56002C16.9142 7.56002 17.25 7.89581 17.25 8.31002V9.00002C17.249 10.7791 16.6729 12.5106 15.6077 13.9355C14.5425 15.3604 13.0452 16.4027 11.3392 16.9072C9.63313 17.4116 7.80974 17.351 6.14094 16.7345C4.47214 16.1179 3.04734 14.9785 2.07904 13.486C1.11074 11.9936 0.650828 10.2281 0.767883 8.45291C0.884939 6.67771 1.5727 4.98792 2.72858 3.63553C3.88447 2.28315 5.44655 1.34064 7.18186 0.94857C8.91716 0.5565 10.7327 0.735878 12.3578 1.45995C12.7361 1.62853 12.9062 2.07192 12.7376 2.45027C12.569 2.82863 12.1256 2.99868 11.7473 2.83009Z"
	                        fill="#245f9b"
	                    ></path>
	                    <path
	                        fill-rule="evenodd"
	                        clip-rule="evenodd"
	                        d="M17.0301 2.4694C17.3231 2.76215 17.3233 3.23703 17.0306 3.53006L9.5306 11.0376C9.38997 11.1783 9.19916 11.2575 9.00019 11.2575C8.80121 11.2576 8.61037 11.1785 8.46967 11.0378L6.21967 8.78783C5.92678 8.49494 5.92678 8.02006 6.21967 7.72717C6.51256 7.43428 6.98744 7.43428 7.28033 7.72717L8.99973 9.44658L15.9694 2.46994C16.2622 2.1769 16.737 2.17666 17.0301 2.4694Z"
	                        fill="#245f9b"
	                    ></path>
	                </svg>
	                <span>Vendor selected</span>
	            </div>
	        </div>
	        <div class="step_2">
	            <span>Step 2: Contact information</span>
	        </div>
	    </div>
	    <div class="accordion">
	        <div class="card" id="step1">
	            <div class="card-header">Step 1: Select a vendor</div>
	            <div class="card-body">
	                <ul id="item_partners"> 
	                	<?php 
	                		$partner_items = get_available_partner_by_item($item_id); 

				            

	                		usort($partner_items, function($a, $b) {
								return $a['price'] <=> $b['price'];
							});
							

	                		if($partner_items): foreach($partner_items as $partner_item) : ?>
	                    <li onclick="getPartnerID(<?php echo $partner_item['user_id']; ?>);" data-qty="<?php echo $partner_item['qty']; ?>" data-id="<?php echo $partner_item['product_ids']; ?>" data-price="<?php  echo $partner_item['price']; ?>">
	                        <span>Partner #  <?php echo $partner_item['user_id']; ?>(<?php echo $partner_item['qty']; ?> pcs.)</span>
	                        <span> <span class="text-muted">Price for 1 piece</span>: <?php  echo $partner_item['price']; ?> </span>
	                    </li>
	                <?php 

				endforeach; endif; ?>
	                </ul> 
	            </div>  
	        </div>
	        <div class="card collapsed" id="step2">
	            <div class="card-header">Step 2: Data entry</div>
	            <div class="card-body">
	                <table class="form">
	                    <tbody>
	                        <tr>
	                            <td>Quantity</td>
	                            <td>
	                                <input type="text" name="qty" id="basket_qty" value="1" />
	                                  
	                            </td>
	                        </tr>
	                        <tr class="phone-number">
	                            <td>Phone</td>
	                            <td class="phone-input-row">
	                                <input type="text" class="phone-input-plus" value="+" disabled="" />
	                                <input type="number" class="phone-input-number" name="phone" />
	                            </td>
	                        </tr>
	                        <!-- 
	                        <tr>
	                            <td>Coupon (if you have)</td>
	                            <td><input type="text" name="coupon" /></td>
	                        </tr> -->
	                        <tr class="price-wrapper">
	                            <td>Price</td>
	                            <td><div id="price"></div></td>
	                        </tr>
	                        <tr>
	                            <td></td>
	                            <td><div id="p_alert"></div></td>
	                        </tr>
	                        <tr>
								<td colspan="2">
									<input type="checkbox" name="subscribe" id="subscribe" value="1" onclick="toggleEmailInput()" />
									<label for="subscribe">Subscribe to E-mail newsletter for products</label>
								</td>
							</tr>
							<tr id="subscribe_email">
								<td colspan="2">
									<?php 
										$current_user = wp_get_current_user();
									?>
									<input type="email" placeholder="Enter your Subscribe Email" value="<?php if(!empty($current_user->user_email)){ echo $current_user->user_email;} ?>" name="email" id="email" />
								</td>
							</tr>
	                        <tr>
	                            <td colspan="2">
	                                <input type="checkbox" name="conditions" id="conditions" value="1" required="required" class="price_recalculate" />
	                                <label for="conditions">I agree to the <a href="<?php echo esc_url(home_url('/public-offer'))?>" target="_blank">public offer</a> and <a href="<?php echo esc_url(home_url('/rules/'))?>" target="_blank">terms of use</a></label>
	                            </td>
	                        </tr>
	                    </tbody>
	                </table>
	                <div class="buy-button-container"><button type="submit" class="buy_button" id="buy_button">Proceed to payment</button></div>
	            </div>
	        </div>
	    </div>
	    <span id="info_subscribe"></span>
	    <div class="coupon_message"></div>
	    <p id="creating_order" style="display: none;"><img src="<?php echo get_template_directory_uri(); ?>/img/ajax-load.gif" alt="" /> Redirecting to checkout page, please wait ...</p>

	</form>

	<script>
		function setWooWalletHideCookie() {
			document.cookie = "woo_wallet_hide=1; path=/; max-age=-360";
			return true; // Ensure form submission continues
		}
		function getPartnerID(partner_id){
			document.getElementById('item_partners_id').value = partner_id;
		}

		function toggleEmailInput() {
			var checkBox = document.getElementById("subscribe");
			var emailRow = document.getElementById("subscribe_email");

			if (checkBox.checked) {
				emailRow.style.display = "table-row";
			} else {
				emailRow.style.display = "none";
			}
		}
	</script>


	<?php 

	die();

}


add_action('wp_ajax_create_order', 'create_order_callback');
add_action('wp_ajax_nopriv_create_order', 'create_order_callback');
function create_order_callback(){
 global $wpdb ;
	if(isset($_POST['subscribe'])){
		$subscribe = $_POST['subscribe'];
		$table = $wpdb->prefix.'subscribe';

		$data['item_id'] = $_POST['item_id'];
		$data['partner_id'] = $_POST['partner_id'];
		$data['user_id'] = $_POST['user_id'];

		$wpdb->insert($table,$data);
	}
	if (isset($_POST['email'])) {
		$email = $_POST['email'];
		$table = $wpdb->prefix . 'subscribe_emails';
		
		// Check if the email already exists
		$email_exists = $wpdb->get_var($wpdb->prepare(
			"SELECT COUNT(*) FROM $table WHERE email = %s", 
			$email
		));
		
		// If the email doesn't exist, insert it
		if ($email_exists == 0) {
			$data = array('email' => $email);
			$wpdb->insert($table, $data);
		}
	}
	


	$product_ids = $_POST['item_id'];
	$qty = $_POST['qty'];
	$pro_ex = explode(',', $product_ids);
	
	

	WC()->cart->empty_cart();

	foreach(array_reverse($pro_ex) as $product_id){
		$product = wc_get_product( $product_id );
		$available_qty = total_free_accounts_by_id($product_id);

		if($available_qty >= $qty){
			WC()->cart->add_to_cart( $product_id, $qty);
			break;
		}else{
			WC()->cart->add_to_cart( $product_id, $available_qty);
			$qty = $qty - $available_qty;
		}

	}

	echo home_url('/checkout');

	
	die();

}



add_action('wp_ajax_update_price', 'update_price_callback');
add_action('wp_ajax_nopriv_update_price', 'update_price_callback');
function update_price_callback(){

	$qty = $_POST['quantity'];

	$key = '';

	foreach ( WC()->cart->get_cart() as $cart_item_key => $cart_item ) {
	    $key = $cart_item_key;
	} 


	WC()->cart->set_quantity($key, $qty);

	die(WC()->cart->get_cart_total());

}

add_action('wp_ajax_woo_add_to_cart', 'woo_add_to_cart_callback');
add_action('wp_ajax_nopriv_woo_add_to_cart', 'woo_add_to_cart_callback');
function woo_add_to_cart_callback(){

	$product_id = $_POST['product_id'];
	$quantity = $_POST['quantity'];

	WC()->cart->empty_cart();
	WC()->cart->add_to_cart( $product_id, $quantity);

	die(WC()->cart->get_cart_total());

}

add_action('wp_ajax_apply_coupon_code', 'apply_coupon_code_callback');
add_action('wp_ajax_nopriv_apply_coupon_code', 'apply_coupon_code_callback');
function apply_coupon_code_callback(){

	$code = $_POST['code'];


	if ( ! WC()->cart->has_discount( $code ) ) {
	    WC()->cart->apply_coupon( $code );
	}

	die(WC()->cart->get_cart_total());

}