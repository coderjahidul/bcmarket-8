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

            	<div class="search_user_by">
            		

					<form action="<?php echo esc_url(home_url(add_query_arg(array(), $wp->request))); ?>">
						<input type="text" placeholder="Search by ID or Email" name="query" value="<?php if(isset($_GET['query'])){echo $_GET['query']; } ?>">
						<button type="submit">Search</button>
						
					</form>
					<?php //get_template_part( 'custom-search-form' );?>


					
            	</div>
             
                <?php 
					
						
					$current_url = esc_url_raw($_SERVER['REQUEST_URI']);
					$page_number = intval(preg_replace('/[^0-9]+/', '', $current_url), 10);

					
					$paged = $page_number == 0 ? 1 : $page_number;
				

					// Construct query arguments based on conditions
					if (isset($_GET['query']) && !empty($_GET['query'])) {
						$args = array(
							'role__in' => array('Customer', 'Subscriber', 'Administrator', 'partner', 'wpseo_manager'),
							'search' => $_GET['query'],
							'search_columns' => array('ID', 'user_email', 'user_login'),
						);
						
					}
					else {
						$args = array(
							'role__in' => array('Customer', 'partner'),
							'number' => 10,
							'paged' => $paged,
							'orderby' => 'user_registered', // Sort by user registration date
    						'order' => 'DESC', // Sort in descending order
						);
					}
					
					// Create a new user query using the arguments
					$user_query = new WP_User_Query($args);

					// Fetch user results and other data
					$users = $user_query->get_results();
					$total_users = $user_query->get_total();
					$total_pages = ceil($total_users / 10);

					

					// Output your user list here

					

                    if($users) : ?>

		                <table class="bids list zebra ac">
		                    <tbody>
		                        <tr>
		                            <th>Id</th>
		                            <th>Balance</th>
		                            <th>Add</th>
		                            <th>Ban</th>
		                        </tr>

		                        <?php 
		                        foreach($users as $user) :
		                            
		                        	?>
		                        		<tr>
											<td><?php echo $user->ID; ?></td>
											<td>
												<?php echo woo_wallet()->wallet->get_wallet_balance( $user->ID ); ?>
											</td>
											<td>
												<form  class="deduct_partner_payment buyer_paym">
													<div class="deduct_payment_con">
														<input required type="text" name="cost">
														<button type="submit" class="btn btn-primary">Submit</button>
													</div>
													<input type="hidden" name="action" value="add_payment">
													<input type="hidden" name="buyer_id" value="<?php echo $user->ID; ?>">
													<div class="deduct_msg"></div>
												</form>
											</td>
											<td>
												<?php if(get_user_meta($user->ID, 'account_status' , true) == 'rejected') : ?>
													<button data-id="<?php echo $user->ID; ?>" class="btn  btn-danger ">Banned</button>
													<button data-id="<?php echo $user->ID; ?>" class="btn  btn-danger rec_partner_account">Re-activate</button>
												<?php else : ?>
													 <input class="ban_inp" type="text" placeholder="Add Ban Reason">
	                                            	<button data-id="<?php echo $user->ID; ?>" class="btn  btn-danger ban_account">Ban</button>
	                                            <?php endif; ?>
											</td>
										</tr>
		                        	<?php 

		                        endforeach; ?>
		                       
		                    </tbody>
		                </table>

                <?php else : echo 'No Buyer found!'; endif; ?>

                <div class="pager_wrap" style="display:flex;justify-content:center;">
					<?php
					if ($total_pages > 1) {
						$pagination_args = array(
							'base' => get_pagenum_link(1) . '%_%',
							'format' => 'page/%#%',
							'current' => $paged,
							'total' => $total_pages,
							'prev_text' => __('&laquo; Prev'),
							'next_text' => __('Next &raquo;'),
						);

						echo '<div class="pagination">';
						echo paginate_links($pagination_args);
						echo '</div>';
					}
					?>
                </div>
            </div>
        </div>
    </div>
</section>

<?php get_footer() ?>