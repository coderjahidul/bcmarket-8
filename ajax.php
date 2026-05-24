<?php 
/*
Template Name: Ajax
*/
get_header(); ?>


<div class="container" style="max-width: 500px; margin:auto">
	<form class="ajax_form">
	
		<div class="form-group">
			<input type="text" class="form-control" name="first_name" placeholder="First name">
		</div>
		<div class="form-group">
			<input type="text" class="form-control" name="last_name" placeholder="Last name">
		</div>
		<div class="form-group">
			<input type="text" class="form-control" name="email" placeholder="enter your email">
		</div>
		<div class="form-group">
			<button type="submit" class="btn btn-primary">Submit</button>
		</div>
		<div class="respon"></div>
		<div class="respon_err"></div>
	</form>
</div>

<script>
	jQuery(document).ready(function($){

		$('.ajax_form').on('submit', function(e){
			e.preventDefault();

			var first_name = $('input[name="first_name"]').val();
			var last_name = $('input[name="last_name"]').val();
			var email = $('input[name="email"]').val();

			$('button').css('pointer-events', 'none');

			$.ajax({

				url: '<?php echo admin_url('admin-ajax.php'); ?>', 
				type: "POST", 
				data : {
					f_name : first_name,
					l_name : last_name,
					email_address : email,
					action: 'ajax_process'
				}, 
				dataType: 'html',
				success: function(respsonse){

					if(respsonse == '1'){
						
						$('button').css('pointer-events', 'inherit');
						$('.ajax_form')[0].reset();
						$('.respon').html('You form has success. ');

							
					}else{
						$('button').css('pointer-events', 'inherit');
						$('.respon_err').html(respsonse);
					}

						

				}

			});

		});

	});
</script>

<?php get_footer(); 
