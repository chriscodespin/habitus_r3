
<?php

$post = get_post( $_GET['post_id']);

//INITIALIZE DUPLICATE POST PLUGIN
//If this doesn't work comment out is_admin() check in dubplicate-post-admin.php
//AND at bottom of duplicate-post.php
duplicate_post_admin_init();

//Duplicate Post
if(!isset($_POST['cloned_post_back']) && empty($_POST['cloned_post_back'])) {
    $new_post_id = duplicate_post_create_duplicate( $post, 'publish' );
}

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
                        'post_id' =>  $new_post_id,
                        'post_title'  => true,
                        'field_groups' => array(5),
                        'return' => '%post_url%',
                        'html_before_fields' => '<input type="hidden" name="cloned_post_back" value="true"/>',
                    ));
                    ?>
				</div><!-- #content -->
			</div><!-- #primary -->
		</section> 
    </div>
    
<?php
get_footer();