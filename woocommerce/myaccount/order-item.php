<?php 


$order = wc_get_order( $args['order_id'] );
$order_items = $order->get_items();

if (empty($order_items)) {
    global $wpdb;
    $order_items = $wpdb->get_results($wpdb->prepare("SELECT * FROM {$wpdb->prefix}wc_order_product_lookup WHERE order_id = %d", $order->get_id()));
}

$item_status = '';
$download_expired = false;

foreach ( $order_items as $item_id => $item ) {
    if (is_object($item) && method_exists($item, 'get_product_id')) {
        $product_id = $item->get_product_id();
    } else {
        $product_id = $item->product_id;
    }
    $item_status  = get_post_status ( $product_id );
}


// Disable downloads after 90 days from the order creation date.
$order_date = $order ? $order->get_date_created() : null;
if ( $order_date instanceof WC_DateTime ) {
    $download_expired = ( time() - $order_date->getTimestamp() ) > ( 90 * DAY_IN_SECONDS );
}
?>
<tr>
    <td class="table-tablet-hide"><?php echo $order->get_order_number(); ?></td>
    <td>
        <div class="sub-col-data desktop-hide"><span>Order#:</span> ><?php echo $order->get_order_number(); ?></div>
        <?php foreach ( $order_items as $item_id => $item ) :
            if (is_object($item) && method_exists($item, 'get_product_id')) {
                $product_id = $item->get_product_id();
                $item_name = $item->get_name();
            } else {
                $product_id = $item->product_id;
                $item_name = get_the_title($product_id);
            }
            ?>
             <?php if($item_status != 'private') : ?>
            <a href="<?php echo get_permalink($product_id); ?>">
                    <?php echo $item_name; ?>
            </a>
        <?php else: ?>
            <?php echo $item_name; ?>
        <?php endif; ?>
    <?php endforeach; ?>
        <div class="sub-col-data tablet-hide align-right"><span>Payment:</span> <?php echo $order->get_payment_method_title(); ?></div>
        <div class="sub-col-data tablet-hide align-right"><span>Cost:</span><?php echo $order->get_total(); ?></div>
        <div class="sub-col-data tablet-hide main-block-status-line">
            <div>
                 <?php if($item_status != 'private' && ! $download_expired) : ?>
                <a href="<?php echo esc_url(home_url('/download-accounts/')); ?>?order_id=<?php echo $order->get_order_number(); ?>&order_key=<?php echo $order->get_order_key(); ?>" class="main-block-download-btn download-link" target="_blank">
                    <svg width="15" height="15" viewBox="0 0 15 15" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path
                            d="M14.5833 1.83333L13.4167 0.416667C13.25 0.166667 12.9167 0 12.5 0H2.5C2.08333 0 1.75 0.166667 1.5 0.416667L0.416667 1.83333C0.166667 2.16667 0 2.5 0 2.91667V13.3333C0 14.25 0.75 15 1.66667 15H13.3333C14.25 15 15 14.25 15 13.3333V2.91667C15 2.5 14.8333 2.16667 14.5833 1.83333ZM7.5 12.0833L2.91667 7.5H5.83333V5.83333H9.16667V7.5H12.0833L7.5 12.0833ZM1.75 1.66667L2.41667 0.833333H12.4167L13.1667 1.66667H1.75Z"
                            fill="#A4A4A4"
                        ></path>
                    </svg>
                    Download
                </a>
            <?php endif; ?>
            </div>
            <div>
                <a href="<?php echo esc_url(home_url('tickets/new/?order_id=')); ?><?php echo $order->get_order_number(); ?>" class="main-block-create-ticket-btn">
                    <svg width="18" height="15" viewBox="0 0 18 15" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path
                            d="M1.56522 0C0.696522 0 0 0.702632 0 1.57895V11.0526C0 11.4714 0.164906 11.873 0.458442 12.1691C0.751977 12.4652 1.1501 12.6316 1.56522 12.6316H10.2412C10.1274 11.9525 10.1618 11.2565 10.342 10.5922C10.5221 9.92791 10.8437 9.31121 11.2843 8.78511C11.7249 8.25902 12.274 7.83617 12.8932 7.54605C13.5124 7.25592 14.1868 7.1055 14.8696 7.10526C15.1317 7.10684 15.3939 7.13053 15.6522 7.17632V1.57895C15.6522 1.16018 15.4873 0.758573 15.1937 0.462463C14.9002 0.166353 14.5021 0 14.087 0H1.56522ZM1.56522 1.57895L7.82609 5.52632L14.087 1.57895V3.15789L7.82609 7.10526L1.56522 3.15789V1.57895ZM14.087 8.68421V11.0526H11.7391V12.6316H14.087V15H15.6522V12.6316H18V11.0526H15.6522V8.68421H14.087Z"
                            fill="#A4A4A4"
                        ></path>
                    </svg>
                    Ticket
                </a>
            </div>
        </div>
    </td>
    <td class="table-mobile-hide">
        <div class="ps">
            <?php echo $order->get_payment_method_title(); ?>
        </div>
    </td>
    <td class="table-mobile-hide"><?php echo wc_price($order->get_total()); ?></td>
    <td class="table-mobile-hide align-center">
         <?php if($item_status != 'private' && ! $download_expired) : ?>
            <a href="<?php echo esc_url(home_url('/download-accounts/')); ?>?order_id=<?php echo $order->get_order_number(); ?>&order_key=<?php echo $order->get_order_key(); ?>" class="main-block-download-btn download-link" target="_blank">
                <svg width="15" height="15" viewBox="0 0 15 15" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path
                        d="M14.5833 1.83333L13.4167 0.416667C13.25 0.166667 12.9167 0 12.5 0H2.5C2.08333 0 1.75 0.166667 1.5 0.416667L0.416667 1.83333C0.166667 2.16667 0 2.5 0 2.91667V13.3333C0 14.25 0.75 15 1.66667 15H13.3333C14.25 15 15 14.25 15 13.3333V2.91667C15 2.5 14.8333 2.16667 14.5833 1.83333ZM7.5 12.0833L2.91667 7.5H5.83333V5.83333H9.16667V7.5H12.0833L7.5 12.0833ZM1.75 1.66667L2.41667 0.833333H12.4167L13.1667 1.66667H1.75Z"
                        fill="#A4A4A4"
                    ></path>
                </svg>
            </a>
        <?php elseif ( $download_expired ) : ?>
            <span class="download-expired-note">Download period expired</span>
        <?php endif; ?>
    </td>
    <td class="table-mobile-hide align-center">
        <a href="<?php echo esc_url(home_url('tickets/new/?order_id=')); ?><?php echo $order->get_order_number(); ?>" class="main-block-create-ticket-btn">
            <svg width="18" height="15" viewBox="0 0 18 15" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path
                    d="M1.56522 0C0.696522 0 0 0.702632 0 1.57895V11.0526C0 11.4714 0.164906 11.873 0.458442 12.1691C0.751977 12.4652 1.1501 12.6316 1.56522 12.6316H10.2412C10.1274 11.9525 10.1618 11.2565 10.342 10.5922C10.5221 9.92791 10.8437 9.31121 11.2843 8.78511C11.7249 8.25902 12.274 7.83617 12.8932 7.54605C13.5124 7.25592 14.1868 7.1055 14.8696 7.10526C15.1317 7.10684 15.3939 7.13053 15.6522 7.17632V1.57895C15.6522 1.16018 15.4873 0.758573 15.1937 0.462463C14.9002 0.166353 14.5021 0 14.087 0H1.56522ZM1.56522 1.57895L7.82609 5.52632L14.087 1.57895V3.15789L7.82609 7.10526L1.56522 3.15789V1.57895ZM14.087 8.68421V11.0526H11.7391V12.6316H14.087V15H15.6522V12.6316H18V11.0526H15.6522V8.68421H14.087Z"
                    fill="#A4A4A4"
                ></path>
            </svg>
        </a>
    </td>
</tr>
