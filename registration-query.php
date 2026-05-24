<?php
if(is_user_logged_in() && current_user_has_bid()){
    wp_safe_redirect( home_url('/partner/offers/') );
}else if(!is_user_logged_in()){
    wp_safe_redirect( home_url('/registration/partner') );
}
/*
Template Name: Registration Query
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
                        <span class="current" itemprop="name">Account Provider Registration</span>
                        <meta itemprop="position" content="1" />
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="container">
        <div class="flex">
            <h1 class="partner-reg">Account Provider Registration</h1>
            <div class="partner-reg__steps">
                <div class="step_1">
                    <div class="step_1_text">
                        <svg width="18" height="18" viewBox="0 0 18 18" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path
                                fill-rule="evenodd"
                                clip-rule="evenodd"
                                d="M11.7473 2.83009C10.4177 2.23767 8.93223 2.09091 7.51243 2.41169C6.09263 2.73248 4.81456 3.50362 3.86884 4.61012C2.92312 5.71661 2.36041 7.09917 2.26463 8.5516C2.16886 10.004 2.54516 11.4485 3.3374 12.6696C4.12964 13.8907 5.29539 14.823 6.66077 15.3274C8.02615 15.8319 9.51802 15.8814 10.9139 15.4687C12.3097 15.056 13.5348 14.2031 14.4063 13.0373C15.2778 11.8715 15.7492 10.4552 15.75 8.99959V8.31002C15.75 7.89581 16.0858 7.56002 16.5 7.56002C16.9142 7.56002 17.25 7.89581 17.25 8.31002V9.00002C17.249 10.7791 16.6729 12.5106 15.6077 13.9355C14.5425 15.3604 13.0452 16.4027 11.3392 16.9072C9.63313 17.4116 7.80974 17.351 6.14094 16.7345C4.47214 16.1179 3.04734 14.9785 2.07904 13.486C1.11074 11.9936 0.650828 10.2281 0.767883 8.45291C0.884939 6.67771 1.5727 4.98792 2.72858 3.63553C3.88447 2.28315 5.44655 1.34064 7.18186 0.94857C8.91716 0.5565 10.7327 0.735878 12.3578 1.45995C12.7361 1.62853 12.9062 2.07192 12.7376 2.45027C12.569 2.82863 12.1256 2.99868 11.7473 2.83009Z"
                                fill="#2FB241"
                            ></path>
                            <path
                                fill-rule="evenodd"
                                clip-rule="evenodd"
                                d="M17.0301 2.4694C17.3231 2.76215 17.3233 3.23703 17.0306 3.53006L9.5306 11.0376C9.38997 11.1783 9.19916 11.2575 9.00019 11.2575C8.80121 11.2576 8.61037 11.1785 8.46967 11.0378L6.21967 8.78783C5.92678 8.49494 5.92678 8.02006 6.21967 7.72717C6.51256 7.43428 6.98744 7.43428 7.28033 7.72717L8.99973 9.44658L15.9694 2.46994C16.2622 2.1769 16.737 2.17666 17.0301 2.4694Z"
                                fill="#2FB241"
                            ></path>
                        </svg>
                        Step 1: Entering Login Information
                    </div>
                </div>
                <div class="step_2 active">
                    <div class="step_2_text">
                        <span>Step 2: account delivery application</span>
                    </div>
                </div>
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