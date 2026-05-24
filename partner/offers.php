<?php 
if(is_user_logged_in() && current_user_has_approve_bid()){
    wp_safe_redirect( home_url('/partners/') );
}else if(!is_user_logged_in()){
    wp_safe_redirect( home_url('/my/') );
}
/*
Template Name: Partner Offer 
*/ 
get_header(); ?>
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
            <h1>Partner interface</h1>
            <div class="partner-menu">
                <button class="button"><img src="/img/menu-icons/line-menu.svg" /><span>Menu Cabinet</span></button>
                <?php get_template_part( 'partner/menu'); ?>
            </div>
            <div class="not-partner-yet__items">
                <a href="/partner/offers" class="item_link item_1_link active">Current bids requests</a>
                <a href="/partner/new_offer" class="item_link item_2_link">Add new bid</a>
            </div>
            <div class="partner-reg__form_step_2 2_step_user_offer user_offers">
                <h2>Current bids requests</h2>
                <div id="datatable_wrapper" class="dataTables_wrapper no-footer">
                    <table class="form small table order-list reg-accounts dataTable no-footer" id="datatable" role="grid" aria-describedby="datatable_info">
                        <thead>
                            <tr role="row">
                                <th class="sorting_disabled" rowspan="1" colspan="1" style="width: 180px;">Category and quantity</th>
                                <th class="sorting_disabled" rowspan="1" colspan="1" style="width: 401.833px;">Account description</th>
                                <th class="sorting_disabled" rowspan="1" colspan="1" style="width: 283.467px;">Status</th>
                                <th class="sorting_disabled" rowspan="1" colspan="1" style="width: 132.7px;">Comment</th>
                            </tr>
                        </thead>
                        <tbody>

                        <?php  

                    		$offer_query = new WP_Query(array(
                    			'post_type' => 'bids', 
                    			'posts_per_page' => -1, 
                                'author' => get_current_user_id()
                    		));

                    		if($offer_query->have_posts()) : 

                    			while($offer_query->have_posts()) : $offer_query->the_post();

                    				$cat_term =  get_term_by('id', get_post_meta(get_the_ID(), 'offer_cat', true), 'product_cat'); 


                    				?>
		                            <tr role="row" class="odd">
		                                <td>
		                                    <div class="user_offer_cat_and_qty">
		                                        <div class="user_offer_cat"><?php echo $cat_term->name; ?></div>
		                                        <div class="user_offer_qty"><?php echo get_post_meta(get_the_ID(), 'offer_count', true); ?> / <?php  

                                                        if(get_post_meta(get_the_ID(), 'offer_days', true) == 1){
                                                            echo 'Per Day';
                                                        }
                                                        if(get_post_meta(get_the_ID(), 'offer_days', true) == 2){
                                                            echo 'Per week';
                                                        }
                                                        if(get_post_meta(get_the_ID(), 'offer_days', true) == 3){
                                                            echo 'Per month';
                                                        }
                                                        if(get_post_meta(get_the_ID(), 'offer_days', true) == 4){
                                                            echo 'Only 1 account';
                                                        }

                                                 ?></div>
		                                    </div>
		                                </td>
		                                <td>
		                                    <?php the_title(); ?><br />
		                                    <strong>Registration country (IP):</strong> <?php echo get_post_meta(get_the_ID(), 'offer_country', true); ?><br />
		                                    <strong>Accounts format:</strong> <?php echo get_post_meta(get_the_ID(), 'offer_format', true); ?>
		                                </td>
		                                <td>
		                                    <div class="status_wrapper">
		                                        <div class="thead desktop-hide">Status:</div>
		                                        <div class="user_offer_checking"><?php echo get_post_meta(get_the_ID(), 'offer_status', true); ?></div>
		                                    </div>
		                                </td>
		                                <td>
		                                    <div class="thead desktop-hide">Comment:</div>
		                                </td>
		                            </tr>
		                        <?php endwhile; wp_reset_postdata(); endif; ?>
                            
                        </tbody>
                    </table>
                    <div class="dataTables_length" id="datatable_length">
                        <label>
                            Show by
                            <select name="datatable_length" aria-controls="datatable" class="">
                                <option value="10">10</option>
                                <option value="25">25</option>
                                <option value="50">50</option>
                                <option value="100">100</option>
                            </select>
                        </label>
                    </div>
                    <div class="dataTables_paginate paging_simple_numbers" id="datatable_paginate">
                        <a class="paginate_button previous disabled" aria-controls="datatable" data-dt-idx="0" tabindex="0" id="datatable_previous">
                            <svg width="5" height="9" viewBox="0 0 5 9" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path
                                    d="M0.00314249 4.4509C0.00314249 4.60482 0.0619117 4.75872 0.179204 4.87606L3.87199 8.56881C4.10689 8.80372 4.48776 8.80372 4.72257 8.56881C4.95738 8.334 4.95738 7.95321 4.72257 7.71829L1.45499 4.4509L4.72245 1.18349C4.95727 0.948583 4.95727 0.567836 4.72245 0.333042C4.48764 0.0980215 4.10678 0.0980215 3.87187 0.333042L0.17909 4.02573C0.0617787 4.14314 0.0031425 4.29704 0.00314249 4.4509Z"
                                    fill="#9D9D9D"
                                ></path>
                            </svg>
                        </a>
                        <span><a class="paginate_button current" aria-controls="datatable" data-dt-idx="1" tabindex="0">1</a></span>
                        <a class="paginate_button next disabled" aria-controls="datatable" data-dt-idx="2" tabindex="0" id="datatable_next">
                            <svg width="5" height="9" viewBox="0 0 5 9" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path
                                    d="M0.00314249 4.4509C0.00314249 4.60482 0.0619117 4.75872 0.179204 4.87606L3.87199 8.56881C4.10689 8.80372 4.48776 8.80372 4.72257 8.56881C4.95738 8.334 4.95738 7.95321 4.72257 7.71829L1.45499 4.4509L4.72245 1.18349C4.95727 0.948583 4.95727 0.567836 4.72245 0.333042C4.48764 0.0980215 4.10678 0.0980215 3.87187 0.333042L0.17909 4.02573C0.0617787 4.14314 0.0031425 4.29704 0.00314249 4.4509Z"
                                    fill="#9D9D9D"
                                ></path>
                            </svg>
                        </a>
                    </div>
                    <div class="dataTables_info" id="datatable_info" role="status" aria-live="polite">Show 2 from 2</div>
                </div>
            </div>
        </div>
    </div>
</section>
<?php get_footer(); ?>