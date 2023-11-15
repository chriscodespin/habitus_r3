<?php

get_header();

while(have_posts()) {
    the_post(); 
    ?>
     <div class="container mt-5 p-5 pb-2 bg-light rounded-3 border border-warning border-3">
        <h1 class=""><?php the_title() ?></h1>
        <?php

    the_content();
    
}
?>
    </div>
    <?php

get_footer();