<?php 
// buyer-history.php
function buyer_history_page_content() {
    ?>
    <style>
        .history {
            padding-top: 20px;
        }
        .history .tablenav-pages a {
            text-decoration: none;
        }
        .history .tablenav-pages .current{
            background-color: #fff;
        }
        .history .tablenav-pages .page-numbers {
            padding: 5px 10px;
            border: solid 1px #ccc;
            border-radius: 5px;
        }
    </style>
    <div class="wrap">
        <h2><?php echo esc_html(get_admin_page_title()); ?></h2>

        <!-- Search Form -->
        <form method="post">
            <p class="search-box">
                <label class="screen-reader-text" for="user-search-input">Search Buyer:</label>
                <input type="search" id="user-search-input" name="user_search" value="<?php echo isset($_POST['user_search']) ? esc_attr($_POST['user_search']) : ''; ?>">
                <input type="submit" id="search-submit" class="button" value="Search Partner">
            </p>
        </form>

        <?php
        // Query and display user information
        $search_term = isset($_POST['user_search']) ? sanitize_text_field($_POST['user_search']) : '';
        $current_page = max(1, isset($_GET['paged']) ? absint($_GET['paged']) : 1);
        // Use get_query_var as a fallback
        $current_page = max(1, get_query_var('paged', $current_page));

        $users_per_page = 100; // Change this as needed
        $offset = ($current_page - 1) * $users_per_page;

        $user_query = new WP_User_Query(array(
            'role__in' => array('customer', 'subscriber','partner'),
            'orderby' => 'login',
            'order' => 'ASC',
            'search' => '*' . $search_term . '*',
            'number' => $users_per_page,
            'offset' => $offset,
        ));

        if (!empty($user_query->get_results())) {
            ?>
            <div class="tablenav top history">
                <?php
                // Top Pagination
                $total_users = $user_query->get_total();
                $total_pages = ceil($total_users / $users_per_page);

                $pagination_args_top = array(
                    'base' => add_query_arg('paged', '%#%'),
                    'format' => '',
                    'total' => $total_pages,
                    'current' => $current_page,
                    'prev_text' => 'Prev',
                    'next_text' => 'Next',
                );

                echo '<div class="tablenav-pages">' . paginate_links($pagination_args_top) . '</div>';
                ?>
            </div>

            <table class="wp-list-table widefat striped">
                <thead>
                    <tr>
                        <th><?php echo esc_html( 'User ID'); ?></th>
                        <th><?php echo esc_html( 'Email' );?></th>
                        <th><?php echo esc_html( 'Total Deposit' );?></th>
                        <th><?php echo esc_html( 'Total Purchase' );?></th>
                        <th><?php echo esc_html( 'Total Orders' );?></th>
                        <th><?php echo esc_html( 'Wallet Balance' );?></th>
                        <th><?php echo esc_html( 'Joined Date' );?></th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    function get_customer_total_spend($user_id) {
                        $total_spend = 0;
                    
                        $customer_orders = wc_get_orders(array(
                            'customer' => $user_id,
                            'status'   => array('completed', 'processing'),
                            'limit'    => -1
                        ));
                    
                        foreach ($customer_orders as $order) {
                            $total_spend += $order->get_total();
                        }
                    
                        return $total_spend;
                    }

                    function column_total_diposit($user_id) {
                        $args = array(
                            'user_id'    => $user_id,
                            'where'      => array(
                                array(
                                    'key'   => 'type',
                                    'value' => 'credit',
                                ),
                            ),
                            'where_meta' => array(
                                array(
                                    'key'   => '_type',
                                    'value' => 'credit_purchase',
                                ),
                            ),
                        );
                    
                        $transactions  = get_wallet_transactions($args);
                        $total_diposit = array_sum(wp_list_pluck($transactions, 'amount'));
                    
                        return wc_price($total_diposit, woo_wallet_wc_price_args());
                    }
                    

                    foreach ($user_query->get_results() as $user) {
                        $user_id = $user->ID;
                        $username = $user->user_login;
                        $name = $user->display_name;
                        $email = $user->user_email;
                        $roles = implode(', ', $user->roles);
                        $wallet_balance = woo_wallet()->wallet->get_wallet_balance($user_id);
                    
                        // Assuming that the following functions are correctly defined
                        $total_deposit = column_total_diposit($user_id);
                        // Check if the function exists before calling it
                        if (function_exists('get_customer_total_spend')) {
                            $total_spend = get_customer_total_spend($user_id);
                        } else {
                            $total_spend = 0; // Set a default value or handle the situation accordingly
                        }
                        $total_orders = count(wc_get_orders(array(
                            'customer' => $user_id,
                            'status'   => array('completed','processing'),
                            'limit'    => -1
                        )));
                        
                    
                        echo '<tr>';
                        echo '<td>' . $user_id . '</td>';
                        echo '<td>' . $email . '</td>';
                        echo '<td>' . $total_deposit . '</td>';
                        echo '<td>' . wc_price($total_spend) . '</td>';
                        echo '<td>' . $total_orders . '</td>';
                        echo '<td>' . $wallet_balance . '</td>';
                        echo '<th>' . $user->user_registered . '</th>';
                        echo '</tr>';
                    }
                
                    ?>
                </tbody>
            </table>

            <div class="tablenav bottom history">
                <?php
                // Bottom Pagination
                $pagination_args_bottom = array(
                    'base' => add_query_arg('paged', '%#%'),
                    'format' => '',
                    'total' => $total_pages,
                    'current' => $current_page,
                    'prev_text' => 'Prev',
                    'next_text' => 'Next',
                );

                echo '<div class="tablenav-pages">' . paginate_links($pagination_args_bottom) . '</div>';
                ?>
            </div>
            <?php
        } else {
            echo '<p>No Partner found</p>';
        }
        ?>
    </div>
    <?php
}