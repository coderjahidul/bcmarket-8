<div class="login-shadow">
    <div class="login">
        <div class="t-block">Account Login</div>
        <hr>
        <form action="#" method="post" id="login_form">
             <?php wp_nonce_field('bcmarket_login_nonce' ); ?>
            <input type="hidden" name="action" value="login_form">
            <input type="hidden" name="remember_me" value="1">
            <div class="form-line">
                <label for="log-email">Email:</label>
                <input type="text" id="log-email" name="email" value="" required="">
            </div>
            <div class="form-line">
                <label for="log-password">Your password:</label>
                <input type="password" name="password" id="log-password" value="" required="">
            </div>
            <button id="login_button" type="submit">Log in to account</button>
            <a href="<?php echo home_url( )?>/your-account/lost-password/" class="forgot_password">Forgot password?</a>
            <p class="mobile-hide">Don't have an account? <a href="#" class="registration-open">Sign up</a></p>
            <button class="registration-open registration-open-mobile">Sign up</button>
            <div class="close-login">
                <svg width="14" height="14" viewBox="0 0 14 14" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M2.19985 1.05671L6.99985 5.6742L11.7999 1.0707C11.8944 0.978194 12.0078 0.90556 12.133 0.857391C12.2582 0.809221 12.3924 0.786563 12.5271 0.790851C12.7915 0.807312 13.0407 0.91579 13.2281 1.09603C13.4154 1.27627 13.5282 1.51595 13.5453 1.77032C13.5466 1.89539 13.5215 2.01941 13.4715 2.13486C13.4215 2.25031 13.3476 2.35479 13.2544 2.44195L8.43985 7.08743L13.2544 11.7329C13.4435 11.9092 13.5482 12.1527 13.5453 12.4045C13.5282 12.6589 13.4154 12.8986 13.2281 13.0788C13.0407 13.2591 12.7915 13.3675 12.5271 13.384C12.3924 13.3883 12.2582 13.3656 12.133 13.3175C12.0078 13.2693 11.8944 13.1967 11.7999 13.1042L6.99985 8.50066L2.2144 13.1042C2.11989 13.1967 2.00646 13.2693 1.88128 13.3175C1.75609 13.3656 1.62188 13.3883 1.48713 13.384C1.21777 13.3705 0.963163 13.2615 0.772444 13.0781C0.581725 12.8946 0.46842 12.6497 0.454399 12.3905C0.453083 12.2655 0.47819 12.1415 0.528196 12.026C0.578203 11.9106 0.652067 11.8061 0.745308 11.7189L5.55985 7.08743L0.730763 2.44195C0.640147 2.35361 0.568971 2.2486 0.521487 2.13321C0.474004 2.01781 0.451187 1.89439 0.454399 1.77032C0.471511 1.51595 0.584277 1.27627 0.771642 1.09603C0.959007 0.91579 1.20816 0.807312 1.47258 0.790851C1.60629 0.784742 1.73987 0.805195 1.86499 0.850935C1.99012 0.896675 2.10411 0.966721 2.19985 1.05671Z" fill="#757575"></path>
                </svg>
            </div>
        </form>
        <div class="login-error"></div>
        <div class="login_success_message"></div>
    </div>
</div>

<div class="forgot-shadow">
    <div class="forgot">
        <div class="t-block">Password recovery</div>
        <hr />
        <form action="#" data-action="forgot_password" method="post" id="forgot_form" onsubmit="return !1">
            <input type="hidden" name="section" value="forgot_password" />
            <div id="recaptcha_forgot_password" class="g-recaptcha1">
                <div class="grecaptcha-badge" data-style="none" style="width: 256px; height: 60px; position: fixed; visibility: hidden;">
                    <div class="grecaptcha-logo">
                        <iframe
                            title="reCAPTCHA"
                            src="https://www.google.com/recaptcha/api2/anchor?ar=1&amp;k=6LcuPQAVAAAAAGJQa0gEZJFzO3zCGjRh7zhubUx2&amp;co=aHR0cHM6Ly9hY2NzbWFya2V0LmNvbTo0NDM.&amp;hl=en&amp;v=jF-AgDWy8ih0GfLx4Semh9UK&amp;size=invisible&amp;cb=c36gr4wt8wt"
                            width="256"
                            height="60"
                            role="presentation"
                            name="a-xcnhwlqvshan"
                            frameborder="0"
                            scrolling="no"
                            sandbox="allow-forms allow-popups allow-same-origin allow-scripts allow-top-navigation allow-modals allow-popups-to-escape-sandbox"
                        ></iframe>
                    </div>
                    <div class="grecaptcha-error"></div>
                    <textarea
                        id="g-recaptcha-response-1"
                        name="g-recaptcha-response"
                        class="g-recaptcha-response"
                        style="width: 250px; height: 40px; border: 1px solid rgb(193, 193, 193); margin: 10px 25px; padding: 0px; resize: none; display: none;"
                    ></textarea>
                </div>
                <iframe style="display: none;"></iframe>
            </div>
            <script src="/js/recaptcha2invisible.min.js" defer=""></script>
            <script src="https://www.google.com/recaptcha/api.js?onload=onloadCallback&amp;render=explicit" defer=""></script>
            <script>
                var reCapPubKey = "6LcuPQAVAAAAAGJQa0gEZJFzO3zCGjRh7zhubUx2";
            </script>
            <input type="hidden" name="act" value="form" />
            <input type="hidden" name="lang" value="en" />
            <div class="form-line">
                <label for="forgot-email">YOUR Email:</label>
                <input type="text" id="forgot-email" name="email" value="" required="" />
            </div>
            <button id="forgot_button" type="submit">Restore password</button>
            <div class="close-forgot">
                <svg width="14" height="14" viewBox="0 0 14 14" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path
                        d="M2.19985 1.05671L6.99985 5.6742L11.7999 1.0707C11.8944 0.978194 12.0078 0.90556 12.133 0.857391C12.2582 0.809221 12.3924 0.786563 12.5271 0.790851C12.7915 0.807312 13.0407 0.91579 13.2281 1.09603C13.4154 1.27627 13.5282 1.51595 13.5453 1.77032C13.5466 1.89539 13.5215 2.01941 13.4715 2.13486C13.4215 2.25031 13.3476 2.35479 13.2544 2.44195L8.43985 7.08743L13.2544 11.7329C13.4435 11.9092 13.5482 12.1527 13.5453 12.4045C13.5282 12.6589 13.4154 12.8986 13.2281 13.0788C13.0407 13.2591 12.7915 13.3675 12.5271 13.384C12.3924 13.3883 12.2582 13.3656 12.133 13.3175C12.0078 13.2693 11.8944 13.1967 11.7999 13.1042L6.99985 8.50066L2.2144 13.1042C2.11989 13.1967 2.00646 13.2693 1.88128 13.3175C1.75609 13.3656 1.62188 13.3883 1.48713 13.384C1.21777 13.3705 0.963163 13.2615 0.772444 13.0781C0.581725 12.8946 0.46842 12.6497 0.454399 12.3905C0.453083 12.2655 0.47819 12.1415 0.528196 12.026C0.578203 11.9106 0.652067 11.8061 0.745308 11.7189L5.55985 7.08743L0.730763 2.44195C0.640147 2.35361 0.568971 2.2486 0.521487 2.13321C0.474004 2.01781 0.451187 1.89439 0.454399 1.77032C0.471511 1.51595 0.584277 1.27627 0.771642 1.09603C0.959007 0.91579 1.20816 0.807312 1.47258 0.790851C1.60629 0.784742 1.73987 0.805195 1.86499 0.850935C1.99012 0.896675 2.10411 0.966721 2.19985 1.05671Z"
                        fill="#757575"
                    ></path>
                </svg>
            </div>
        </form>
    </div>
</div>


<script>
    jQuery(document).ready(function($){

        $("#login_form").on('submit', function() {

            $('.login-error').html('<?php _e('Please wait...', 'bcmarket'); ?>');
            $('.login-error').show();
            var input_data = $(this).serialize();
            //make ajax call
            $.ajax({
                type: "POST",
                url: "<?php echo admin_url('admin-ajax.php') ?>",
                dataType : 'json',
                data: input_data,
                success: function(response) {
                    console.log('fdsafdsaf');

                    if(response.loggedin == false){
                        $('.login_success_message').html(' ');
                        $('.login-error').html(response.message);
                        $('.login-error').show();
                    }else{
                        $('.login_success_message').html(' ');
                        $('.login-error').html(' ');
                        $('.login_success_message').html(response.message);
                        $('.login-error').hide();

                            setTimeout(function(){

                                window.location.href = response.link;

                            }, 300);
                                            
                    }

                }
            });
            return false;
        });

    });

</script>
