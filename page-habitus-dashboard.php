<?php

get_header();

if (isset($_GET['opt_out']) && isset($_GET['post_id'])) {

    habitus_theme_challenge_opt_out($_GET['post_id']);
}


$active_challenges = habitus_theme_get_active_challenges();
$user_messages = habitus_get_user_msg_inbox();

?>

<div class="container mt-5 p-5 bg-light rounded-3 border border-warning border-3">
    <div class="row">
        <div class="col-12 order-first"><!--my challenges -->
            <h3>My Challenges</h3>
            <?php
            if (!$active_challenges->have_posts()) {
                ?>
                <div class="mb-3">
                    <a href="<?php echo site_url('/new-challenge') ?>" class="btn btn btn-sm btn-primary text-center align-self-end">Create First Challenge</a>
                </div>
            <?php
            } else {
            ?>
            <div class="overflow-auto mb-3" style="max-height: 300px;"><!-- challenges -->
                <table class="table table-sm table-hover border border-2 border-light table-bordered">
                    <thead>
                        <tr>
                            <th scope="col" style="width: 5%;" class="small text-center" >Group</th>
                            <th scope="col" style="width: 5%;" class="small text-center ">Status</th>
                            <th scope="col" class="small">Name</th>
                            <th scope="col" style="width: 10%;" class="small text-center">Tracking %</th>
                            <th scope="col" style="width: 10%;" class="small">Target Days</th>
                            <th scope="col" style="width: 20%;" class="small">Dates</th>
                            <th scope="col" style="width: 20px;" class="small" ></th>
                            <th scope="col" style="width: 20px;" class="small" ></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                            while($active_challenges->have_posts()) {
                            $active_challenges->the_post(); 

                            //SKIP OPT_OUT group challenge
                            if ($post->opt_out) {
                                continue;
                            }

                            $group_icon="";
                            $active = "none";
                            $today = date("m/d/y");
                            $end_date = date("m/d/y", strtotime(get_field( "end_date" )));

                            if ($post->is_active) {
                                $active = "lime";
                            } else {
                                $active = "none";
                            }
                            
                        ?>
                            <tr >
                                <td class="text-center">
                                    <?php if($post->is_group_challenge ) : ?>
                                        <span class="dashicons dashicons-groups"></span> 
                                    <?php else : ?>
                                        <span class="dashicons dashicons-admin-users"></span>
                                    <?php endif ?>
                                </td>
                                
                                <td class="text-center" >
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" stroke="black" stroke-width="2" fill="<?php echo $active ?>" shape-rendering="geometricPrecision">
                                    <circle cx="8" cy="8" r="8"/>
                                    </svg>
                                </td>
                                <td class="" style="transform: rotate(0);">
                                    <a id="<?php the_ID(  );?>" class="stretched-link" href="<?php the_permalink() ?>" ></a><?php the_title(); ?> 
                                </td>
                                <td class="text-center">
                                    <span class="ms-auto badge rounded-pill bg-primary align-self-end"><?php echo $post->progress ?>%</span>
                                </td>
                                <td class="small"><?php echo implode(",", get_field( "target_days" )); ?></td>
                                <td class="small"><?php echo date("m/d/y",strtotime(get_field( "start_date" ))); ?>  - <?php echo $end_date; ?></td>
                                <td class="small text-center">
                                    <?php if($post->is_group_challenge ) : ?>
                                        <a href="<?php echo add_query_arg(array('post_id' => get_the_id()), site_url('tracking-settings') ) ; ?>"> 
                                            <span style="text-decoration: none; color: grey;" class="dashicons dashicons-admin-generic" title="Change activity settings"></span> 
                                        </a>
                                    <?php endif ?>
                                </td>
                                <td class="small text-center">
                                    <?php if($post->is_group_challenge ) : ?>
                                        <?php if (get_the_author_meta( 'ID' ) != get_current_user_id()) : ?>
                                            <a class="opt-out" href="<?php echo esc_url( add_query_arg(array( 'opt_out' => '1', 'post_id' => get_the_id()) ) ); ?>">
                                                <span style="text-decoration: none; color: grey;" class="dashicons dashicons-no-alt" title="Opt out of this challenge?"></span> 
                                            </a>
                                        <?php endif ?>
                                    <?php endif ?>
                                </td>
                            </tr>
                        <?php } //end while 
                            ?>
                    </tbody>
                </table>
            </div>
            <?php
            } //end query check
            ?>
        </div><!--my challenges-->
        
        <?php if(count($user_messages) > 0) : ?>
            <div class="col-12  mb-3"><!--messages-->
                <h3>Activity</h3>
                <div class="overflow-auto mb-3 border border-secondary bg-white" style="max-height: 200px;">
                    <table class="table table-sm ">
                        <thead>
                            <tr>
                                <th scope="col" class="small">User</th>
                                <th scope="col" class="small">Challenge</th>
                                <th scope="col" style="width: 20px;" class="small" ></th>
                                <th scope="col" class="small">Comment</th>
                                <th scope="col" class="small">Date</th>
                                <th scope="col" class="small">time stamp</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($user_messages as $msg): ?>
                                <tr>
                                    <td class="small"><?php echo $msg->user_nicename ?></td>
                                    <td class="small"><?php echo (strlen($msg->post_title) > 20) ? substr($msg->post_title,0,20).'...' : $msg->post_title ?></td>
                                    <?php if ($msg->completed) : ?>
                                        <td class="small">
                                            <span style="text-decoration: none; color: green;" class="dashicons dashicons-yes" title="Tracking completed"></span> 
                                        </td>
                                    <?php else : ?>
                                        <td class="small">
                                             
                                        </td>
                                    <?php endif; ?>
                                    <td class="small"><?php echo (strlen($msg->comment) > 45) ? substr($msg->comment,0,45).'...' : $msg->comment ?></td>
                                    <td class="small"><?php echo $msg->tracking_date ?></td>
                                    <?php 
                                        $dt = new DateTime($msg->date_stamp);
                                    ?>
                                    <td class="small"><span title="<?php echo $dt->format('Y-m-d H:i:s') ?>"><?php echo $dt->format('Y-m-d') ?></span></td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div><!--border-->
            </div><!--messages-->
        <?php endif ?>

        <?php
            $crazey_groups = get_groups_and_members_by_current_user();
            $current_group_id = 0;
            $current_user_id = get_current_user_id();
            $close_accordian_item = true;
            $loop_count = 0;

        ?>

        <?php if(count($crazey_groups) > 0) : ?>
            <div class="col-12 col-lg-6 "><!--my groups-->
                <h3>My Groups</h3>
                    <table class="table border border-2 border-light">
                        <tr>
                            <td class="p-0">
                                <div class="accordion accordion-flush" id="Groupsaccordion">
                                    <?php
                                    
                                    foreach ($crazey_groups as $group) {
                                        ?>
                                        
                                        <?php
                                        //check if we need to close tags
                                        if ($current_group_id != 0 && $current_group_id != $group->id) {
                                        
                                            //close accordian item
                                        ?>
                                            <!-- </ul> -->
                                            </div>
                                                </div><!--acc body-->
                                                </div><!--acc collapse-->
                                                </div><!--acc item-->
                                        <?php
                                        } //end close tags check
                                        
                                        if ($current_group_id != $group->id) {
                                            $current_group_id = $group->id;?>
                                            
                                            <div class="accordion-item">
                                                <div class="accordion-button p-1 text-dark" type="button" data-bs-toggle="collapse" data-bs-target="#collapse<?php echo $group->id ?>" aria-expanded="true" aria-controls="collapseOne">
                                                    <?php echo $group->name ?>
                                                </div>
                                                <div id="collapse<?php echo $group->id ?>" class="accordion-collapse collapse" aria-labelledby="headingOne" data-bs-parent="#Groupsaccordion">
                                                <div class="accordion-body py-2">
                                                    <!-- <ul class="mb-0" style="list-style: none;"> -->
                                                    <div class="list-group list-group-flush mb-2 text-truncate" id="my-groups">
                                        <?php
                                            } // group_id check
                                            ?>
                                            
                                                <?php       
                                                    if ($group->user_id != $current_user_id ) { ?>
                                                        <!-- <li><a href="<?php echo site_url('/members/' . $group->user_nicename . '/') ?>" > <?php echo $group->user_nicename; ?></a></li> -->
                                                        <a class="list-group-item list-group-item-action  pb-1 pt-1" href="<?php echo site_url('/members/' . $group->user_nicename . '/') ?>" ><?php echo $group->user_nicename; ?></a>
                                                    <?php
                                                    } //user_id check
                                            } //end group loop
                                                ?>
                                    
                                </div><!--close accordion-->
                            </td>
                        </tr>
                    </table>
            </div><!--my groups-->
        <?php endif ?>

        <div class="col-12 col-lg-6  order-last overflow-auto" style="max-height: 100px;"><!--news-->
            <h3>Habit.US News!</h3>
            <div class="border border-secondary bg-white">
                <!-- <ul class="list-group list-group-flush">--> <?php 
                    $news_items = habitus_get_news_items();
                    while( $news_items->have_posts()) {
                        $news_items->the_post(); ?>
                        <a class="list-group-item list-group-item-action  pb-1 pt-1" href="<?php the_permalink(); ?>" ><?php echo the_title(); ?></a> 
                    <!-- <li class="list-group-item"><?php the_title() ?></li>  -->
                    <?php
                    } //end news item loop ?>
                <!-- </ul> -->
            </div><!--border-->
        </div><!--col 4-->
    </div><!--close row 1-->   
</div><!--closing page container-->

         <script>
        var elems = document.getElementsByClassName('opt-out');
        var confirmIt = function (e) {
            if (!confirm('Are you sure you want to opt out of this Challenge?')) e.preventDefault();
        };
        for (var i = 0, l = elems.length; i < l; i++) {
            elems[i].addEventListener('click', confirmIt, false);
        }
    </script> 
<?php
get_footer();