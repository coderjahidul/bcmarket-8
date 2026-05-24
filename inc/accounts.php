<?php 
function bcmarket_create_accounts_table() {
 
    global $wpdb;
 
    $table_name = $wpdb->prefix . "accounts";
 
    $charset_collate = $wpdb->get_charset_collate();
 
    $sql = "CREATE TABLE IF NOT EXISTS $table_name (
      id bigint(20) NOT NULL AUTO_INCREMENT,
      product_id bigint(20) UNSIGNED NOT NULL,
      user_id bigint(20) UNSIGNED NOT NULL,
      email_id varchar(255),
      password varchar(255),
      alternative_email varchar(255),
      created_at datetime NOT NULL,
      expires_at datetime NOT NULL,
      PRIMARY KEY id (id)
    ) $charset_collate;";
 
    require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
    dbDelta($sql);
}    
 
add_action('init', 'bcmarket_create_accounts_table');


function total_uploaded_accounts_by_id($item_id){

   global $wpdb;
 
    $table_name = $wpdb->prefix . "accounts";

    $total = $wpdb->get_var( "SELECT COUNT(*) FROM $table_name WHERE product_id = $item_id"); 

    return $total;

}

function total_free_accounts_by_id($item_id){

   global $wpdb;
 
    $table_name = $wpdb->prefix . "accounts";

    $total = $wpdb->get_var( "SELECT COUNT(*) FROM $table_name WHERE product_id = $item_id and item_status = 'free'"); 

    return $total;

}

function total_repeat_accounts_by_id($item_id){

   global $wpdb;
 
    $table_name = $wpdb->prefix . "accounts";

    $total = $wpdb->get_var( "SELECT COUNT(*) FROM $table_name WHERE product_id = $item_id and item_status = 'repeat'"); 

    return $total;

}


function fdsafdsafsafd(){

   for($num = 1; $num < 20; $num++){


      echo 'jobo' . rand() . '@gmail.com';
      echo ':';
      echo 'fdsafdsaf' . rand();
      echo ':';
      echo 'somo' . rand() . '@gmail.com';
      echo '<br>';

   }

}
//add_action('init', 'fdsafdsafsafd');