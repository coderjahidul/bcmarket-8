<?php 
/*
Template Name: Login
*/
get_header(); ?>
<style>
form#login_form {
    text-align: left;
}

#login_form .forgot_password {
    text-align: left;
}

#login_form p {
    text-align: left;
}

#forgot_form input, #login_form input, #registration_form input {
    width: 100%;
}
</style>
<section class="soc-category" id="content">
    <div class="wrap-breadcrumbs">
        <div class="container">
            <div class="flex">
                <div class="block" itemscope="" itemtype="http://schema.org/BreadcrumbList" id="breadcrumbs">
                    <div itemprop="itemListElement" itemscope="" itemtype="http://schema.org/ListItem">
                        <span class="current" itemprop="name">Home</span>
                        <meta itemprop="position" content="0" />
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="container">
        <div class="flex">
            <h1>Your account</h1>
            <form action="#" method="post" id="login_form">
                 <?php wp_nonce_field('bcmarket_login_nonce' ); ?>
                <input type="hidden" name="action" value="login_form">
                <input type="hidden" name="remember_me" value="1">
                <table class="form">
                    <tbody>
                        <tr>
                            <td>Email</td>
                            <td><input type="text" name="email" value="" /></td>
                        </tr>
                        <tr>
                            <td>Password</td>
                            <td><input type="password" name="password" value="" /></td>
                        </tr>
                        <tr style="postion:relative !important;">
                            <td>&nbsp;</td>
                            <td><input type="checkbox" style="position: absolute !important; margin-left: -950px !important; margin-top: 2px !important;" name="remember_me" id="remember_me" value="1" /> <label for="remember_me">Remember me</label></td>
                        </tr>
                    </tbody>
                </table>
                <a href="<?php echo home_url( )?>/your-account/lost-password/" class="forgot_password">Forgot password?</a>
                <p><button type="submit">Send</button></p>
            </form>
            <div class="login-error"></div>
            <div class="login_success_message"></div>
        </div>
    </div>
</section>


<?php get_footer(); ?>