<?php $item_id = get_the_ID(); ?>

<div class="soc-body">
    <div class="soc-img">
        <a href="<?php the_permalink(); ?>">
            <?php the_post_thumbnail('thumbnail'); ?>
        </a>
    </div>
    <div class="soc-text">
        <p>
            <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
        </p>
        <a href="<?php the_permalink(); ?>" class="learn-more">More...</a>
    </div>
    <div class="soc-price">
        <p><?php echo get_total_pcs_by_item($item_id); ?> pcs.
            <br>
            <span>Price per pc<br></span>
            <div class="">from $<?php 
        //  $soldoutPrice =  get_field('item_price');
           $price = get_post_meta($item_id, 'item_price', true);
        
        if($price == " "){
        echo get_per_pcs_by_item($item_id);
        }else{
            echo $price;
        }
        ?></div>
        </p>
    </div>
    <div class="soc-qty">
    <?php 
        echo get_total_pcs_by_item($item_id);
    ?> pcs.</div>
    <div class="soc-cost-label">Price per pc</div>
    <div class="soc-cost">from $
      <?php 
        //  $soldoutPrice =  get_field('item_price');
           $price = get_post_meta($item_id, 'item_price', true);
        
        if($price == " "){
        echo get_per_pcs_by_item($item_id);
        }else{
            echo $price;
        }
        ?>
    </div>

   
    <?php if(get_total_pcs_by_item($item_id) != 0) : ?>
        <div class="soc-cell">
            <button type="button" class="basket-button" data-id="<?php echo $item_id; ?>">
                <img src="<?php echo get_template_directory_uri(); ?>/img/ic-basket.png" alt=""><span>Buy</span>
            </button>
        </div>
    <?php else : ?>
         <div class="subscribe-cell" data-help="Subscribe to newsletter">
            <!-- Button trigger modal -->
            <button type="button" class="subscribe_button" data-toggle="modal" data-target="#exampleModal<?php echo $item_id; ?>">
                <i class="fa-regular fa-envelope"></i>
            </button>

            <!-- Subscribe Modal -->
            <div class="modal fade" id="exampleModal<?php echo $item_id; ?>" tabindex="-1" role="dialog" aria-labelledby="subscribeModalLabel" aria-hidden="true">
                <div class="modal-dialog" role="document" style="width: 800px;">
                    <div class="modal-content" style="margin-top: 200px;">
                        <div class="modal-header" style="display: flex;">
                            <h5 class="modal-title" id="subscribeModalLabel" style="font-size: 14px; text-transform: uppercase; line-height: 2.428571;">Subscribe to Newsletter</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close" style="margin-left: 500px !important; font-size: 35px; font-weight: 400; color: #000; margin: 0; border: 0;">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-bodys" style="padding: 25px;">
                            <p style="font-size: 14px; font-weight: 400; color: #000;">You want to subscribe to an item update:</p>
                            <p style="font-size: 14px; font-weight: 600; color: #000; margin-bottom: 10px !important;">GMail Accounts | Accounts could be used in some services. The accounts are verified through SMS. Phone number not included in Profile Security method. Male or female. Registered from different countries IPs.</>
                            <form id="subscribe-form-<?php echo $item_id; ?>" action="<?php echo esc_url(admin_url('admin-ajax.php')); ?>" method="POST">
                                <input type="email" name="subscriber_email" placeholder="Enter Your Email" required>
                                <input type="hidden" name="action" value="subscribe_form">
                                <p style="margin-top: 10px; color: red; font-size: 14px; font-weight: 400;">After clicking "subscribe" you will need to confirm your subscription in the mail!</p>
                                <button type="submit" style="width: 120px; height: 30px; line-height: 1.2; text-align: center; color: #fff; font-size: 16px; margin-top: 10px; margin-left: 0; border-radius: 4px; background-color: #245F9B; border: none;">Subscribe</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    <?php  endif; ?>

</div>
