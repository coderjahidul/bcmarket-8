<?php 
// Customizer API 
function bcmarket_customize_register($wp_customize) { 
    
    
    // Header Options
    $wp_customize->add_section("header", array(
        "title" => __("Header Options", "designspiegels"),
        "priority" => 30,
    ));

        // Header Fields
        $wp_customize->add_setting("top_left_text", array(
            "default" => "",
            "transport" => "postMessage",
        ));
        $wp_customize->add_control( 'top_left_text', array(
              'label' => __( 'Top Left Text' ),
              'type' => 'text',
              'section' => 'header',
            )  
        );

        $wp_customize->add_setting("top_left_text_mobile", array(
            "default" => "",
            "transport" => "postMessage",
        ));
        $wp_customize->add_control( 'top_left_text_mobile', array(
              'label' => __( 'Top Left Text Mobile' ),
              'type' => 'text',
              'section' => 'header',
            )  
        );

        $wp_customize->add_setting("header_telegram_link", array(
            "default" => "",
            "transport" => "postMessage",
        ));
        $wp_customize->add_control( 'header_telegram_link', array(
              'label' => __( 'Header Telegram Link' ),
              'type' => 'text',
              'section' => 'header',
            )  
        );

        $wp_customize->add_setting("header_telegram_text", array(
            "default" => "",
            "transport" => "postMessage",
        ));
        $wp_customize->add_control( 'header_telegram_text', array(
              'label' => __( 'Header Telegram Text' ),
              'type' => 'text',
              'section' => 'header',
            )  
        );
        $wp_customize->add_setting("header_skype", array(
            "default"   => "",
            "transport" => "postMessage",
        ));
        $wp_customize->add_control("header_skype", array(
            "label" => __("Header Skype Link"),
            "type"  => "text",
            "section"  => "header",
        ));
        // $wp_customize->add_setting("header_whatsapp", array(
        //     "default"   => "",
        //     "transport" => "postMessage",
        // ));
        // $wp_customize->add_control("header_whatsapp", array(
        //     "label" => __("Header Whatsapp Link"),
        //     "type"  => "text",
        //     "section"  => "header",
        // ));

        $wp_customize->add_setting("header_important_message", array(
            "default" => "",
            "transport" => "postMessage",
        ));
        $wp_customize->add_control( 'header_important_message', array(
              'label' => __( 'Header Important Message' ),
              'type' => 'textarea',
              'section' => 'header',
            )  
        );

     // Footer Options
    $wp_customize->add_section("footer", array(
        "title" => __("Footer Options", "designspiegels"),
        "priority" => 30,
    ));

        // Footer Fields
        $wp_customize->add_setting("footer_telegram", array(
            "default" => "",
            "transport" => "postMessage",
        ));
        $wp_customize->add_control( 'footer_telegram', array(
            'label' => __( 'Footer Telegram' ),
            'type' => 'text',
            'section' => 'footer',
        ));

        $wp_customize->add_setting("footer_email", array(
            "default" => "",
            "transport" => "postMessage",
        ));
        $wp_customize->add_control( 'footer_email', array(
              'label' => __( 'Footer Email' ),
              'type' => 'text',
              'section' => 'footer',
            )  
        );
        $wp_customize->add_setting("footer_skype", array(
            "default" => "",
            "transport" => "postMessage",
        ));
        $wp_customize->add_control( 'footer_skype', array(
            'label' => __( 'Footer Skype Link' ),
            'type' => 'text',
            'section' => 'footer',
        ));
        // $wp_customize->add_setting("footer_whatsapp", array(
        //     "default"   => "",
        //     "transport" => "postMessage",
        // ));
        // $wp_customize->add_control("footer_whatsapp", array(
        //     "label" => "Footer Whatsapp Link",
        //     "type"  => "text",
        //     "section" => "footer",
        // ));

         $wp_customize->add_setting("footer_copyright", array(
            "default" => "",
            "transport" => "postMessage",
        ));
        $wp_customize->add_control( 'footer_copyright', array(
              'label' => __( 'Footer Copyright' ),
              'type' => 'text',
              'section' => 'footer',
            )  
        );
	
		$wp_customize->add_setting("admin_percentage", array(
            "default" => "",
            "transport" => "postMessage",
        ));
        $wp_customize->add_control( 'admin_percentage', array(
              'label' => __( 'Enter Admin percentage' ),
              'type' => 'text',
              'section' => 'woocommerce_checkout',
            )  
        );

        $wp_customize->add_setting("usdt_min", array(
            "default" => "",
            "transport" => "postMessage",
        ));
        $wp_customize->add_control( 'usdt_min', array(
              'label' => __( 'USDT Min' ),
              'type' => 'text',
              'section' => 'woocommerce_checkout',
            )  
        );

        $wp_customize->add_setting("etherium_min", array(
            "default" => "",
            "transport" => "postMessage",
        ));
        $wp_customize->add_control( 'etherium_min', array(
              'label' => __( 'Etherium  Min' ),
              'type' => 'text',
              'section' => 'woocommerce_checkout',
            )  
        );

        $wp_customize->add_setting("litecoin_min", array(
            "default" => "",
            "transport" => "postMessage",
        ));
        $wp_customize->add_control( 'litecoin_min', array(
              'label' => __( 'Litecoin Min' ),
              'type' => 'text',
              'section' => 'woocommerce_checkout',
            )  
        );

        $wp_customize->add_setting("bitcoin_min", array(
            "default" => "",
            "transport" => "postMessage",
        ));
        $wp_customize->add_control( 'bitcoin_min', array(
              'label' => __( 'Bitcoin  Min' ),
              'type' => 'text',
              'section' => 'woocommerce_checkout',
            )  
        );
   
}
add_action("customize_register","bcmarket_customize_register");