<?php 
if(is_user_logged_in() && !current_user_has_approve_bid()){
    wp_safe_redirect( home_url('/partner/offers') );
}else if(!is_user_logged_in()){
    wp_safe_redirect( home_url('/my/') );
}
/*
Template Name: Partner
*/
get_header(); ?>
<style>
#loaderr {
  display: none;
  border: 4px solid rgba(255, 255, 255, 0.3);
  border-top: 4px solid #3498db;
  border-radius: 50%;
  width: 70px;
  height: 70px;
  animation: spin 2s linear infinite;
  position: absolute;
  left: calc(50% - 10px);
  top: calc(50% - 10px);
}

@keyframes spin {
  0% { transform: rotate(0deg); }
  100% { transform: rotate(360deg); }
}
</style>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
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
                        <span class="current" itemprop="name">Partner interface</span>
                        <meta itemprop="position" content="1" />
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="container">
        <div class="flex">
            <h1><?php the_title(); ?></h1>
           
            <?php get_template_part( 'partner/menu'); ?>

            <div class="body partner_cabinet">

                <h2 style="margin-top: 20px;">MY BIDS</h2>
                <style>
                    .search_admin_col {
                        display: flex;
                        flex-wrap: wrap;
                        gap: 10px;
                        align-items: center;
                    }
                    .search_admin_col select, 
                    .search_admin_col input, 
                    .search_admin_col button {
                        padding: 10px;
                        border: 1px solid #ddd;
                        border-radius: 4px;
                        height: 42px; /* Ensure sufficient height for text */
                        box-sizing: border-box; /* Include padding in height */
                    }
                    @media (max-width: 767px) {
                        .search_admin_col {
                            flex-direction: column;
                            align-items: stretch;
                        }
                        .search_admin_col span {
                            display: block; /* Show label on mobile */
                            margin-bottom: 5px;
                            font-weight: bold;
                        }
                        /* Add margin to separate fields visually */
                        .search_admin_col > * {
                            margin-bottom: 10px;
                        }
                    }
                </style>
                <form action="<?php echo esc_url(home_url('/')); ?>partners" class="search_admin_col">
                    <span>Status: </span>
                    <select name="status">
                        <option value="">All</option>
                        <option value="checking_accounts" <?php if(isset($_GET['status']) && $_GET['status'] == 'checking_accounts'){echo 'selected';} ?> >Checking Accounts</option>
                        <option value="processing" <?php if(isset($_GET['status']) && $_GET['status'] == 'processing'){echo 'selected';} ?>>Processing</option>
                        <option value="declined" <?php if(isset($_GET['status']) && $_GET['status'] == 'declined'){echo 'selected';} ?>>Declined</option>
                        <option value="onsale" <?php if(isset($_GET['status']) && $_GET['status'] == 'onsale'){echo 'selected';} ?>>On Sale</option>
                        <option value="soldout" <?php if(isset($_GET['status']) && $_GET['status'] == 'soldout'){echo 'selected';} ?>>Sold Out</option>
                        <option value="awaiting_upload" <?php if(isset($_GET['status']) && $_GET['status'] == 'awaiting_upload'){echo 'selected';} ?>>Awaiting upload</option>
                    </select>
                    <select name="cat">
                        <option value="">All Cat</option>
                        <?php  
                            $terms = get_terms( array(
                                'taxonomy' => 'product_cat',
                                'hide_empty' => false,
                                'parent' => 0
                            ) );

                            if($terms){
                                foreach($terms as $term){
                                    ?>
                                        <option value="<?php echo $term->term_id; ?>" <?php if(isset($_GET['cat']) && $_GET['cat'] == $term->term_id){echo 'selected';} ?>><?php echo $term->name; ?></option>
                                    <?php 
                                }
                            }
                        ?>
                    </select>
                    <input type="text" placeholder="Search by ID" value="<?php if(isset($_GET['product_id'])){echo $_GET['product_id'];} ?>" name="product_id">
                    <input type="text" placeholder="Search by name" value="<?php if(isset($_GET['search_name'])){echo $_GET['search_name'];} ?>" name="search_name">
                    <button type="submit">Search</button>
                </form>
             
                <?php 

                    $paged = ( get_query_var('paged') ) ? get_query_var('paged') : 1;

                    $args['post_type'] = 'product'; 
                    $args['author'] = get_current_user_id(); 
                    $args['posts_per_page'] = 10; 
                    $args['post_status'] = array('pending', 'publish'); 
                    $args['paged'] = $paged; 

					$item_id = isset($_GET['item_id']) ? $_GET['item_id'] : null;
					$status = isset($_GET['status']) ? $_GET['status'] : null;
					$product_id = isset($_GET['product_id']) ? $_GET['product_id'] : null;
					$cat = isset($_GET['cat']) ? $_GET['cat'] : null;
                    $search_name = isset($_GET['search_name']) ? $_GET['search_name'] : null;

                    if(!empty($search_name)){
                        $args['s'] = $search_name;
                    }

                    if(!empty($cat)){
                        $args['tax_query'] = array(
                            array(
                                'taxonomy' => 'product_cat',
                                'terms'    => array($cat),
                            )
                        );
                    }  



                    if(!empty($status)){
                        $meta_item[] = array(
                            'key' => 'bid_status',
                            'value'    => $status,
                        );
                    }

                    if(!empty($product_id)){
                        $meta_item[] = array(
                            'key' => 'custom_product_id',
                            'value'    => $product_id,
                        );
                    }

                    if( !empty($status) || !empty($product_id) ){
                        $args['meta_query'][] = $meta_item;
                        $args['meta_query']['relation'] = 'AND';
                    }

                    $items_query = new WP_Query($args);

                    if($items_query->have_posts()) : ?>

                <table class="bids list zebra ac">
                    <tbody>
                        <tr>
                            <th class="table-tablet-hide">Id</th>
                            <th class="table-tablet-hide">Date of order creation</th>
                            <th class="table-mobile-hide">Status</th>
                            <th>Product information</th>
                            <th class="table-mobile-hide">Price for 1 piece</th>
                            <th class="table-mobile-hide">Uploaded / Remained</th>
                            <th class="table-mobile-hide">Actions</th>
                            <th class="table-tablet-hide">Payments</th>
                        </tr>

                        <?php 
                        while($items_query->have_posts()) : $items_query->the_post();
                            

                            get_template_part('template-parts/partner', 'item');

                        endwhile; wp_reset_postdata(); ?>
                       
                    </tbody>
                </table>

                <?php else : echo 'No Account found!'; endif; ?>

                <div class="pager_wrap">
                    <?php 
                        $big = 999999999; // need an unlikely integer

                            $paginations_array =  paginate_links( array(
                                'base' => str_replace( $big, '%#%', esc_url( get_pagenum_link( $big ) ) ),
                                'format' => '?paged=%#%',
                                'current' => max( 1, get_query_var('paged') ),
                                'total' => $items_query->max_num_pages, 
                                'type' => 'array'
                            ) );


                        ?>

                    <?php if($paginations_array) : ?>
                        <div class="pager">
                            <table>
                                <tbody>
                                    <tr>
                                        <?php foreach($paginations_array as $pag_item) : ?>
                                            <td><?php echo $pag_item; ?></td>
                                        <?php endforeach; ?>
                                    </tr>
                                </tbody>
                            </table>
                            <div class="cb"></div>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</section>


<div class="parnter_upload_popup">
    <div class="loaderr" id="loaderr"></div>
    <div class="partner_upload_container" id="partner_upload_container_id">
        <div class="partner_upload_top">
            <h3>Upload your accounts</h3>
            <div class="partner_upload_close">
                <i style="color: #000;" class="fa-solid fa-close"></i>
            </div>
        </div>
        <form class="partner_upload_form">
            <div class="partner_upload_warning">
                <div class="upload_warning">Read carefully<a href="https://pvamarkets.com/requirements-for-accounts/" target="_blank">account placing requirements!</a><br>If your accounts were added with errors, your bid will be rejected</div>
            </div>
            <div class="partner_upload_textarea">
                <textarea name="accounts" required></textarea>
            </div>
            <div class="partner_upload_action">
                <button type="button" class="partner_close">Cancel</button>
                <button type="submit" id="uploadButtons">Upload</button>
                <input type="hidden" name="action" value="upload_accounts">
                <input type="hidden" name="bid_id" value="">
            </div>
        </form>
    </div>
</div>



<?php get_footer() ?>