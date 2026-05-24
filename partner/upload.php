<?php 
if (is_user_logged_in() && !current_user_has_approve_bid()) {
    wp_safe_redirect(home_url('/partner/offers'));
} else if (!is_user_logged_in()) {
    wp_safe_redirect(home_url('/my/'));
}

/*
Template Name: Upload
*/
get_header(); 

$like_id = '';
$item_format_array = [];

if (isset($_GET['id']) && !empty($_GET['id'])) : 
    $args = array(
        'post_type' => 'product', // change this to your post type
        'meta_query' => array(
            array(
                'key' => 'custom_product_id', 
                'value' => $_GET['id']
            )
        ), 
        'post_status' => array('publish', 'pending', 'draft')
    );

    $query = new WP_Query($args);

    if ($query->have_posts()) {
        $query->the_post();
        $like_id = get_the_ID();

        // Retrieve the 'item_format' meta field for this post
        $item_format = get_post_meta($like_id, 'item_format', true);

        // Explode the comma-separated string into an array
        $item_format_array = explode(',', $item_format);
    }

    wp_reset_postdata();
endif; 
?>

<section class="soc-category" id="content" id="uploding">
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
                        <span class="current" itemprop="name">Partner interface</span>
                        <meta itemprop="position" content="1" />
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="container">
        <div class="flex">
            <h1>Partner interface</h1>
            <?php get_template_part('partner/menu'); ?>
            <form action="#" method="post" id="new_bid_form" class="partner-reg__form_step_2">
                <h2>Accounts upload</h2>
                <?php wp_nonce_field('bcmarket_bids_nonce'); ?>
                <input type="hidden" name="action" value="add_bids" />
                <table class="form small table order-list reg-accounts">
                    <thead>
                        <tr>
                            <th>Accounts category <span>*</span>
                                <div class="help" data-help="Example, Twitter"></div>
                            </th>
                            <th>Description <span>*</span>
                                <div class="help" data-help="with avatar, 100 friends, registered on Gmail email"></div>
                            </th>
                            <th>Registration country (IP) <span>*</span>
                                <div class="help" data-help="from which country ip accounts are registered?"></div>
                            </th>
                            <th>Accounts formats <span>*</span>
                                <div class="help" data-help="example: login:password:email:emails password"></div>
                            </th>
                            <th colspan="2">Price for 1 pc. <span>*</span>
                                <div class="help" data-help="Indicate what price you want to get for 1 account after it is sold"></div>
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>
                                <div class="thead desktop-hide">
                                    Accounts category <span>*</span>
                                    <div class="help" data-help="Example, Twitter"></div>
                                </div>
                                <select name="categories[]" class="form-control">
                                    <option value="" disabled="" selected="" hidden="">Select a category</option>
                                    <?php 
                                    // Get the product categories associated with the current product
                                    $terms = get_terms(array(
                                        'taxonomy' => 'product_cat',
                                        'hide_empty' => false,
                                        'parent' => 0
                                    ));

                                    $selected_category = ''; // Default value if no category is selected
                                    if (!empty($like_id)) {
                                        $selected_category = get_the_terms($like_id, 'product_cat');
                                        if ($selected_category && !is_wp_error($selected_category)) {
                                            $selected_category = $selected_category[0]->term_id; // Assuming only one category is selected
                                        }
                                    }

                                    if ($terms) :
                                        foreach ($terms as $term) : ?>
                                            <option value="<?php echo $term->term_id; ?>" 
                                                <?php echo ($term->term_id == $selected_category) ? 'selected' : ''; ?>>
                                                <?php echo $term->name; ?>
                                            </option>
                                        <?php endforeach; 
                                    endif; ?>
                                </select>
                            </td>
                            <td>
                                <div class="thead desktop-hide">
                                    Description <span>*</span>
                                    <div class="help" data-help="with avatar, 100 friends, registered on Gmail email"></div>
                                </div>
                                <textarea name="description[]" class="form-control-dsc"><?php  
                                    if (!empty($like_id)) {
                                        echo get_the_title($like_id);
                                    }
                                ?></textarea>
                            </td>
                            <td>
                                <div class="thead desktop-hide">
                                    Registration country (IP) <span>*</span>
                                    <div class="help" data-help="from which country ip accounts are registered?"></div>
                                </div>
                                <textarea name="country[]" class="form-control"><?php  
                                    if (!empty($like_id)) {
                                        echo get_post_meta($like_id, 'item_country', true);
                                    }
                                ?></textarea>
                            </td>
                            <td class="account_format_row">
                                <div class="thead desktop-hide">
                                    Accounts format <span>*</span>
                                    <div class="help" data-help="example: login:password:email:emails password"></div>
                                </div>
                                <select class="format_fi" name="format[0][]" multiple id="accountFormat">
                                    <?php
                                    // Dynamic formats managed from WP Admin → Account Formats
                                    $account_formats = bcmarket_get_account_formats();
                                    foreach ( $account_formats as $fmt ) :
                                        $is_selected = in_array( $fmt['value'], $item_format_array ) ? 'selected' : '';
                                    ?>
                                        <option value="<?php echo esc_attr( $fmt['value'] ); ?>" <?php echo $is_selected; ?>>
                                            <?php echo esc_html( $fmt['label'] ); ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </td>
                            <td>
                                <div class="thead desktop-hide">
                                    Price for 1 pc. <span>*</span>
                                    <div class="help" data-help="Indicate what price you want to get for 1 account after it is sold"></div>
                                </div>
                                <div class="accounts_count partner_price">
                                    <input name="partner_cost[]" value="<?php 
                                        if (!empty($like_id)) {
                                            $product = wc_get_product($like_id); 
                                            echo get_post_meta($like_id, 'partner_price', true);
                                        }
                                    ?>" class="form-control" />
                                    <div class="form-control-currency">USD</div>
                                </div>
                            </td>
                        </tr>
                    </tbody>
                </table>
                <input type="hidden" name="like_this[]" value="<?php if(!empty($like_id)){ echo $like_id; } ?>">
                <button type="submit" class="partner_uploads_button">Add bids</button>
            </form>
            <div class="bid_error_message"></div>
            <div class="bid_success_message"></div>
        </div>
    </div>
</section>

<?php get_footer(); ?>
