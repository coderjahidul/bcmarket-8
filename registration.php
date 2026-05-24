<?php 
/*
Template Name: Registration
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
                        <span class="current" itemprop="name">Sign Up</span>
                        <meta itemprop="position" content="1" />
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="container">
        <div class="flex">
            <h1 class="partner-reg">Sign Up</h1>
            <form action="#" data-action="registration_page" id="registration_form_page" method="post">
            	<?php wp_nonce_field('bcmarket_register_page_nonce' ); ?>
                <input type="hidden" name="action" value="registration_form_page" />
               
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
                                <input type="checkbox" name="conditions_reg"  style="margin-left: 0px;" id="conditions_reg_page" value="1" />
                                <label class="container-checkbox" for="conditions_reg_page">I agree to the <a href="/en/public-offer" target="_blank">public offer</a> and <a href="/en/conditions" target="_blank">terms of use</a></label>
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

        $("#registration_form_page").on('submit', function() {

            $('.register_success_message').html('<?php _e('Please wait...', 'tirisi'); ?>');
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

                                window.location.href = "<?php echo wc_get_account_endpoint_url('dashboard'); ?>";

                            }, 300);
                                            
                    }


                }
            });
            return false;
        });

    });
</script>
<?php get_footer(); ?>