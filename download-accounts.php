<?php
/*
Template Name: Download Accounts
*/ 
if(isset($_GET['order_id']) && !empty($_GET['order_id']) && isset($_GET['order_key']) && !empty($_GET['order_key'])){

	$order_id_param = sanitize_text_field($_GET['order_id']);
	$order_key = sanitize_text_field($_GET['order_key']);

	// 1. Try treating it as a Post ID
	$order = wc_get_order($order_id_param);

	// 2. Fallback: Try treating it as a custom Order Number
	if (!$order || $order->get_order_key() !== $order_key) {
		$args = array(
			'post_type' => 'shop_order',
			'post_status'    => 'any',
			'meta_query' => array(
				array(
					'key' => '_order_number', 
					'value' => $order_id_param
				)
			)
		);

		$query = new WP_Query($args);

		if ($query->have_posts()) {
			$query->the_post();
			$order = wc_get_order(get_the_ID());
		}
		wp_reset_postdata();
	}

	if (!$order || $order->get_order_key() !== $order_key) {
		wp_die("Invalid order or security key.");
	}

	$order_id = $order->get_id();


	global $wpdb;
	$table_name = $wpdb->prefix . "accounts";

	$file = 'order'. $order->get_order_number() .'.txt';

	$results = $wpdb->get_results( "SELECT * FROM $table_name WHERE order_id = $order_id"); 

	$txt = fopen($file, "w") or die("Unable to open file!");

	

	$item_id = '';

	$order_items = $order->get_items();

    if (empty($order_items)) {
        global $wpdb;
        $order_items = $wpdb->get_results($wpdb->prepare("SELECT * FROM {$wpdb->prefix}wc_order_product_lookup WHERE order_id = %d", $order->get_id()));
    }

	foreach ( $order_items as $item ) {
		if (is_object($item) && method_exists($item, 'get_product_id')) {
            $item_id = $item->get_product_id();
        } else {
            $item_id = $item->product_id;
        }
	}

	$item_format = get_post_meta($item_id, 'item_format', true);
    $item_format_ex = explode(',', $item_format);

	if($results){
		foreach($results as $result){

			$all_items = array();
            foreach($item_format_ex as $item_single){
                $all_items[] = $result->$item_single; 
            }
            $item_line =  implode(':', $all_items);

			fwrite($txt, $item_line . "\n");
		}
	}
	
	fclose($txt);


	header('Content-Description: File Transfer');
	header('Content-Disposition: attachment; filename='.basename($file));
	header('Expires: 0');
	header('Cache-Control: must-revalidate');
	header('Pragma: public');
	header('Content-Length: ' . filesize($file));
	header("Content-Type: text/plain");
	readfile($file);
}



?>