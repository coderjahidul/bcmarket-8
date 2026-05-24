<?php 
if(is_user_logged_in()){
    $current_user = wp_get_current_user();
    $roles = array('administrator', 'employee', 'wpseo_manager');
    $found = 0; 
    foreach($roles as $role){
        if(in_array( $role, (array) $current_user->roles) ){
            $found = 1;
           
        }
    }

    if($found == 0){
         wp_redirect( home_url('/my/') );
            exit(); 
    }
    
}else{
    wp_redirect( home_url('/my/') );
    exit(); 
}
get_header(); ?>

<section class="soc-category" id="content">
    
    <?php get_template_part('template-parts/admin', 'breadcrumb'); ?>

    <div class="container">
        <div class="flex">
            <h1><?php the_title(); ?></h1>
           
            <?php get_template_part( 'admin/admin', 'menu'); ?>
            <div class="body partner_cabinet">

            <div class="search_user_by">
            		<!-- <form action="<?php //echo 'https://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI']; ?>">
            			<input type="text" placeholder="Search by ID or Email" name="query" value="<?php //if(isset($_GET['query'])){echo $_GET['query']; } ?>">
            			<button type="submit">Search</button>
            		</form> -->
					<!-- <form action="<?php //echo esc_url(home_url(add_query_arg(array(), $wp->request))); ?>">
						<input type="text" placeholder="Search by ID or Email" name="query" value="<?php //echo isset($_GET['query']) ? esc_attr($_GET['query']) : ''; ?>">
						<button type="submit">Search</button>
					</form> -->
					<form action="<?php echo esc_url(home_url($wp->request)); ?>" method="GET">
						<input type="text" placeholder="Search by ID or Email" name="query" value="<?php echo isset($_GET['query']) ? esc_attr($_GET['query']) : ''; ?>">
						<button type="submit">Search</button>
					</form>

				</div>
             
                <?php 

                    $current_url = esc_url_raw($_SERVER['REQUEST_URI']);
        			$page_number = intval(preg_replace('/[^0-9]+/', '', $current_url), 10);
        
        			$paged = $page_number == 0 ? 1 : $page_number;
                    

                    if(isset($_GET['query']) && !empty($_GET['query']) ) {
                         $args = array(
                            'post_type'  => 'shop_order',
                            'post_status' => array_keys( wc_get_order_statuses() ),
                            // 'paged' => $paged,
                            // 'posts_per_page' => 10, 
                            'meta_query' => array(
                                array(
                                    'key' => '_order_number', 
                                    'value' => $_GET['query']
                                )
                            )
                        );
                    }else{
                        $args = array(
                            'post_type'  => 'shop_order',
                            'post_status' => array_keys( wc_get_order_statuses() ),
                            'paged' => $paged,
                            'posts_per_page' => 100
                        );
                    }
                    
                    $orders = new WP_Query($args);

                    if($orders) : ?>

                        <table class="bids list zebra ac">
                            <tbody>
                                <tr>
                                    <th>Id</th>
                                    <th>Buyer Id</th>
                                    <th>Partner Id</th>
                                    <th>Product Id</th>
                                    <th>Item Id</th>
                                    <th>Qty</th>
                                    <th>Order total</th>
                                    <th>Per Qty Price</th>
                                    <th>Date</th>
                                    <th>Payment Method</th>
                                    <th>Invalid Item</th>
                                    <th>Download Item</th>
                                </tr>

                                <?php 
                                while($orders->have_posts()) : $orders->the_post(); 
                                $order_id = get_the_ID();
                                $order = wc_get_order($order_id);
                                $items = $order->get_items();
                                
                                if (empty($items)) {
                                    global $wpdb;
                                    $items = $wpdb->get_results($wpdb->prepare("SELECT * FROM {$wpdb->prefix}wc_order_product_lookup WHERE order_id = %d", $order_id));
                                }

                                foreach ($items as $item_id => $item) : 
                                    if (is_object($item) && method_exists($item, 'get_product_id')) {
                                        // Standard WC_Order_Item_Product object
                                        $product_id = $item->get_product_id();
                                        $order_total_qty = $item->get_quantity();
                                        $item_total_price = $item->get_total();
                                        $invalid_item_count = $item->get_meta('invalid_items', true);
                                    } else {
                                        // Row from wc_order_product_lookup
                                        $item_id = $item->order_item_id;
                                        $product_id = $item->product_id;
                                        $order_total_qty = $item->product_qty;
                                        $item_total_price = $item->product_net_revenue;
                                        $invalid_item_count = 0; // Meta is lost if itemmeta is empty
                                    }
                            ?>
                                <tr>
                                    <td><?php echo $order->get_order_number(); ?></td>
                                    <td><?php echo $order->get_user_id(); ?></td>
                                    <td>
                                        <?php 
                                            $partner_id = get_post_field('post_author', $product_id);
                                            echo $partner_id;
                                        ?>
                                    </td>
                                    <td>
                                        <?php echo get_post_meta($product_id, 'custom_product_id', true); ?>
                                    </td>
                                    <td>
                                        <?php 
                                        $related_item_id = get_post_meta($product_id, 'item_id', true);
                                        echo $related_item_id;
                                        ?>
                                    </td>
                                    <td>
                                        <?php
                                            echo $order_total_qty;
                                        ?>
                                    </td>
                                    <td>
                                        <?php
                                            echo wc_price($item_total_price);
                                        ?>
                                    </td>
                                    <td>
                                        <?php
                                            $pre_qty_partner_price = get_post_meta($product_id, 'partner_price', true);
                                            if ($order_total_qty != 0) {
                                                $pre_qty_price = $item_total_price / $order_total_qty;
                                                $pre_qty_price = number_format($pre_qty_price, 2);
                                                echo "$" . $pre_qty_price;
                                            } else {
                                                echo "0";
                                            }
                                        ?>
                                    </td>
                                    <td>
                                        <?php echo $order->get_date_created(); ?>
                                    </td>
                                    <td>
                                        <?php echo $order->get_payment_method_title(); ?>
                                    </td>
                                    <td class="invalid" data-itemid="<?php echo $item_id; ?>" data-id="<?php echo $item_id; ?>" data-partner_id="<?php echo $partner_id; ?>" data-pre_qty_partner_price="<?php echo $pre_qty_partner_price; ?>">
                                        <input type="number" name="invalid_item" placeholder="Add Total Invalid" value="<?php //echo $invalid_item_count; ?>">
                                    </td>
                                    <td><a href="<?php echo esc_url(home_url('/download-accounts/')); ?>?order_id=<?php echo $order->get_order_number(); ?>&order_key=<?php echo $order->get_order_key(); ?>" target="_blank">Download</a></td>
                                </tr>
                            <?php 
                                endforeach;
                            endwhile; 
                            wp_reset_postdata();
                            ?>
                            
                               
                            </tbody>
                        </table>

                <?php else : echo 'No Order found!'; endif; ?>

                <div class="pager_wrap" style="display:flex;justify-content:center;">
                    <?php 
                        $big = 999999999; // need an unlikely integer

                        
                            
                            if ( $orders->max_num_pages > 1 ) {
                            	echo '<div class="pagination">';
                            	$pagination_args = array(
                            		'base'      => get_pagenum_link(1) . '%_%',
                            		'format'    => 'page/%#%',
                            		'current'   => $paged,
                            		'total'     => $orders->max_num_pages,
                            		'prev_text' => __('&laquo; Previous'),
                            		'next_text' => __('Next &raquo;'),
                            	);
                            	echo paginate_links( $pagination_args );
                            	echo '</div>';
                            }


                        ?>
                </div>
            </div>
        </div>
    </div>
</section>

<?php get_footer() ?>