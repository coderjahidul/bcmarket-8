jQuery(document).ready(function($){



	 $(document).on('submit', '.update_item_status', function(){

	 	var data = $(this).serialize();
	 

        $.ajax({
            type: "POST",
            url: my_ajax_object.ajax_url,
            dataType : 'html',
            data: data,
            success: function(response) {
                console.log(response);
                alert('Status Changed!');

            }
        });

        return false;

    });

    $(document).on('click', '.approve_bids', function(){

        var id  = $(this).attr('data-id');
        var dis = $(this);
        dis.text('Approving');
        dis.css("pointer-events",'none');

        $.ajax({
            type: "POST",
            url: my_ajax_object.ajax_url,
            dataType : 'html',
            data: {
                action: 'approve_account_application', 
                id: id, 
            },
            success: function(response) {
                console.log(response);

                if(response == 'yes'){
                    dis.text('Approved'); 
                }else{
                    alert('Could not be approved. Please try again.');
                    dis.text('Approve'); 
                }
                
            }
        });

    });


     $(document).on('click', '.reject_bids', function(){

        var id  = $(this).attr('data-id');
        var reason  = $(this).prev('input').val();
        var dis = $(this);
        dis.text('Rejecting');
        dis.css("pointer-events",'none');
        console.log(reason);

        $.ajax({
            type: "POST",
            url: my_ajax_object.ajax_url,
            dataType : 'html',
            data: {
                action: 'reject_account_application', 
                id: id, 
                reason: reason, 
            },
            success: function(response) {
                console.log(response);

                if(response == 'yes'){
                    dis.text('Rejected'); 
                }else{
                    alert('Could not be approved. Please try again.');
                    dis.text('Reject'); 
                }
                
            }
        });

    });


    $(document).on('click', '.ban_account', function() {
        var id = $(this).attr('data-id');
        var reason = $(this).prevAll('input[name="reason"]').val();
        var ban_datetime = $(this).prevAll('input[name="ban_datetime"]').val();
        var dis = $(this);
        dis.text('Banning');
        dis.css("pointer-events", 'none');
        console.log(id, reason, ban_datetime);
    
        $.ajax({
            type: "POST",
            url: my_ajax_object.ajax_url,
            dataType: 'html',
            data: {
                action: 'ban_account', 
                id: id, 
                reason: reason,
                datetime: ban_datetime,
            },
            success: function(response) {
                console.log(response);
    
                if(response === 'yes'){
                    dis.text('Banned'); 
                } else {
                    alert('Could not be Banned. Please try again.');
                    dis.text('Ban'); 
                    dis.css("pointer-events", 'auto');
                }
            }
        });
    });
    

     $(document).on('click', '.rec_partner_account', function(){

        var dis = $(this);
        var id  = $(this).attr('data-id');
        dis.css("pointer-events",'none');
        console.log(id);

        $.ajax({
            type: "POST",
            url: my_ajax_object.ajax_url,
            dataType : 'html',
            data: {
                action: 'rec_partner_account', 
                id: id, 
            },
            success: function(response) {
                console.log(response);

                if(response == 'yes'){
                    dis.text('Reactivated'); 
                }else{
                    alert('Could not be Banned. Please try again.');
                    dis.text('Re-activate'); 
                }
                
            }
        });

    });


     $(document).on('change', '.select_term_child', function(){

        var cat  = $(this).val();
        var id  = $(this).attr('data-id');
        var parent  = $(this).attr('data-parent');
        

        $.ajax({
            type: "POST",
            url: my_ajax_object.ajax_url,
            dataType : 'html',
            data: {
                action: 'select_term_child', 
                cat: cat, 
                id: id, 
                parent: parent, 
            },
            success: function(response) {
                console.log(response);
                if(response == 'yes'){
                    
                    alert('Child Category Changed!');
                }else{
                    alert('Child Category could not be Changed!');
                }
                

            }
        });

    });

    $(document).on('submit', '.mark_as_paid_form', function(){

        var input_data = $(this).serialize();
        var dis = $(this);
    
        $.ajax({
            type: "POST",
            url: my_ajax_object.ajax_url,
            dataType : 'html',
            data: input_data,
            success: function(response) {
                console.log(response);
                dis.prev().text('Paid');
                dis.hide();
            }
        });

        return false;

    });

    $(document).on('submit', '.payment_comment_form', function(){

        var input_data = $(this).serialize();
        var dis = $(this);

        console.log(input_data);
        console.log(3333333333);
    
        $.ajax({
            type: "POST",
            url: my_ajax_object.ajax_url,
            dataType : 'html',
            data: input_data,
            success: function(response) {
                console.log(response);
                dis.children('.payment_comment_form_message').text('Updated');
            }
        });

        return false;

    });



    $(document).on('click', '.update_pro_descr', function(){

        $(this).next().slideToggle();


    });


    $(document).on('submit', '.update_pro_detail', function(){

        var input_data = $(this).serialize();
        var dis = $(this);

        console.log(input_data);
        console.log(3333333333);
    
        $.ajax({
            type: "POST",
            url: my_ajax_object.ajax_url,
            dataType : 'html',
            data: input_data,
            success: function(response) {
                console.log(response);
                dis.find('.pro_de_message').text('Updated');
            }
        });

        return false;

    });

  
     $(document).on('submit', '.similar_item_form', function(){

        var input_data = $(this).serialize();
        var dis = $(this);
 
        console.log(input_data);
        console.log(3333333333);
    
        $.ajax({
            type: "POST",
            url: my_ajax_object.ajax_url,
            dataType : 'html',
            data: input_data,
            success: function(response) {
                console.log(response);
                dis.find('.similar_item_form_message').text('Updated');
            }
        });

        return false;

    });

     $(document).on('click','.set_similar_item', function(){

        $(this).next().slideToggle();

     });

     $(document).on('keyup', '.item_search_inp', function(){

        $('.item_search_result').show();

        var keyword = $(this).val();
        var dis = $(this);

        $.ajax({
            type: "POST",
            url: my_ajax_object.ajax_url,
            dataType : 'html',
            data: {
                'action': 'similar_pro_find', 
                'keyword' : keyword
            },
            success: function(response) {
                console.log(response);
                dis.next().next('.item_search_result').html(response);
            }
        });

     });

     $(document).on('click', '.item_search_result li', function(){

        var title = $(this).text(); 
        var pro_id = $(this).attr('data-id'); 

        $(this).parents('.similar_item_container').find('input[name="similar_product_id"]').val(pro_id);
        $(this).parents('.similar_item_container').find('.item_search_inp').val(title);
        $(this).parents('.similar_item_container').prev().find('.similar_pro_name').text(pro_id + ' - ' + title);

        $('.item_search_result').hide();

     });

     $(document).on('submit', '.connect_to_item_form', function(){

        var data = $(this).serialize();
        $("#preloader").show();

        $.ajax({
            type: "POST",
            url: my_ajax_object.ajax_url,
            dataType : 'html',
            data: data,
            success: function(response) {
                console.log(response);
                $("#preloader").hide();
                alert('Item Id updated');
            }
        });

        return false

    });

    $('select[name="cat_by"]').on('change', function(){

        $('.search_cat_by').submit();

    });

    $(document).on('keyup', 'input[name="invalid_item"]', function(){
        var invalid_item = $(this).val();
        var order_id = $(this).parent('.invalid').attr('data-id');
        var partner_id = $(this).parent('.invalid').attr('data-partner_id');
        var pre_qty_partner_price = $(this).parent('.invalid').attr('data-pre_qty_partner_price');
        var dis = $(this);
        console.log('keyup');
    
        $.ajax({
            type: "POST",
            url: my_ajax_object.ajax_url,
            dataType: 'html',
            data: {
                action: 'update_invalid_item',
                invalid_item: invalid_item,
                order_id: order_id,
                partner_id: partner_id,
                pre_qty_partner_price: pre_qty_partner_price
            },
            success: function(response) {
                console.log(response);
                alert('Invalid Item Updated.');
            }
        });
    
        return false;
    });
    
});