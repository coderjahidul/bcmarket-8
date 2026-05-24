jQuery(document).ready(function($) {

    var allorders = Object.values(JSON.parse($(".all-orders").val()));
    var blank_arr = [];
    allorders.map(function(e) {
        blank_arr.push(parseInt(e));
    });

    $("#order_id").on('change', function() {
        var valIS = $(this).val();
        var pageUrl = window.location.origin + "/your-account/tickets";
        $(".new-ticket-submit").attr("disabled", false);

        allorders.map(function(e) {
            $(".new-ticket-submit").addClass("clicked");
            if (valIS == parseInt(e)) {
                $(".new-ticket-submit").attr("disabled", "disabled");
                $(".new-ticket-submit").addClass("error-color"); // Add this line to apply the error color
                Swal.fire({
                    icon: 'error',
                    title: 'Oops...',
                    text: 'You already have an active ticket for this order Id. Please reply to this ticket',
                    footer: "<a href='" + pageUrl + "'>Go Ticket Page</a>"
                })
            }
        });

    });

    // Add a click event listener to the button to toggle the class
    $(".new-ticket-submit").on('click', function() {
        $(this).toggleClass("clicked");
    });

});

jQuery(document).ready(function($) {
    $('#new_ticket_form').submit(function(e) {
        e.preventDefault(); // Prevent the form from submitting normally

        // Get form data
        var formData = $('#new_ticket_form').serialize();

        // Perform AJAX request to create a new ticket
        $.ajax({
            type: 'POST',
            url: ajaxurl, // WordPress AJAX handler URL
            data: {
                action: 'create_new_ticket',
                form_data: formData
            },
            success: function(response) {
                console.log('Ticket created successfully');
                // Add any further actions or UI updates here
                $('#ticket_result').html('Ticket created successfully');
            },
            error: function(error) {
                console.error('Error creating ticket');
                $('#ticket_result').html('Error creating ticket');
            }
        });
    });
});
