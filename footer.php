<footer>
            <div class="ftr-top">
                <div class="container">
                    <div class="flex">
                        <div class="col-33">
                            <a href="<?php echo esc_url(home_url('/')); ?>" class="ftr-logo">
                                <?php echo wp_get_attachment_image(get_theme_mod( 'custom_logo' ), 'full', false, array('class' => 'footer_logo')); ?>
                            </a>
                        </div>
                        <div class="col-33 flex footer_menu">
                            <?php  
                                wp_nav_menu(array(
                                    'theme_location' => 'footer_menu', 
                                    'container' => false, 
                                    'menu_class' => ''
                                )); 
                            ?>
                        </div>
                        <div class="col-33 ftr-last-col">
                            <ul>
                                <li class="gold-sp"><a id="footer_provider" href="<?php echo esc_url(home_url('/sell-account/')); ?>">Sell Account</a></li>
                                <li class="green-sp"><a class="ic-updates" href="<?php echo get_theme_mod('header_telegram_link'); ?>" rel="nofollow"><?php echo get_theme_mod('header_telegram_text'); ?></a></li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
            <div class="ftr-center">
                <div class="container">
                    <div class="flex">
                        <div class="tex-res">
                            <img src="<?php echo get_template_directory_uri(); ?>/img/new/about.png" loading="lazy" alt="">
                            <p>Support:</p>
                        </div>
                        <div class="create-ticket" id="support_ticket">
                            <a href="<?php echo esc_url(home_url('/tickets/new/')); ?>">New ticket / Ask a question </a>
                        </div>
                        <div id="sitemap">
                            <div>
                                <a href="<?php echo home_url();?>/sitemap">Sitemap</a>
                            </div>
                            <div>
                                <a href="<?php echo home_url();?>/public-offer/">Public offer</a>
                                <a href="<?php echo home_url();?>/privacy-policy/">Privacy policy</a>
                            </div>
                            <div>
                                <a href="<?php echo home_url();?>/blog/">Blog</a>
                            </div>
                        </div>
                        <div class="soc_icon_block">
                            <!-- <a href="<?php echo get_theme_mod('footer_telegram'); ?>" target="_blank">
                                <img src="<?php echo get_template_directory_uri(); ?>/img/soc/telegram.svg" alt="Telegram">
                            </a>
                            <a href="mailto:<?php echo get_theme_mod('footer_email'); ?>" target="_blank">
                                <img src="<?php echo get_template_directory_uri(); ?>/img/soc/email-at.svg">
                            </a> -->
                            <ul>
                                <li><a target="_blank" href="<?php echo get_theme_mod('footer_telegram'); ?>"><i class="fa-brands fa-telegram"></i></a></li>
                                <li><a target="_blank" href="mailto:<?php echo get_theme_mod('footer_email'); ?>"><i class="fa-solid fa-envelope"></i></a></li>
                                <li><a target="_blank" href="<?php echo get_theme_mod('footer_skype'); ?>"><i class="fa-solid fa-people-group"></i></a></li>
                                <!-- <li><a target="_blank" href="<?php //echo get_theme_mod('footer_whatsapp'); ?>"><i class="fab fa-whatsapp"></i></a></li> -->
                                <!-- <li><a target="_blank" href="https://twitter.com/Accountsseller1"><i class="fa-brands fa-twitter"></i></a></li>
                                <li><a target="_blank" href="https://www.instagram.com/accountsseller2012/"><i class="fa-brands fa-instagram"></i></a></li> -->
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
            <div id="copyright">
                <div class="container">
                    <div class="flex">
                        <div class="wrap">
                            <div class="r">&copy; <?php echo date('Y'); ?></div>
                            <div class="l"><?php echo get_theme_mod('footer_copyright'); ?></div>
                        </div>
                    </div>
                </div>
            </div>
        </footer>
    </div>
    <div class="modal-shadow">
        <div class="modal-body">
            <div class="t-block"></div>
            <hr>
            <div class="message"></div>
            <div class="close-modal">
                <svg width="14" height="14" viewBox="0 0 14 14" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M2.19985 1.05671L6.99985 5.6742L11.7999 1.0707C11.8944 0.978194 12.0078 0.90556 12.133 0.857391C12.2582 0.809221 12.3924 0.786563 12.5271 0.790851C12.7915 0.807312 13.0407 0.91579 13.2281 1.09603C13.4154 1.27627 13.5282 1.51595 13.5453 1.77032C13.5466 1.89539 13.5215 2.01941 13.4715 2.13486C13.4215 2.25031 13.3476 2.35479 13.2544 2.44195L8.43985 7.08743L13.2544 11.7329C13.4435 11.9092 13.5482 12.1527 13.5453 12.4045C13.5282 12.6589 13.4154 12.8986 13.2281 13.0788C13.0407 13.2591 12.7915 13.3675 12.5271 13.384C12.3924 13.3883 12.2582 13.3656 12.133 13.3175C12.0078 13.2693 11.8944 13.1967 11.7999 13.1042L6.99985 8.50066L2.2144 13.1042C2.11989 13.1967 2.00646 13.2693 1.88128 13.3175C1.75609 13.3656 1.62188 13.3883 1.48713 13.384C1.21777 13.3705 0.963163 13.2615 0.772444 13.0781C0.581725 12.8946 0.46842 12.6497 0.454399 12.3905C0.453083 12.2655 0.47819 12.1415 0.528196 12.026C0.578203 11.9106 0.652067 11.8061 0.745308 11.7189L5.55985 7.08743L0.730763 2.44195C0.640147 2.35361 0.568971 2.2486 0.521487 2.13321C0.474004 2.01781 0.451187 1.89439 0.454399 1.77032C0.471511 1.51595 0.584277 1.27627 0.771642 1.09603C0.959007 0.91579 1.20816 0.807312 1.47258 0.790851C1.60629 0.784742 1.73987 0.805195 1.86499 0.850935C1.99012 0.896675 2.10411 0.966721 2.19985 1.05671Z"
                    fill="#757575" />
                </svg>
            </div>
        </div>
    </div>
    <script>
        var styles = [
            '<?php echo get_template_directory_uri(); ?>/js/jquery-ui/jquery-ui.min.css',
            '<?php echo get_template_directory_uri(); ?>/css/new/font-awesome.min.css',
    		        '<?php echo get_template_directory_uri(); ?>/css/tooltipster.bundle.min.css'
        ];
        for (var styleKey in styles) {
            var link = document.createElement('link');
            link.rel = 'stylesheet';
            link.type = 'text/css';
            link.href = styles[styleKey];
            document.head.append(link);
        }
    </script>
    

    <div class="shadow"></div>



    <?php 

    get_template_part('template-parts/singup'); ?>
    <?php get_template_part('template-parts/login'); ?>
    

    <?php wp_footer(); ?>
    <?php get_template_part('template-parts/buy'); ?>

</body>
</html>