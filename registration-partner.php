<?php 
if(is_user_logged_in()){
    wp_safe_redirect( home_url('/registration/query/') );
}
/*
Template Name: Registration Partner
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
                <div class="step_1 active">
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
                <div class="step_2">
                    <div class="step_2_text">
                        <span>Step 2: account delivery application</span>
                    </div>
                </div>
            </div>
            <form action="#" id="partner_registration" method="post">
                <?php wp_nonce_field('bcmarket_register_partner_nonce' ); ?>
                <input type="hidden" name="action" value="partner_registration" />
                
                <h2>Entering Login Information:</h2>
                <table class="form">
                    <tbody>
                        <tr>
                            <td>Email: <span class="red">*</span></td>
                            <td><input type="email" name="email" value="" tabindex="1" required="required" /></td>
                        </tr>
                        <tr>
                            <td>Your password: <span class="red">*</span></td>
                            <td><input type="password" name="password" value="" tabindex="4" required="required" /></td>
                        </tr>
                        <tr>
                            <td>Confirm the password: <span class="red">*</span></td>
                            <td><input type="password" name="password2" value="" tabindex="5" required="required" /></td>
                        </tr>
                        <tr>
                            <td>&nbsp;</td>
                            <td>
                                <input type="checkbox" style="margin-left: 0px;" name="conditions_reg" id="conditions_reg_page" value="1" />
                                <label class="container-checkbox" for="conditions_reg_page">I agree to the <a href="/public-offer" target="_blank">public offer</a> and <a href="/rules" target="_blank">terms of use</a></label>
                            </td>
                        </tr>
                    </tbody>
                </table>
                <p><button type="submit" tabindex="9">Sign up</button></p>
                <p class="required_info"><span class="red">*</span> - All fields is requried</p>
            </form>
            <div class="registration-error"></div>
            <div class="register_success_message"></div>
        </div>
    </div>
</section>


<script type="text/javascript">
    jQuery(document).ready(function($){

        $("#partner_registration").on('submit', function() {

            $('.register_success_message').html('<?php _e('Please wait...', 'bcmarket'); ?>');
            var input_data = $(this).serialize();
            //make ajax call
            $.ajax({
                type: "POST",
                url: "<?php echo admin_url('admin-ajax.php') ?>",
                dataType : 'json',
                data: input_data,
                success: function(response) {
                    console.log(response);

                    if(response.error == 1){
                        $('.register_success_message').html(' ');
                        $('.registration-error').show();
                        $('.registration-error').html(' ');
                        if(response.user){
                            $('.registration-error').append(response.user + '<br>');
                        }
                        if(response.email){
                            $('.registration-error').append(response.email + '<br>');
                        }

                        if(response.password){
                            $('.registration-error').append(response.password + '<br>');
                        }

                        if(response.term){
                          $('.registration-error').append(response.term + '<br>');
                        }
                        
                    }else{
                        $('.registration-error').hide();
                        $('.register_success_message').html(' ');
                        $('.registration-error').html(' ');
                        $('.register_success_message').html(response.message);

                            setTimeout(function(){

                                window.location.href = "<?php echo esc_url(home_url('/registration/query')); ?>";

                            }, 300);
                                            
                    }


                }
            });
            return false;
        });

    });
</script>

<?php get_footer(); ?>