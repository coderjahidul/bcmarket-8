<?php get_header(); ?>
<section class="soc-category" id="content">
    <div class="wrap-breadcrumbs">
        <div class="container">
            <div class="flex">
                <div class="block" itemscope="" itemtype="http://schema.org/BreadcrumbList" id="breadcrumbs">
                    <div itemprop="itemListElement" itemscope="" itemtype="http://schema.org/ListItem">
                        <a href="<?php echo esc_url(home_url('/')); ?>" itemprop="item">
                            <span itemprop="name">Home</span>
                            <meta itemprop="position" content="0" />
                        </a>
                        <span class="divider">/</span>
                    </div>
                    <div itemprop="itemListElement" itemscope="" itemtype="http://schema.org/ListItem">
                        <span class="current" itemprop="name"><?php the_title(); ?></span>
                        <meta itemprop="position" content="1" />
                    </div>
                </div>
            </div>
        </div>
    </div>
    <?php while(have_posts()) : the_post(); ?>
	    <div class="container">
	        <div class="flex">
	        	<h1><?php the_title(); ?></h1>
	            <?php the_content(); ?>
	        </div>
	    </div>
	<?php endwhile; wp_reset_postdata(); ?>
</section>


<?php get_footer(); ?>