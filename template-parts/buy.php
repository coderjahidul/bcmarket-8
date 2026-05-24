<script>
    jQuery(document).ready(function($){

        $(document).on('click', '.basket-button', function(){

            $('body').append('<div id="buy_dialog"></div>');

            var prod = $(this).attr('data-id');
            $('body').append('<img id="buy-dialog-loader" src="<?php echo get_template_directory_uri(); ?>/img/buy-dialog-loader.gif">');
            $('body').css('opacity', '0.5');

            $.ajax({
                url: "<?php echo admin_url('admin-ajax.php'); ?>",
                type: 'POST',
                data: {
                    'action' : 'buy_product', 
                    'item_id' : prod,
                },
                dataType: 'html',
                success: function(response) { 
                    $('#buy-dialog-loader').remove();
                    $('#buy_dialog').html(response);
                    $('body').css('opacity', '1');
                    

                    var a = $("#buy_dialog").dialog({
                        autoOpen: !0,
                        modal: !0,
                        dialogClass: "buy_dialog",
                        resizable: !1,
                        width: "auto",
                        buttons: [],
                        open: function () {
                            $(".ui-widget-overlay").click(function () {
                                a.remove();
                            });
                        },
                        close: function () {
                            $("#buy_dialog").remove();
                            
                        },
                    });

                    var max_qty = $('input[name="max_qty"]').val();

                    $("#basket_qty").spinner({
                        min: 1,
                        max: max_qty,
                        change: function () {
                            updatePrice();
                        },
                        stop: function () {
                            updatePrice();
                        },
                    });

                    $('.ui-dialog-title').html('Buy');
                }

            }); 

        });

        function updatePrice(){

            var qty = $('#basket_qty').val();
            var item_price = $('#item_price').val();
            var item_total = parseFloat(qty * item_price);


            $('#price').html('<span class="woocommerce-Price-amount amount"><bdi><span class="woocommerce-Price-currencySymbol">$</span>' + item_total +'</bdi></span>');


        }


        $(document).on('keyup', '.submit_buy input[name="coupon"]', function(){

            var code = $(this).val();

            $.ajax({
                url: "<?php echo admin_url('admin-ajax.php'); ?>",
                type: 'POST',
                data: {
                    action : 'apply_coupon_code', 
                    code : code,
                },
                dataType: 'html',
                success: function(response) { 
                    console.log(response);
                    $('#price').html(response);
        
                }

            });

        });

        
        $(document).on("click", "#item_partners li:not(.preloader)", function () {
            $("#item_partners li").removeClass("active"),
            $(this).addClass("active"),
            //orders.price(),
            $("#step1").addClass("collapsed"),
            $(".step_in_process").hide(),
            $(".step_done").show(),
            $(".step_1").addClass("done"),
            $(".step_2").addClass("active"),
            $("#step2").removeClass("collapsed");



            var product_id = $(this).attr('data-id');
            var price = $(this).attr('data-price');
            var qty = $(this).attr('data-qty');
            $('input[name="item_id"]').val(product_id);
            $('input[name="price"]').val(price);
            $('input[name="max_qty"]').val(qty);

            $('#price').html('<span class="woocommerce-Price-amount amount"><bdi><span class="woocommerce-Price-currencySymbol">$</span>' + price +'</bdi></span>');


            $("#basket_qty").spinner({
                min: 1,
                max: qty,
                change: function () {
                    updatePrice();
                },
                stop: function () {
                    updatePrice();
                },
            });

        });

        $(document).on("click", "#step1 .card-header", function () {
            $("#step1").removeClass("collapsed"), 
            $(".step_done").hide(), 
            $(".step_in_process").show(), 
            $(".step_1").removeClass("done"), 
            $(".step_2").removeClass("active"), 
            $("#step2").addClass("collapsed");
        }); 

        $(document).on('submit', '.submit_buy', function(){

            var data = $('.submit_buy').serialize(); 
            console.log(data);
            $('#creating_order').show();

            $.ajax({
                url: "<?php echo admin_url('admin-ajax.php'); ?>",
                type: 'POST',
                data: data,
                dataType: 'html',
                success: function(response) { 
                    console.log(response);

                    window.location.replace(response);
                }

            }); 

            return false;

        });

        


    });
</script>