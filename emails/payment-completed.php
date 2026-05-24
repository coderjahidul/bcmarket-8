<?php get_template_part('emails/email', 'header');
$payment_id = $args['payment_id'];
?> 
<p style="text-align:center">
	<span style="font-size:22px">Payment is completed!</span>
</p>
<p style="text-align:center">
	Comment: <br>
	<?php 
        $order_ids = get_post_meta($payment_id, 'order_ids', true); 
        $order_ids = explode(',', $order_ids);

        if($order_ids){
            $count_total = count($order_ids);
            $num = 1; 
            foreach($order_ids as $order_id){

                $quantity = '';
                $order = wc_get_order( $order_id );
                foreach ( $order->get_items() as $item_id => $item ) {
                     $product_id = $item->get_product_id();
                    $quantity = $item->get_quantity();
                }
               

               echo 'Payment application #'. get_post_meta($product_id, 'custom_product_id', true) .'('. $quantity .')';
                if($count_total != $num){
                    echo '<br/>';
                }
            }
        }
    ?>
</p>
<?php get_template_part('emails/email', 'footer'); ?> 