<?php
/**
 * Shop breadcrumb
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/global/breadcrumb.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see         https://docs.woocommerce.com/document/template-structure/
 * @package     WooCommerce\Templates
 * @version     2.3.0
 * @see         woocommerce_breadcrumb()
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! empty( $breadcrumb ) ) {

	echo '<div class="block" itemscope="" itemtype="http://schema.org/BreadcrumbList" id="breadcrumbs">';
	$num = 0;
	foreach ( $breadcrumb as $key => $crumb ) {

		echo $before;

		if ( ! empty( $crumb[1] ) && sizeof( $breadcrumb ) !== $key + 1 ) {
			echo '<div itemprop="itemListElement" itemscope="" itemtype="http://schema.org/ListItem"><a  itemprop="item" href="' . esc_url( $crumb[1] ) . '"><span itemprop="name">' . esc_html( $crumb[0] ) . '</span><meta itemprop="position" content="'. $num .'"></a><span class="divider">/</span></div>';
		} else {
			echo '<div itemprop="itemListElement" itemscope="" itemtype="http://schema.org/ListItem">
                        <span class="current" itemprop="name">' . esc_html( $crumb[0] ) . '</span>
                        <meta itemprop="position" content="'. $num .'"></div>';
		}

		echo $after;

		if ( sizeof( $breadcrumb ) !== $key + 1 ) {
			//echo $delimiter;
		}
		$num++;
	}

	echo '</div>';

}
