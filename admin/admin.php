<?php 
if(is_user_logged_in()){
    $current_user = wp_get_current_user();
    $roles = array('administrator', 'employee');
    $found = 0; 
    foreach($roles as $role){
        if(in_array( $role, (array) $current_user->roles) ){
            $found = 1;
           
        }
    }

    if($found == 0){
         wp_redirect( home_url('/my/') );
            exit(); 
    }
    
}else{
    wp_redirect( home_url('/my/') );
    exit(); 
}
/*
Template Name: Admin
*/
get_header(); ?>



<section class="soc-category" id="content">
    
    <?php get_template_part('template-parts/admin', 'breadcrumb'); ?>

    <div class="container">
        <div class="flex">
            <h1><?php the_title(); ?></h1>
           
            <?php get_template_part( 'admin/admin', 'menu'); ?>
            <div class="body partner_cabinet">

                <form action="<?php echo esc_url(home_url('/')); ?>admin-interface" class="search_admin_col">
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
                                        <option value="<?php echo $term->term_id; ?>"><?php echo $term->name; ?></option>
                                    <?php 
                                }
                            }
                        ?>
                    </select>
                    <input type="text" placeholder="Search by ID" name="product_id" value="<?php if(isset($_GET['product_id'])){echo $_GET['product_id'];} ?>">
                    <input type="text" placeholder="Search by Item Id" name="item_id" value="<?php if(isset($_GET['item_id'])){echo $_GET['item_id'];} ?>">
                    <input type="text" placeholder="Search by Partner" name="partner_id" value="<?php if(isset($_GET['partner_id'])){echo $_GET['partner_id'];} ?>">
                    <button type="submit">Search</button>
                </form>
             
                <?php 

                    $paged = ( get_query_var('paged') ) ? get_query_var('paged') : 1;

                    $args['post_type'] = 'product'; 
                    $args['posts_per_page'] = 10; 
                    $args['post_status'] = array('pending', 'publish'); 
                    $args['paged'] = $paged; 

                    $item_id = isset($_GET['item_id']) ? $_GET['item_id'] : null;
                    $status = isset($_GET['status']) ? $_GET['status'] : null;
                    $product_id = isset($_GET['product_id']) ? $_GET['product_id'] : null;
                    $cat = isset($_GET['cat']) ? $_GET['cat'] : null;
                    $partner_id = isset($_GET['partner_id']) ? $_GET['partner_id'] : null;

                    if(!empty($cat)){
                        $args['tax_query'] = array(
                            array(
                                'taxonomy' => 'product_cat',
                                'terms'    => array($cat),
                            )
                        );
                    }  

                    if(!empty($item_id)){
                        $meta_item[] = array(
                            'key' => 'item_id',
                            'value'    => $item_id,
                        );
                    }

                    if(!empty($product_id)){
                        $meta_item[] = array(
                            'key' => 'custom_product_id',
                            'value'    => $product_id,
                        );
                    } 

                    if(!empty($partner_id)){
                        $args['author'] = $partner_id;
                    }

                    if(!empty($status)){
                        $meta_item[] = array(
                            'key' => 'bid_status',
                            'value'    => $status,
                        );
                    }

                    if( !empty($item_id) || !empty($status) || !empty($product_id) ){
                        $args['meta_query'][] = $meta_item;
                        $args['meta_query']['relation'] = 'AND';
                    }

                    $items_query = new WP_Query($args);


                    if($items_query->have_posts()) : ?>

                <table class="bids list zebra ac">
                    <tbody>
                        <tr>
                            <th>Id</th>
                            <th>Partner Id</th>
                            <th>Date of order creation</th>
                            <th>Status</th>
                            <th>Product information</th>
                            <th>Format</th>
                            <th>Price for 1 piece</th>
                            <th>Uploaded / Remained</th>
                            <th>Actions</th>
                            <th>Payments</th>
                            <th>Change Info</th>
                        </tr>

                        <?php 
                        while($items_query->have_posts()) : $items_query->the_post();
                            

                            get_template_part('template-parts/admin', 'item');

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


<div class="partner_price_popup">
    <div class="partner_price_container">
        <div class="partner_price_top">
                <h3>Change Price</h3>
                <div class="price_close">
                    <i style="color: #000;" class="fa-solid fa-close"></i>
                </div>
            </div>
        <form class="pp_price_form">
            
            <div class="partner_price_text">
                <input type="text" name="price" placeholder="Enter new price here">
            </div>
            <div class="partner_price_action">
                <button type="submit">Submit</button>
                <input type="hidden" name="action" value="change_pp_price">
                <input type="hidden" name="product_id" value="">
                <input type="hidden" name="item_id" value="<?php echo $item_id; ?>">
            </div>
        </form>
    </div>
</div>


<?php get_footer() ?>