<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

// Load Composer's autoloader
require_once __DIR__ . '/../vendor/autoload.php';
function send_subscription_emails_in_smtp($to, $product_url) {
    $subject = 'Get new Accounts from Pvamarkets';
    $site_name = get_bloginfo('name');
    $domain_name = parse_url(get_site_url(), PHP_URL_HOST);
    $unsubscribe_link = home_url() . '/unsubscribe?email=' . urlencode($to);

    // Start buffering to create the email body
    ob_start();
    ?>
    <p style='text-align:center'>
        <?php the_custom_logo(); ?>
    </p>
    <p>Hi,</p>
    <p>Just to let you know — Some New Accounts are published in Pvamarkets.com.</p>

    <h2 style="color: #7f54b3; display: block; font-family: Helvetica,Roboto,Arial,sans-serif; font-size: 18px; font-weight: bold; line-height: 130%; margin: 0 0 18px; text-align: left;">
        <a href="<?php echo $product_url; ?>" target="_blank">Get New Accounts Here</a>
    </h2>

    <div style="margin-bottom: 40px;">
        <p>Thanks for using Pvamarkets!</p>
        <p>If you no longer wish to receive these emails, you can <a href='<?php echo $unsubscribe_link; ?>'>unsubscribe</a>.</p>
        <p style="text-align: center;">Please read these articles to avoid problems when working with accounts</p>
        <p style="text-align: center;">
            <a target="_blank" href="<?php echo home_url(); ?>/accounts-guidelines/" style="color: #7f54b3; text-decoration: underline;">Recommendations for working with any accounts</a>
        </p>
        <p style="text-align: center;">
            <a target="_blank" href="<?php echo home_url(); ?>/faq" style="color: #7f54b3; text-decoration: underline;">FAQ (frequently asked questions)</a>
        </p>
        <p style="text-align: center;">
            <a target="_blank" href="<?php echo home_url(); ?>/" style="color: #7f54b3; text-decoration: underline;">Back to store</a>&nbsp;|&nbsp;
            <a target="_blank" href="<?php echo home_url(); ?>/tickets/new" style="color: #7f54b3; text-decoration: underline;">Ask a question</a>&nbsp;|&nbsp;
            <a target="_blank" href="<?php echo home_url(); ?>/tickets/new" style="color: #7f54b3; text-decoration: underline;">Problems with the order</a>&nbsp;|&nbsp;
            <a target="_blank" href="https://t.me/pvamarkets" style="color: #7f54b3; text-decoration: underline;">5% discount on Telegram</a>
        </p>
        <p style="text-align: center; color: #999999; font-size: 12px;">The message was created automatically and it does not require a reply</p>
        <p style="text-align: center; color: #999999; font-size: 12px;">Copyright © Pvamarkets 2023.</p>
    </div>
    <?php
    $body = ob_get_clean();

    // Create a new PHPMailer instance
    $mail = new PHPMailer(true);

    try {
        // Server settings
        $mail->isSMTP();
        $mail->Host = get_option('mailtrap_host');
        $mail->SMTPAuth = true;
        $mail->Username = get_option('mailtrap_username'); // Replace with your Mailtrap username
        $mail->Password = get_option('mailtrap_password'); // Replace with your Mailtrap password
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port = get_option('mailtrap_port');

        // Enable debug output for troubleshooting
        $mail->SMTPDebug = SMTP::DEBUG_SERVER; // Set to SMTP::DEBUG_OFF in production
        $mail->Debugoutput = function ($str, $level) {
            error_log("SMTP Debug Level $level: $str");
        };

        // Sender and recipient
        $mail->setFrom(get_option('mailtrap_email'), $site_name);
        $mail->addAddress($to); // Recipient email

        // Email content
        $mail->isHTML(true);
        $mail->Subject = $subject;
        $mail->Body = $body;

        // Send email
        $mail->send();
        error_log('Email sent successfully to ' . $to);
    } catch (Exception $e) {
        error_log('Email could not be sent. Error: ' . $mail->ErrorInfo);
        error_log('Full error details: ' . $e->getMessage());
    }
}

