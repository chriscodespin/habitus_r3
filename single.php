<?php
//page vars

use DeliciousBrains\WPMDB\Container\Dotenv\Result\Result;
use JP\UM\Menu\Item;

$tracking = null;
$post_progress = 0;
$is_author = false;
$post_id = 0;
$user_id = get_current_user_id();
$members = null;
$is_group_challenge = false;
$group_tracking_meta = null;
$user_tracking_meta = null;
$group_tracking = null;
$group_id = 0;
$group = null;
$local_date = null;
$user_timezone = '';

//Set object to local time
if(isset($_POST['local-timezone']) && !empty($_POST['local-timezone'])) {
    date_default_timezone_set($_POST['local-timezone']);
    $user_timezone = $_POST['local-timezone'];
} elseif ($user_timezone = get_user_meta($user_id , 'time_zone', true)) {
    date_default_timezone_set($user_timezone);
}

//PRE HEADER LOOP
//hit the loop and do the tracking before get_header()
while(have_posts()) {
    the_post(); 
    $post_id = get_the_ID( );
    $tracking = new Habitus_Theme_Tracking();
    $tracking->user_timezone = $user_timezone;
    $tracking->process_tracking();
    $tracking->get_tracking();

    $user_tracking_meta = $tracking->get_tracking_meta($post_id);

    if (is_null($user_tracking_meta)) {
        $tracking->create_tracking_meta($post_id, $user_id);
        $user_tracking_meta = $tracking->get_tracking_meta($post_id);
    }

    $post_progress = 0;
    $is_author = false;
    
    if (get_the_author_meta( 'ID' ) == get_current_user_id()) {
        $is_author = true;
    }

    if ( $tracking->target_day_cnt != 0) {
            $post_progress = (int)(($tracking->tracking_cnt/$tracking->target_day_cnt) * 100);
        }
    }

    //IF GROUP CHALLENGE GET FULL HISTORY
    if ($group_id = get_field('group_id')) {
        $is_group_challenge = true;
        //$member_list_array = get_members_by_group_id(get_field('group_id'));
        $group_tracking_meta = $tracking->get_group_tracking_meta($post_id);
        $group_tracking = $tracking->get_group_tracking($post_id);
        $group = groups_get_group( $group_id );	
    }
    

// REWIND LOOP
rewind_posts();

function get_page_by_slug($slug) {
    $post = get_page_by_path($slug);
    return get_permalink($post->ID);
  }

get_header();


//DO POST HEADER LOOP
while(have_posts()) {
     the_post();     
    ?>
    <div class="container mt-md-5 p-md-5 mb-3 bg-light rounded-3 border border-warning border-3">
        <div class="d-flex m-0">
            <div class="flex-fill "><h1><?php the_title() ?></h1></div>
            <div class="d-flex justify-content-end">
            <?php if ($is_author) : ?>
                <?php if ($tracking->days_left() > 0) : ?>
                    <div class="p-2 flex-fill align-self-end">
                        <a href="<?php echo get_page_by_slug('edit-challenge') . '?post_id=' . get_the_ID();?>">Edit Habit Challenge</a>
                    </div>
                <?php else : ?>
                    <div class="p-2 flex-fill align-self-end">
                        <a href="<?php echo get_page_by_slug('clone-challenge') . '?post_id=' . get_the_ID();?>">Clone Habit Challenge</a>
                    </div>
                <?php endif; ?>

                <div class="p-2 flex-fill align-self-end">
                    <a style="color:red;" href="<?php echo get_delete_post_link(null,'',true) . '&frontend=true';?>" onclick="return confirm('are you sure you want to delete this Habit Challenge?')">            
                        <span style="text-decoration: none; color: red;" class="dashicons dashicons-no-alt" title="delete this challenge?"></span> 
                    </a>
                </div>
            <?php endif; //is_author ?>
            </div>
        </div>    
            
        <div class="row row-cols-3 bg-secondary mb-2">
            <a id="tracking-previous-link" class="col col-2 btn btn-primary <?php echo $tracking->previous_link ?>"  href="<?php echo add_query_arg( 'date-offset', $tracking->offset - 1 ); ?>"><-- Prev</a>
            <div class="col col-8 text-center text-light  fs-3 " id="tracking-date-display"><?php echo $tracking->objTrackingDate->format('F d, Y'); ?></div>
            <a class="col col-2 btn btn-primary <?php echo $tracking->next_link ?>" href="<?php echo add_query_arg( 'date-offset', $tracking->offset + 1 ); ?>">Next --></a>
        </div>
        <?php
    }

        $checked = '';
        $color = 'blue';
        $submit_color = '';
        $submit_text = 'Record it!';
        $previously_tracked = 0;
        if ($tracking->completed) {
            $color = 'green';
            $submit_color = 'green';
            $submit_text = 'Update';
            $checked = "checked";
            $previously_tracked = 1;
        }
        else 
        {
            $color = '#ccc';
            $submit_color = '#03a9f4';
            $submit_text = 'Record it!';
        } 
        
        if ($tracking->days_left()) :
            ?>
            <section class="d-flex align-items-center text-center mb-3">
                <div class="container">
                    <form class="" id="tracker" method="POST" action="">
                        <input type="hidden" name="meta-id" value="<?php echo $tracking->track_id ?>">
                        <input type="hidden" name="post-progress" value="<?php echo $post_progress ?>">
                        <input type="hidden" name="tracking-cnt" value="<?php echo $tracking->tracking_cnt ?>">
                        <input type="hidden" name="share-comments" value="<?php echo $user_tracking_meta->share_updates_and_comments ?>">
                        <input type="hidden" name="target-day-cnt" value="<?php echo $tracking->target_day_cnt ?>">
                        <input type="hidden" name="previously_tracked" value="<?php echo $previously_tracked ?>">
                        <input type="hidden" name="local-date" id="form-local-date">
                        <input type="hidden" name="local-timezone" id="form-timezone">
                        <input class="d-none" type="checkbox" name="cb_complete" id="cb_complete" <?php echo $checked ?> >
                        <div class="row justify-content-center">
                                <div class="col col-lg-8 col-xl-6">
                                    <p class="mb-2">Completed?</p>
                                    <svg class="bi bi-check-square mb-2" id="svg_complete" xmlns="http://www.w3.org/2000/svg" width="100" height="100" fill="<?php echo $color ?>"  viewBox="0 0 16 16">
                                        <path d="M14 1a1 1 0 0 1 1 1v12a1 1 0 0 1-1 1H2a1 1 0 0 1-1-1V2a1 1 0 0 1 1-1h12zM2 0a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V2a2 2 0 0 0-2-2H2z"/>
                                        <path d="M10.97 4.97a.75.75 0 0 1 1.071 1.05l-3.992 4.99a.75.75 0 0 1-1.08.02L4.324 8.384a.75.75 0 1 1 1.06-1.06l2.094 2.093 3.473-4.425a.235.235 0 0 1 .02-.022z"/>
                                    </svg>
                                    <textarea class="col-11 form-control mb-2" name="comment" id="comment"  rows="3" ><?php echo $tracking->comment ?></textarea>
                                    <div class="w-100 d-flex justify-content-end">
                                        <input class="btn btn-success" type="submit" name="track" id="track" value="<?php echo $submit_text ?>" ></input>
                                    </div>
                            </div>
                        </div>
                    </form>
                </div>
            </section>
        <?php else : ?>
            <div class="bg-danger text-white text-center mb-3 w-50 mx-auto">Expired</div>
        <?php endif; ?>

        <?php if(!empty($tracking->dt1)) : ?>
        <h3 class="">Progress</h3>
            <?php if ( $tracking->target_day_cnt != 0) : ?>
                <div class="row row-cols-2  justify-content-between">
                    <p class="col mb-0"><?php echo $tracking->dt1 ?></p>
                    <p class="col   mb-0 text-end"><?php echo $tracking->dt2 ?></p>
                </div>
                <div class="progress">
                    <div class="progress-bar" style="width: <?php echo (int)(($tracking->tracking_cnt/$tracking->target_day_cnt) * 100) ."%" ?>;">
                    <?php echo (int)(($tracking->tracking_cnt/$tracking->target_day_cnt) * 100) ."%" ?>
                    </div>
                </div>
            <?php endif; //$tracking->target_day_cnt  ?>
            <div class="row row-cols-2 justify-content-between">
                <p class="col" >Target Days Left: <?php echo $tracking->target_days_left() ?></p>
                <p class="col text-end">Total Days Left: <?php echo $tracking->days_left() ?></p>
            </div>
        <?php endif; //$tracking->dt1 ?>
 
        <?php if ($is_group_challenge) : ?>
            <section class="">
            <div class="border border-1 col-lg-6 col-sm-12 mx-auto p-3">
                <div class="text-center "><?php echo $group->name ?></div>
                <?php
                    
                    $progress_color = '';
                    foreach ($group_tracking_meta as $member) { 
                        if($member->ID == $user_id ) {
                            $progress_color = 'bg-primary';
                        } else {
                            $progress_color = 'bg-info';
                        }
                        ?>
                        <div class="">
                            <?php echo $member->user_nicename ?>  
                            <div class="progress">
                                <span class="progress-bar <?php echo $progress_color?>" style="width: <?php echo (int)($member->progress)."%" ?>;">
                                    <?php echo $member->progress ."%" ?>
                                </span>
                            </div>
                        </div>
                    <?php  
                    }
                ?>
            </div>
            </section>
        <?php endif; //group id ?>

    </div><!--container-->

    <div class="container mt-md-5 p-md-5 mb-3 bg-light rounded-3 border border-warning border-3">
        <h3 class="">History</h3>
        <div class="overflow-auto mb-3 border border-secondary bg-white" style="max-height: 300px;">
            <table class="table table-sm">
                <?php 
                    //GROUP HISTORY
                    if ($is_group_challenge) : 
                         foreach ($group_tracking as $v):  ?>
                            <tr>
                                <td><?php echo $v->user_nicename ?></td>
                                <?php if ($v->completed) : ?>
                                    <td class="small" style="width: 20px;">
                                        <span style="text-decoration: none; color: green;" class="dashicons dashicons-yes" title="Tracking completed"></span> 
                                    </td>
                                    <?php else : ?>
                                        <td class="small" style="width: 20px;"></td>
                                <?php endif; ?>
                                <td><?php echo $v->tracking_date ?></td>
                                <td><?php echo (strlen($v->comment) > 45) ? substr($v->comment,0,45).'...' : $v->comment ?></td>
                            </tr>
                    <?php endforeach;
                //INDIVIDUAL HISTORY
                else :
                    foreach ($tracking->results as $v):  ?>
                        <tr>
                            <td><?php echo $v->tracking_date_display ?></td>
                            <td><?php echo (strlen($v->comment) > 45) ? substr($v->comment,0,45).'...' : $v->comment ?></td>
                        </tr>
                    <?php endforeach; 
                endif ; ?>
            </table>
        </div>
    </div>
    
<?php
get_footer();
