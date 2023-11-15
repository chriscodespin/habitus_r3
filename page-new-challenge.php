<?php

get_header();
?>
     <div class="container mt-5 p-5 pb-2 bg-light rounded-3 border border-warning border-3">
        <h1 class=""><?php the_title() ?></h1>
        
		<section>
			<div id="primary" class="content-area">
				<div id="content">
					<?php 
						if (!pmpro_hasMembershipLevel('Pro'))
						{
							if (get_user_meta(get_current_user_id(), 'user_at_max_limit', true)) {
								?>
								<div class="mb-5">
									You are only allowed to have 3 active challenges with the free version of Habitus.
								</div>
								<a href="<?php echo site_url('/membership-account/membership-checkout/'); ?>" class="btn btn btn-sm btn-primary text-center align-self-end">Go Pro</a> and get unlimited access.
							<?php
							} else {
								acf_form('new-challenge');
							}
						} else {
							acf_form('new-challenge');
						}
					 ?>
				</div><!-- #content -->
			</div><!-- #primary -->
		</section> 
    </div>
    
<?php
get_footer();