<?php
// AJAX Login form processing 
function add_bids_callback()
{

	// Verify nonce
	check_ajax_referer('bcmarket_bids_nonce', 'security');

	$categories = $_POST['categories'];
	$description = $_POST['description'];
	$country = $_POST['country'];
	$format = $_POST['format'];
	$partner_cost = $_POST['partner_cost'];
	$like_this = $_POST['like_this'];

	$json = array();


	$count = count($description);

	$json['fo'] = $format;


	if ($count != 0) {

		for ($i = 0; $i < $count; $i++) {


			$partner_price = $partner_cost[$i];
			$admin_percentage = get_theme_mod('admin_percentage');
			$new_price = $partner_price + (($admin_percentage / 100) * $partner_price);

			$item_id = 0;

			if (isset($like_this[$i]) && !empty($like_this[$i])) {

				$product_id = $like_this[$i];

				$cat_ids = array();

				$cats = get_the_terms($product_id, 'product_cat');
				if ($cats && !is_wp_error($cats)):
					foreach ($cats as $term) {
						$cat_ids[] = $term->term_id;
					}
				endif;

				$like_cat_ids = $cat_ids;
				$like_title = get_the_title($product_id);
				$like_country = get_post_meta($product_id, 'item_country', true);
				$like_format = get_post_meta($product_id, 'item_format', true);

				$json['datas']['title'][] = $like_title;
				$json['datas']['title'][] = $description[$i];

				$json['datas']['cat'][] = $cat_ids;
				$json['datas']['cat'][] = $categories[$i];

				$json['datas']['country'][] = $like_country;
				$json['datas']['country'][] = $country[$i];

				$json['datas']['format'][] = $like_format;
				$json['datas']['format'][] = implode(',', $format[$i]);

				if (in_array($categories[$i], $cat_ids) && $like_title == $description[$i] && $like_country == $country[$i] && $like_format == implode(',', $format[$i])) {
					$item_id = get_post_meta($product_id, 'item_id', true);
				}




			}

			if ($item_id != 0) {

				$item_pro_query = new WP_Query(
					array(
						'posts_per_page' => -1,
						'post_type' => 'product',
						'author' => get_current_user_id(),
						'meta_query' => array(
							array(
								'key' => 'item_id',
								'value' => $item_id
							)
						)
					)
				);
				while ($item_pro_query->have_posts()) {
					$item_pro_query->the_post();

					$product_id = get_the_ID();
					$product = wc_get_product($product_id);
					$product->set_regular_price($new_price);
					$product->update_meta_data('partner_price', $partner_price);

					$item_id = get_post_meta($product_id, 'item_id', true);


					$product->save();

				}
				wp_reset_postdata();

			}




			$product = new WC_Product_Simple();

			$product->set_name($description[$i]);
			$product->set_regular_price($new_price);
			$product->set_status('pending');
			$product->update_meta_data('item_format', implode(',', $format[$i]));
			$product->update_meta_data('item_country', $country[$i]);
			$product->update_meta_data('bid_status', 'awaiting_upload');
			$product->update_meta_data('partner_price', $partner_price);

			$product->set_category_ids(array($categories[$i]));

			if ($item_id != 0) {
				$product->update_meta_data('item_id', $item_id);
			}

			$product->save();




			$json['sucess'] = 1;

		}

	}



	die(json_encode($json));

}

add_action('wp_ajax_add_bids', 'add_bids_callback');

// upload_accounts
function upload_accounts_callback()
{
	global $wpdb;
	$repeatedAccount = 0;

	$json = array();

	$accounts_text = $_POST['accounts'];
	$bid_id = $_POST['bid_id'];

	$table_name = $wpdb->prefix . 'accounts';

	// Update query to select both login and password
	$query = "SELECT login, password FROM $table_name";
	$accounts = $wpdb->get_results($query, ARRAY_A);

	$line_array = explode("\n", str_replace("\r", "", $accounts_text));

	foreach ($line_array as $element) {
		if (strpos($element, '|') !== false) {
			$contains = "|";
		} elseif (strpos($element, ':') !== false) {
			$contains = ":";
		}
	}

	if ($line_array) {
		if ($contains === "|") {
			$filteredArray = array_filter($line_array, function ($item) use ($accounts) {
				list($login, $password) = explode('|', $item);
				foreach ($accounts as $account) {
					if ($login === $account['login'] && $password === $account['password']) {
						return false; // Match found, filter out this item
					}
				}
				return true; // No match found, keep this item
			});
		} elseif ($contains === ":") {
			$filteredArray = array_filter($line_array, function ($item) use ($accounts) {
				list($login, $password) = explode(':', $item);
				foreach ($accounts as $account) {
					if ($login === $account['login'] && $password === $account['password']) {
						return false; // Match found, filter out this item
					}
				}
				return true; // No match found, keep this item
			});
		}

		$uniqueArray = array_unique($line_array);
		$result1 = array_diff_assoc($line_array, $filteredArray);
		$result2 = array_diff_assoc($line_array, $uniqueArray);
		$duplicates = array_merge($result1, $result2);

		$duplicate_string = implode("\n", $duplicates);
		update_post_meta($bid_id, 'duplicate_accounts', $duplicate_string);
		$json['duplicate_array'] = $duplicates;

		if (count($duplicates) == 0) {
			$repeatedAccount = 0;
		} else {
			$repeatedAccount = count($duplicates);
		}

		$uniqueFilterarray = array_unique($filteredArray);
		if (!empty($uniqueFilterarray)) {
			$json['uploaded_account'] = count($uniqueFilterarray);
			$json['filter_account'] = $uniqueFilterarray;
			$json['login'] = array_column($accounts, 'login');
			$json['repeated_account'] = $repeatedAccount;
			$json['account_text'] = $accounts_text;

			$original_string = implode("\n", $uniqueFilterarray);
			update_post_meta($bid_id, 'accounts_as_text', $original_string);
		}
		update_post_meta($bid_id, 'bid_status', 'checking_accounts');
		update_post_meta($bid_id, 'account_checked', 0);
		$json['bid_id'] = $bid_id;

		update_option('repeat_account', $repeatedAccount);
	}

	die(json_encode($json));
}

add_action('wp_ajax_upload_accounts', 'upload_accounts_callback');





// AJAX Account Delivery Application
function add_offer_callback()
{

	// Verify nonce
	check_ajax_referer('bcmarket_offer_nonce', 'security');

	$categories = $_POST['categories'];
	$description = $_POST['description'];
	$country = $_POST['country'];
	$format = $_POST['format'];
	$supply_count = $_POST['supply_count'];
	$days_select = $_POST['days_select'];

	$json = array();


	$count = count($description);


	if ($count != 0) {

		for ($i = 0; $i < $count; $i++) {

			// Gather post data.
			$my_post = array(
				'post_title' => $description[$i],
				'post_status' => 'publish',
				'post_type' => 'bids',
				'post_author' => get_current_user_id(),
			);

			// Insert the post into the database.
			$offer_id = wp_insert_post($my_post);

			update_post_meta($offer_id, 'offer_format', $format[$i]);
			update_post_meta($offer_id, 'offer_country', $country[$i]);
			update_post_meta($offer_id, 'offer_cat', $categories[$i]);
			update_post_meta($offer_id, 'offer_count', $supply_count[$i]);
			update_post_meta($offer_id, 'offer_days', $days_select[$i]);
			update_post_meta($offer_id, 'offer_status', 'under_considertion');


			$json['sucess'] = 1;

		}
	}

	die(json_encode($json));

}

add_action('wp_ajax_add_offer', 'add_offer_callback');



// Hook into that action that'll fire every one minutes
add_action('init', 'bcmarket_run_every_one_minute_callbacks');
function bcmarket_run_every_one_minute_callbacks()
{

	if (isset($_REQUEST['checking_account']) && $_REQUEST['checking_account'] == 'yes'):

		ignore_user_abort(1);
		ini_set('max_execution_time', 0);

		global $wpdb;

		$account_query = new WP_Query(
			array(
				'post_type' => 'product',
				'post_status' => array('publish', 'pending'),
				'posts_per_page' => 1,
				'meta_query' => array(
					array(
						'key' => 'bid_status',
						'value' => 'checking_accounts'
					),
					array(
						'relation' => 'OR',
						array(
							'key' => 'account_checked',
							'value' => 0
						),
						array(
							'key' => 'account_checked',
							'compare' => 'NOT EXISTS'
						)
					)
				)
			)
		);


		if ($account_query->have_posts()):

			while ($account_query->have_posts()):
				$account_query->the_post();



				$author_id = get_post_field('post_author', get_the_ID());

				update_post_meta(get_the_ID(), 'bid_status', 'processing');

				$accounts_text = get_post_meta(get_the_ID(), 'accounts_as_text', true);
				$duplicate_text = get_post_meta(get_the_ID(), 'duplicate_accounts', true);




				$line_array = explode("\n", str_replace("\r", "", $accounts_text));
				$duplicate_array = explode("\n", str_replace("\r", "", $duplicate_text));

				$item_format = get_post_meta(get_the_ID(), 'item_format', true); // Example: login,password,alt_email,phone,dob,gender,username,email,cookies,gauth,mailpassword,profile_link
				$item_format_ex = explode(',', $item_format);  // Example: array([0] => login, [1] => password, [2] => alt_email, [3] => phone, [4] => dob, [5] => gender, [6] => username, [7] => email, [8] => cookies, [9] => gauth, [10] => mailpassword, [11] => profile_link)
				$profile_index = array_search('profile_link', $item_format_ex); // Example: profile index = 11
				$item_format_count = count($item_format_ex); // Example: Format count = 12
				$item_format_login_key = array_search('login', $item_format_ex); // Example: login index = 0
				$item_format_pass_key = array_search('password', $item_format_ex); // Example: password index = 1
				$item_format_alt_email_key = array_search('alt_email', $item_format_ex); // Example: alt_email index = 2
				$item_format_phone_key = array_search('phone', $item_format_ex); // Example: phone index = 3
				$item_format_dob_key = array_search('dob', $item_format_ex); // Example: dob index = 4
				$item_format_gender_key = array_search('gender', $item_format_ex); // Example: gender index = 5
				$item_format_username_key = array_search('username', $item_format_ex); // Example: username index = 6
				$item_format_email_key = array_search('email', $item_format_ex); // Example: email index = 7
				$item_format_cookies_key = array_search('cookies', $item_format_ex); // Example: cookies index = 8
				$item_format_gauth_key = array_search('gauth', $item_format_ex); // Example: gauth index = 9
				$item_format_mailpassword_key = array_search('mailpassword', $item_format_ex); // Example: mailpassword index = 10
				$item_format_profile_link_key = array_search('profile_link', $item_format_ex); // Example: profile_link index = 11
				$item_format_userid_key = array_search('userid', $item_format_ex); // Example: userid index = 12
				$item_format_name_key = array_search('name', $item_format_ex); // Example: name index = 13
				$item_format_token_key = array_search('token', $item_format_ex); // Example: token index = 14
				$item_format_useragent_key = array_search('useragent', $item_format_ex); // Example: useragent index = 15
				$item_format_uid_key = array_search('uid', $item_format_ex); // Example: uid index = 16
				$item_format_backup_code_key = array_search('backup_code', $item_format_ex); // Example: backup_code index = 17
				$item_format_code_1_key = array_search('code_1', $item_format_ex); // Example: code_1 index = 18
				$item_format_code_2_key = array_search('code_2', $item_format_ex); // Example: code_2 index = 19
				$item_format_code_3_key = array_search('code_3', $item_format_ex); // Example: code_3 index = 20
				$item_format_code_4_key = array_search('code_4', $item_format_ex); // Example: code_4 index = 21
				$item_format_code_5_key = array_search('code_5', $item_format_ex); // Example: code_5 index = 22 

				// 	if (strlen($accounts_text)==0 ) {



				// 	$logger = wc_get_logger();

				//   	$logger->info('One item has checked. ' . get_the_ID() . 'and found an error.', array( 'source' => 'queue-checking' ) );

				//  update_post_meta(get_the_ID(), 'bid_status', 'checking_accounts');
				// // update_post_meta(get_the_ID(), 'account_checked', 0);

				// do_action('send_uploaded_email', get_the_ID());

				// //   	update_post_meta(get_the_ID(), 'bid_status', 'declined');
				// //   	update_post_meta(get_the_ID(), 'declined_reason', 'Invalid accounts format.');
				// //   	break;
				// }

				
				if ($profile_index) {

					if (strlen($accounts_text) != 0) {
						foreach ($line_array as $each_item) {

							$uniqueString = "##COLONSLASH##";
							$modifiedString = str_replace('://', $uniqueString, $each_item);
							// if (strpos($accounts_text, '|')) {
							// 	$item_array = explode('|', $modifiedString, $item_format_count);
							// } else 
							if (strpos($accounts_text, ':')) {
								$item_array = explode(':', $modifiedString, $item_format_count);
							}
							foreach ($item_array as &$item) {
								$item = str_replace($uniqueString, '://', $item);
							}

							echo "Count Items: " . $colon_count = count($item_array);

							// if($colon_count < 3){
							// 	$item_array = explode("|", $each_item);
							// }
							$count_item = count($item_array);


							if ($count_item == $item_format_count) {

								$table = $wpdb->prefix . 'accounts';

								$data['product_id'] = get_the_ID();
								$data['user_id'] = $author_id;

								if (isset($item_array[$item_format_login_key])) {
									$data['login'] = $item_array[$item_format_login_key];
								}

								if (isset($item_array[$item_format_pass_key])) {
									$data['password'] = $item_array[$item_format_pass_key];
								}

								if (isset($item_array[$item_format_alt_email_key])) {
									$data['alt_email'] = $item_array[$item_format_alt_email_key];
								}

								if (isset($item_array[$item_format_phone_key])) {
									$data['phone'] = $item_array[$item_format_phone_key];
								}

								if (isset($item_array[$item_format_dob_key])) {
									$data['dob'] = $item_array[$item_format_dob_key];
								}

								if (isset($item_array[$item_format_gender_key])) {
									$data['gender'] = $item_array[$item_format_gender_key];
								}

								if (isset($item_array[$item_format_username_key])) {
									$data['username'] = $item_array[$item_format_username_key];
								}

								if (isset($item_array[$item_format_email_key])) {
									$data['email'] = $item_array[$item_format_email_key];
								}

								if (isset($item_array[$item_format_cookies_key])) {
									$data['cookies'] = $item_array[$item_format_cookies_key];
								}

								if (isset($item_array[$item_format_gauth_key])) {
									$data['gauth'] = $item_array[$item_format_gauth_key];
								}

								if (isset($item_array[$item_format_mailpassword_key])) {
									$data['mailpassword'] = $item_array[$item_format_mailpassword_key];
								}

								if (isset($item_array[$item_format_profile_link_key])) {
									$data['profile_link'] = $item_array[$item_format_profile_link_key];
								}
								if(isset($item_array[$item_format_userid_key])){
									$data['userid'] = $item_array[$item_format_userid_key];
								}
								if(isset($item_array[$item_format_name_key])){
									$data['name'] = $item_array[$item_format_name_key];
								}
								if(isset($item_array[$item_format_token_key])){
									$data['token'] = $item_array[$item_format_token_key];
								}
								if(isset($item_array[$item_format_useragent_key])){
									$data['useragent'] = $item_array[$item_format_useragent_key];
								}
								if(isset($item_array[$item_format_uid_key])){
									$data['uid'] = $item_array[$item_format_uid_key];
								}
								if(isset($item_array[$item_format_backup_code_key])){
									$data['backup_code'] = $item_array[$item_format_backup_code_key];
								}
								if(isset($item_array[$item_format_code_1_key])){
									$data['code_1'] = $item_array[$item_format_code_1_key];
								}
								if(isset($item_array[$item_format_code_2_key])){
									$data['code_2'] = $item_array[$item_format_code_2_key];
								}
								if(isset($item_array[$item_format_code_3_key])){
									$data['code_3'] = $item_array[$item_format_code_3_key];
								}
								if(isset($item_array[$item_format_code_4_key])){
									$data['code_4'] = $item_array[$item_format_code_4_key];
								}
								if(isset($item_array[$item_format_code_5_key])){
									$data['code_5'] = $item_array[$item_format_code_5_key];
								}

								$check_values = array('login', 'username', 'email');

								$checked_item = '';

								foreach ($item_format_ex as $item) {
									if (in_array($item, $check_values)) {

										$checked_item = $item;
										break;

									}
								}

								if (isset($item_array[0]) && !empty($item_array[0])) {
									$search_login = $item_array[0];
									$total = $wpdb->get_var("SELECT COUNT(*) FROM $table WHERE $checked_item = '$search_login'");

									if ($total == 0) {
										$data['item_status'] = 'free';
									} else {
										$data['item_status'] = 'repeat';
									}

								} else {
									$data['item_status'] = 'free';
								}

								$wpdb->insert($table, $data);
								$my_id = $wpdb->insert_id;

							}


						}



					}



					// 	for duplicate account upload 

					if ($duplicate_array) {

						if (strlen($duplicate_text) != 0) {
							foreach ($duplicate_array as $each_item) {

								$uniqueString = "##COLONSLASH##";
								$modifiedString = str_replace('://', $uniqueString, $each_item);
								// if (strpos($duplicate_text, '|')) {
								// 	$item_array = explode('|', $modifiedString, $item_format_count);
								// } else 
								if (strpos($duplicate_text, ':')) {
									$item_array = explode(':', $modifiedString, $item_format_count);
								}
								foreach ($item_array as &$item) {
									$item = str_replace($uniqueString, '://', $item);
								}

								$colon_count = count($item_array);

								// if($colon_count < 3){
								// 	$item_array = explode("|", $each_item);
								// }
								$count_item = count($item_array);


								if ($count_item == $item_format_count) {

									$table = $wpdb->prefix . 'accounts';

									$data['product_id'] = get_the_ID();
									$data['user_id'] = $author_id;

									if (isset($item_array[$item_format_login_key])) {
										$data['login'] = $item_array[$item_format_login_key];
									}

									if (isset($item_array[$item_format_pass_key])) {
										$data['password'] = $item_array[$item_format_pass_key];
									}

									if (isset($item_array[$item_format_alt_email_key])) {
										$data['alt_email'] = $item_array[$item_format_alt_email_key];
									}

									if (isset($item_array[$item_format_phone_key])) {
										$data['phone'] = $item_array[$item_format_phone_key];
									}

									if (isset($item_array[$item_format_dob_key])) {
										$data['dob'] = $item_array[$item_format_dob_key];
									}

									if (isset($item_array[$item_format_gender_key])) {
										$data['gender'] = $item_array[$item_format_gender_key];
									}

									if (isset($item_array[$item_format_username_key])) {
										$data['username'] = $item_array[$item_format_username_key];
									}

									if (isset($item_array[$item_format_email_key])) {
										$data['email'] = $item_array[$item_format_email_key];
									}

									if (isset($item_array[$item_format_cookies_key])) {
										$data['cookies'] = $item_array[$item_format_cookies_key];
									}

									if (isset($item_array[$item_format_gauth_key])) {
										$data['gauth'] = $item_array[$item_format_gauth_key];
									}

									if (isset($item_array[$item_format_mailpassword_key])) {
										$data['mailpassword'] = $item_array[$item_format_mailpassword_key];
									}

									if (isset($item_array[$item_format_profile_link_key])) {
										$data['profile_link'] = $item_array[$item_format_profile_link_key];
									}
									if(isset($item_array[$item_format_userid_key])){
									$data['userid'] = $item_array[$item_format_userid_key];
    								}
    								if(isset($item_array[$item_format_name_key])){
    									$data['name'] = $item_array[$item_format_name_key];
    								}
    								if(isset($item_array[$item_format_token_key])){
    									$data['token'] = $item_array[$item_format_token_key];
    								}
    								if(isset($item_array[$item_format_useragent_key])){
    									$data['useragent'] = $item_array[$item_format_useragent_key];
    								}
    								if(isset($item_array[$item_format_uid_key])){
									    $data['uid'] = $item_array[$item_format_uid_key];
    								}
    								if(isset($item_array[$item_format_backup_code_key])){
    									$data['backup_code'] = $item_array[$item_format_backup_code_key];
    								}
    								if(isset($item_array[$item_format_code_1_key])){
    									$data['code_1'] = $item_array[$item_format_code_1_key];
    								}
    								if(isset($item_array[$item_format_code_2_key])){
    									$data['code_2'] = $item_array[$item_format_code_2_key];
    								}
    								if(isset($item_array[$item_format_code_3_key])){
    									$data['code_3'] = $item_array[$item_format_code_3_key];
    								}
    								if(isset($item_array[$item_format_code_4_key])){
    									$data['code_4'] = $item_array[$item_format_code_4_key];
    								}
    								if(isset($item_array[$item_format_code_5_key])){
    									$data['code_5'] = $item_array[$item_format_code_5_key];
    								}

									$check_values = array('login', 'username', 'email');

									$checked_item = '';

									foreach ($item_format_ex as $item) {
										if (in_array($item, $check_values)) {

											$checked_item = $item;
											break;

										}
									}


									$data['item_status'] = 'repeat';
									$wpdb->insert($table, $data);
									$my_id = $wpdb->insert_id;

								}


							}


						}




					}





				} else {

					if (strlen($accounts_text) != 0) {
						foreach ($line_array as $each_item) {


							// if (strpos($accounts_text, '|')) { 
							// 	$item_array = explode('|', $each_item, $item_format_count);
							// } else 
							if (strpos($accounts_text, ':')) {
								$item_array = explode(':', $each_item, $item_format_count);
							}


							$colon_count = count($item_array);


							$count_item = count($item_array); // jahiduil


							if ($count_item == $item_format_count) {

								$table = $wpdb->prefix . 'accounts';

								$data['product_id'] = get_the_ID();
								$data['user_id'] = $author_id;

								if (isset($item_array[$item_format_login_key])) {
									$data['login'] = $item_array[$item_format_login_key];
								}

								if (isset($item_array[$item_format_pass_key])) {
									$data['password'] = $item_array[$item_format_pass_key];
								}

								if (isset($item_array[$item_format_alt_email_key])) {
									$data['alt_email'] = $item_array[$item_format_alt_email_key];
								}

								if (isset($item_array[$item_format_phone_key])) {
									$data['phone'] = $item_array[$item_format_phone_key];
								}

								if (isset($item_array[$item_format_dob_key])) {
									$data['dob'] = $item_array[$item_format_dob_key];
								}

								if (isset($item_array[$item_format_gender_key])) {
									$data['gender'] = $item_array[$item_format_gender_key];
								}

								if (isset($item_array[$item_format_username_key])) {
									$data['username'] = $item_array[$item_format_username_key];
								}

								if (isset($item_array[$item_format_email_key])) {
									$data['email'] = $item_array[$item_format_email_key];
								}

								if (isset($item_array[$item_format_cookies_key])) {
									$data['cookies'] = $item_array[$item_format_cookies_key];
								}

								if (isset($item_array[$item_format_gauth_key])) {
									$data['gauth'] = $item_array[$item_format_gauth_key];
								}

								if (isset($item_array[$item_format_mailpassword_key])) {
									$data['mailpassword'] = $item_array[$item_format_mailpassword_key];
								}

								if (isset($item_array[$item_format_profile_link_key])) {
									$data['profile_link'] = $item_array[$item_format_profile_link_key];
								}
								if(isset($item_array[$item_format_userid_key])){
									$data['userid'] = $item_array[$item_format_userid_key];
								}
								if(isset($item_array[$item_format_name_key])){
									$data['name'] = $item_array[$item_format_name_key];
								}
								if(isset($item_array[$item_format_token_key])){
									$data['token'] = $item_array[$item_format_token_key];
								}
								if(isset($item_array[$item_format_useragent_key])){
									$data['useragent'] = $item_array[$item_format_useragent_key];
								}
								if(isset($item_array[$item_format_uid_key])){
									$data['uid'] = $item_array[$item_format_uid_key];
								}
								if(isset($item_array[$item_format_backup_code_key])){
									$data['backup_code'] = $item_array[$item_format_backup_code_key];
								}
								if(isset($item_array[$item_format_code_1_key])){
									$data['code_1'] = $item_array[$item_format_code_1_key];
								}
								if(isset($item_array[$item_format_code_2_key])){
									$data['code_2'] = $item_array[$item_format_code_2_key];
								}
								if(isset($item_array[$item_format_code_3_key])){
									$data['code_3'] = $item_array[$item_format_code_3_key];
								}
								if(isset($item_array[$item_format_code_4_key])){
									$data['code_4'] = $item_array[$item_format_code_4_key];
								}
								if(isset($item_array[$item_format_code_5_key])){
									$data['code_5'] = $item_array[$item_format_code_5_key];
								}

								$check_values = array('login', 'username', 'email');

								$checked_item = '';

								foreach ($item_format_ex as $item) {
									if (in_array($item, $check_values)) {

										$checked_item = $item;
										break;

									}
								}

								if (isset($item_array[0]) && !empty($item_array[0])) {
									$search_login = $item_array[0];
									$total = $wpdb->get_var("SELECT COUNT(*) FROM $table WHERE $checked_item = '$search_login'");

									if ($total == 0) {
										$data['item_status'] = 'free';
									} else {
										$data['item_status'] = 'repeat';
									}

								} else {
									$data['item_status'] = 'free';
								}

								$wpdb->insert($table, $data);
								$my_id = $wpdb->insert_id;

							}


						}



					}





					if ($duplicate_array) {
						if (strlen($duplicate_text) != 0) {

							foreach ($duplicate_array as $each_item) {


								// if (strpos($duplicate_text, '|')) {
								// 	$item_array = explode('|', $each_item, $item_format_count);
								// } else 
								if (strpos($duplicate_text, ':')) {
									$item_array = explode(':', $each_item, $item_format_count);
								}


								$colon_count = count($item_array);


								$count_item = count($item_array);


								if ($count_item == $item_format_count) {

									$table = $wpdb->prefix . 'accounts';

									$data['product_id'] = get_the_ID();
									$data['user_id'] = $author_id;

									if (isset($item_array[$item_format_login_key])) {
										$data['login'] = $item_array[$item_format_login_key];
									}

									if (isset($item_array[$item_format_pass_key])) {
										$data['password'] = $item_array[$item_format_pass_key];
									}

									if (isset($item_array[$item_format_alt_email_key])) {
										$data['alt_email'] = $item_array[$item_format_alt_email_key];
									}

									if (isset($item_array[$item_format_phone_key])) {
										$data['phone'] = $item_array[$item_format_phone_key];
									}

									if (isset($item_array[$item_format_dob_key])) {
										$data['dob'] = $item_array[$item_format_dob_key];
									}

									if (isset($item_array[$item_format_gender_key])) {
										$data['gender'] = $item_array[$item_format_gender_key];
									}

									if (isset($item_array[$item_format_username_key])) {
										$data['username'] = $item_array[$item_format_username_key];
									}

									if (isset($item_array[$item_format_email_key])) {
										$data['email'] = $item_array[$item_format_email_key];
									}

									if (isset($item_array[$item_format_cookies_key])) {
										$data['cookies'] = $item_array[$item_format_cookies_key];
									}

									if (isset($item_array[$item_format_gauth_key])) {
										$data['gauth'] = $item_array[$item_format_gauth_key];
									}

									if (isset($item_array[$item_format_mailpassword_key])) {
										$data['mailpassword'] = $item_array[$item_format_mailpassword_key];
									}

									if (isset($item_array[$item_format_profile_link_key])) {
										$data['profile_link'] = $item_array[$item_format_profile_link_key];
									}
									if(isset($item_array[$item_format_userid_key])){
									$data['userid'] = $item_array[$item_format_userid_key];
    								}
    								if(isset($item_array[$item_format_name_key])){
    									$data['name'] = $item_array[$item_format_name_key];
    								}
    								if(isset($item_array[$item_format_token_key])){
    									$data['token'] = $item_array[$item_format_token_key];
    								}
    								if(isset($item_array[$item_format_useragent_key])){
    									$data['useragent'] = $item_array[$item_format_useragent_key];
    								}
    								if(isset($item_array[$item_format_uid_key])){
    									$data['uid'] = $item_array[$item_format_uid_key];
    								}
    								if(isset($item_array[$item_format_backup_code_key])){
    									$data['backup_code'] = $item_array[$item_format_backup_code_key];
    								}
    								if(isset($item_array[$item_format_code_1_key])){
    									$data['code_1'] = $item_array[$item_format_code_1_key];
    								}
    								if(isset($item_array[$item_format_code_2_key])){
    									$data['code_2'] = $item_array[$item_format_code_2_key];
    								}
    								if(isset($item_array[$item_format_code_3_key])){
    									$data['code_3'] = $item_array[$item_format_code_3_key];
    								}
    								if(isset($item_array[$item_format_code_4_key])){
    									$data['code_4'] = $item_array[$item_format_code_4_key];
    								}
    								if(isset($item_array[$item_format_code_5_key])){
    									$data['code_5'] = $item_array[$item_format_code_5_key];
    								}

									$check_values = array('login', 'username', 'email');

									$checked_item = '';

									foreach ($item_format_ex as $item) {
										if (in_array($item, $check_values)) {

											$checked_item = $item;
											break;

										}
									}


									$data['item_status'] = 'repeat';
									$wpdb->insert($table, $data);
									$my_id = $wpdb->insert_id;

								}


							}

						}




					}




				}








				update_post_meta(get_the_ID(), 'bid_status', 'checking_accounts');
				update_post_meta(get_the_ID(), 'account_checked', 1);

				do_action('send_uploaded_email', get_the_ID());


				$logger = wc_get_logger();

				$logger->info('One item has checked. ' . get_the_ID(), array('source' => 'queue-checking'));

				echo 'One item has checked. ' . get_the_ID();

			endwhile;
			wp_reset_postdata();

		else:


			$logger = wc_get_logger();

			$logger->info('No item found for checking accounts. ', array('source' => 'queue-checking'));
			echo 'No item found for checking accounts.';

		endif;

		die();

	endif;



}


function send_uploaded_email_callback($post_id)
{

	$product_id = get_post_meta($post_id, 'custom_product_id', true);
	$author_id = get_post_field('post_author', $post_id);
	$to = get_the_author_meta('user_email', $author_id);
	$site_name = get_bloginfo('name');
	$domain_name = parse_url(get_site_url(), PHP_URL_HOST);

	$subject = 'Accounts are uploaded (№' . $product_id . ') - ' . $domain_name;

	$args = array(
		'post_id' => $post_id
	);

	ob_start();

	get_template_part('emails/partner', 'uploaded', $args);

	$body = ob_get_clean();



	$headers = array('Content-Type: text/html; charset=UTF-8', 'From: ' . $site_name . ' <noreply@' . $domain_name . '>');

	wp_mail($to, $subject, $body, $headers);


}
add_action('send_uploaded_email', 'send_uploaded_email_callback');


function add_partner_roles_bcmarket()
{
	add_role('partner', 'Partner', array('read' => true, 'level_0' => true));
}
add_action('init', 'add_partner_roles_bcmarket');


function current_user_has_bid()
{

	$bids_query = new WP_Query(
		array(
			'post_type' => 'bids',
			'posts_per_page' => -1,
			'author' => get_current_user_id()
		)
	);

	if ($bids_query->have_posts()) {

		return true;

	} else {

		return false;

	}

}


function current_user_has_approve_bid()
{

	$bids_query = new WP_Query(
		array(
			'post_type' => 'bids',
			'posts_per_page' => 1,
			'author' => get_current_user_id(),
			'meta_query' => array(
				array(
					'key' => 'offer_status',
					'value' => 'approved'
				)
			)
		)
	);

	if ($bids_query->have_posts()) {

		return true;

	} else {

		return false;

	}

}

function get_orders_ids_by_product_id($product_id)
{

	global $wpdb;
	$order_status = ['wc-processing', 'wc-completed'];

	$results = $wpdb->get_col($wpdb->prepare("
        SELECT lookup.order_id
        FROM {$wpdb->prefix}wc_order_product_lookup as lookup
        INNER JOIN {$wpdb->posts} AS posts ON lookup.order_id = posts.ID
        WHERE posts.post_type = 'shop_order'
        AND posts.post_status IN ( '" . implode("','", $order_status) . "' )
        AND lookup.product_id = %d
        ORDER BY lookup.order_id DESC", $product_id));

	return $results;
}

function get_pending_payment_by_product_id($product_id_ar)
{
	global $wpdb;
	$order_ids = get_orders_ids_by_product_id($product_id_ar);
	$total_costs = array();

	if ($order_ids) {
		$partner_price = floatval(get_post_meta($product_id_ar, 'partner_price', true));
		foreach ($order_ids as $order_id) {
			if (empty(get_post_meta($order_id, 'partner_payment_status', true))) {
				$qty = $wpdb->get_var($wpdb->prepare("SELECT SUM(product_qty) FROM {$wpdb->prefix}wc_order_product_lookup WHERE order_id = %d AND product_id = %d", $order_id, $product_id_ar));
				$total_costs[] = $partner_price * floatval($qty);
			}
		}
		return $total_costs ? array_sum($total_costs) : 0;
	}
	return 0;
}

// Get Pending Payment in the User Interface
function get_pending_payment_by_product_id_interface($product_id_ar)
{
	global $wpdb;
	$order_ids = get_orders_ids_by_product_id($product_id_ar);
	$total_costs = array();

	if ($order_ids) {
		$partner_price = floatval(get_post_meta($product_id_ar, 'partner_price', true));
		foreach ($order_ids as $order_id) {
			$p_status = get_post_meta($order_id, 'partner_payment_status', true);
			if (empty($p_status) || $p_status == 2) {
				$qty = $wpdb->get_var($wpdb->prepare("SELECT SUM(product_qty) FROM {$wpdb->prefix}wc_order_product_lookup WHERE order_id = %d AND product_id = %d", $order_id, $product_id_ar));
				$total_costs[] = $partner_price * floatval($qty);
			}
		}
		return $total_costs ? array_sum($total_costs) : 0;
	}
	return 0;
}

// Get Paid Payment by Product Id
function get_paid_payment_by_product_id($product_id_ar)
{
	global $wpdb;
	$order_ids = get_orders_ids_by_product_id($product_id_ar);
	$total_costs = array();

	if ($order_ids) {
		$partner_price = floatval(get_post_meta($product_id_ar, 'partner_price', true));
		foreach ($order_ids as $order_id) {
			if (get_post_meta($order_id, 'partner_payment_status', true) == 3) {
				$qty = $wpdb->get_var($wpdb->prepare("SELECT SUM(product_qty) FROM {$wpdb->prefix}wc_order_product_lookup WHERE order_id = %d AND product_id = %d", $order_id, $product_id_ar));
				$total_costs[] = $partner_price * floatval($qty);
			}
		}
		return $total_costs ? array_sum($total_costs) : 0;
	}
	return 0;
}


function get_pending_pcs_by_product_id($product_id_ar)
{
	global $wpdb;
	$order_ids = get_orders_ids_by_product_id($product_id_ar);
	$total_qty = array();

	if ($order_ids) {
		foreach ($order_ids as $order_id) {
			if (empty(get_post_meta($order_id, 'partner_payment_status', true))) {
				$qty = $wpdb->get_var($wpdb->prepare("SELECT SUM(product_qty) FROM {$wpdb->prefix}wc_order_product_lookup WHERE order_id = %d AND product_id = %d", $order_id, $product_id_ar));
				$total_qty[] = floatval($qty);
			}
		}
		return $total_qty ? array_sum($total_qty) : 0;
	}
	return 0;
}


// Get Pending Interface PCS
function get_pending_pcs_by_product_id_interface($product_id_ar)
{
	global $wpdb;
	$order_ids = get_orders_ids_by_product_id($product_id_ar);
	$total_qty = array();

	if ($order_ids) {
		foreach ($order_ids as $order_id) {
			$p_status = get_post_meta($order_id, 'partner_payment_status', true);
			if (empty($p_status) || $p_status == 2) {
				$qty = $wpdb->get_var($wpdb->prepare("SELECT SUM(product_qty) FROM {$wpdb->prefix}wc_order_product_lookup WHERE order_id = %d AND product_id = %d", $order_id, $product_id_ar));
				$total_qty[] = floatval($qty);
			}
		}
		return $total_qty ? array_sum($total_qty) : 0;
	}
	return 0;
}


// Get Paid Interface PCS
function get_paid_pcs_by_product_id_interface($product_id_ar)
{
	global $wpdb;
	$order_ids = get_orders_ids_by_product_id($product_id_ar);
	$total_qty = array();

	if ($order_ids) {
		foreach ($order_ids as $order_id) {
			if (get_post_meta($order_id, 'partner_payment_status', true) == 3) {
				$qty = $wpdb->get_var($wpdb->prepare("SELECT SUM(product_qty) FROM {$wpdb->prefix}wc_order_product_lookup WHERE order_id = %d AND product_id = %d", $order_id, $product_id_ar));
				$total_qty[] = floatval($qty);
			}
		}
		return $total_qty ? array_sum($total_qty) : 0;
	}
	return 0;
}



function get_pending_total_by_user_id($user_id)
{

	$product_query = new WP_Query(
		array(
			'post_type' => 'product',
			'posts_per_page' => -1,
			'author' => $user_id,
			'post_status' => array('publish', 'draft', 'pending')
		)
	);

	$total = array();

	global $wpdb;

	$dtotal = array();

	$table_name = $wpdb->prefix . "wallet_minus";
	$results = $wpdb->get_results("SELECT * FROM $table_name WHERE partner_id = $user_id AND status = 1");
	if ($results) {

		foreach ($results as $item) {
			$dtotal[] = $item->amount;
		}

	}


	if ($product_query->have_posts()) {

		while ($product_query->have_posts()) {
			$product_query->the_post();

			$total[] = get_pending_payment_by_product_id(get_the_ID());

		}
		wp_reset_postdata();

		if ($total) {
			return array_sum($total) - array_sum($dtotal);
		} else {
			return 0 - array_sum($dtotal);
		}


	} else {
		return 0 - array_sum($dtotal);
	}

}
// function get_pending_total_by_user_ids($user_id){

// 	$product_query = new WP_Query(array(
// 		'post_type' => 'product', 
// 		'posts_per_page' => 5, 
// 		'author' => $user_id, 
// 		'post_status' => array('publish', 'draft', 'pending')
// 	));

// 	$total = array();

// 	global $wpdb;

// 	$dtotal = array();

// 	$table_name = $wpdb->prefix . "wallet_minus";
// 	$results = $wpdb->get_results( "SELECT * FROM $table_name WHERE partner_id = $user_id AND status = 1");
// 	if($results){

// 		foreach($results as $item){
// 			$dtotal[] = $item->amount;
// 		}

// 	}


// 	if($product_query->have_posts()){

// 		while($product_query->have_posts()){
// 			$product_query->the_post();

// 			$total[] = get_pending_payment_by_product_id(get_the_ID());

// 		} wp_reset_postdata();

// 		if($total){
// 			return array_sum($total) - array_sum($dtotal);
// 		}else{
// 			return 0 - array_sum($dtotal);
// 		}


// 	}else{
// 		return 0 - array_sum($dtotal);
// 	}

// }

function get_pending_total_by_user_ids($user_id)
{
	global $wpdb;

	// Fetching total amount from wallet_minus table
	$table_name = $wpdb->prefix . "wallet_minus";
	$dtotal = $wpdb->get_col($wpdb->prepare("SELECT amount FROM $table_name WHERE partner_id = %d AND status = 1", $user_id));

	// Fetching pending payment from product posts
	$product_query = new WP_Query(
		array(
			'post_type' => 'product',
			'posts_per_page' => -1,
			'author' => $user_id,
			'post_status' => array('publish', 'draft', 'pending')
		)
	);

	$total = array();
	if ($product_query->have_posts()) {
		while ($product_query->have_posts()) {
			$product_query->the_post();
			$total[] = get_pending_payment_by_product_id(get_the_ID());
		}
		wp_reset_postdata();
	}
	// Calculating and returning total pending payment
	return array_sum($total) - array_sum($dtotal);
}


function withdraw_request_callback()
{

	$json = array();

	$user_id = $_POST['user_id'];


	$product_query = new WP_Query(
		array(
			'post_type' => 'product',
			'posts_per_page' => -1,
			'author' => $user_id
		)
	);

	$updated_order_ids = array();

	global $wpdb;
	$table_name = $wpdb->prefix . "wallet_minus";


	if ($product_query->have_posts()) {

		while ($product_query->have_posts()) {
			$product_query->the_post();



			$order_ids = get_orders_ids_by_product_id(get_the_ID());

			if ($order_ids) {

				foreach ($order_ids as $order_id) {

					if (empty(get_post_meta($order_id, 'partner_payment_status', true))) {
						update_post_meta($order_id, 'partner_payment_status', 2);
						$updated_order_ids[] = $order_id;
					}



				}

			}

		}
		wp_reset_postdata();


		if (isset($updated_order_ids)) {

			// Gather post data.
			$my_payment = array(
				'post_title' => 'Payment ',
				'post_status' => 'publish',
				'post_author' => $user_id,
				'post_type' => 'payment'
			);

			// Insert the post into the database.
			$payment_id = wp_insert_post($my_payment);


			$payment_update = array(
				'ID' => $payment_id,
				'post_title' => 'Payment ' . $payment_id,
			);

			$wpdb->update(
				$table_name,
				array(
					'status' => '0',
					'payment_id' => $payment_id,
				),
				array(
					'partner_id' => $user_id,
					'status' => '1'
				),
				array(
					'%s',
					'%d'
				),
				array(
					'%d',
					'%s'
				)
			);

			wp_update_post($payment_update);

			update_post_meta($payment_id, 'order_ids', implode(',', $updated_order_ids));
			update_post_meta($payment_id, 'payment_status', 'payment_requested');


		}


	}


	die(json_encode($json));

}
add_action('wp_ajax_withdraw_request', 'withdraw_request_callback');

function get_payment_request_total($order_ids, $user_id)
{
	global $wpdb;
	$total_costs = array();
	$order_ids = explode(',', $order_ids);

	if ($order_ids) {
		foreach ($order_ids as $order_id) {
			// Find all products in this order that belong to this user
			$items = $wpdb->get_results($wpdb->prepare("
				SELECT lookup.product_id, lookup.product_qty
				FROM {$wpdb->prefix}wc_order_product_lookup as lookup
				INNER JOIN {$wpdb->posts} as p ON lookup.product_id = p.ID
				WHERE lookup.order_id = %d AND p.post_author = %d
			", $order_id, $user_id));

			if ($items) {
				foreach ($items as $item) {
					$partner_price = floatval(get_post_meta($item->product_id, 'partner_price', true));
					$total_costs[] = $partner_price * floatval($item->product_qty);
				}
			}
		}
		return $total_costs ? array_sum($total_costs) : 0;
	}
	return 0;
}


function get_the_accounts_ordering($order_id)
{

	global $wpdb;


	$table_name = $wpdb->prefix . "accounts";
	$results = $wpdb->get_results("SELECT * FROM $table_name WHERE order_id = $order_id");
	$first = '';
	$last = '';

	if ($results) {
		$total_accounts = count($results);
		$num = 1;
		foreach ($results as $result) {
			if ($num == 1) {
				$first = $result->id;
			}
			if ($num == $total_accounts) {
				$last = $result->id;
			}
			$num++;
		}
	}

	echo $first;
	echo '<br>';
	echo $last;
	echo '<br>';

}

add_action('wp_ajax_update_payment_profile', 'update_payment_profile_callback');
function update_payment_profile_callback()
{

	$wallet = $_POST['wallets'];
	$user_id = get_current_user_id();

	if (count(array_filter($wallet)) == 1) {
		update_user_meta($user_id, 'wallets', $wallet);
		echo count(array_filter($wallet));
		die();
	} else {
		echo count(array_filter($wallet));
		die();
	}


}

function bcmarket_create_deduct_table()
{
	global $wpdb;

	$table_name = $wpdb->prefix . "wallet_minus";
	$charset_collate = $wpdb->get_charset_collate();

	// Create table if it doesn't exist
	$sql = "CREATE TABLE IF NOT EXISTS $table_name (
      id bigint(20) NOT NULL AUTO_INCREMENT,
      partner_id varchar(255),
      amount INT(11),
      status VARCHAR(255),
      created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
      PRIMARY KEY (id)
    ) $charset_collate;";

	require_once (ABSPATH . 'wp-admin/includes/upgrade.php');
	dbDelta($sql);

	// Add the new column if it doesn't exist
	$column_exists = $wpdb->get_results("SHOW COLUMNS FROM $table_name LIKE 'order_id'");

	if (empty($column_exists)) {
		$sql = "ALTER TABLE $table_name ADD order_id INT(11) DEFAULT NULL AFTER partner_id;";
		$wpdb->query($sql);
	}
}


add_action('init', 'bcmarket_create_deduct_table');


add_action('wp_ajax_deduct_payment', 'deduct_payment_callback');
function deduct_payment_callback()
{

	global $wpdb;

	$table_name = $wpdb->prefix . "wallet_minus";

	$cost = $_POST['cost'];
	$partner_id = $_POST['partner_id'];

	$data['partner_id'] = $partner_id;
	$data['amount'] = $cost;
	$data['status'] = '1';

	$wpdb->insert($table_name, $data);
	$ins_row = $wpdb->insert_id;

	echo $ins_row;

	die();

}

add_action('wp_ajax_add_payment', 'add_payment_callback');
function add_payment_callback()
{

	global $wpdb;

	$table_name = $wpdb->prefix . "woo_wallet_transactions";

	$cost = $_POST['cost'];
	$buyer_id = $_POST['buyer_id'];

	$balance = woo_wallet()->wallet->get_wallet_balance($buyer_id, '');
	$balance += $cost;

	$data['blog_id'] = 1;
	$data['user_id'] = $buyer_id;
	$data['type'] = 'credit';
	$data['amount'] = $cost;
	$data['balance'] = $balance;
	$data['currency'] = 'USD';
	$data['details'] = 'Wallet credit by Admin';
	$data['date'] = current_time('mysql');
	$data['created_by'] = 1;

	$wpdb->insert($table_name, $data, array('%d', '%d', '%s', '%f', '%f', '%s', '%s', '%s', '%d'));
	$transaction_id = $wpdb->insert_id;



	$transaction_id = $wpdb->insert_id;
	update_user_meta($buyer_id, '_current_woo_wallet_balance', $balance);
	clear_woo_wallet_cache($buyer_id);
	do_action('woo_wallet_transaction_recorded', $transaction_id, $buyer_id, $amount, 'credit');
	$email_admin = WC()->mailer()->emails['Woo_Wallet_Email_New_Transaction'];
	if (!is_null($email_admin) && apply_filters('is_enable_email_notification_for_transaction', true, $transaction_id)) {
		$email_admin->trigger($transaction_id);
	}

	echo $transaction_id;

	die();

}


function deduct_payment_by_payment_id($payment_id)
{


	$dtotal = array();

	global $wpdb;

	$table_name = $wpdb->prefix . "wallet_minus";
	$results = $wpdb->get_results("SELECT * FROM $table_name WHERE payment_id = $payment_id AND status = 0");
	if ($results) {

		foreach ($results as $item) {
			$dtotal[] = $item->amount;
		}

		return array_sum($dtotal);

	} else {
		return 0;
	}



}

function ac_time_ago_chat($timestamp)
{
	$post_date = strtotime($timestamp);
	$delta = time() - $post_date;
	if ($delta < 60) {
		echo 'Less than 1 minute ago';
	} elseif ($delta > 60 && $delta < 120) {
		echo '1 minute ago';
	} elseif ($delta > 120 && $delta < (60 * 60)) {
		echo strval(round(($delta / 60), 0)), ' minutes ago';
	} elseif ($delta > (60 * 60) && $delta < (120 * 60)) {
		echo '1 hour ago';
	} elseif ($delta > (120 * 60) && $delta < (24 * 60 * 60)) {
		echo strval(round(($delta / 3600), 0)), ' hour ago';
	} else {
		echo strval(round(($delta / (3600 * 24)), 0)), ' days ago';
	}
}

function get_invalid_total_by_pro_id($product_id)
{

	$order_ids = get_orders_ids_by_product_id($product_id, array('wc-processing'));

	if (count($order_ids) > 0) {

		$total = array();

		$args = array(
			'post_type' => 'shop_order',
			'post__in' => $order_ids,
			'post_status' => array_keys(wc_get_order_statuses()),
		);
		$orders = new WP_Query($args);

		while ($orders->have_posts()):
			$orders->the_post();

			$order = wc_get_order(get_the_ID());
			foreach ($order->get_items() as $item_id => $item) {
				$product_id_item = $item->get_product_id();
				if ($product_id_item == $product_id) {
					$total[] = intval($item->get_meta('invalid_items', true));
				}

			}

		endwhile;
		wp_reset_postdata();

		return array_sum($total);

	} else {
		return 0;
	}

}

// Count bad accounts
function count_bad_accounts($product_id)
{
	global $wpdb;
	$table_name = $wpdb->prefix . "accounts";
	$bad_accounts = $wpdb->get_var("SELECT COUNT(*) FROM $table_name WHERE product_id = $product_id and item_status = 'bad'");
	return $bad_accounts;
}


add_action('pre_get_posts', 'search_items_by_id');
function search_items_by_id($query)
{
	global $pagenow, $typenow;

	if ($pagenow == 'edit.php' && $typenow == 'item' && isset($_GET['s'])) {
		$search_term = trim($_GET['s']);

		if (is_numeric($search_term)) {
			$query->set('post__in', array(intval($search_term)));
			$query->set('s', '');
		}
	}
}


// Hook into that action that'll fire every one minutes
add_action('init', 'update_item_price_callbacks');
function update_item_price_callbacks()
{

	if (isset($_REQUEST['update_item_price']) && $_REQUEST['update_item_price'] == 'yes'):

		ignore_user_abort(1);
		ini_set('max_execution_time', 0);

		$pro_query = new WP_Query(
			array(
				'post_type' => 'product',
				'posts_per_page' => 100
			)
		);

		while ($pro_query->have_posts()):
			$pro_query->the_post();

			$item_id = get_post_meta(get_the_ID(), 'item_id', true);
			$partner_price = get_post_meta(get_the_ID(), 'partner_price', true);

			if ($partner_price == 0) {
				update_post_meta($item_id, 'item_price', 0);
			} else {
				update_post_meta($item_id, 'item_price', $partner_price);
			}



		endwhile;
		wp_reset_postdata();


		die();
	endif;
}

// Add new column to orders table
add_filter('manage_edit-shop_order_columns', 'add_order_id_column');
function add_order_id_column($columns)
{
	$columns['order_id'] = 'Order ID';
	return $columns;
}

// Populate new column with data
add_action('manage_shop_order_posts_custom_column', 'populate_order_id_column');
function populate_order_id_column($column)
{
	global $post;
	if ($column == 'order_id') {
		echo $post->ID;
	}
}