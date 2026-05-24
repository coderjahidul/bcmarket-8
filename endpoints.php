<?php
add_action('rest_api_init', function () {
    register_rest_route('pvamarkets/v1', '/send-subscriber-mail', array(
        'methods' => 'POST',
        'callback' => 'send_mail_to_subscriber',
        'permission_callback' => '__return_true', // Update this with authentication as needed
    ));
});

function send_mail_to_subscriber() {
    global $wpdb;

    $table_name = $wpdb->prefix . 'subscriber_pending_email';
    $query = "SELECT * FROM $table_name WHERE status = 'pending' LIMIT 6";
    $emails = $wpdb->get_results($query);  // Use get_results for multiple rows

    // Check if any emails are pending
    if (empty($emails)) {
        error_log('No pending emails found to send.');
        return new WP_Error('no_pending_emails', 'No pending emails found.', array('status' => 404));
    }

    // Loop through each email in the result set
    foreach ($emails as $email) {
        $to = $email->email;
        $product_url = $email->product_url;
        $id = $email->id;
        send_subscription_emails_in_smtp($to, $product_url);

        // Update subscriber_pending_email table status from pending to sent
        $wpdb->query($wpdb->prepare("UPDATE $table_name SET status = 'sent' WHERE id = %d", $id));
    }
}
