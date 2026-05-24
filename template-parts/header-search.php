<div class="head-search">
    <input class="search-input" type="search" name="keyword" placeholder="Search for accounts" autocomplete="off">
    <div class="head_search_result" style="display:none"></div>
</div>

<script>
jQuery(document).ready(function($) {

    const $input = $('.search-input');
    const $results = $('.head_search_result');

    // Show placeholder message on focus
    $input.on('focus', function() {
        $(this).addClass('focused');
        if ($(this).val().length < 1) {
            $results.html('Please enter 1 or more characters').show();
        }
    });

    // AJAX search on keyup
    $input.on('keyup', function() {
        var keyword = $(this).val().trim();

        if (keyword.length < 1) {
            $results.html('Please enter 1 or more characters').show();
            return; // stop AJAX if empty
        }

        $results.html('Searching...').show();

        $.ajax({
            url: "<?php echo admin_url('admin-ajax.php'); ?>",
            type: 'POST',
            data: {
                action: 'header_search',
                keyword: keyword,
            },
            dataType: 'html',
            success: function(response) {
                $results.html(response);
            },
            error: function() {
                $results.html('Error loading results.');
            }
        });
    });

    // Keep results visible when hovering over them
    $results.on('mouseenter', function() {
        $results.data('hover', true);
    }).on('mouseleave', function() {
        $results.data('hover', false);
    });

    // Hide results if clicked outside input and results
    $(document).on('click', function(e) {
        if (!$(e.target).closest($input).length && !$(e.target).closest($results).length) {
            $results.hide();
            $input.removeClass('focused');
        }
    });

});
</script>
