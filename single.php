<?php 
get_header(); 
$post_date = get_the_date(); // Get the post date

// Convert the post date into a DateTime object
$post_date_obj = date_create($post_date);

// Format the month and date separately
$month = date_format($post_date_obj, 'M'); // Full month name
$day = date_format($post_date_obj, 'd');   // Day without leading zeros

// Output the formatted date


?>




<section class="soc-category" id="content">
    
    <?php get_template_part('template-parts/admin', 'breadcrumb'); ?>

    <div class="container">
        <div class="flex">
		<div id="main-content" class="site-content" style="transform: none;">
				<div class="container" style="transform: none;">		
					<div class="row" style="transform: none;">
		<div class="content-area col-xs-12 col-sm-8 col-md-9" style="padding-left: 0;">
			<div class="blog-posts">
							
							
												
					<div class="blog-posts post_large_image">
		
					<article class="post-23147 post type-post status-publish format-standard has-post-thumbnail hentry category-blog" style="background-color: #fff; margin-bottom: 20px;">

						<div class="entry-thumbnail">
							<a class="post-thumbnail" href="<?php the_permalink();?>">
								<?php the_post_thumbnail();?>
							</a>
						</div>

							
						<div class="post-content">
							<div class="post-heading" style="display: flex; ">
								<div class="psot-date">
									<span style="display: block; padding: 5px; background-color: #f7f7f7; align-items: center; text-align: center; margin-right: 8px; font-width: 500;"><?php echo $day;?></span>
									<span style="display: block; padding: 3px 5px; background-color: #337ab7; align-items: center; text-align: center; margin-right: 8px; color: #fff;"><?php echo $month;?></span>
								</div>
							
								<header class="entry-header" style="background-color: #fff;">
								
								<h2 class="entry-title" style="background-color: #fff; margin: 6px 0;"><a href="<?php the_permalink( );?>"><?php the_title();?></a></h2>
								<div class="comments-count">
									<span class="comments-link"><a href="#respond">Leave a comment<span class="screen-reader-text"> on How Trustpilot Reviews Affect Consumer Behavior?</span></a></span>
								</div>	
								</header><!-- .entry-header -->
							</div>
								
							
							<div class="entry-content"  style="padding-top: 5px;">
								<?php the_content();?>		
							</div>
						</div>
						
					</article><!-- #post-## -->
                    <?php comment_form(  );?>
					
					
		</div>
			
				
				<div id="sidebar" class="sidebar col-xs-12 col-sm-4 col-md-3 " style="position: relative; overflow: visible; box-sizing: border-box; min-height: 1px;">
                
				<div class="theiaStickySidebar" style="padding-top: 0px; padding-bottom: 1px; position: static; top: 30px; left: 1067.1px;"><div id="secondary" class="secondary">

						
					</div></div></div>
				</div>
			</div><!-- .content-area -->
		</div>
		</div>
    </div>
</section>

<?php get_footer() ?>