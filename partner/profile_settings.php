<?php 
if(!is_user_logged_in()){
    wp_safe_redirect( home_url('/my/') );
}
/*
Template Name: Profile Settings
*/
get_header(); ?>
<section class="soc-category" id="content">
    <div class="wrap-breadcrumbs">
        <div class="container">
            <div class="flex">
                <div class="block" itemscope="" itemtype="http://schema.org/BreadcrumbList" id="breadcrumbs">
                    <div itemprop="itemListElement" itemscope="" itemtype="http://schema.org/ListItem">
                        <a href="/" itemprop="item">
                            <span itemprop="name">Home</span>
                            <meta itemprop="position" content="0" />
                        </a>
                        <span class="divider">/</span>
                    </div>
                    <div itemprop="itemListElement" itemscope="" itemtype="http://schema.org/ListItem">
                        <span class="current" itemprop="name">Partner interface</span>
                        <meta itemprop="position" content="1" />
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="container">
        <div class="flex">
            <h1>Partner interface</h1>
            <?php get_template_part( 'partner/menu'); ?>
            <div class="body partner_profile">
                <h1>Settings</h1>
                <?php 

                    $user_id = get_current_user_id();

                    $wallets = get_user_meta($user_id, 'wallets', true);

                ?>

                <form action="#" method="post" class="update_payment_profile" autocomplete="off">
                    <input type="hidden" name="action" value="update_payment_profile" />
                    <table class="form partner_payment_systems">
                        <tbody id="sortable" class="ui-sortable">
                            <tr style="padding-top: 20px;">
                                <td colspan="3"><span class="profile_title">eWallets for funds withdrawals:</span></td>
                            </tr>

                            <?php 

                            $bitcoin_min = get_theme_mod('bitcoin_min');
                            $litecoin_min = get_theme_mod('litecoin_min');
                            $etherium_min = get_theme_mod('etherium_min');
                            $usdt_min = get_theme_mod('usdt_min');

                            if($wallets) : foreach($wallets as $key => $value) :

                                $gat_name = '';
                                

                                if($key == 52){
                                    $gat_name = 'Litecoin (LTC)(min. $'. $litecoin_min .'):';
                                }
                                if($key == 74){
                                    $gat_name = 'USDT(TRC20) (min. $'. $usdt_min .'):';
                                }
                                if($key == 11){
                                    $gat_name = 'Bitcoin (BTC) (min. $'. $bitcoin_min .'):';
                                }
                                if($key == 60){
                                    $gat_name = 'Etherium (ETH) (min. $'. $etherium_min .'):';
                                }


                                ?>

                                <tr class="sort" id="60">
                                    <td class="handle ui-sortable-handle"><?php echo $gat_name; ?></td>
                                    <td>
                                        <input
                                            type="text"
                                            name="wallets[<?php echo $key; ?>]"
                                            value="<?php echo $value; ?>"
                                            autocomplete="off"
                                            readonly=""
                                            onblur="this.setAttribute('readonly', true);"
                                            onfocus="this.removeAttribute('readonly');"
                                            onfocusin="this.removeAttribute('readonly');"
                                            onfocusout="this.setAttribute('readonly', true);"
                                        />
                                    </td>
                                    <td class="handle ui-sortable-handle">⇕</td>
                                </tr>

                            <?php endforeach; else : ?>

                            <tr class="sort" id="52">
                                <td class="handle ui-sortable-handle">Litecoin (LTC)(min. $<?php echo $litecoin_min; ?>):</td>
                                <td>
                                    <input
                                        type="text"
                                        name="wallets[52]"
                                        value=""
                                        autocomplete="off"
                                        readonly=""
                                        onblur="this.setAttribute('readonly', true);"
                                        onfocus="this.removeAttribute('readonly');"
                                        onfocusin="this.removeAttribute('readonly');"
                                        onfocusout="this.setAttribute('readonly', true);"
                                    />
                                </td>
                                <td class="handle ui-sortable-handle">⇕</td>
                            </tr>
                            <tr class="sort" id="11">
                                <td class="handle ui-sortable-handle">Bitcoin (BTC) (min. $<?php echo $bitcoin_min; ?>):</td>
                                <td>
                                    <input
                                        type="text"
                                        name="wallets[11]"
                                        value=""
                                        autocomplete="off"
                                        readonly=""
                                        onblur="this.setAttribute('readonly', true);"
                                        onfocus="this.removeAttribute('readonly');"
                                        onfocusin="this.removeAttribute('readonly');"
                                        onfocusout="this.setAttribute('readonly', true);"
                                    />
                                </td>
                                <td class="handle ui-sortable-handle">⇕</td>
                            </tr>
                            <tr class="sort" id="74">
                                <td class="handle ui-sortable-handle">USDT(TRC20) (min. $<?php echo $usdt_min; ?>):</td>
                                <td>
                                    <input
                                        type="text"
                                        name="wallets[74]"
                                        value=""
                                        autocomplete="off"
                                        readonly=""
                                        onblur="this.setAttribute('readonly', true);"
                                        onfocus="this.removeAttribute('readonly');"
                                        onfocusin="this.removeAttribute('readonly');"
                                        onfocusout="this.setAttribute('readonly', true);"
                                    />
                                </td>
                                <td class="handle ui-sortable-handle">⇕</td>
                            </tr>
                            <tr class="sort" id="60">
                                <td class="handle ui-sortable-handle">Etherium (ETH) (min. $<?php echo $etherium_min; ?>):</td>
                                <td>
                                    <input
                                        type="text"
                                        name="wallets[60]"
                                        value=""
                                        autocomplete="off"
                                        readonly=""
                                        onblur="this.setAttribute('readonly', true);"
                                        onfocus="this.removeAttribute('readonly');"
                                        onfocusin="this.removeAttribute('readonly');"
                                        onfocusout="this.setAttribute('readonly', true);"
                                    />
                                </td>
                                <td class="handle ui-sortable-handle">⇕</td>
                            </tr>

                        <?php endif; ?>
                           
                        </tbody>
                    </table>
                    <p><button type="submit">Save</button></p>
                    <p><span class="red">*</span> - Required fields.</p>
                    <p class="update_suc"></p>
                </form>

            </div>
        </div>
    </div>
    <div></div>
</section>
<?php get_footer() ?>