<?php
/**
 * The Template for displaying product archives, including the main shop page which is a post type archive
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/archive-product.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see https://docs.woocommerce.com/document/template-structure/
 * @package WooCommerce\Templates
 * @version 3.4.0
 */

defined( 'ABSPATH' ) || exit;

get_header( 'shop' );

/**
 * Hook: woocommerce_before_main_content.
 *
 * @hooked woocommerce_output_content_wrapper - 10 (outputs opening divs for the content)
 * @hooked woocommerce_breadcrumb - 20
 * @hooked WC_Structured_Data::generate_website_data() - 30
 */
//do_action( 'woocommerce_before_main_content' );
$term = get_queried_object();
$parent = ( isset( $term->parent ) ) ? get_term_by( 'id', $term->parent, 'product_cat' ) : false;

?>
<section class="soc-category" id="content">
    <div class="wrap-breadcrumbs">
        <div class="container">
            <div class="flex">
                <div class="block" itemscope="" itemtype="http://schema.org/BreadcrumbList" id="breadcrumbs">
                    <div itemprop="itemListElement" itemscope="" itemtype="http://schema.org/ListItem">
                        <a href="<?php echo esc_url(home_url( '/' )); ?>" itemprop="item">
                            <span itemprop="name">Home</span>
                            <meta itemprop="position" content="0">
                        </a>
                        <span class="divider">/</span>
                    </div>
                    <?php if( $parent ): ?>
	                    <div itemprop="itemListElement" itemscope="" itemtype="http://schema.org/ListItem">
	                        <a href="<?php echo get_term_link($parent); ?>" itemprop="item">
	                            <span itemprop="name"><?php echo $parent->name; ?></span>
	                            <meta itemprop="position" content="1">
	                        </a>
	                        <span class="divider">/</span>
	                    </div>
	                <?php endif; ?>
                    <div itemprop="itemListElement" itemscope="" itemtype="http://schema.org/ListItem">
                        <span class="current" itemprop="name"><?php woocommerce_page_title(); ?></span>
                        <meta itemprop="position" content="2">
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="container">
        <div class="flex">
        	<?php if( $parent ): ?>
        		<h1><?php echo $parent->name; ?> accounts for sale - <?php woocommerce_page_title(); ?></h1>
        	<?php else : ?>
            	<h1><?php woocommerce_page_title(); ?></h1>
            <?php endif; ?>
            <div class="soc-bl">
                <div class="soc-title" data-help="Softregs are accounts registered by a special program automatically">
                    <h2 class="soc-name" data-id="10"><?php woocommerce_page_title(); ?></h2>
                    <p class="soc-qty">In stock</p>
                    <p class="soc-cost-label"></p>
                    <p class="soc-cost">Price</p>
                    <p class="soc-control"></p>
                </div>

                <?php 
	                if ( wc_get_loop_prop( 'total' ) ) {
						while ( have_posts() ) {
							the_post(); 
							wc_get_template_part( 'content', 'product' );
	                	}
					} 
				?>
                
            </div>
            <div class="recat">
                <?php echo get_term_meta(get_queried_object_id(), 'long_description', true); ?>
            </div>
        </div>
    </div>
    
</section>


<?php get_footer( 'shop' );
