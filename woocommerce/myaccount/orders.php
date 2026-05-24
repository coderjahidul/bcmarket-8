
<?php
/**
 * Orders
 *
 * Shows orders on the account page.
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/myaccount/orders.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see https://docs.woocommerce.com/document/template-structure/
 * @package WooCommerce\Templates
 * @version 7.8.0
 */

 defined( 'ABSPATH' ) || exit;

 do_action( 'woocommerce_before_account_orders', $has_orders ); ?>
 
 <?php if ( $has_orders ) : ?>
    <h4 class="profile_title mb-0">Shopping list</h4>
    <div class="table-responsive">
        <div id="datatable_wrapper" class="dataTables_wrapper no-footer">
        <table class="table dataTable no-footer  " >
            <thead>
				<tr>
					<th class="table-tablet-hide">ID</th>
					<th class="table-tablet-hide">Categories</th>
					<th class="table-mobile-hide">Goods</th>
					<th class="table-mobile-hide">Download</th>
					<th class="table-mobile-hide">Ticket</th>
					<th class="table-mobile-hide shopping-list-last-col">About</th>
					<th class="table-tablet-hide">Date</th>
				</tr>
            </thead>

            <tbody>
				<?php
				$current_url = esc_url_raw($_SERVER['REQUEST_URI']);
				$page_number = intval(preg_replace('/[^0-9]+/', '', $current_url), 10);
				$paged = $page_number == 0 ? 1 : $page_number;
				$current_user_id = get_current_user_id();

				$orders_query = new WP_Query(array(
					'post_type' => 'shop_order',
					'posts_per_page' => 10,
					'post_status'    => 'any',
					'order'			=> 'DESC',
					'paged'          => $paged,
					'meta_query' => array(
						array(
							'key' => '_customer_user', 
							'value' => get_current_user_id()
						)
					)
				));

				while($orders_query->have_posts()){
					
					$orders_query->the_post();

					$customer_order = get_the_ID();

					$order      = wc_get_order( $customer_order ); // phpcs:ignore WordPress.WP.GlobalVariablesOverride.Prohibited
					$order_items = $order->get_items();
					
					if (empty($order_items)) {
						global $wpdb;
						$order_items = $wpdb->get_results($wpdb->prepare("SELECT * FROM {$wpdb->prefix}wc_order_product_lookup WHERE order_id = %d", $customer_order));
					}

					$item_count = $order->get_item_count() - $order->get_item_count_refunded();
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
					<tr role="row" class="odd">
						<td class="table-tablet-hide"><?php echo $order->get_order_number(); ?></td>
						<td class="table-tablet-hide">
							<?php if($item_status != 'private') : ?>
							<?php 
							foreach ( $order_items as $item_id => $item ) :
								if (is_object($item) && method_exists($item, 'get_product_id')) {
									$product_id = $item->get_product_id();
								} else {
									$product_id = $item->product_id;
								}
								$product = wc_get_product( $product_id );
								$categories_name = wc_get_product_category_list($product_id);
								if (strpos($categories_name, 'Facebook') !== false) {
									echo "<a>Other</a>";
								} else {
									echo $categories_name;
								}
							endforeach; endif; ?>
						</td>
						<td>
							<div class="sub-col-data2 desktop-hide"><span>ID:</span> <?php echo $order->get_order_number(); ?></div>

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
								<?php else : ?>
									<?php echo $item_name; ?>
								<?php endif; ?>
							<?php endforeach; ?>

							<div class="sub-col-data2 desktop-hide">
								<?php if($item_status != 'private') : ?>
								<span>Categories:</span> 
								<?php foreach ( $order_items as $item_id => $item ) :
									if (is_object($item) && method_exists($item, 'get_product_id')) {
										$product_id = $item->get_product_id();
									} else {
										$product_id = $item->product_id;
									}
									$product = wc_get_product( $product_id );
									echo wc_get_product_category_list($product_id);
									?>
								<?php endforeach; endif; ?>
							</div>
							<?php foreach ( $order_items as $item_id => $item ) : 
								if (is_object($item) && method_exists($item, 'get_quantity')) {
									$qty = $item->get_quantity();
								} else {
									$qty = $item->product_qty;
								}
							?>
								<div class="sub-col-data tablet-hide align-right"><span>Quantity:</span> <?php echo $qty; ?> pcs</div>
							<?php endforeach; ?>
							<div class="sub-col-data tablet-hide align-right"><span>Cost:</span> <?php echo wc_price($order->get_total()); ?></div>

							<?php if($item_status != 'private') : ?>
							<?php foreach ( $order_items as $item_id => $item ) : 
								if (is_object($item) && method_exists($item, 'get_product_id')) {
									$product_id = $item->get_product_id();
								} else {
									$product_id = $item->product_id;
								}
							?>
								<div class="sub-col-data tablet-hide align-right"><span>Seller:</span> Partner #<?php echo get_post_field ('post_author', $product_id); ?></div>
							<?php endforeach; endif; ?>

							<div class="sub-col-data tablet-hide align-right product-paid"><span>Status:</span> <?php if($order->get_status() == 'processing'){
									echo 'Paid';
								}else{
									echo $order->get_status();
								} ?></div>
								<div class="sub-col-data tablet-hide main-block-status-line">
            <div>
                 <?php if($item_status != 'private' && ! $download_expired) : ?>
                <a href="<?php echo esc_url(home_url('/download-accounts/')); ?>?order_id=<?php echo $order->get_id(); ?>&order_key=<?php echo $order->get_order_key(); ?>" class="main-block-download-btn download-link" target="_blank">
                    <svg width="15" height="15" viewBox="0 0 15 15" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path
                            d="M14.5833 1.83333L13.4167 0.416667C13.25 0.166667 12.9167 0 12.5 0H2.5C2.08333 0 1.75 0.166667 1.5 0.416667L0.416667 1.83333C0.166667 2.16667 0 2.5 0 2.91667V13.3333C0 14.25 0.75 15 1.66667 15H13.3333C14.25 15 15 14.25 15 13.3333V2.91667C15 2.5 14.8333 2.16667 14.5833 1.83333ZM7.5 12.0833L2.91667 7.5H5.83333V5.83333H9.16667V7.5H12.0833L7.5 12.0833ZM1.75 1.66667L2.41667 0.833333H12.4167L13.1667 1.66667H1.75Z"
                            fill="#A4A4A4"
                        ></path>
                    </svg>
                    Download
                </a>
            <?php elseif ( $download_expired ) : ?>
							<span class="download-expired-note">Download period expired</span>
						<?php endif; ?>
            </div>
            <div>
                <a href="<?php echo esc_url(home_url('tickets/new/?order_id=')); ?><?php echo $order->get_id(); ?>" class="main-block-create-ticket-btn">
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
							
							</div>
							<div class="sub-col-data2 desktop-hide"><span>Date:</span> <?php echo $order->get_date_created()->date('Y-m-d'); ?> <?php echo $order->get_date_created()->date('H:i:s'); ?></div><br class="table-mobile-hide desktop-hide">
						</td>
						<td class="table-mobile-hide product-download">
							<?php if($item_status != 'private' && ! $download_expired) : ?>
								<a class="download-link" href="<?php echo esc_url(home_url('/download-accounts/')); ?>?order_id=<?php echo $order->get_id(); ?>&order_key=<?php echo $order->get_order_key(); ?>" target="_blank">
								<svg width="15" height="15" viewBox="0 0 15 15" fill="none" xmlns="http://www.w3.org/2000/svg">
								<path d="M14.5833 1.83333L13.4167 0.416667C13.25 0.166667 12.9167 0 12.5 0H2.5C2.08333 0 1.75 0.166667 1.5 0.416667L0.416667 1.83333C0.166667 2.16667 0 2.5 0 2.91667V13.3333C0 14.25 0.75 15 1.66667 15H13.3333C14.25 15 15 14.25 15 13.3333V2.91667C15 2.5 14.8333 2.16667 14.5833 1.83333ZM7.5 12.0833L2.91667 7.5H5.83333V5.83333H9.16667V7.5H12.0833L7.5 12.0833ZM1.75 1.66667L2.41667 0.833333H12.4167L13.1667 1.66667H1.75Z" fill="#A4A4A4"></path>
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
						<td class="table-mobile-hide">
							<?php foreach ( $order_items as $item_id => $item ) : 
								if (is_object($item) && method_exists($item, 'get_quantity')) {
									$qty = $item->get_quantity();
								} else {
									$qty = $item->product_qty;
								}
								if (is_object($item) && method_exists($item, 'get_product_id')) {
									$product_id = $item->get_product_id();
								} else {
									$product_id = $item->product_id;
								}
							?>
								<div class="sub-col-data align-right"><span>Quantity:</span> <?php echo $qty; ?> pcs</div>
							<?php endforeach; ?>
							<div class="sub-col-data align-right"><span>Cost:</span> <?php echo wc_price($order->get_total()); ?></div>
							<?php if($item_status != 'private') : ?>
							<?php foreach ( $order_items as $item_id => $item ) : 
								if (is_object($item) && method_exists($item, 'get_product_id')) {
									$product_id = $item->get_product_id();
								} else {
									$product_id = $item->product_id;
								}
							?>
								<div class="sub-col-data align-right"><span>Seller:</span> Partner #<?php echo get_post_field ('post_author', $product_id); ?></div>
							<?php endforeach; endif; ?>
							<div class="sub-col-data align-right product-paid"><span>Status:</span> 
								<?php if($order->get_status() == 'processing'){
									echo 'Paid';
								}else{
									echo $order->get_status();
								} ?>
							</div>
						</td>
						<td class="table-tablet-hide"><?php echo $order->get_date_created()->date('Y-m-d'); ?> <?php echo $order->get_date_created()->date('H:i:s'); ?></td>
					</tr>
					<?php
				}
				?>
			</tbody>
        </table>
        </div>
    </div>

    <?php do_action( 'woocommerce_before_account_orders_pagination' ); ?>

    <?php if ( 1 < $orders_query->max_num_pages ) : ?>
        <div class="woocommerce-pagination woocommerce-pagination--without-numbers woocommerce-Pagination" style="display: flex; justify-content: center;">
            <?php
            $big = 999999999; // Need an unlikely integer
            $pagination = paginate_links(array(
                'base' => str_replace($big, '%#%', esc_url(get_pagenum_link($big))),
                'format' => '?paged=%#%',
                'current' => max(1, $paged),
                'total' => $orders_query->max_num_pages,
            ));

			$pagination = str_replace('span', 'span', $pagination);

			echo $pagination;
            ?>
        </div>
    <?php endif; ?>

<?php else : ?>
    <div class="woocommerce-message woocommerce-message--info woocommerce-Message woocommerce-Message--info woocommerce-info">
		<a class="woocommerce-Button button" href="<?php echo esc_url( apply_filters( 'woocommerce_return_to_shop_redirect', wc_get_page_permalink( 'shop' ) ) ); ?>"><?php esc_html_e( 'Browse products', 'woocommerce' ); ?></a>
		<?php esc_html_e( 'No order has been made yet.', 'woocommerce' ); ?>
	</div>
<?php endif; ?>

<?php do_action( 'woocommerce_after_account_orders', $has_orders ); ?>








