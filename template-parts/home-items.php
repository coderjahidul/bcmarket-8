 <div class="soc-bl">

    <?php

        $ordered_sections = bcmarket_get_homepage_section_order();

        if ( $ordered_sections ) :

            foreach ( $ordered_sections as $section ) :
                $term  = $section['parent'];
                $child = $section['child'];

                if ( 'child' === $section['type'] && $child ) :

                        $pr_query = new WP_Query(array(
                            'post_type' => 'item', 
                            'posts_per_page' => -1, 
                            
                            
                            'tax_query' => array(
                                array(
                                    'taxonomy' => 'item_cat',
                                    'terms'    => $child->term_id,
                                ),
                            ),
                            'meta_key' => 'item_price',
                            'orderby' => 'meta_value_num',
                            'order' => 'ASC',
                        ));

                        $new_query = new WP_Query(array(
                            'post_type' => 'item', 
                            'posts_per_page' => -1, 
                            
                            'tax_query' => array(
                                array(
                                    'taxonomy' => 'item_cat',
                                    'terms'    => $child->term_id,
                                ),
                            ),
                            'meta_key' => 'item_price',
                            'orderby' => 'meta_value_num',
                            'order' => 'ASC',
                        ));
                        // echo "<pre>";
                        // print_r($new_query);
                        // echo "</pre>";
                        if($pr_query->have_posts() || $new_query->have_posts()) : 
                        ?>
                            <div class="soc-title" data-help="<?php echo $child->description; ?>">
                                <h2 class="soc-name" data-id="10"><a href="<?php echo get_term_link($child); ?>"><?php echo $term->name; ?> <?php echo $child->name; ?></a></h2>
                                <p class="soc-qty">In stock</p>
                                <p class="soc-cost-label"></p>
                                <p class="soc-cost">Price</p>
                                <p class="soc-control"></p>
                            </div>

                            <div class="socs ">
                                <div class="first_div">
                                <?php 
                                $count = 0;
                                while ($pr_query->have_posts() && $count < 5) : $pr_query->the_post();
                                    $item_id = get_the_ID();
                                    $total_pcs = get_total_pcs_by_item($item_id);
                                    $post_thumbnail = get_the_post_thumbnail($item_id, 'thumbnail');
                                    $post_title = get_the_title($item_id);
                                    $post_link = get_the_permalink($item_id);
                                   
                                    if ($total_pcs != 0) {?>  
                                        <div class="soc-body">
                                        <div class="soc-img">
                                            <a href="<?php echo $post_link; ?>">
                                                <?php echo $post_thumbnail; ?>
                                            </a>
                                        </div>
                                        <div class="soc-text">
                                            <p>
                                                <a href="<?php echo $post_link; ?>"><?php echo $post_title; ?></a>
                                            </p>
                                            <a href="<?php echo $post_link; ?>" class="learn-more">More...</a>
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

                                        </div><?php
                                        $count++;
                                    }
                                endwhile; wp_reset_postdata();
                                    $counts = $count;
                                    while($pr_query->have_posts() && $counts < 5) : $pr_query->the_post();
                                        $item_id = get_the_ID();
                                        $total_pcs = get_total_pcs_by_item($item_id);
                                        $post_thumbnail = get_the_post_thumbnail($item_id, 'thumbnail');
                                        $post_title = get_the_title($item_id);
                                        $post_link = get_the_permalink($item_id);
                                        
                                        if ($total_pcs == 0) {?>  
                                            <div class="soc-body">
                                            <div class="soc-img">
                                                <a href="<?php echo $post_link; ?>">
                                                    <?php echo $post_thumbnail; ?>
                                                </a>
                                            </div>
                                            <div class="soc-text">
                                                <p>
                                                    <a href="<?php echo $post_link; ?>"><?php echo $post_title; ?></a>
                                                </p>
                                                <a href="<?php echo $post_link; ?>" class="learn-more">More...</a>
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

                                            </div><?php
                                            $counts++;
                                        }
                                    endwhile; wp_reset_postdata();
                                ?>
                                </div>
                            
                                    <div class=" new_div_toggle" style="display:none;">
                                        <?php 
                                            // while($new_query->have_posts()) : $new_query->the_post();
                                            //     get_template_part('template-parts/content', 'item');
                                            // endwhile; wp_reset_postdata();

                                            while ($new_query->have_posts()) : $new_query->the_post();
                                                $item_id = get_the_ID();
                                                $total_pcs = get_total_pcs_by_item($item_id);
                                                $post_thumbnail = get_the_post_thumbnail($item_id, 'thumbnail');
                                                $post_title = get_the_title($item_id);
                                                $post_link = get_the_permalink($item_id);
                                            
                                                if ($total_pcs != 0) {?>  
                                                    <div class="soc-body">
                                                    <div class="soc-img">
                                                        <a href="<?php echo $post_link; ?>">
                                                            <?php echo $post_thumbnail; ?>
                                                        </a>
                                                    </div>
                                                    <div class="soc-text">
                                                        <p>
                                                            <a href="<?php echo $post_link; ?>"><?php echo $post_title; ?></a>
                                                        </p>
                                                        <a href="<?php echo $post_link; ?>" class="learn-more">More...</a>
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
            
                                                </div><?php
                                                }
                                            endwhile; wp_reset_postdata();
                                            while ($new_query->have_posts()) : $new_query->the_post();
                                                $item_id = get_the_ID();
                                                $total_pcs = get_total_pcs_by_item($item_id);
                                                $post_thumbnail = get_the_post_thumbnail($item_id, 'thumbnail');
                                                $post_title = get_the_title($item_id);
                                                $post_link = get_the_permalink($item_id);
                                            
                                                if ($total_pcs == 0) {?>  
                                                    <div class="soc-body">
                                                    <div class="soc-img">
                                                        <a href="<?php echo $post_link; ?>">
                                                            <?php echo $post_thumbnail; ?>
                                                        </a>
                                                    </div>
                                                    <div class="soc-text">
                                                        <p>
                                                            <a href="<?php echo $post_link; ?>"><?php echo $post_title; ?></a>
                                                        </p>
                                                        <a href="<?php echo $post_link; ?>" class="learn-more">More...</a>
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
            
                                                </div><?php
                                                }
                                            endwhile; wp_reset_postdata();
                                            
                                        ?>
                                        
                                    </div> 
                            <div class="collapse"></div>
                                <button type="button" data-cat="<?php echo $child->term_id; ?>" class="expand_subcat_button">View all</button>
                            </div> 
                         

                        <?php

                        endif;

                else :

                    $parent_pro_query = new WP_Query(array(
                        'post_type' => 'product', 
                        'posts_per_page' => 5, 
                        'tax_query' => array(
                            array(
                                'taxonomy' => 'item_cat',
                                'terms'    => $term->term_id,
                            ),
                        ),
                    ));
                   
                    if($parent_pro_query->have_posts()) : 
                        ?>
                            <div class="soc-title" data-help="<?php echo $term->description; ?>">
                                <h2 class="soc-name" data-id="10"><a href="<?php echo get_term_link($term); ?>"><?php echo $term->name; ?> <?php echo $term->name; ?></a></h2>
                                <p class="soc-qty">In stock</p>
                                <p class="soc-cost-label"></p>
                                <p class="soc-cost">Price</p>
                                <p class="soc-control"></p>
                            </div>

                            <div class="socs">
                                <?php while($parent_pro_query->have_posts()) : $parent_pro_query->the_post();
                                    get_template_part('content', 'item');
                                    ?>
                                   
                                <?php endwhile; wp_reset_postdata(); ?>
                                
                                <div class="collapse"></div>
                                <button type="button" data-cat="<?php echo $term->term_id; ?>" class="expand_subcat_button">View all</button>
                            </div>

                        <?php

                        endif;

                endif;
            endforeach;
        endif;
    ?>
</div>
