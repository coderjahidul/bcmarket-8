<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Abel&family=Ubuntu:wght@400;700&display=swap" rel="stylesheet"> 
    <script type="text/javascript">
    (function(c,l,a,r,i,t,y){
        c[a]=c[a]||function(){(c[a].q=c[a].q||[]).push(arguments)};
        t=l.createElement(r);t.async=1;t.src="https://www.clarity.ms/tag/"+i;
        y=l.getElementsByTagName(r)[0];y.parentNode.insertBefore(t,y);
    })(window, document, "clarity", "script", "tqebla6ce0");
</script>
    <?php wp_head(); ?>
	
<body <?php body_class(); ?>>
    <style>
        .login-icon {
            display: none;
        }
        .login-open {
            color: #fff;
        }
    </style>

    <div class="main-wrapper">
        <header>
            <div class="hdr-top">
                <div class="container">
                    <div class="flex">
                        <p><?php echo get_theme_mod('top_left_text'); ?> </p>
                        <p class="xs-vis"> <?php echo get_theme_mod('top_left_text_mobile'); ?> </p>
                        <div id="navigation_right">
                            <?php if(current_user_can('administrator') || current_user_can('employee')) : ?>
                                <div>
                                    <a class="btn btn-primary btn-xs" href="<?php echo esc_url(home_url('/admin-interface/')); ?>">Admin Interface</a>
                                    <a class="btn btn-primary btn-xs" href="<?php echo esc_url(home_url('/admin-interface/bid-request/')); ?>">Bid Request</a>
                                    <a class="btn btn-primary btn-xs" href="<?php echo esc_url(home_url('/partners')); ?>">Partner</a>
                                </div>
                            <?php //elseif(current_user_can('manage_options')): ?>
                                <!--<div>-->
                                <!--    <a class="btn btn-primary btn-xs" href="<?php //echo esc_url(home_url('/admin-interface/bid-request/')); ?>">Bid Request</a>-->
                                <!--    <a class="btn btn-primary btn-xs" href="<?php //echo esc_url(home_url('/partners')); ?>">Partner</a>-->
                                <!--</div>-->
                            <?php endif; ?>

                            <div id="navigation_menu">
                                <!-- <a class="ic-updates" href="<?php //echo get_theme_mod('header_telegram_link'); ?>" rel="nofollow">
                                    <?php //echo get_theme_mod('header_telegram_text'); ?>
                                </a> -->
                                <a target="_blank" href="<?php echo esc_url(get_theme_mod('header_telegram_link')); ?>"><i class="fab fa-telegram"></i></a>
                                <a target="_blank" href="mailto:<?php echo esc_html(get_theme_mod('footer_email')); ?>"><i class="fa-solid fa-envelope"></i></a>
                                <a target="_blank" href="<?php echo esc_url(get_theme_mod('header_skype')); ?>"><i class="fa-solid fa-people-group"></i></a>
                                <!-- <a target="_blank" href="<?php //echo esc_url(get_theme_mod('header_whatsapp')); ?>"><i class="fab fa-whatsapp"></i></a> -->
                            </div>
                            <div class="authorization">
                                <?php if(!is_user_logged_in()) : ?>
                                    <a href="javascript:void(0);" class="registration-open">+ Sign Up</a>
                                    <a href="javascript:void(0);" class="login-open">
                                        <img style="width: 20px;" class="img-svg login-icon"> Login </a>
                                <?php else : ?>
                                    <div class="cabinet-menu">
                                        <a href="<?php echo home_url();?>/your-account" class="cabinet-open">
                                            <!-- Profile Icon SVG -->
                                            <svg width="16" height="16" viewBox="0 0 16 16" xmlns="http://www.w3.org/2000/svg" fill="none" class="profile-icon">
                                                <circle cx="8" cy="5" r="3" fill="white"></circle> <!-- Head -->
                                                <path d="M8 9C5 9 2 10 2 12V14H14V12C14 10 11 9 8 9Z" fill="white"></path> <!-- Body -->
                                            </svg>
                                            Dashboard
                                        </a>
                                        <ul class="cabinet-sub-menu">
                                            <li>
                                                <a href="<?php echo home_url();?>/your-account">
                                                    <svg width="15" height="15" viewBox="0 0 15 15" fill="none" xmlns="http://www.w3.org/2000/svg" class="img-svg replaced-svg">
                                                        <path
                                                            d="M14.7482 6.8282L12.4749 4.55488V1.92881C12.4749 1.45398 12.0901 1.06917 11.6146 1.06917C11.1402 1.06917 10.7554 1.45398 10.7554 1.92881V2.83538L9.0627 1.14265C8.22583 0.306233 6.77078 0.307717 5.93582 1.14417L0.251661 6.8282C-0.0838871 7.16442 -0.0838871 7.70843 0.251661 8.04413C0.587361 8.38026 1.13244 8.38026 1.46802 8.04413L7.15163 2.35995C7.33678 2.17576 7.66313 2.17576 7.84731 2.3594L13.5319 8.04413C13.7005 8.21221 13.9202 8.29581 14.1399 8.29581C14.3601 8.29581 14.5802 8.21212 14.7482 8.04413C15.0839 7.70846 15.0839 7.16445 14.7482 6.8282Z"
                                                            fill="#A4A4A4"
                                                        ></path>
                                                        <path
                                                            d="M7.79862 3.99174C7.63348 3.82669 7.36608 3.82669 7.20143 3.99174L2.20163 8.99006C2.12269 9.06896 2.07794 9.1767 2.07794 9.28912V12.9347C2.07794 13.7902 2.77157 14.4838 3.627 14.4838H6.1024V10.6502H8.8971V14.4838H11.3725C12.2279 14.4838 12.9216 13.7902 12.9216 12.9347V9.28912C12.9216 9.1767 12.8772 9.06896 12.7979 8.99006L7.79862 3.99174Z"
                                                            fill="#A4A4A4"
                                                        ></path>
                                                    </svg>
                                                    Dashboard
                                                </a>
                                            </li>
                                            <li>
                                                <a href="<?php echo home_url();?>/your-account/tickets">
                                                    <svg width="15" height="15" viewBox="0 0 15 15" fill="none" xmlns="http://www.w3.org/2000/svg" class="img-svg replaced-svg">
                                                        <path
                                                            d="M14.6707 4.97731L13.549 3.86456C13.2167 4.19682 12.813 4.36307 12.3383 4.36307C11.8635 4.36307 11.46 4.19682 11.1278 3.86456C10.7953 3.53219 10.6293 3.12869 10.6293 2.65385C10.6293 2.17898 10.7955 1.77548 11.1278 1.44307L10.0149 0.330329C9.79507 0.110822 9.52661 0.00100708 9.20926 0.00100708C8.89173 0.00100708 8.62327 0.110854 8.40367 0.330329L0.329446 8.39562C0.109784 8.61531 0 8.88399 0 9.20121C0 9.51869 0.109784 9.78711 0.329446 10.0068L1.44219 11.1286C1.77457 10.7963 2.17816 10.6301 2.65293 10.6301C3.12768 10.6301 3.53117 10.7961 3.86352 11.1286C4.1958 11.4609 4.36199 11.8644 4.36199 12.3392C4.36199 12.8141 4.1959 13.2174 3.86352 13.5499L4.98524 14.6716C5.20487 14.8912 5.47346 15.0009 5.79096 15.0009C6.10849 15.0009 6.37708 14.8912 6.59655 14.6716L14.6707 6.5885C14.8903 6.36893 15 6.10044 15 5.7829C15 5.4654 14.8903 5.19694 14.6707 4.97731ZM12.2582 6.75768L6.75672 12.259C6.65002 12.366 6.51648 12.4194 6.35615 12.4194C6.19598 12.4194 6.06247 12.366 5.95565 12.259L2.733 9.03668C2.62022 8.92377 2.56388 8.79029 2.56388 8.63611C2.56388 8.4818 2.62022 8.34813 2.733 8.23532L8.23443 2.73392C8.34134 2.62707 8.47495 2.57359 8.63521 2.57359C8.79529 2.57359 8.92886 2.62707 9.03578 2.73392L12.2582 5.95645C12.3711 6.06929 12.4275 6.20283 12.4275 6.35711C12.4274 6.51136 12.3711 6.64487 12.2582 6.75768Z"
                                                            fill="#A4A4A4"
                                                        ></path>
                                                        <path d="M8.63552 3.54333L11.4487 6.35632L6.35675 11.4482L3.54376 8.63525L8.63552 3.54333Z" fill="#A4A4A4"></path>
                                                    </svg>
                                                    Tickets
                                                </a>
                                            </li>
                                            <li>
                                                <a href="<?php echo home_url();?>/your-account/orders">
                                                    <svg width="15" height="15" viewBox="0 0 15 15" fill="none" xmlns="http://www.w3.org/2000/svg" class="img-svg replaced-svg">
                                                        <path
                                                            d="M4.5 12C3.675 12 3 12.675 3 13.5C3 14.325 3.675 15 4.5 15C5.325 15 6 14.325 6 13.5C6 12.675 5.325 12 4.5 12ZM0 0V1.5H1.5L4.2 7.2L3.15 9C3.075 9.225 3 9.525 3 9.75C3 10.575 3.675 11.25 4.5 11.25H13.5V9.75H4.8C4.725 9.75 4.65 9.675 4.65 9.6V9.52497L5.325 8.24997H10.875C11.475 8.24997 11.925 7.94997 12.15 7.49997L14.85 2.625C15 2.475 15 2.4 15 2.25C15 1.8 14.7 1.5 14.25 1.5H3.15L2.475 0H0ZM12 12C11.175 12 10.5 12.675 10.5 13.5C10.5 14.325 11.175 15 12 15C12.825 15 13.5 14.325 13.5 13.5C13.5 12.675 12.825 12 12 12Z"
                                                            fill="#A4A4A4"
                                                        ></path>
                                                    </svg>
                                                    My orders
                                                </a>
                                            </li>
                                            <li>
                                                <a href="<?php echo home_url();?>/your-account/edit-account">
                                                    <svg width="13" height="15" viewBox="0 0 13 15" fill="none" xmlns="http://www.w3.org/2000/svg" class="img-svg replaced-svg">
                                                        <path
                                                            d="M6.50005 6.95652C8.42104 6.95652 9.97831 5.39925 9.97831 3.47826C9.97831 1.55727 8.42104 0 6.50005 0C4.57906 0 3.02179 1.55727 3.02179 3.47826C3.02179 5.39925 4.57906 6.95652 6.50005 6.95652Z"
                                                            fill="#A4A4A4"
                                                        ></path>
                                                        <path d="M6.50003 8.69565C3.01823 8.69565 0.195679 11.5182 0.195679 15H12.8044C12.8044 11.5182 9.98182 8.69565 6.50003 8.69565Z" fill="#A4A4A4"></path>
                                                    </svg>
                                                    Profile
                                                </a>
                                            </li>
                                            <li>
                                                <a href="<?php echo home_url();?>/your-account/woo-wallet">
                                                    <svg width="15" height="15" viewBox="0 0 15 15" fill="none" xmlns="http://www.w3.org/2000/svg" class="img-svg replaced-svg">
                                                        <path
                                                            d="M6.76851 8.22916V8.0884C6.76851 6.17023 8.32501 4.62024 10.2435 4.62024H13.9863V3.5753C13.9863 3.34624 13.8009 3.16052 13.5719 3.16052H2.606C2.19317 3.16052 1.85887 2.82296 1.85887 2.41013C1.85887 1.99762 2.1935 1.66006 2.606 1.66006H13.5908C13.8091 1.66006 13.9863 1.48281 13.9863 1.2645V0.905109C13.9863 0.707655 13.827 0.565918 13.6292 0.565918H1.39163C0.617777 0.565918 0 1.17425 0 1.9481V13.0316C0 13.8054 0.617777 14.4343 1.39163 14.4343H12.5862C13.3601 14.4343 13.9863 13.8054 13.9863 13.0316V11.7175H10.2435C8.32501 11.7165 6.76851 10.1477 6.76851 8.22916Z"
                                                            fill="#A4A4A4"
                                                        ></path>
                                                        <path
                                                            d="M14.0725 5.71545H10.2436C8.94096 5.71545 7.90416 6.78679 7.90416 8.08947V8.22957C7.90416 9.53225 8.94096 10.5818 10.2436 10.5818H14.0728C14.5762 10.5818 15.0005 10.1803 15.0005 9.67757V6.64245C15.0001 6.13871 14.5759 5.71545 14.0725 5.71545ZM10.4538 9.18687C9.88652 9.18687 9.42645 8.72712 9.42645 8.15952C9.42645 7.59257 9.88652 7.1325 10.4538 7.1325C11.0211 7.1325 11.4811 7.59257 11.4811 8.15952C11.4811 8.72712 11.0211 9.18687 10.4538 9.18687Z"
                                                            fill="#A4A4A4"
                                                        ></path>
                                                    </svg>
                                                    Add funds
                                                </a>
                                            </li>
                                            <li>
                                                <a href="<?php echo wp_logout_url( home_url() ); ?>">
                                                    <svg width="15" height="15" viewBox="0 0 15 15" fill="none" xmlns="http://www.w3.org/2000/svg" class="img-svg replaced-svg">
                                                        <path
                                                            d="M14.9523 6.63621C14.9204 6.55995 14.8748 6.49058 14.8167 6.43245L12.9423 4.55806C12.6979 4.31431 12.3029 4.31431 12.0586 4.55806C11.8142 4.80243 11.8142 5.19806 12.0586 5.44181L12.8667 6.24993H9.37542C9.0298 6.24993 8.75043 6.52992 8.75043 6.87492C8.75043 7.21992 9.0298 7.49991 9.37542 7.49991H12.8667L12.0585 8.30804C11.8142 8.5524 11.8142 8.94803 12.0585 9.19178C12.1804 9.31427 12.3404 9.37491 12.5004 9.37491C12.6604 9.37491 12.8204 9.3143 12.9423 9.19178L14.8167 7.31739C14.8748 7.25988 14.9204 7.19051 14.9523 7.11363C15.0154 6.9612 15.0154 6.7887 14.9523 6.63621Z"
                                                            fill="#A4A4A4"
                                                        ></path>
                                                        <path
                                                            d="M10.6254 8.75001C10.2798 8.75001 10.0004 9.03 10.0004 9.375V12.5H7.50043V2.49999C7.50043 2.22437 7.31917 1.98062 7.05479 1.90125L4.88416 1.25001H10.0004V4.37502C10.0004 4.72002 10.2798 5.00001 10.6254 5.00001C10.971 5.00001 11.2504 4.72002 11.2504 4.37502V0.62502C11.2504 0.27999 10.971 0 10.6254 0H0.625417C0.602917 0 0.582908 0.009375 0.561052 0.0118652C0.531667 0.015 0.504802 0.0199805 0.476677 0.0268652C0.411052 0.0437402 0.351667 0.069375 0.296062 0.104355C0.282322 0.113115 0.265447 0.11373 0.252322 0.123721C0.247283 0.1275 0.245408 0.134385 0.240398 0.138135C0.172283 0.191865 0.115417 0.25749 0.0741675 0.335625C0.0654077 0.3525 0.0635327 0.370635 0.0566772 0.388125C0.0366675 0.435615 0.014812 0.481875 0.00731201 0.534375C0.00417725 0.553125 0.00980225 0.570615 0.00918701 0.58875C0.00857178 0.60126 0.000427246 0.61251 0.000427246 0.62499V13.125C0.000427246 13.4231 0.211042 13.6794 0.502927 13.7375L6.75292 14.9875C6.79355 14.9963 6.8348 15 6.87541 15C7.01852 15 7.15915 14.9507 7.27165 14.8581C7.41602 14.7394 7.5004 14.5625 7.5004 14.375V13.75H10.6254C10.971 13.75 11.2504 13.47 11.2504 13.125V9.375C11.2504 9.03 10.971 8.75001 10.6254 8.75001Z"
                                                            fill="#A4A4A4"
                                                        ></path>
                                                    </svg>
                                                    Sign out
                                                </a>
                                            </li>
                                        </ul>
                                    </div>


                                <?php endif; ?>
                            </div>
                        </div>
                        <div id="head_mob_navigation">
                            <a href="#" class="open-menu"></a>
                        </div>
                    </div>
                </div>
            </div>
            <div class="hdr-middle">
                <div class="container">
                    <div class="flex">
                        <?php 
                            wp_nav_menu(array(
                                'theme_location' => 'main_menu', 
                                'menu_class' => 'flexbox', 
                                'menu_id' => 'head_menu', 
                                'container' => false
                            )); 
                        ?>
                        
                        <div class="info" id="info">
                            <p class="h1 accent">Important</p>
                            <ul>
                                <?php echo get_theme_mod('header_important_message'); ?>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
            <div class="hdr-bottom">
                <div class="container">
                    <?php 
                        
                    ?>
                    <div class="">
                        <div id="head" style="width: 100%">
                            <div class="wrap flex">
                                <div id="head_navigation">

                                    <?php the_custom_logo(); ?>

                                    <button class="search_trigger"></button>
                                </div>
                                <div class="head-category-navigation">
                                    <button class="button"><img src="<?php echo get_template_directory_uri(); ?>/img/menu-icons/line-menu.svg">Select a category</button>
                                    <ul class="head-menu">

                                        <?php 
                                            
                                            $terms = get_terms( array(
                                                'taxonomy' => 'item_cat',
                                                'hide_empty' => false,
                                                'parent' => 0
                                            ) );

                                            if($terms) : 

                                                foreach($terms as $term) : 

                                                    $termchildren = get_term_children( $term->term_id, 'item_cat');
                                        ?>

                                        <li>
                                            <a href="<?php echo get_term_link($term->term_id); ?>" class="facebook" data-tooltip="<?php //echo $term->description; ?>">
                                                <?php 
                                                    $thumbnail_id = get_term_meta( $term->term_id, 'thumbnail_id', true );
                                                    echo wp_get_attachment_image( $thumbnail_id, 'thumbnail' );
                                                ?>
                                                <?php echo $term->name; ?>
                                            </a>
                                            <?php if($termchildren) : ?>
                                            <ul>
                                                <?php foreach ( $termchildren as $child ) :
                                                    $child = get_term( $child, 'item_cat' );
                                                     ?>
                                                    <li><a href="<?php echo get_term_link($child->term_id); ?>" data-tooltip="<?php echo $child->description; ?>"><?php echo $child->name; ?></a></li>
                                                    
                                                <?php endforeach; ?>
                                                <li class="view-all"><a href="<?php echo get_term_link($term->term_id); ?>">Show all</a></li>
                                                <li class="category-tooltip"></li>
                                            </ul>
                                        <?php endif; ?>
                                        </li>
                                    <?php endforeach; endif; ?>
                                    </ul>
                                </div>
                                <?php get_template_part( 'template-parts/header','search' ); ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </header>

     