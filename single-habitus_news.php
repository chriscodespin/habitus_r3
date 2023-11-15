<?php
/*
Template Name: News Item
*/

get_header();

?>

<div class="container mt-5 p-5 bg-light rounded-3 border border-warning border-3">
    <?php
        while(have_posts()) {
            the_post();  ?>
            <h1><?php the_title() ?></h1>
            <div><?php the_content() ?></div>
        <?php
        } //end while ?>

 </div> <!--end container -->