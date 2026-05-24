<?php 
$product = wc_get_product( get_the_ID() );
$post_7 = get_post( get_the_ID());
$decline_reason = get_post_meta(get_the_ID(), 'note', true);
?>
<style>
    /* Make only this row responsive */
.custom-partner-row td {
    padding: 8px;
    white-space: normal;   /* allow text to wrap */
    vertical-align: top;
}

.custom-partner-row td:nth-child(3) {
    width: 130px;  
    white-space: normal; /* allow wrapping inside status cell */
    word-break: break-word; /* break long words if needed */
}

/* Remove margin-top from adjacent links in the mobile action grid */
.mobile-actions-grid a + a {
    margin-top: 0 !important;
}

/* Desktop Action Buttons */
.action-btn {
    display: inline-block;
    padding: 5px 10px;
    margin-bottom: 5px;
    border-radius: 4px;
    background-color: #f0f0f1;
    color: #333;
    text-decoration: none;
    font-size: 12px;
    border: 1px solid #ccc;
    text-align: center;
    white-space: nowrap;
}
.action-btn:hover {
    background-color: #e5e5e5;
    color: #000;
}
.action-btn.delete-btn {
    background-color: #fff0f0;
    color: #d32f2f;
    border-color: #ffcdd2;
}
.action-btn.delete-btn:hover {
    background-color: #ffebee;
}

/* Mobile card style */
@media (max-width: 767px) {
    .custom-partner-row td.col_title {
        display: block;
        width: 100%;
        background: #fff;
        border: 1px solid #e0e0e0;
        border-radius: 8px;
        box-shadow: 0 2px 5px rgba(0,0,0,0.05);
        padding: 15px !important;
        margin-bottom: 15px;
    }
    /* Hide the spacer row since margin-bottom handles the gap now */
    .partner-spacer-row {
        display: none !important;
    }
    .col_title {
     max-width: 100%; 
}
}
/* CSS Table Styles */
.divTable{
	display: table;
	width: 100%;
}
.divTableRow {
	display: table-row;
}
.divTableHeading {
	background-color: #EEE;
	display: table-header-group;
}
.divTableCell, .divTableHead {
	border: 1px solid #999999;
	display: table-cell;
	padding: 3px 10px;
    border: none;
}
.divTableHeading {
	background-color: #EEE;
	display: table-header-group;
	font-weight: bold;
}
.divTableFoot {
	background-color: #EEE;
	display: table-footer-group;
	font-weight: bold;
}
.divTableBody {
	display: table-row-group;
}
</style>
<tr class="tr_decline custom-partner-row">
    <td class="table-tablet-hide"><a target="blank" href="<?php echo esc_url(home_url('/')); ?>partners/view?pro_id=<?php echo get_post_meta(get_the_ID(), 'custom_product_id', true); ?>">#&nbsp;<?php echo get_post_meta(get_the_ID(), 'custom_product_id', true); ?></a></td>
    <td class="table-tablet-hide"><?php echo $product->get_date_created()->date('m.d.Y'); ?></td>
    
    <td class="table-mobile-hide">

        <?php if(get_post_meta(get_the_ID(), 'bid_status', true)  == 'checking_accounts') : ?>
             <div id="status_name<?php echo get_the_ID(); ?>">Checking Accounts</div>
        <?php elseif(get_post_meta(get_the_ID(), 'bid_status', true)  == 'processing') : ?>
             <div id="status_name<?php echo get_the_ID(); ?>">In Processing</div>
       <?php elseif(get_post_meta(get_the_ID(), 'bid_status', true)  == 'declined') : ?>
             <div id="status_name<?php echo get_the_ID(); ?>">Declined</div>
             <?php if ($decline_reason): ?>
            <div class="decline-reason" style="color: #ff0000; font-size: 12px; margin-top: 5px;">
                Reason: <?php echo esc_html($decline_reason); ?>
            </div>
		<?php endif; ?>
        <?php elseif(get_post_meta(get_the_ID(), 'bid_status', true)  == 'onsale') : ?>
             <div id="status_name<?php echo get_the_ID(); ?>">On Sale</div> 
        <?php elseif(get_post_meta(get_the_ID(), 'bid_status', true)  == 'soldout') : ?>
             <div id="status_name<?php echo get_the_ID(); ?>">Sold Out</div> 
        <?php elseif(get_post_meta(get_the_ID(), 'bid_status', true)  == 'awaiting_upload') : ?>
            <div id="status_name<?php echo get_the_ID(); ?>">awaiting upload</div>
            <div class="upload" id="upload1<?php echo get_the_ID(); ?>">
                <a href="javascript:void(0)" class="upload_partner_account" data-id="<?php echo get_the_ID(); ?>"  
                    onclickss="bids.upload_dialog(<?php // echo get_the_ID(); ?>)"  >
                    upload accounts
                </a>
            </div> 
        <?php endif; ?>

    </td>
    <td class="col_title">
        <div class="desktop-hide" style="margin-bottom: 10px; text-align: left;">
            <div style="display: flex; justify-content: space-between; border-bottom: 1px solid #eee; padding-bottom: 5px; margin-bottom: 5px;">
                <span style="font-weight: bold;">ID:</span>
                <a target="blank" href="<?php echo esc_url(home_url('/')); ?>partners/view?pro_id=<?php echo get_post_meta(get_the_ID(), 'custom_product_id', true); ?>">#&nbsp;<?php echo get_post_meta(get_the_ID(), 'custom_product_id', true); ?></a>
            </div>
            <div style="display: flex; justify-content: space-between;">
                <span style="color: #9d9d9d;">Date:</span>
                <span><?php echo $product->get_date_created()->date('m.d.Y'); ?></span>
            </div>
        </div>
        
        <div id="item_control_<?php echo get_the_ID(); ?>">
            <a href="<?php the_permalink(); ?>"><?php echo $product->get_name(); ?></a>
        </div>
        <a href="javascript:void(0)" onclick="bids.description_dialog(<?php echo get_the_ID(); ?>)">partner description</a>
        <div id="description_<?php echo get_the_ID(); ?>" style="display: none;"><?php echo $product->get_name(); ?></div>
        
        <?php
            // Get the 'item_format' meta value from the current post
            $item_format = get_post_meta(get_the_ID(), 'item_format', true);

            // Split the item format value into an array
            $output_values = explode(',', $item_format);

            // Split the output into two parts: first three values and the remaining values
            $first_three = array_slice($output_values, 0, 3);
            $remaining = array_slice($output_values, 3);

            // Combine the first three values into a single string
            $first_three_str = implode(',', $first_three);

            // Combine the remaining values into a single string
            $remaining_str = implode(',', $remaining);

            // Print the first three values on one line and the remaining values on the next line
            echo "<div id='account_format'>{$first_three_str}</div>";
            if (!empty($remaining_str)) {
                echo "<div id='account_format'>{$remaining_str}</div>";
            }
        ?>
        
        <div class="tablet-hide" style="margin-bottom: 10px; text-align: left;">
             <div style="display: flex; justify-content: space-between; margin-bottom: 8px; font-size: 12px;">
                 <span style="color: #9d9d9d;">Status:</span>
                 <span>
                 <?php if(get_post_meta(get_the_ID(), 'bid_status', true)  == 'checking_accounts') : ?>
                     Checking Accounts
                <?php elseif(get_post_meta(get_the_ID(), 'bid_status', true)  == 'processing') : ?>
                     In Processing
               <?php elseif(get_post_meta(get_the_ID(), 'bid_status', true)  == 'declined') : ?>
                     Declined
                <?php elseif(get_post_meta(get_the_ID(), 'bid_status', true)  == 'onsale') : ?>
                     On Sale
                <?php elseif(get_post_meta(get_the_ID(), 'bid_status', true)  == 'soldout') : ?>
                     Sold Out
                <?php elseif(get_post_meta(get_the_ID(), 'bid_status', true)  == 'awaiting_upload') : ?>
                    awaiting upload
                    <div class="upload" id="upload1<?php echo get_the_ID(); ?>" style="display: inline-block; margin-left: 5px;">
                        <a href="javascript:void(0)" class="upload_partner_account" data-id="<?php echo get_the_ID(); ?>"  
                            onclickss="bids.upload_dialog(<?php // echo get_the_ID(); ?>)"  >
                            upload accounts
                        </a>
                    </div> 
                <?php endif; ?>
                </span>
            </div>
            
            <div style="display: flex; justify-content: space-between; margin-bottom: 8px; font-size: 12px;">
                <span style="color: #9d9d9d;">Uploaded:</span> 
                <span>
                <?php if(get_post_meta(get_the_ID(), 'bid_status', true)  == 'processing') : ?>
                    <a href="#" class="processing_tool" data-toggle="tooltip" title="Expect your accounts to be uploaded and displayed in the application soon"><i class="fa fa-question" aria-hidden="true"></i></a>
                <?php else : ?>
                    <?php echo total_uploaded_accounts_by_id(get_the_ID()); ?> / <?php echo total_free_accounts_by_id(get_the_ID()); ?>
                <?php endif; ?>
                </span>
            </div>

            <?php 
                $partner_price = get_post_meta($product->get_id(), 'partner_price', true);
                $admin_percentage = get_theme_mod('admin_percentage');
    			$new_price = $partner_price + (($admin_percentage / 100) * $partner_price);
            ?>
            <div style="display: flex; justify-content: space-between; margin-bottom: 8px; font-size: 12px;">
                <span style="color: #9d9d9d;">Price / Sell:</span> 
                <span><?php echo $partner_price; ?>$ / <?php echo $new_price; ?>$</span>
            </div>
         </div>
        
        <div class="tablet-hide" style="margin-bottom: 15px; text-align: left;">
             <div style="font-weight: bold; margin-bottom: 10px;">Actions</div>
             <div class="mobile-actions-grid" style="display: grid; grid-template-columns: 1fr 1fr; gap: 8px;">
                 <a href="javascript:void(0)" onclick="bids.list(<?php echo get_the_ID(); ?>, 0, 0)" style="background: #f5f5f5; padding: 10px 4px; border-radius: 3px; text-align: center; color: #333; font-size: 12px; display: flex; align-items: center; justify-content: center; min-height: 36px; text-decoration: none; border: 1px solid transparent;">View Uploaded</a>
                 
                 <?php if(total_free_accounts_by_id(get_the_ID()) != 0) : ?>
                    <a href="javascript:void(0)" onclick="bids.list(<?php echo get_the_ID(); ?>, 0, 1)" style="background: #f5f5f5; padding: 10px 4px; border-radius: 3px; text-align: center; color: #333; font-size: 12px; display: flex; align-items: center; justify-content: center; min-height: 36px; text-decoration: none; border: 1px solid transparent;">View Free</a>
                 <?php else: ?>
                    <div></div>
                 <?php endif; ?>

                 <a href="<?php echo esc_url(home_url('/partners/upload/')); ?>?id=<?php echo get_post_meta(get_the_ID(), 'custom_product_id', true); ?>" style="background: #f5f5f5; padding: 10px 4px; border-radius: 3px; text-align: center; color: #333; font-size: 12px; display: flex; align-items: center; justify-content: center; min-height: 36px; text-decoration: none; border: 1px solid transparent;">Create Like This</a>

                 <?php if(total_free_accounts_by_id(get_the_ID()) != 0) : ?>
                    <a href="javascript:void(0)" onclick="bids.remove_unsold(<?php echo get_the_ID(); ?>)" style="background: #fff0f0; padding: 10px 4px; border-radius: 3px; text-align: center; color: #d32f2f; border: 1px solid #ffcdd2; font-size: 12px; display: flex; align-items: center; justify-content: center; min-height: 36px; text-decoration: none;">Delete Unsold</a>
                 <?php else: ?>
                    <div></div>
                 <?php endif; ?>
             </div>
         </div>
         
         <div class="desktop-hide" style="border-top: 1px solid #eee; padding-top: 10px; margin-top: 10px;">
             <div style="font-weight: bold; margin-bottom: 10px;">Payments</div>
             <div style="display: flex; justify-content: space-between; font-size: 12px; margin-bottom: 8px;">
                 <span>Paid: <?php echo get_paid_pcs_by_product_id_interface($product->get_id()); ?> pcs (<?php echo get_paid_payment_by_product_id($product->get_id()); ?>$)</span>
                 <span style="<?php if(get_pending_pcs_by_product_id_interface($product->get_id()) != 0 ){echo 'color:red;';} ?>">Pending: <?php echo get_pending_pcs_by_product_id_interface($product->get_id()); ?> pcs (<?php echo get_pending_payment_by_product_id_interface($product->get_id()); ?>$)</span>
             </div>
             <div style="display: flex; justify-content: space-between; font-size: 12px;">
                 <span>Invalid: <?php echo get_invalid_total_by_pro_id($product->get_id()); ?></span>
                 <span>Bad: <?php echo count_bad_accounts($product->get_id()); ?></span>
             </div>
         </div>

    </td>
    <td class="table-mobile-hide">
        <?php  echo "Price Per: " . get_post_meta($product->get_id(), 'partner_price', true); ?>&nbsp; USD <br>
        <?php 
            $partner_price = get_post_meta($product->get_id(), 'partner_price', true);
            $admin_percentage = get_theme_mod('admin_percentage');
			$new_price = $partner_price + (($admin_percentage / 100) * $partner_price);
            // Retrieve the selling price from post meta and convert it to a float
            

            // Display the new selling price with a descriptive text
            echo "Selling Price Per: " . $new_price . " USD";
        ?>
    </td>
    <td class="table-mobile-hide">
        <?php if(get_post_meta($product->get_id(), 'bid_status', true)  == 'processing') : ?>
            <a href="#" class="processing_tool" data-toggle="tooltip" title="Expect your accounts to be uploaded and displayed in the application soon"><i class="fa fa-question" aria-hidden="true"></i></a>

        <?php else : ?>
        <?php echo total_uploaded_accounts_by_id($product->get_id()); ?> / <?php echo total_free_accounts_by_id($product->get_id()); ?>
        <?php endif; ?>
            
        </td>
    <td class="table-mobile-hide">
        <a href="javascript:void(0)" onclick="bids.list(<?php echo $product->get_id(); ?>, 0, 0)" >view uploaded</a>
        <?php if(total_free_accounts_by_id($product->get_id()) != 0) : ?>
            <a href="javascript:void(0)" onclick="bids.list(<?php echo $product->get_id(); ?>, 0, 1)" >view free accounts</a>
            <a href="javascript:void(0)" onclick="bids.remove_unsold(<?php echo $product->get_id(); ?>)" >delete unsold</a>
        <?php endif; ?>

        <a href="<?php echo esc_url(home_url('/partners/upload/')); ?>?id=<?php echo get_post_meta($product->get_id(), 'custom_product_id', true); ?>" >create like this</a>
    </td>
    <td class="table-tablet-hide">
        <table style="width: 100%; border-collapse: collapse; border: none;">
            <tbody>
                <tr>
                    <td style="text-align: right; font-weight: bold; width: 30%; border-right: 1px solid #999999; padding: 3px 10px; border-bottom: none; border-top: none; border-left: none;">&nbsp;Paid:</td>
                    <td style="text-align: left; padding: 3px 10px; border: none;">&nbsp;<?php echo get_paid_pcs_by_product_id_interface($product->get_id()); ?> pcs (<?php echo get_paid_payment_by_product_id($product->get_id()); ?> USD)</td>
                </tr>
                <tr>
                    <td style="text-align: right; font-weight: bold; width: 30%; border-right: 1px solid #999999; padding: 3px 10px; border-bottom: none; border-top: none; border-left: none; <?php if(get_pending_pcs_by_product_id_interface($product->get_id()) != 0 ){echo 'color:red;';} ?>">&nbsp;Pending payment:</td>
                    <td style="text-align: left; padding: 3px 10px; border: none;">&nbsp;<?php echo get_pending_pcs_by_product_id_interface($product->get_id()); ?> pcs (<?php echo get_pending_payment_by_product_id_interface($product->get_id()); ?> USD)</td>
                </tr>
                <tr>
                    <td style="text-align: right; font-weight: bold; width: 30%; border-right: 1px solid #999999; padding: 3px 10px; border-bottom: none; border-top: none; border-left: none;"><a href="<?php echo esc_url(home_url('/')); ?>partners/view?pro_id=<?php echo get_post_meta($product->get_id(), 'custom_product_id', true); ?>">Invalid:</a></td>
                    <td style="text-align: left; padding: 3px 10px; border: none;">&nbsp;<?php echo get_invalid_total_by_pro_id($product->get_id()); ?></td>
                </tr>
                <tr>
                    <td style="text-align: right; font-weight: bold; width: 30%; border-right: 1px solid #999999; padding: 3px 10px; border-bottom: none; border-top: none; border-left: none;">Bad Account:</td>
                    <td style="text-align: left; padding: 3px 10px; border: none;">&nbsp;<?php echo count_bad_accounts($product->get_id()); ?></td>
                </tr>
            </tbody>
        </table>
    </td>
    
</tr>

