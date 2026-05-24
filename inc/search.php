<?php 
add_action('wp_ajax_header_search', 'header_search_callback');
add_action('wp_ajax_nopriv_header_search', 'header_search_callback');
function header_search_callback(){

    $keyword = $_POST['keyword'];
    

    $args['post_type'] = 'product';
    $args['post_status'] = 'publish';
    $args['posts_per_page'] = 10;
    $args['orderby'] = 'relevance';
    $args['order'] = 'ASC';
    $args['s'] = $keyword;


    $search_query = new WP_Query($args);


    if($search_query->have_posts()){
        
        echo '<ul>';
        while($search_query->have_posts()){
            $search_query->the_post();
             $product = wc_get_product( get_the_ID() );
            ?>
                <li>
                    <div class="search_item_left">
                        <a href="<?php the_permalink(); ?>"><?php the_post_thumbnail('thumbnail');  ?></a>
                    </div>
                    <div class="search_item_right">
                        <div class="search_item_title">
                            <h4><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h4>
                        </div>
                    </div>
                </li>
            <?php 

        } wp_reset_postdata();
        echo '</ul>';
    }else{
        ?>
            <div class="no_search_pro"><?php echo __('No results found!', 'bcmarket'); ?></div>
        <?php 
    }


    die();
}

?>