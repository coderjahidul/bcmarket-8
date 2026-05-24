<?php 
if(is_user_logged_in() && !current_user_has_approve_bid()){
    wp_safe_redirect( home_url('/partner/offers') );
}else if(!is_user_logged_in()){
    wp_safe_redirect( home_url('/my/') );
}

if( isset($_GET['pro_id']) && !empty($_GET['pro_id']) ) : 

    $paged = !isset( $_GET['page_number'] ) ? 1 : $_GET['page_number'];

    $args = array(
        'post_type'     => 'product', 
        'post_status'   => array('publish', 'draft', 'pending'),
        'meta_query'    => array(
            array(
                'key' => 'custom_product_id', 
                'value' => $_GET['pro_id']
            )
        )
    );

    $query = new WP_Query($args);

    if ($query->have_posts()) {
        $query->the_post();
        $product_id = get_the_ID();
    } wp_reset_postdata();



get_header(); ?>

<section class="soc-category" id="content">
    <div class="wrap-breadcrumbs">
        <div class="container">
            <div class="flex">
                <div class="block" itemscope="" itemtype="http://schema.org/BreadcrumbList" id="breadcrumbs">
                    <div itemprop="itemListElement" itemscope="" itemtype="http://schema.org/ListItem">
                        <a href="/" itemprop="item">
                            <span itemprop="name">Home</span>
                            <meta itemprop="position" content="0" />
                        </a>
                        <span class="divider">/</span>
                    </div>
                    <div itemprop="itemListElement" itemscope="" itemtype="http://schema.org/ListItem">
                        <span class="current" itemprop="name">Partner interface</span>
                        <meta itemprop="position" content="1" />
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="container">
        <div class="flex">
            <h1><?php the_title(); ?></h1>
           
            <?php get_template_part( 'partner/menu'); ?>

            <div class="body partner_cabinet">

                <table class="bids list zebra ac">
                    <tbody>
                        <tr>
                             <th>Request ID #</th>
                            <th>Date of creation of the application</th>
                            <th>Price for 1 Pc</th>
                            <th>Was Loaded/Left in the store</th>
                        </tr>
                        <tr>
                            <?php  
                                $product = wc_get_product( $product_id );
                                $post_7 = get_post( $product_id);
                            ?>
                            <td><?php echo get_post_meta($product_id, 'custom_product_id', true); ?></td>
                            <td><?php echo $product->get_date_created()->date('m.d.Y'); ?></td>
                            <td><?php echo wc_price(get_post_meta($product->get_id(), 'partner_price', true)); ?></td>
                            <td> <?php echo total_uploaded_accounts_by_id($product_id); ?> / <?php echo total_free_accounts_by_id($product_id); ?></td>
                        </tr>
                    </tbody>
                </table>

                <table class="bids list zebra ac">
                    <tbody>
                        <tr>
                            <th>Ordinal #</th>
                            <th>Order Number</th>
                            <th>Note</th>
                            <th>The date</th>
                            <th>Qty</th>
                            <th>To Pay</th>
                            <th>Payment State</th>
                            <th>Number of Invalids</th>
                            <th>Ticket</th>
                        </tr>

                        <?php 

                            $order_ids = get_orders_ids_by_product_id($product_id, array('wc-processing'));

                            if(count($order_ids) > 0) : 

                                $args = array(
                                    'post_type'  => 'shop_order',
                                    'posts_per_page' => 10,
                                    'post__in'   => $order_ids,
                                    'paged' => $paged,
                                    'post_status' => array_keys( wc_get_order_statuses() ),
                                );
                                $orders = new WP_Query($args);
                                if ($orders->have_posts()) :

                                    $num = ($paged - 1) * 10 + 1;

                                while($orders->have_posts()) : $orders->the_post();  

                                    $order = wc_get_order( get_the_ID() );
                                    $date_created = $order->get_date_created();

                                    $invalid = 0;

                                    foreach ( $order->get_items() as $item_id => $item ) {
                                        $quantity = $item->get_quantity();
                                        $product_id = $item->get_product_id();
                                        $partner_price = intval(get_post_meta($product_id, 'partner_price', true));
                                        $partner_price = (int)$item->get_meta( 'partner_price', true );
                                        
                                        if(!empty($item->get_meta( 'invalid_items', true ))){
                                            $invalid = $item->get_meta( 'invalid_items', true );
                                        }
                                    }

                                    $partner_total = $partner_price * $quantity;

                                    $ticket_id = '';

                                    $ticket_query = new WP_Query(array(
                                        'post_type' => 'tickets', 
                                        'posts_per_page' => 1,
                                        'post_status' => 'publish',
                                        'meta_query' =>  array(
                                            array(
                                                'key' => 'order_id_client', 
                                                'value' => $order->get_order_number()
                                            )
                                        )
                                    ));

                                    if($ticket_query->have_posts()){
                                        while($ticket_query->have_posts()) : $ticket_query->the_post(); 
                                            $ticket_id = get_post_meta(get_the_ID(), 'ticket_id', true);
                                        endwhile; wp_reset_postdata();
                                    }

                                    ?>
                                    
                                    <tr>
                                        <td><?php echo $num; ?></td>
                                        <td><?php echo $order->get_order_number(); ?></td>
                                        <td></td>
                                        <td><?php echo date_i18n( 'F j, Y', strtotime( $date_created->date('Y-m-d H:i:s') ) ); ?></td>
                                        <td><?php echo $quantity; ?></td>
                                        <td><?php echo wc_price($partner_total); ?></td>
                                        <td>
                                            <?php 

                                                $payment_status = '';

                                                $payment_query = new WP_Query(array(
                                                    'post_type' => 'payment', 
                                                    'posts_per_page' => 1,
                                                    'post_status' => 'publish',
                                                    'meta_query' =>  array(
                                                        array(
                                                            'key' => 'order_ids', 
                                                            'value' => $order->get_id(), 
                                                            'compare' => "LIKE"
                                                        )
                                                    )
                                                ));
                                                if($payment_query->have_posts()){
                                                    while($payment_query->have_posts()) : $payment_query->the_post(); 
                                                        $payment_status = get_post_meta(get_the_ID(), 'payment_status', true);
                                                    endwhile; 
                                                }

                                                if($payment_status != 'paid') : 
                                            ?>
                                            Awaiting Payments
                                        <?php else : ?>
                                            Paid
                                        <?php endif; ?>
                                        </td>
                                        <td><?php echo $invalid; ?></td> 
                                        <td>
                                            <?php if(!empty($ticket_id) && $invalid != 0) : ?>
                                                <a href="<?php echo esc_url(home_url('/')); ?>partner/pticket?id=<?php echo $ticket_id; ?>">Ticket</a>
                                            <?php endif; ?>
                                        </td>
                                    </tr>
                            <?php  $num++; endwhile;
                           ?>
                    </tbody>
                </table>
                <div class="pager_wrap" style="display:flex;justify-content:center;">
                <?php
                $total_pages = $orders->max_num_pages;
                    if ($total_pages > 1) {
                        echo '<div class="pagination">';
                        echo paginate_links(array(
                            'base' => add_query_arg('page_number', '%#%'),
                            'format' => '',
                            'current' => $paged,
                            'total' => $total_pages,
                            'prev_text' => '&laquo;',
                            'next_text' => '&raquo;',
                        ));
                        
                        echo '</div>';
                    }

                    
                    endif;
                    ?>
                    </div><?php

                    wp_reset_postdata();

                endif; 
                ?>
                
            </div>
        </div>
    </div>
</section>

<?php endif; ?>

<?php get_footer() ?>