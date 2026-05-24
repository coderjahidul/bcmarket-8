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
<style>
.payment_comment_form {
  font-size: 0px;
  padding: 15px;
}
.payment_comment_form button {
  margin-top: 20px;
}
.mark_as_paid_form button{
    margin-top: 10px;
}
.payment_comment_form textarea {
  font-size: 15px;
  max-width: 250px;
}
.payment_comment_form_message {
  display: block;
  font-size: 16px;
}
</style>

<section class="soc-category" id="content">
    
    <?php get_template_part('template-parts/admin', 'breadcrumb'); ?>

    <div class="container">
        <div class="flex">
            <h1>Partner interface</h1>
            
            <?php get_template_part( 'admin/admin', 'menu'); ?>
            <div class="body">
                <h2>Summary</h2>
                <table class="list zebra ac">
                    <tbody>
                        <tr>
                            <th>Partner ID</th>
                            <th>Date</th>
                            <th>Comment</th>
                            <th>Sum</th>
                            <th>Status</th>
                            <th>Comment to the payment</th>
                            <th>Payment Info</th>
                        </tr>

                        <?php
                            

$paged = ( get_query_var('paged') ) ? get_query_var('paged') : 1;

$payment_query = new WP_Query(array(
    'post_type'      => 'payment',
    'posts_per_page' => 10,
    'paged'          => $paged,
));

                            if($payment_query->have_posts()) : 

                                while($payment_query->have_posts()) : $payment_query->the_post();

                                 ?>

                                    <tr>
                                        <td><?php echo  get_post_field( 'post_author', get_the_ID() ); ?></td>
                                        <td><?php echo get_the_date(); ?></td>
                                        <td>
                                            <a href="javascript:void(0)" onclick="toggleComments(<?php echo get_the_ID(); ?>)">view comments</a>
                                            
                                            <div id="uploaded-data-<?php echo get_the_ID(); ?>" style="display:none; margin-top:5px;">
                                                <table border="1" cellpadding="5" cellspacing="0">
                                                    <thead>
                                                        <tr>
                                                            <th>Payment Application ID</th>
                                                            <th>Quantity</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <?php 
                                                        global $wpdb;
                                                        $order_ids = get_post_meta(get_the_ID(), 'order_ids', true); 
                                                        $order_ids = explode(',', $order_ids);
                                                        $user_id = get_post_field( 'post_author', get_the_ID() );
                                        
                                                        if($order_ids){
                                                            foreach($order_ids as $order_id){
                                        
                                                                $quantity = '';
                                                                $order = wc_get_order( $order_id );
                                        
                                                                if ( ! $order ) {
                                                                    continue;
                                                                }
                                        
                                                                foreach ( $order->get_items() as $item_id => $item ) {
                                                                    $product_id = $item->get_product_id();
                                                                    $post_author_id = get_post_field( 'post_author', $product_id );
                                        
                                                                    if($post_author_id == $user_id){
                                                                        $quantity = $item->get_quantity();
                                                                    }
                                                                }
                                        
                                                                echo '<tr>';
                                                                echo '<td>' . get_post_meta($product_id, 'custom_product_id', true) . '</td>';
                                                                echo '<td>' . $quantity . '</td>';
                                                                echo '</tr>';
                                                            }
                                                        }
                                                        ?>
                                                    </tbody>
                                                </table>
                                            </div>
                                        </td>


                                        <td><?php echo wc_price(get_payment_request_total(get_post_meta(get_the_ID(), 'order_ids', true), get_post_field( 'post_author', get_the_ID() )) - deduct_payment_by_payment_id(get_the_ID())); ?></td>
                                        <td>
                                            <span>
                                                <?php 
                                                    if(get_post_meta(get_the_ID(), 'payment_status', true) == 'payment_requested'){
                                                        echo 'Payment Requested';
                                                    }
                                                    if(get_post_meta(get_the_ID(), 'payment_status', true) == 'paid'){
                                                        echo 'Paid';
                                                    }
                                                ?>
                                            </span>
                                            <?php if(get_post_meta(get_the_ID(), 'payment_status', true) == 'payment_requested') : ?>
                                                <form class="mark_as_paid_form">
                                                    <input type="hidden" name="action" value="mark_as_paid">
                                                    <input type="hidden" name="payment_id" value="<?php echo get_the_ID(); ?>">
                                                    <input type="hidden" name="partner_id" value="<?php echo  get_post_field( 'post_author', get_the_ID() ); ?>">
                                                    <div>
                                                        <button type="submit" class="btn btn-primary">Mark As paid</button>
                                                    </div>
                                                </form>
                                        <?php endif; ?>
                                        </td>
                                        <td class="pay_comment">
                                            <form class="payment_comment_form">
                                                <textarea name="payment_comment" id="" cols="30" rows="10"><?php echo get_post_meta( get_the_ID(), 'payment_comment', true ); ?></textarea>
                                                <input type="hidden" name="action" value="update_payment_comment">
                                                <input type="hidden" name="payment_id" value="<?php echo get_the_ID(); ?>">
                                                <div>
                                                    <button type="submit" class="btn btn-primary">Update</button>
                                                </div>
                                                <div class="payment_comment_form_message"></div> 
                                            </form>
                                            
                                        </td>
                                        <td style="text-align:left">
                                            <?php 

                                            $user_id = get_post_field( 'post_author', get_the_ID() );


                                            $wallets = get_user_meta($user_id, 'wallets', true);

                                            $bitcoin_min = get_theme_mod('bitcoin_min');
                                                $litecoin_min = get_theme_mod('litecoin_min');
                                                $etherium_min = get_theme_mod('etherium_min');
                                                $usdt_min = get_theme_mod('usdt_min');



                                            if($wallets) : foreach($wallets as $key => $value) :

                                                
                                                $gat_name = '';

                                                if($key == 52){
                                                    $gat_name = 'Litecoin (LTC)(min. $'. $litecoin_min .'):';
                                                }
                                                if($key == 74){
                                                    $gat_name = 'USDT(TRC20) (min. $'. $usdt_min .'):';
                                                }
                                                if($key == 11){
                                                    $gat_name = 'Bitcoin (BTC) (min. $'. $bitcoin_min .'):';
                                                }
                                                if($key == 60){
                                                    $gat_name = 'Etherium (ETH) (min. $'. $etherium_min .'):';
                                                }

                                                if(!empty($value)){
                                                    echo '<strong>'. $gat_name .'</strong>'; 
                                                    echo ' ';
                                                    echo $value; 
                                                    echo '<br>';
                                                }

                                            endforeach; endif; 


                                            ?>


                                        </td>

                                    </tr>
                                <?php endwhile; wp_reset_postdata(); ?>

                            <?php else : echo '<tr><td colspan="5">No Payment found.</td></tr>'; endif; ?>
                       
                    </tbody>
                </table>
                <div class="pager_wrap" style="display: flex; justify-content: center;">
                    <?php echo paginate_links(array(
                        'base' => str_replace(999999999, '%#%', esc_url(get_pagenum_link(999999999))),
                        'format' => '?paged=%#%',
                        'current' => max(1, $paged),
                        'total' => $payment_query->max_num_pages,
                        'prev_text' => __('&laquo; Prev'),
                        'next_text' => __('Next &raquo;'),
                    )); ?>
                </div>
            </div>
        </div>
    </div>
    <script>
        function toggleComments(postId) {
            var div = document.getElementById('uploaded-data-' + postId);
            if(div.style.display === 'none') {
                div.style.display = 'block';
            } else {
                div.style.display = 'none';
            }
        }
    </script>
</section>

<?php get_footer() ?>