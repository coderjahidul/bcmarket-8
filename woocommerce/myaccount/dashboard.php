<?php
/**
 * My Account Dashboard
 *
 * Shows the first intro screen on the account dashboard.
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/myaccount/dashboard.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see     https://woo.com/document/template-structure/
 * @package WooCommerce\Templates
 * @version 4.4.0
 */
 ?>
<div class="row main-block mb-25">
    <div class="col-xs-12">
        <h4 class="profile_title">Your orders</h4>
        <p class="main-block-description">Last 10 orders you made</p>
        <div class="table-responsive mt-20">
            <table class="table orders">
                <thead>
                    <tr>
                        <th class="table-tablet-hide">Order#</th>
                        <th class="table-mobile-hide">Name</th>
                        <th class="table-mobile-hide">Payment</th>
                        <th class="table-mobile-hide">Cost</th>
                        <th class="table-mobile-hide align-center">Download</th>
                        <th class="table-mobile-hide align-center">Ticket</th>
                    </tr>
                </thead>
                <tbody>

                    <?php 
                    

                    // Get orders by customer with ID 12.
                    $args = array(
                        'customer_id' => get_current_user_id(),
                    );
                    $orders = wc_get_orders( $args );

                    if($orders) : 

                        foreach($orders as $order) :

                                $actions = wc_get_account_orders_actions( $order );

                                $args = array(
                                    'order_id' => $order->get_id()
                                );

                                get_template_part('woocommerce/myaccount/order', 'item', $args);
                         ?> 
 
                    

                    <?php endforeach; else: ?>

                        <tr>
                            <td colspan="6" align="center">There's nothing here yet</td>
                        </tr>

                    <?php endif; ?>
                </tbody>
            </table>
            
            <div class="pager_wrap" style="display: flex; justify-content: center;">
                <a href="<?php echo home_url();?>/your-account/orders/page/2/">Next>></a>
            </div>
        </div>
    </div>

    <div class="col-xs-12">
        <h4 class="profile_title">Requests to the seller</h4>
        <p class="main-block-description">Last 10 tickets you created</p>
        <div class="table-responsive mt-20">
            <table class="table -tickets">
                <thead>
                    <tr>
                        <th class="table-tablet-hide">Request#</th>
                        <th class="table-mobile-hide">Subject</th>
                        <th class="table-mobile-hide">Last message</th>
                    </tr>
                </thead>
                <tbody>
                    <?php 
                        $current_url = esc_url_raw($_SERVER['REQUEST_URI']);
                        $page_number = intval(preg_replace('/[^0-9]+/', '', $current_url), 10);

                        $paged = $page_number == 0 ? 1 : $page_number;

                        $current_user = wp_get_current_user();

                        $tickets_query = new WP_Query(array(
                            'post_type' => 'tickets', 
                            'posts_per_page' => 10,
                            'paged' => $paged,
                            'meta_query' => array(
                                array(
                                    'key' => 'user_id', // Replace 'user_id' with the actual custom field key where you store user IDs
                                    'value' => $current_user->ID, // Get the current user's ID
                                    'compare' => '=',
                                ),
                                // Add more conditions if needed
                            ),
                        ));

                        if($tickets_query->have_posts()) : 
                            while($tickets_query->have_posts()) : $tickets_query->the_post();
                                ?>
                                    <tr>
                                        <td><a href="<?php echo esc_url(home_url('your-account/tickets/?id=')); ?><?php echo get_the_ID(); ?>"><?php echo get_post_meta(get_the_ID(), 'ticket_id', true); ?></a></td>
                                        <td><?php echo get_post_meta(get_the_ID(), 'subject_title', true); ?></td>
                                        <td></td>
                                    </tr>
                                 
                        <?php endwhile; wp_reset_postdata(); else : ?>
                            <tr>
                                <td colspan="3" align="center">There's nothing here yet</td>
                            </tr>
                        <?php endif; ?>
                </tbody>
            </table>
            <div class="pager_wrap" style="display: flex; justify-content: center;">
                <?php echo paginate_links(array(
                    'base' => str_replace(999999999, '%#%', esc_url(get_pagenum_link(999999999))),
                    'format' => '?paged=%#%',
                    'current' => max(1, $paged),
                    'total' => $tickets_query->max_num_pages,
                    'prev_text' => __('&laquo; Prev'),
                    'next_text' => __('Next &raquo;'),
                )); ?>
            </div>
        </div>
    </div>
</div>
