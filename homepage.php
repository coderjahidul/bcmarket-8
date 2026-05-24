<?php 
/*
Template Name: Home
*/
get_header(); ?>
        <section class="soc-category" id="content">
            <div class="wrap-breadcrumbs">
                <div class="container">
                    <div class="flex">
                        <div class="block" itemscope itemtype="http://schema.org/BreadcrumbList" id="breadcrumbs">
                            <div itemprop="itemListElement" itemscope itemtype="http://schema.org/ListItem">
                                <span class="current" itemprop="name">Home</span>
                                <meta itemprop="position" content="0">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="container">
                <div class="flex">

                    <?php get_template_part('template-parts/home', 'items'); ?>
                   
                    <?php the_content(); ?>

                </div>
            </div>
        </section>
        
 <?php get_footer(); ?>