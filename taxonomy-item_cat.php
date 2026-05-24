<?php
defined('ABSPATH') || exit;

get_header();

$term = get_queried_object();


$parent = $term->parent;

?>
<section class="soc-category" id="content">
    <div class="wrap-breadcrumbs">
        <div class="container">
            <div class="flex">
                <div class="block" itemscope="" itemtype="http://schema.org/BreadcrumbList" id="breadcrumbs">
                    <div itemprop="itemListElement" itemscope="" itemtype="http://schema.org/ListItem">
                        <a href="<?php echo esc_url(home_url('/')); ?>" itemprop="item">
                            <span itemprop="name">Home</span>
                            <meta itemprop="position" content="0">
                        </a>
                        <span class="divider">/</span>
                    </div>
                    <?php if ($parent): ?>
                        <div itemprop="itemListElement" itemscope="" itemtype="http://schema.org/ListItem">
                            <a href="<?php echo get_term_link($parent); ?>" itemprop="item">
                                <span itemprop="name">
                                    <?php echo $term->name; ?>
                                </span>
                                <meta itemprop="position" content="1">
                            </a>
                            <span class="divider">/</span>
                        </div>
                    <?php endif; ?>
                    <div itemprop="itemListElement" itemscope="" itemtype="http://schema.org/ListItem">
                        <span class="current" itemprop="name">
                            <?php woocommerce_page_title(); ?>
                        </span>
                        <meta itemprop="position" content="2">
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="container">
        <div class="flex">
            <?php if ($parent): ?>
                <h1>
                    <?php echo $term->name; ?> accounts for sale -
                    <?php woocommerce_page_title(); ?>
                </h1>
            <?php else: ?>
                <h1>
                    <?php woocommerce_page_title(); ?>
                </h1>
            <?php endif; ?>
            <div class="soc-bl">

                <?php
                if ($parent == 0) {
                    $termchildren = get_term_children($term->term_id, 'item_cat');
                    $terms = get_terms(
                        array(
                            'taxonomy' => 'item_cat',
                            'orderby' => 'meta_value_num',
                            'order' => 'ASC',
                            'meta_query' => array(
                                'relation' => 'OR',
                                array(
                                    'key' => 'item_order',
                                    'compare' => 'NOT EXISTS'
                                ),
                                array(
                                    'key' => 'item_order',
                                    'value' => 0,
                                    'compare' => '>='
                                )
                            ),
                            'hide_empty' => true,
                            'parent' => $term->term_id
                        )
                    );

                    if ($terms):

                        foreach ($terms as $child):

                            ?>
                            <div class="soc-title" data-help="<?php echo $child->description; ?>">
                                <h2 class="soc-name" data-id="10"><a href="<?php echo get_term_link($child); ?>">
                                        <?php echo $term->name; ?>
                                        <?php echo $child->name; ?>
                                    </a></h2>
                                <p class="soc-qty">In stock</p>
                                <p class="soc-cost-label"></p>
                                <p class="soc-cost">Price</p>
                                <p class="soc-control"></p>
                            </div>

                            <div class="socs ">
                                <div class="first_div">
                                    <?php

                                    $new_query = new WP_Query(
                                        array(
                                            'post_type' => 'item',
                                            'posts_per_page' => -1,
                                            'orderby' => 'meta_value_num',
                                            'meta_key' => 'item_price',
                                            'order' => 'ASC',
                                            'tax_query' => array(
                                                array(
                                                    'taxonomy' => 'item_cat',
                                                    'field' => 'term_id',
                                                    'terms' => $child->term_id,
                                                ),
                                            ),
                                        )
                                    );
                                    // while ($new_query->have_posts()):$new_query->the_post();
                                    //     get_template_part('template-parts/content', 'item');
                                    // endwhile;
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
                            </div>





                            <?php

                        endforeach;





                    endif;
                } else { ?>

                    <div class="soc-title" data-help="<?php echo $term->name; ?>">
                        <h2 class="soc-name" data-id="10"><a href="<?php echo get_term_link($term); ?>">
                                <?php echo $term->name; ?>
                                <?php echo $term->name; ?>
                            </a></h2>
                        <p class="soc-qty">In stock</p>
                        <p class="soc-cost-label"></p>
                        <p class="soc-cost">Price</p>
                        <p class="soc-control"></p>
                    </div>

                    <div class="socs ">
                        <div class="first_div">
                            <?php

                            $new_query = new WP_Query(
                                array(
                                    'post_type' => 'item',
                                    'posts_per_page' => -1,
                                    'orderby' => 'meta_value_num',
                                    'meta_key' => 'item_price',
                                    'order' => 'ASC',
                                    'tax_query' => array(
                                        array(
                                            'taxonomy' => 'item_cat',
                                            'field' => 'term_id',
                                            'terms' => $term->term_id,
                                        ),
                                    ),
                                )
                            );
                            // while ($new_query->have_posts()):$new_query->the_post();
                            //     get_template_part('template-parts/content', 'item');
                            // endwhile;
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
                    </div>



                <?php }


                ?>


                <div class="recat">
                    <?php echo get_term_meta(get_queried_object_id(), 'long_description', true); ?>
                    <?php echo $term->description; ?>
                </div>
            </div>
        </div>
</section>


<?php get_footer('shop');
