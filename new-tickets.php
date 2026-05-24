<?php 
/*
 * Template Name: New Tickets
 */
 get_header(); 
 
 
 $tickets = new WP_Query(array(
    'post_type' => 'tickets',
    'posts_per_page'=> -1 
 ));

$all_orders = [];

while($tickets->have_posts()) {
    $tickets->the_post();

    $all_orders[] = get_post_meta(get_the_id(), 'order_id_client', true);
}

$unique_orders = array_filter( array_unique($all_orders) );

 ?>
<style>
    .clicked {
        background-color: red; 
        color: #fff; 
        cursor: not-allowed;
    }

    /* Header Layout */
    .ticket-header-flex {
        display: flex; 
        align-items: center; 
        justify-content: space-between;
        margin-bottom: 20px;
        flex-wrap: wrap;
    }
    
/* ===============================
   Responsive Message Field & Form
================================ */

@media (max-width: 768px) {
    /* Header Responsive */
    .ticket-header-flex {
        display: block;
        text-align: left;
    }
    
    .ticket-header-flex h1 {
        margin-bottom: 10px;
        font-size: 24px;
    }

    /* Main container adjustments */
    .container {
        padding-left: 15px;
        padding-right: 15px;
        box-sizing: border-box;
    }
    
    .wrap#content {
        width: 100%;
        padding: 0;
    }

    /* Convert Table to Block Layout */
    #tickets_form, 
    #tickets_form tbody, 
    #tickets_form tr, 
    #tickets_form td {
        display: block;
        width: 100%;
    }

    /* Row Styling */
    #tickets_form tr {
        margin-bottom: 15px;
        position: relative;
    }

    /* Cell Styling */
    #tickets_form td {
        padding: 5px 0;
        text-align: left;
    }

    /* Label Cell */
    #tickets_form td:first-child {
        font-weight: 600;
        color: #333;
    }

    /* Input/Control Cell */
    #tickets_form td:last-child {
        padding-bottom: 5px;
    }

    /* Form Controls */
    #tickets_form input[type="text"],
    #tickets_form input[type="email"],
    #tickets_form input[type="number"],
    #tickets_form select,
    #tickets_form textarea {
        width: 100%;
        max-width: 100%;
        box-sizing: border-box;
        height: 40px;
        padding: 8px 10px;
        border: 1px solid #ddd;
        border-radius: 4px;
        font-size: 16px; /* Prevent zoom on iOS */
    }

    #tickets_form textarea {
        height: auto;
        min-height: 120px;
    }

    #tickets_form input[type="checkbox"] {
        width: auto;
        margin-right: 8px;
        height: auto;
    }

    /* Submit Button */
    .new-ticket-submit {
        width: 100%;
        padding: 12px;
        margin-top: 10px;
        font-size: 16px;
    }

    /* Help text adjustments */
    .help {
        display: inline-block;
        margin-left: 5px;
        vertical-align: middle;
    }
}
</style>

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
                        <span class="current" itemprop="name">New ticket</span>
                        <meta itemprop="position" content="1" />
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="container">
        <div class="flex">
            <div class="wrap" id="content">
                <div class="ticket-header-flex">
                <h1>New ticket</h1>
                
                
                <h4 style="font-size:15px;color:red;">
                    <?php
                    if(isset($_REQUEST['order_id'])){
                        $order_id = $_REQUEST['order_id'];
                        if(in_array($order_id, $unique_orders)){
                            
                        echo "You have an active ticket with this <span style='color:black;'>#$order_id</span> ID .You Can,t Open a new ticket with this id";
                        echo " ";
                        echo '<a href="' . home_url('/your-account/tickets') . '">Visit Ticket Page</a>';
                        }
                    }
                    ?>
                </h4>
                
                </div>
               <?php 
                $order_id = isset($_REQUEST['order_id']) ? $_REQUEST['order_id'] : '';
                if($order_id) :?>
                    <?php  if(!in_array($order_id, $unique_orders)){?> 
                        <form action="#" data-action="ticket" id="new_ticket_form">
                            <input type="hidden" name="action" value="new_tickets" />
                            <input type="hidden" name="user_id" value="<?php echo get_current_user_id(); ?>" />
                        
                            <div id="ticket_result" class="notify"></div>
                            <table class="form" id="tickets_form">
                                <tbody>
                                    <tr>
                                        <td>Subject</td>
                                        <td>
                                            <select name="subject_id" onchange="tickets.subject_select(this.options[this.selectedIndex].value);" required="required">
                                                <option value="" selected="selected">Select</option>
                                                <option value="1">I have problems with the product</option>
                                                <option value="2">I did not receive the product automatically</option>
                                                <option value="3">I have a simple question/Need consult</option>
                                            </select>
                                        </td>
                                    </tr>
                                    <!-- 
                                    <tr id="faq" style="display: none;">
                                        <td colspan="2">
                                            <div class="info" style="display: block;">
                                                <p class="h3">Answers to many questions are already in our FAQ</p>
                                                <p>
                                                    <a href="/en/faq" style="color: #ff5a6c;">Open FAQ</a>
                                                    &nbsp;&nbsp;&nbsp;
                                                    <a href="javascript:void(0)" onclick="$('#tickets_form tr:not(.ticket-field)').show(); $('#order_id_container').hide()">I did not find an answer, contact the administration</a>
                                                </p>
                                            </div>
                                        </td>
                                    </tr> -->
                                    <?php if(!is_user_logged_in()) : ?>
                                        <tr style="display: none;">
                                            <td>Email <span class="red">*</span></td>
                                            <td>
                                                <input type="email" name="email" style="margin-bottom:5px" required=""><br>
                                                <input type="checkbox" name="email_notify" id="email_notify" value="1" checked="checked"><label for="email_notify">Notify new messages</label>
                                            </td>
                                        </tr>
                                    <?php endif; ?>

                                    <tr id="order_id_container" style="display: none;">
                                        <td>
                                            # order <span class="red">*</span>
                                            <div class="help" data-help="The order number is a 6-digit number that we sent to your mail with a link to upload the order."></div>
                                        </td>
                                        <?php
                                            $order_id = isset($_GET['order_id']) ? $_GET['order_id'] : null;
                                            
                                            if($order_id !== NULL) :?>
                                                <td>
                                                    <input type="number" min="0" max="4294967295" name="order_id_client" value="<?php echo $order_id;?>" id="order_id" readonly/>
                                                </td>
                                            <?php else :?>
                                                <td>
                                                <input type="number" min="0" max="4294967295" name="order_id_client" value="" id="order_id" />
                                            </td>
                                            <?php endif;
                                        ?>
                                    </tr>
                                    <tr class="ticket-field field1" style="display: none;">
                                        <td>
                                            What proxy was used <span class="red">*</span> &nbsp;
                                            <div class="help" data-help="Write us whether you used a proxy when logging into accounts or not. If used, then: ipv4 or ipv6."></div>
                                        </td>
                                        <td>
                                            <textarea name="field1"></textarea>
                                        </td>
                                    </tr>
                                    <tr class="ticket-field field1" style="display: none;">
                                        <td>
                                            How did you check your purchased accounts <span class="red">*</span> &nbsp;
                                            <div class="help" data-help="How did you check your purchased accounts? Did you use a browser, a special program, or other methods?"></div>
                                        </td>
                                        <td>
                                            <textarea name="field2"></textarea>
                                        </td>
                                    </tr>
                                    <tr style="display: none;">
                                        <td>Message <span class="red">*</span></td>
                                        <td><textarea name="message"></textarea></td>
                                    </tr>
                                    <tr style="display: none;">
                                        <td></td>
                                        <td>
                                            <div class="ticket-trouble-info">
                                                If you have any problems with the ticket system or you haven’t received an answer, please email us at <a href="mailto:supportpvamarkets@gmail.com">supportpvamarkets@gmail.com</a> <a href="mailto:contact@pvamarkets.com">contact@pvamarkets.com</a><br />
                                                Technical support is provided in English
                                            </div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <input class="all-orders" type="hidden" value='<?php echo json_encode($unique_orders, true); ?>'>
                                    </tr>
                                    <tr style="display: none;">
                                        <td colspan="2"><button id="new-ticket-submit" class="new-ticket-submit"  type="submit">Send</button></td>
                                        
                                    </tr>
                                </tbody>
                            </table>
                        </form>
                    <?php }?>
                <?php else : ?>
                    <form action="#" data-action="ticket" id="new_ticket_form">
                    <input type="hidden" name="action" value="new_tickets" />
                    <input type="hidden" name="user_id" value="<?php echo get_current_user_id(); ?>" />
                   
                    <div id="ticket_result" class="notify"></div>
                    <table class="form" id="tickets_form">
                        <tbody>
                            <tr>
                                <td>Subject</td>
                                <td>
                                    <select name="subject_id" onchange="tickets.subject_select(this.options[this.selectedIndex].value);" required="required">
                                        <option value="" selected="selected">Select</option>
                                        <option value="3">I have a simple question/Need consult</option>
                                    </select>
                                </td>
                            </tr>
							<!-- 
                            <tr id="faq" style="display: none;">
                                <td colspan="2">
                                    <div class="info" style="display: block;">
                                        <p class="h3">Answers to many questions are already in our FAQ</p>
                                        <p>
                                            <a href="/en/faq" style="color: #ff5a6c;">Open FAQ</a>
                                            &nbsp;&nbsp;&nbsp;
                                            <a href="javascript:void(0)" onclick="$('#tickets_form tr:not(.ticket-field)').show(); $('#order_id_container').hide()">I did not find an answer, contact the administration</a>
                                        </p>
                                    </div>
                                </td>
                            </tr> -->
                            <?php if(!is_user_logged_in()) : ?>
                                <tr style="display: none;">
                                    <td>Email <span class="red">*</span></td>
                                    <td>
                                        <input type="email" name="email" style="margin-bottom:5px" required=""><br>
                                        <input type="checkbox" name="email_notify" id="email_notify" value="1" checked="checked"><label for="email_notify">Notify new messages</label>
                                    </td>
                                </tr>
                            <?php endif; ?>

                            <tr id="order_id_container" style="display: none;">
                                <td>
                                    # order <span class="red">*</span>
                                    <div class="help" data-help="The order number is a 6-digit number that we sent to your mail with a link to upload the order."></div>
                                </td>
                                <td>
                                    
                                    <input type="number" min="0" max="4294967295" name="order_id_client" value="" id="order_id" />
                                </td>
                            </tr>
                            <tr class="ticket-field field1" style="display: none;">
                                <td>
                                    What proxy was used <span class="red">*</span> &nbsp;
                                    <div class="help" data-help="Write us whether you used a proxy when logging into accounts or not. If used, then: ipv4 or ipv6."></div>
                                </td>
                                <td>
                                    <textarea name="field1"></textarea>
                                </td>
                            </tr>
                            <tr class="ticket-field field1" style="display: none;">
                                <td>
                                    How did you check your purchased accounts <span class="red">*</span> &nbsp;
                                    <div class="help" data-help="How did you check your purchased accounts? Did you use a browser, a special program, or other methods?"></div>
                                </td>
                                <td>
                                    <textarea name="field2"></textarea>
                                </td>
                            </tr>
                            <tr >
                                <td>Message <span class="red">*</span></td>
                                <td><textarea name="message"></textarea></td>
                            </tr>
                            <tr style="display: none;">
                                <td></td>
                                <td>
                                    <div class="ticket-trouble-info">
                                         If you have any problems with the ticket system or you haven’t received an answer, please email us at <a href="mailto:supportpvamarkets@gmail.com">supportpvamarkets@gmail.com</a> <a href="mailto:contact@pvamarkets.com">contact@pvamarkets.com</a><br />
                                        Technical support is provided in English
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <input class="all-orders" type="hidden" value='<?php echo json_encode($unique_orders, true); ?>'>
                            </tr>
                            <tr style="display: none;">
                                <td colspan="2"><button id="new-ticket-submit" class="new-ticket-submit"  type="submit">Send</button></td>
                                
                            </tr>
                        </tbody>
                    </table>
                </form>
                <?php endif; ?>
                <div class="tickets_err"></div>
            </div>
        </div>
    </div>
</section>

<!-- <script>
    $(document).ready(function () {
        $("#new-ticket-submit").on('click', function () {
            // Disable the button
            $(this).prop("disabled", true);

            // You can add additional logic or actions here if needed

            // Optionally, you can submit a form or perform other actions
            // $("#yourFormId").submit();
        });
    });
</script> -->


<?php get_footer(); ?>