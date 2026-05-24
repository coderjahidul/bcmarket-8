<style>
    #conditions_reg {
        position: absolute !important; 
        margin-left: -133px !important; 
        margin-top:2px !important;
    }
    @media screen and (max-width: 550px) {
        #conditions_reg {
            position: absolute !important; 
            margin-left: -75px !important; 
            margin-top:2px !important;
            height: 13px !important;
        }
    }
</style>
<div class="reg-shadow">
    <div class="registration">
        <div class="t-block">Sign up to your account</div>
        <hr>
        <form action="#" method="post" id="registration_form" >
            <?php wp_nonce_field('bcmarket_register_nonce' ); ?>
            <input type="hidden" name="action" value="registration_form">
            <div class="form-line">
                <label for="reg_email">Email:</label>
                <input type="email" name="email" id="reg_email" required="">
            </div>
            <div class="form-line">
                <label for="reg_password1">Your password:</label>
                <input type="password" name="password" id="reg_password1" required="">
            </div>
            <div class="form-line">
                <label for="reg_password2">Confirm password:</label>
                <input type="password" name="password2" id="reg_password2" required="">
            </div>
            <div class="form-line" style="position:relative !important;">
            <input type="checkbox" name="conditions_reg" id="conditions_reg" value="1">
                <label for="conditions_reg">I agree to the <a href="/public-offer" target="_blank">public offer</a> and <a href="/rules" target="_blank">terms of use</a></label>
            </div>
            <button id="reg_button" type="submit">Sign up</button>
            <p>Already have an account? <a href="#" class="login-open">Login</a></p>
            <div class="close-registration">
                <svg width="14" height="14" viewBox="0 0 14 14" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M2.19985 1.05671L6.99985 5.6742L11.7999 1.0707C11.8944 0.978194 12.0078 0.90556 12.133 0.857391C12.2582 0.809221 12.3924 0.786563 12.5271 0.790851C12.7915 0.807312 13.0407 0.91579 13.2281 1.09603C13.4154 1.27627 13.5282 1.51595 13.5453 1.77032C13.5466 1.89539 13.5215 2.01941 13.4715 2.13486C13.4215 2.25031 13.3476 2.35479 13.2544 2.44195L8.43985 7.08743L13.2544 11.7329C13.4435 11.9092 13.5482 12.1527 13.5453 12.4045C13.5282 12.6589 13.4154 12.8986 13.2281 13.0788C13.0407 13.2591 12.7915 13.3675 12.5271 13.384C12.3924 13.3883 12.2582 13.3656 12.133 13.3175C12.0078 13.2693 11.8944 13.1967 11.7999 13.1042L6.99985 8.50066L2.2144 13.1042C2.11989 13.1967 2.00646 13.2693 1.88128 13.3175C1.75609 13.3656 1.62188 13.3883 1.48713 13.384C1.21777 13.3705 0.963163 13.2615 0.772444 13.0781C0.581725 12.8946 0.46842 12.6497 0.454399 12.3905C0.453083 12.2655 0.47819 12.1415 0.528196 12.026C0.578203 11.9106 0.652067 11.8061 0.745308 11.7189L5.55985 7.08743L0.730763 2.44195C0.640147 2.35361 0.568971 2.2486 0.521487 2.13321C0.474004 2.01781 0.451187 1.89439 0.454399 1.77032C0.471511 1.51595 0.584277 1.27627 0.771642 1.09603C0.959007 0.91579 1.20816 0.807312 1.47258 0.790851C1.60629 0.784742 1.73987 0.805195 1.86499 0.850935C1.99012 0.896675 2.10411 0.966721 2.19985 1.05671Z" fill="#757575"></path>
                </svg>
            </div>
        </form>
        <div class="registration-error"></div>
        <div class="register_success_message"></div>
    </div>
</div>

<script type="text/javascript">
    jQuery(document).ready(function($){

        $("#registration_form").on('submit', function() {

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