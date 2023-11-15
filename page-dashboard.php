<?php

get_header();

// $request = new WP_REST_Request( 'GET', '/buddypress/v1/messages' );
// $request->set_query_params( [ 'per_page' => 2, box => 'inbox', 'user_id' => 2 ] );
// $response = rest_do_request( $request );
// $server = rest_get_server();
// $data = $server->response_to_data( $response, false );
//$json = wp_json_encode( $data );

?>

<div class="container mt-5 p-5 bg-light rounded-3 border border-warning border-3">
        <div class="row row-cols-3 ">
            <div class="col border border-primary">
                <h3>My Challenges</h3>
                <div class="list-group mb-2" id="active-challenges">
                <?php
                $active_challenges = habitus_theme_get_active_challenges();
                while($active_challenges->have_posts()) {
                    $active_challenges->the_post(); 
                    ?>
                    <a id="<?php the_ID(  );?>" class="list-group-item list-group-item-action active-challenge-item " href="<?php the_permalink() ?>" > <?php the_title(); ?> </a>

                    <?php
                }
                ?>
                </div>
                <div class="col  d-flex justify-content-end">
                    <a href="" class="btn btn-primary">Next -></a>
                </div>
            </div>
            <div class="col border border-secondary ">
                <h3>My Groups</h3>
                <div class="list-group mb-2" id="active-challenges">
                    <?php
                    $groups = get_groups_by_current_user();
                    foreach ($groups as $group) {
                        ?>
                        <a class="list-group-item list-group-item-action " href="<?php echo site_url('groups/' . $group->slug); ?>" ><?php echo $group->name ?></a>
                    <?php
                    }
                    ?>
                </div>
                <div class="col  d-flex justify-content-end">
                    <a href="" class="btn btn-primary">Next -></a>
                </div>
            </div>
            <div class="col border border-danger">
                <h3>My Messages</h3>
                <div class="list-group mb-2" id="active-challenges">
                    <?php 
                        $user_messages = habitus_get_user_msg_inbox();
                        
                        foreach ($user_messages as $msg) : ?>
                            <a class="list-group-item list-group-item-action " href="<?php echo site_url('/members/trackeruser/view/' . $msg->thread_id . '/') ?>" ><?php echo $msg->subject; ?></a>
                        <?php endforeach; ?>
                </div>
                <div class="d-flex justify-content-end">
                    <a href="" class="btn btn-primary">Next -></a>
                </div>
            </div>
        </div> <!--row-->
        
    </div>


<?php
get_footer();