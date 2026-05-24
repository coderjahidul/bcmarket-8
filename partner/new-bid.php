<?php
if(is_user_logged_in() && current_user_has_approve_bid()){
    wp_safe_redirect( home_url('/partner/') );
}if(!is_user_logged_in()){
    wp_safe_redirect( home_url('/my/') );
}
/*
Template Name: Partner New Offer
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
                <a href="/partner/offers" class="item_link item_1_link">Current bids requests</a>
                <a href="/partner/new_offer" class="item_link item_2_link active">Add new bid</a>
            </div>
            <form action="/" data-action="registration" method="post" class="2_step_user_offer partner-reg__form_step_2">
            	<?php wp_nonce_field('bcmarket_offer_nonce' ); ?>
                <input type="hidden" name="action" value="add_offer" />
                
                <h2>Account Delivery Application</h2>
                <table class="form small table order-list reg-accounts">
                    <thead>
                        <tr>
                            <th>
                                Accounts category <span>*</span>
                                <div class="help" data-help="Example, Twitter"></div>
                            </th>
                            <th>
                                Description <span>*</span>
                                <div class="help" data-help="with avatar, 100 friends, registered on Gmail email"></div>
                            </th>
                            <th>
                                Registration country (IP) <span>*</span>
                                <div class="help" data-help="from which country ip accounts are registered?"></div>
                            </th>
                            <th>
                                Accounts format <span>*</span>
                                <div class="help" data-help="example: login:password:email:emails password"></div>
                            </th>
                            <th colspan="2">Scope of supply, pcs. <span>*</span></th>
                        </tr>
                    </thead>
                    <tbody>
                    	 <?php  

                    		$offer_query = new WP_Query(array(
                    			'post_type' => 'bids', 
                    			'posts_per_page' => 10, 
                    			'meta_query' =>  array(
                    				array(
                    					'key' => 'offer_status', 
                    					'value' => 'under_considertion'
                    				)
                    			)
                    		));

                    		if($offer_query->have_posts()) : 

                    			while($offer_query->have_posts()) : $offer_query->the_post();

                    				$cat_term =  get_term_by('id', get_post_meta(get_the_ID(), 'offer_cat', true), 'product_cat'); 


                    				?>
			                        <tr>
			                            <td>
			                                <div class="thead desktop-hide">
			                                    Accounts category <span>*</span>
			                                    <div class="help" data-help="Example, Twitter"></div>
			                                </div>
			                                <select class="form-control" disabled="">
			                                    <option value="" disabled="" selected="" hidden="">Select a category</option>
			                                    <?php 
			                                        $terms = get_terms( array(
			                                            'taxonomy' => 'product_cat',
			                                            'hide_empty' => false,
			                                            'parent' => 0
			                                        ) );

		                                        if($terms) : foreach($terms as $term) : ?>
		                                            <option <?php if($cat_term->term_id == $term->term_id){echo 'selected';} ?>  value="<?php echo $term->term_id; ?>"><?php echo $term->name; ?></option>
		                                        <?php endforeach; endif; ?>
			                                </select>
			                            </td>
			                            <td>
			                                <div class="thead desktop-hide">
			                                    Description <span>*</span>
			                                    <div class="help" data-help="with avatar, 100 friends, registered on Gmail email"></div>
			                                </div>
			                                <textarea class="form-control" disabled=""><?php the_title(); ?></textarea>
			                            </td>
			                            <td>
			                                <div class="thead desktop-hide">Registration country (IP) <span>*</span></div>
			                                <textarea class="form-control" disabled=""><?php echo get_post_meta(get_the_ID(), 'offer_country', true); ?></textarea>
			                            </td>
			                            <td>
			                                <div class="thead desktop-hide">
			                                    Accounts format <span>*</span>
			                                    <div class="help" data-help="example: login:password:email:emails password"></div>
			                                </div>
			                                <textarea class="form-control" disabled=""><?php echo get_post_meta(get_the_ID(), 'offer_format', true); ?></textarea>
			                            </td>
			                            <td>
			                                <div class="thead desktop-hide">Scope of supply, pcs. <span>*</span></div>
			                                <input value="<?php echo get_post_meta(get_the_ID(), 'offer_count', true); ?>" style="display: inherit; padding: 0; width: 80px;" class="form-control" disabled="" /> Per day
			                            </td>
			                        </tr>
			                  <?php endwhile; wp_reset_postdata(); else : ?>


			                  	<tr>
                            <td>
                                <div class="thead desktop-hide">
                                    Accounts category <span>*</span>
                                    <div class="help" data-help="Example, Twitter"></div>
                                </div>
                                <select name="categories[]" class="form-control">
                                    <option value="" disabled="" selected="" hidden="">Select a category</option>
                                    <?php 
                                        $terms = get_terms( array(
                                            'taxonomy' => 'product_cat',
                                            'hide_empty' => false,
                                            'parent' => 0
                                        ) );

                                        if($terms) : foreach($terms as $term) : ?>
                                            <option value="<?php echo $term->term_id; ?>"><?php echo $term->name; ?></option>
                                        <?php endforeach; endif; ?>
                                </select>
                            </td>
                            <td>
                                <div class="thead desktop-hide">
                                    Description <span>*</span>
                                    <div class="help" data-help="with avatar, 100 friends, registered on Gmail email"></div>
                                </div>
                                <textarea name="description[]" class="form-control"></textarea>
                            </td>
                            <td>
                                <div class="thead desktop-hide">Registration country (IP) <span>*</span></div>
                                <textarea name="country[]" class="form-control"></textarea>
                            </td>
                            <td>
                                <div class="thead desktop-hide">
                                    Accounts format <span>*</span>
                                    <div class="help" data-help="example: login:password:email:emails password"></div>
                                </div>
                                <textarea name="format[]" class="form-control"></textarea>
                            </td>
                            <td>
                                <div class="thead desktop-hide">Scope of supply, pcs. <span>*</span></div>
                                <div class="accounts_count">
                                    <input name="supply_count[]" value="" class="form-control" />
                                    <select name="days_select[]" class="form-control">
                                        <option value="1">Per day</option>
                                        <option value="2">Per week</option>
                                        <option value="3">Per month</option>
                                        <option value="4">Only 1 account</option>
                                    </select>
                                </div>
                            </td>
                        </tr>

			                  <?php  endif; ?>
			                        
                    </tbody>
                    <tfoot>
                        <tr>
                            <td colspan="6" align="center">
                                <input type="button" class="btn btn-lg btn-block" id="addrow" value="+ Add another line" />
                            </td>
                        </tr>
                        <tr></tr>
                    </tfoot>
                </table>
                <div class="row after_reg_info_block">
                    <div class="col-lg-7">
                        <div class="after_reg_info">
                            <h3>What will happen after registration?</h3>
                            <div class="after_reg_info__items">
                                <div class="after_reg_info__item">1</div>
                                <div class="after_reg_info__item_text">The application will be processed within 48 hours</div>
                            </div>
                            <div class="after_reg_info__items">
                                <div class="after_reg_info__item">2</div>
                                <div class="after_reg_info__item_text">After registration, in your account you can add and send new applications for verification</div>
                            </div>
                            <div class="after_reg_info__items">
                                <div class="after_reg_info__item">3</div>
                                <div class="after_reg_info__item_text">
                                    If the application is approved, then you will get access to your partner’s personal account and will be able to upload accounts there. We will notify you of any decision by mail, as well as in the status
                                    of the application in your account
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-5">
                        <div class="after_reg_info__send_query_block">
                            <button type="submit" tabindex="9">Send</button>
                            <div><span class="red">*</span> - All fields is requried</div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</section>
<?php get_footer(); ?>