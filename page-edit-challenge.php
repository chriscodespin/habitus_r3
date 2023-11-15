<?php

get_header();
?>
     <div class="container mt-5 p-5 pb-2 bg-light rounded-3 border border-warning border-3">
        <h1 class=""><?php the_title() ?></h1>
        
		<section>
			<div id="primary" class="content-area">
				<div id="content">
                    <?php 
                    acf_form(array(
                        //'id' => 'update-challenge',
                        'post_id' => $_GET['post_id'],
                        'post_title'  => true,
                        'field_groups' => array(5),
                        'return' => '%post_url%'));
                    ?>
				</div><!-- #content -->
			</div><!-- #primary -->
		</section> 
    </div>
    
<?php
get_footer();