<?php 
if(is_user_logged_in()){
    $current_user = wp_get_current_user();
    $roles = array('administrator', 'employee', 'wpseo_manager');
    $found = 0; 
    foreach($roles as $role){
        if(in_array( $role, (array) $current_user->roles) ){
            $found = 1;
           
        }
    }

    if($found == 0){
         wp_redirect( home_url('/my/') );
            exit(); 
    }
    
}else{
    wp_redirect( home_url('/my/') );
    exit(); 
}
get_header(); ?>

<section class="soc-category" id="content">
    
    <?php get_template_part('template-parts/admin', 'breadcrumb'); ?>

    <div class="container">
        <div class="flex">
            <h1><?php the_title(); ?></h1>
           
            <?php get_template_part( 'admin/admin', 'menu'); ?>
            <div class="body partner_cabinet">
                <form action="<?php echo esc_url(home_url('/')); ?>admin-interface/itemdata/" class="search_admin_col">
                    <select name="cat">
                        <option value="">All Cat</option>
                        <?php  
                            $terms = get_terms( array(
                                'taxonomy' => 'item_cat',
                                'hide_empty' => false,
                                'parent' => 0
                            ) );

                            if($terms){
                                foreach($terms as $term){
                                    ?>
                                        <option value="<?php echo $term->term_id; ?>" <?php if(isset($_GET['cat']) && $_GET['cat'] == $term->term_id){echo 'selected';} ?>><?php echo $term->name; ?></option>
                                    <?php 
                                }
                            }
                        ?>
                    </select>
                    <input type="text" placeholder="Search by ID" value="<?php if(isset($_GET['item_id'])){echo $_GET['item_id'];} ?>" name="item_id">
                    <input type="text" placeholder="Search by Title" value="<?php if(isset($_GET['title'])){echo $_GET['title'];} ?>" name="title">
                    <button type="submit" name="search">Search</button>
                </form>
             
                <?php 

                function increase_wp_search_query_length( $query ) {
                    if ( isset( $query->query_vars['s'] ) && strlen( $query->query_vars['s'] ) > 225 ) {
                        $query->query_vars['s'] = substr( $query->query_vars['s'], 0, 500 );
                    }
                }
                    $current_url = esc_url_raw($_SERVER['REQUEST_URI']);
        			$page_number = intval(preg_replace('/[^0-9]+/', '', $current_url), 10);
        
        			$paged = $page_number == 0 ? 1 : $page_number;
                    if(isset($_GET['search'])){
                        $args['posts_per_page'] = -1;
                        $args['post_type'] = 'item'; 
                        $args['post_status'] = array('pending', 'publish');
                         
                    }
                    else {
                       
                        $args['paged'] = $paged;
                        $args['posts_per_page'] = 10;
                        $args['post_type'] = 'item'; 
                        $args['post_status'] = array('pending', 'publish');  
                        
                    } 
                    
                    

                    
					$item_id = isset($_GET['item_id']) ? $_GET['item_id'] : null;
					$title = isset($_GET['title']) ? $_GET['title'] : null;
					$cat = isset($_GET['cat']) ? $_GET['cat'] : null;


                    if(!empty($cat)){
                        $args['tax_query'] = array(
                            array(
                                'taxonomy' => 'item_cat',
                                'terms'    => array($cat),
                            )
                        );
                    }  


                    if(!empty($item_id)){
                        $args['p'] = $item_id;
                    }

                    if(!empty($title)){
                        $args['s'] = $title;
                    }

                   
                    // add the filter before the query
                    add_filter( 'parse_query', 'increase_wp_search_query_length' );

                    $items_query = new WP_Query($args);


                    if($items_query->have_posts()) : ?>

                <table class="bids list zebra ac">
                    <tbody>
                        <tr>
                            <th>Id</th>
                            <th>Title</th>
                            <th>Date of order creation</th>
                            <th>Category</th>
                        </tr>

                        <?php 
                        while($items_query->have_posts()) : $items_query->the_post();
                            
                            ?>
                                <tr>
                                    <td><?php echo get_the_ID(); ?></td>
                                    <td><?php echo get_the_title(); ?></td>
                                    <td><?php echo get_the_date(); ?></td>
                                    <td>
                                        <?php 
                                            $term_obj_list = get_the_terms( get_the_ID(), 'item_cat' );
                                            echo join(', ', wp_list_pluck($term_obj_list, 'name'));
                                        ?>
                                         
                                     </td>
                                </tr>

                            <?php 
                            

                        endwhile; wp_reset_postdata(); ?>
                       
                    </tbody>
                </table>

                <?php else : echo 'No item found!'; endif;


                    remove_filter( 'parse_query', 'increase_wp_search_query_length' );

                 ?>

                <div class="pager_wrap" style="display:flex;justify-content:center;">
                    <?php 
                        $big = 999999999; // need an unlikely integer

                        	if ( $items_query->max_num_pages > 1 ) {
                            	echo '<div class="pagination">';
                            	$pagination_args = array(
                            		'base'      => get_pagenum_link(1) . '%_%',
                            		'format'    => 'page/%#%',
                            		'current'   => $paged,
                            		'total'     => $items_query->max_num_pages,
                            		'prev_text' => __('&laquo; Previous'),
                            		'next_text' => __('Next &raquo;'),
                            	);
                            	echo paginate_links( $pagination_args );
                            	echo '</div>';
                            }


                        ?>
                </div>
            </div>
        </div>
    </div>
</section>



<?php get_footer() ?>