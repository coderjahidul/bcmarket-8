<?php
if(!is_user_logged_in()){
    wp_safe_redirect( home_url('/my/') );
} 
/*
Template Name: Partner FAQ
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
            <h1>FAQ</h1>
            <?php get_template_part( 'partner/menu'); ?>
            <div class="body partner_pfaq">
                <h2 style="text-align: center;"><strong>Start working in The Affiliate cabinet.</strong></h2>
                <ol>
                    <li>
                        <span style="font-size: 14px;">
                            Check&nbsp;<a href="https://pvamarkets.com/en/partner/profile_settings">«Profile settings»</a> and&nbsp;indicate the&nbsp;<a href="http://prntscr.com/ju7bez" target="_blank">current e-wallets</a>&nbsp;for
                            withdrawals of funds. Please, pay attention to the withdrawals that will be made to them. The money will be lost and you will not get them if the wrong e-wallet or one to which you no longer have access is
                            indicated in your profile settings.
                        </span>
                    </li>
                    <li>
                        <span style="font-size: 14px;">Read <a href="https://pvamarkets.com/en/accounts_faq" target="_blank">The Accounts&nbsp;uploading requirements</a>.</span>
                    </li>
                    <li>
                        <span style="font-size: 14px;">
                            Use the&nbsp;<a href="https://pvamarkets.com/en/partner/upload">«Accounts Upload»</a>&nbsp;tab to start uploading your accounts. Fill in the data as it is&nbsp;
                            <a href="http://prntscr.com/ju7f0l" target="_blank">required</a>&nbsp;by the description hint. Be sure to indicate the format of the uploaded accounts. You can use the description of&nbsp;
                            <a href="http://prntscr.com/ju7flr" target="_blank">any product</a> in our store as an example.
                        </span>
                    </li>
                    <li>
                        <span style="font-size: 14px;">
                            Next, click the&nbsp;<a href="https://pvamarkets.com/en/partner">«My Applications»</a> tab and <a href="http://prntscr.com/juzdxm" target="_blank">upload&nbsp;your accounts</a>.&nbsp;If they are uploaded
                            successfully, the application status will be changed and get the&nbsp;<a href="http://prntscr.com/juzfjy" target="_blank">"Accounts are being checked"</a>&nbsp;one.<br />
                        </span>
                        <span style="font-size: 14px;">
                            <strong>Keep in mind</strong>,&nbsp;each new account you are going to upload must be written on a new line and the account data must be separated by colons. Duplicates are removed&nbsp;
                            <a href="http://prntscr.com/juzev7" target="_blank">automatically</a>.
                        </span>
                    </li>
                    <li>
                        <span style="font-size: 14px;">
                            The moderator will consider your application within a day (a little bit longer sometimes). If it is approved, you will receive a&nbsp;
                            <a href="http://prntscr.com/jvo9hv" target="_blank">confirmation letter</a>&nbsp;to the email you indicated in the registration form. The status will be changed to
                            <a href="http://prntscr.com/jvo9wg" target="_blank">"Accounts for sale"</a>.
                        </span>
                    </li>
                    <li>
                        <span style="font-size: 14px;">
                            You will be able to find your order in the correct&nbsp;<a href="http://prntscr.com/jvoau3" target="_blank">category</a>&nbsp;- its&nbsp;
                            <a href="http://prntscr.com/jvoazo" target="_blank">description</a>&nbsp;will be changed to match our store rules, as well as the price (it will become higher because it contains pvamarkets.com store margin).
                            <br />
                        </span>
                    </li>
                    <li>
                        <span style="font-size: 14px;"><strong>If you made a mistake</strong>&nbsp;adding a description and/or accounts you should remove the accounts from the application and just create a new one.</span>
                    </li>
                </ol>
                <h2 style="text-align: center;"><strong>Reward payments.</strong></h2>
                <ol>
                    <li>
                        <span style="font-size: 14px;">
                            You can check the sales of your accounts in the <a href="https://pvamarkets.com/en/partner">"My orders"</a> tab.&nbsp;The quantity of sold accounts and the expected reward will be&nbsp;
                            <a href="http://prntscr.com/jw95lk" target="_blank">changed</a>&nbsp;according to sold ones. The rejected accounts will receive the <a href="http://prntscr.com/jw95v8)" target="_blank">"Invalid"</a>&nbsp;status.
                            The reason why accounts are marked as Invalid is usually indicated in the tickets inside the applications.
                        </span>
                    </li>
                    <li>
                        <strong><span style="font-size: 14px;">Payments are made within&nbsp;72&nbsp;hours after you click the "Request payment" button.&nbsp;</span></strong>
                        <span style="font-size: 14px;">This payout period can't be less because Customers have a 48-hour purchase guarantee</span><span style="font-size: 14px;">.</span>
                    </li>
                    <li>
                        <span style="font-size: 14px;">All the payments&nbsp;for&nbsp;sold accounts will always be available in the&nbsp;<a href="https://pvamarkets.com/en/partner/payments">"Payouts"</a> tab.</span>
                    </li>
                    <li><span style="font-size: 14px;">Payouts in RUB are according to the rate of the Russia Central Bank on the date of payouts are made.</span></li>
                </ol>
                <p>&nbsp;</p>
                <h2 style="text-align: center;"><strong>The terms you agree when start working with the pvamarkets.com store.</strong></h2>
                <ol>
                    <li>
                        <span style="font-size: 14px;"><span style="font-size: 14px;">Withdrawals to any payment systems can be suspended by the store at any time without notice. For example, Advcash.</span></span>
                    </li>
                    <li>
                        <span style="font-size: 14px;">The withdrawal may be delayed if its amount is more than $500 or the equivalent.<br /></span>
                    </li>
                    <li>
                        <span style="font-size: 14px;">
                            Money transfer to the <strong>QIWI payment system</strong> is possible in case of your account has the "Main" identification status or "Professional" one. Pay attention to your QIWI e-wallet limits. Affiliate
                            program withdrawals must not exceed them. If the QIWI payment system does not accept the withdrawal and returns it for some reason, getting money back to the pvamarkets.com store balance is possible only with the
                            help of the Support and with a <strong>10% service charge</strong> of the payment amount.
                        </span>
                    </li>
                </ol>
                <h2 style="text-align: center;">&nbsp;</h2>
                <h2 style="text-align: center;">Possible irregularities in the sale of the accounts and the consequences.</h2>
                <table dir="ltr" style="margin-left: auto; margin-right: auto;" cellspacing="0" cellpadding="0" border="1">
                    <colgroup>
                        <col width="463" />
                        <col width="463" />
                    </colgroup>
                    <tbody>
                        <tr>
                            <td data-sheets-value="{">
                                <strong><span style="font-size: 14px;">Irregulates</span></strong>
                            </td>
                            <td data-sheets-value="{">
                                <strong><span style="font-size: 14px;">Consequences</span></strong>
                            </td>
                        </tr>
                        <tr>
                            <td data-sheets-value="{">
                                <span style="font-size: 14px;">Intentional advertising of your contact details in the accounts.</span><br />
                                <span style="font-size: 12px;"><em>Example: After the purchase is made the Customer receives the message including the information the same accounts may be bought directly from you.</em></span>
                            </td>
                            <td colspan="1" rowspan="2" data-sheets-value="{">
                                <div><span style="font-size: 14px;">The permanent ban and no withdrawals are possible. The ban of all new accounts of the breaker if they are registered in the future.</span></div>
                            </td>
                        </tr>
                        <tr>
                            <td data-sheets-value="{"><span style="font-size: 14px;">Intentional change of sold accounts passwords they are sold.</span></td>
                        </tr>
                        <tr>
                            <td data-sheets-value="{">
                                <span style="font-size: 14px;">Accidental advertising of your contact details in the accounts you sell.</span><br />
                                <span style="font-size: 12px;"><em>For example, you sold your account and for some reason forgot to delete your contact details from its description.</em></span>
                            </td>
                            <td colspan="1" rowspan="2" data-sheets-value="{">
                                <div>
                                    <span style="font-size: 14px;">1st offense – caution.</span><br />
                                    <span style="font-size: 14px;">2nd offense – the ban for 7 days.</span><br />
                                    <span style="font-size: 14px;">3rd offense – the permanent ban and no withdrawals are possible. The ban of all new accounts of the breaker if they are registered in the future.</span>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td data-sheets-value="{"><span style="font-size: 14px;">Accidental change of accounts passwords after they are sold.</span></td>
                        </tr>
                        <tr>
                            <td data-sheets-value="{">
                                <span style="font-size: 14px;">The deliberate ignoring of <a href="https://pvamarkets.com/ru/accounts_faq" target="_blank">the accounts uploading requirements</a>.</span><br />
                                <span style="font-size: 12px;"><em>For example, you upload banned, stolen, resold, and used accounts without the required accounts age, with the incorrect format and invalid email.</em></span>
                            </td>
                            <td data-sheets-value="{">
                                <span style="font-size: 14px;">1st offense – caution.</span><br />
                                <span style="font-size: 14px;">2nd offense - the permanent ban. The ban of all new accounts of the breaker if they are registered in the future.</span>
                            </td>
                        </tr>
                        <tr>
                            <td data-sheets-value="{">
                                <span style="font-size: 14px;">The accidental ignoring of the&nbsp;<a href="https://pvamarkets.com/ru/accounts_faq" target="_blank">accounts uploading requirements</a>.</span><br />
                                <span style="font-size: 12px;"><em>For example, you upload the accounts without the age of the required accounts and with the incorrect format.</em></span>
                            </td>
                            <td colspan="1" rowspan="2" data-sheets-value="{">
                                <div>
                                    <span style="font-size: 14px;">1st – 5th offense - caution.</span><br />
                                    <span style="font-size: 14px;">6th offense – the ban for 7 days.</span><br />
                                    <span style="font-size: 14px;">7th offense – the ban for 14 days</span><br />
                                    <span style="font-size: 14px;">8th offense – the ban for 21 days.</span><br />
                                    <span style="font-size: 14px;">9th offense – the ban for 30 days.</span><br />
                                    <span style="font-size: 14px;">10th offense – the permanent ban. The ban of all new accounts of the breaker if they are registered in the future.</span>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td data-sheets-value="{">
                                <span style="font-size: 14px;">The uploaded accounts' description does not match the stated one.</span><br />
                                <span style="font-size: 12px;"><em>For example, the description you indicate does not match the description of uploaded accounts.</em></span>
                            </td>
                        </tr>
                        <tr>
                            <td data-sheets-value="{">
                                <span style="font-size: 14px;">Registration of multi-accounts.</span><br />
                                <span style="font-size: 12px;"><em>For example, you decided to register one more account and sell the same product from both accounts.</em></span>
                            </td>
                            <td data-sheets-value="{">
                                <span style="font-size: 14px;">1st offense – caution for the first account and the permanent ban of the second one. </span><br />
                                <span style="font-size: 14px;">2nd offense – the permanent ban of both accounts. The ban of all new accounts of the breaker if they are registered in the future.</span>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</section>
<?php get_footer() ?>