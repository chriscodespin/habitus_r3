<?php

//require_once 'classes/class-habitus-permissions.php';
require_once 'classes/class-habitus-public.php';
require_once 'classes/class-groups-and-friends.php';
require_once 'classes/class-habitus-tracking.php';
require_once 'classes/class-habitus-email.php';
require_once 'classes/class-habitus-admin.php';

//THEME ACTIVATION HOOKS
// with activate istall option
if ( is_admin() && isset($_GET['activated'] ) && $pagenow == 'themes.php' ) {
	$habitus_admin = new Habitus_Theme_Admin();
    $habitus_admin->create_habitus_email_templates();
}

//ACTIONS
$plugin_public = new Habitus_Theme_Public();
$groups_friends_class = new Habitus_Theme_Groups_Friends();
$habitus_email_class = new Habitus_Theme_Email();
$habitus_tracking = new Habitus_Theme_Tracking();
//$habitus_register_routes = new Habitus_Register_Routes();

//REGISTER POST TYPES
add_action( 'init', array($plugin_public, 'habitus_register_news_post_type') );
add_action( 'init', array($plugin_public, 'habitus_register_issue_type') );

//ENQUEUE
//add_action( 'wp_enqueue_scripts', array($plugin_public, 'enqueue_styles') );
//add_action( 'wp_enqueue_scripts', array($plugin_public, 'enqueue_scripts') );

//ACF
add_action( 'get_header', 'acf_form_head' );
add_action('acf/init', array($plugin_public, 'my_new_challenge_form_init'));	
add_action('acf/init', array($plugin_public, 'my_new_issue_form_init'));	
add_filter('acf/load_field/name=group_id', array($groups_friends_class, 'get_groups_by_current_user'));
add_filter('acf/load_field/name=select_friends', array($groups_friends_class, 'get_friends_by_current_user'));
add_action( 'acf/save_post', array($plugin_public, 'acf_save_post' ));

//LOGIN AND REDIRECT
add_filter( 'lh_autologin_on_activation_redirect_url', array($plugin_public, 'redirect_after_activation'), 1);
add_action('wp_login', array($plugin_public, 'set_local_timezone'), 10, 2);
add_action( 'template_redirect', array($plugin_public, 'my_page_template_redirect' ));
add_action( 'wp_login_failed', array($plugin_public, 'pippin_login_fail' ));  // hook failed login
add_action( 'wp_logout', array($plugin_public, 'pippin_login_fail' ));  // logged out redirect

//CONTENT FILTER
add_filter( 'the_content', array($plugin_public, 'filter_the_content_in_the_main_loop'), 1);
add_action('bp_before_group_body', array($groups_friends_class, 'habitus_list_group_challenges'));

//TITLE FILTER
//add_filter( 'the_title', array($plugin_public, 'filter_the_title', 10, 2 ));

//BP MESSAGES
add_action('bp_notification_settings', array($habitus_email_class, 'new_challenge_email_settings'));

//TRACKING
add_action('delete_post', array($habitus_tracking, 'delete_post_tracking'));
add_action( 'after_delete_post', array($plugin_public, 'the_dramatist_redirect_after_post_delete' ));

//REGISTRATION
add_action('bp_before_register_page', array($plugin_public, 'display_challenge_pre_registration' ));
add_action('bp_before_account_details_fields', array($plugin_public, 'add_registration_fields' )); 
add_action('bp_core_signup_user', array($groups_friends_class, 'add_registered_user_to_group' ));

//MAIL SETTINGS 
add_filter( 'wp_mail_from', array($habitus_email_class, 'wpb_sender_email' ));
add_filter( 'wp_mail_from_name', array($habitus_email_class, 'wpb_sender_name' ));

//REST API
//add_action( 'rest_api_init', $habitus_register_routes, 'habitus_create_custom_routes' );


// Register Custom Navigation Walker
//require_once('wp-bootstrap5-pagination.php');

//For example, you can paste this into your theme functions.php file

function meks_which_template_is_loaded() {
	if ( is_super_admin() ) {
		global $template;
		print_r( $template );
	}
}

add_action( 'wp_footer', 'meks_which_template_is_loaded' );

function habitus_files() {
    wp_enqueue_style('bootstrap5', 'https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta1/dist/css/bootstrap.min.css');
    //wp_enqueue_style('habitus_main_styles', get_stylesheet_uri(), array('bootstrap5'));
    wp_enqueue_script( 'bootstrap5_js','https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta1/dist/js/bootstrap.bundle.min.js', array() ,'',true );
    wp_enqueue_style( 'habitus_public', get_theme_file_uri('css/habitus-public.css'), array(), date("h:i:sa"), 'all' );
    wp_enqueue_script('habitus_public_js', get_theme_file_uri('js/habitus-public.js'), array( 'jquery' ), date("h:i:sa"), false );


    if (is_page('habitus-dashboard')) {
        wp_enqueue_script('habitus-dashboard', get_theme_file_uri( 'js/dashboard.js' ), array(),'', true);
    }

    if (is_single()) {
        wp_enqueue_script('tracking', get_theme_file_uri( 'js/tracking.js' ), array(),'', true);
    }

    if (is_page('new-challenge') || 
        is_page('edit-challenge') || 
        is_page('clone-challenge')  ) {
            wp_enqueue_script('habitus-acf', get_theme_file_uri( 'js/habitus-acf.js' ), array(),'', true);
    }

}
add_action('wp_enqueue_scripts','habitus_files');


//LOGIN PERMISSION CHECK
function login_permission_check () {
    global $post;
    
    $permissions = new Habitus_Theme_Permissions();
    
    // Check if we're inside the main loop in a single Post.
    if ( is_singular('post') ) {
        $permissions->login_redirect();
        if (!$permissions->can_user_view_post($post)) {
            wp_redirect(home_url() . '/?error=unauthorized' );
            exit;
        }
    } elseif (is_page('habitus-dashboard')) {
        $permissions->login_redirect();
    
     } elseif ( is_page('new-challenge') ) {
        $permissions->login_redirect();
    
    } elseif ( is_page('edit-challenge') ) {
        $permissions->is_users_post();
    }
}


function habitus_widget_area_1_init() {
	$args = array(
	    'name'          => 'Habitus Widget Area 1',
	    'id'            => "habitus-widget-area-1",
	    'description'   => 'Test login widget on home page',
	    'class'         => '',
	    'before_widget' => '<div id="%1$s" class="widget %2$s">',
	    'after_widget'  => "</div>\n",
	    'before_title'  => '<h2 class="widgettitle">',
	    'after_title'   => "</h2>",
	);
	register_sidebar( $args );
}
add_action( 'widgets_init', 'habitus_widget_area_1_init' );

function get_groups_by_current_user() {
    if (!is_admin() ) {
    
        global $wpdb;
        $sql = $wpdb->prepare("SELECT g.id,g.slug, g.name FROM `wp_bp_groups` g JOIN `wp_bp_groups_members` m ON g.id = m.group_id  
                                WHERE m.user_id = %d 
                                AND m.is_confirmed = 1", get_current_user_id());
        $groups = $wpdb->get_results( $sql, OBJECT );

    }

    return $groups;
}

function get_groups_and_members_by_current_user() {
    if (!is_admin() ) {
    
        global $wpdb;
        // $sql = $wpdb->prepare("SELECT g.id,g.slug, g.name FROM `wp_bp_groups` g JOIN `wp_bp_groups_members` m ON g.id = m.group_id  
        //                         WHERE m.user_id = %d 
        //                         AND m.is_confirmed = 1", get_current_user_id());
        
        $sql = $wpdb->prepare("SELECT g.id,g.slug, g.name, m.user_id, u.user_nicename FROM `wp_bp_groups` g JOIN `wp_bp_groups_members` m ON g.id = m.group_id  \n"
    ."                                                JOIN `wp_users` u ON u.ID = m.user_id\n"
    . "                                WHERE g.id IN (SELECT g.id FROM `wp_bp_groups` g \n"
    . "                                               JOIN `wp_bp_groups_members` m ON g.id = m.group_id\n"
    . "                                               WHERE m.user_id = %d AND m.is_confirmed = 1 )\n"
    . "                                ORDER BY g.id", get_current_user_id());

    $groups = $wpdb->get_results( $sql, OBJECT );
    }

    return $groups;
}


function get_members_by_group_id($group_id) {

    global $wpdb;
    $members = null;
    $member_list_array = [];

    //Get users in group
    $sql_group_member_list = $wpdb->prepare("SELECT `user_id` FROM `wp_bp_groups_members` WHERE  `group_id` = %d", $group_id);
    $group_member_list = $wpdb->get_results( $sql_group_member_list, OBJECT);

    foreach ($group_member_list as $member) {
        array_push($member_list_array, $member->user_id);
    }

    return $member_list_array;
}



function habitus_get_user_msg_inbox () {
        global $wpdb;
        
        $sql = $wpdb->prepare(
                "SELECT m.id, m.message, t.completed, t.comment, 
                DATE_FORMAT(t.tracking_date, '%a %c/%e/%Y') tracking_date,
                t.date_stamp,
                u.user_nicename, p.post_title   
                FROM `challenge_messages` m 
                JOIN `tracking` t ON m.tracking_id = t.id
                JOIN `wp_users` u ON u.ID =  m.sender_id
                JOIN `wp_posts` p ON p.ID = t.post_id
                WHERE m.recipient_id = %d
                ORDER BY m.id DESC LIMIT %d, %d", get_current_user_id(), 0 , 20);

        $messages = $wpdb->get_results( $sql, OBJECT );

        return $messages;
}

function habitus_get_msg_paging() {
    global $wpdb;
    $results_per_page = 10;
    $page = 0;
    $pagination = '';
    $disabled = '';
    $nextdisabled = '';
    $prevlink = '#';
    $nextlink = '#';

    if (isset($_GET["page"])) { $page  = $_GET["page"]; } else { $page=1; }; 

     // Prev + Next
    $prev = $page - 1;
    $next = $page + 1;

    $sql = "SELECT COUNT(id) AS total FROM wp_bp_messages_recipients WHERE user_id = " . get_current_user_id();
    $result = $wpdb->get_results( $sql, OBJECT );
    $total_pages = ceil($result[0]->total / $results_per_page); // calculate total pages with results

    if($page <= 1) {
        $disabled = 'disabled';
        $prevlink = "?page=" . $prev;
    }
    
    $pagination = sprintf('<li class="page-item %s"><a class="page-link" href="%s">« Prev</a></li>', $disabled, $prevlink);
            
    for ($i=1; $i<=$total_pages; $i++) {  // print links for all pages
        $active = '';
        if ($i == $page) {
            $active = 'active';
        }

        $pagination .= sprintf('<li class="page-item %s"><a class="page-link" href="page=%d">%d</a></li>',$active,$i,$i);
    }; 

    if($page >= $total_pages) {
        $nextdisabled = 'disabled';
        $nextlink = "?page=" . $next;
    }

    $pagination .= sprintf('<li class="page-item %s"><a class="page-link" href="%s">Next »</a></li>', $nextdisabled, $nextlink);

    return $pagination;
}

function habitus_get_news_items() {

    return new WP_Query(array('post_type'=>'habitus_news', 'post_status'=>'publish', 'posts_per_page'=>-1));
}

function habitus_theme_challenge_opt_out($post_id) {
    global $wpdb;

    $table = 'tracking_meta';
    $data = array('opt_out' => 1);
    $where = array('post_id' => $post_id, 'user_id' => get_current_user_id());

    $updated = $wpdb->update( $table, $data, $where );

    //insert new meta record
    if ($updated == 0) {
        $wpdb->insert( 
            'tracking_meta', 
            array( 
                'post_id' => $post_id, 
                'user_id' => get_current_user_id(),
                'opt_out' => 1
                ), 
            array('%d', '%d', '%d')
            );
    }

    return $updated;
}

function habitus_theme_get_active_challenges() {
    global $wpdb;

    $first_ids = array();
    $second_ids = array();
    $post_ids = array();
    $active_count = 0;
    $today = date("m/d/y");


    //non paid member challenge limit
    $user_post_limit = 30;

    $paged = ( get_query_var( 'paged' ) ) ? absint( get_query_var( 'paged' ) ) : 1;

    $user_id = get_current_user_id();

    //paid member unlimited challenges
    if (pmpro_hasMembershipLevel('Pro'))
    {
        $user_post_limit = 30;
    }

    //GET USER'S CHALLENGES
    $first_ids = get_posts( array(
        'fields'         => 'ids',
        'author'	=> $user_id,
        'numberposts'	=> $user_post_limit,
        'meta_query'	=> array(
            array(
                'key'	 	=> 'group_id',
                'value'     => 0,
                'compare' 	=> '=',
            )
        )
    ));

    $my_var = $GLOBALS['wp_query']->request;

    //GET USER'S GROUPS
    $groups = groups_get_user_groups( $user_id );
    
    //CHECK IF USER IS MEMBER OF ANY GROUPS
    if ( !empty( $groups['groups'] ) ) {

        //GET GROUP CHALLENGES
        $second_ids = get_posts( array(
                    'fields'         => 'ids',
                    'numberposts'	=> -1,
                        'post_type'		=> 'post',
                        'meta_query'	=> array(
                            array(
                                'key'	 	=> 'group_id',
                                'value'	  	=> $groups['groups'],
                                'compare' 	=> 'IN',
                            )
                            ),
                            'post__not_in'   =>  $first_ids
                ));

        //MERGE GROUP CHALLENGE IDS WITH PERSONAL CHALLENGES
        $post_ids = array_merge( $first_ids, $second_ids);
    //IF NOT IN ANY GROUPS
    } else {
        if(!empty($first_ids)) {
            $post_ids = $first_ids;
        }
    }
        //GET POSTS FROM MERGED IDS
        if(!empty($post_ids)) {
        
        $query = new WP_Query(array(
            'post__in'  => $post_ids, 
            'meta_key' => 'end_date',
                    'orderby' => 'meta_value',
                    'meta_type' => 'DATE',
                    'order' => 'DESC'
        ));

        //Get tracking Meta
        $tracking_meta_post_ids = implode(',', $post_ids);

        $sql_tracking_meta = $wpdb->prepare("SELECT `post_id`,`id`, `progress`, `opt_out` FROM `tracking_meta` WHERE post_id IN (%1s) AND user_id = %d",
                                            $tracking_meta_post_ids, $user_id);

        $result_tracking_meta = $wpdb->get_results( $sql_tracking_meta, OBJECT_K );

        //LOOP THROUGH POSTS AND SET FIELDS
        foreach ($query->posts as $i =>  $local_post) {

            //flag posts that are group challenges
            if (in_array($local_post->ID, $second_ids)) {
                $local_post->is_group_challenge = 1;
            }

            $end_date = date("m/d/y", strtotime(get_post_meta($local_post->ID, 'end_date', true)));

            if (strtotime($end_date) >= strtotime($today)) {
                $local_post->is_active = 1;
                $active_count++;
            } else {
                $local_post->is_active = 0;
            }

            //set progress value
            if (array_key_exists($local_post->ID, $result_tracking_meta)) { 
                $local_post->progress = $result_tracking_meta[$local_post->ID]->progress;

                //remove opt-out posts
                if ($result_tracking_meta[$local_post->ID]->opt_out == 1) {
                    $local_post->opt_out = 1;
                } else {
                    $local_post->opt_out = 0;
                }
            } else {
                $local_post->progress = 0;
            } 
        } //end post loop

    } //end main query check

    //RETURN EMPTY QUERY OBJECT IF USER HAS NO CHALLENGES
    if(!isset($query)) {
        $query = new WP_Query(array(
            'post__in' => array( 0 )
        ));
    }

     //check if non-paid member is over their limit
     if (!pmpro_hasMembershipLevel('Pro'))
     {
         if ( $active_count >= $user_post_limit)
         {
             update_user_meta($user_id, 'user_at_max_limit', 1);
         } else {
             update_user_meta($user_id, 'user_at_max_limit', 0);
         }
     }

    return $query;

}

function habitus_admin_class() {
    return new Habitus_Theme_Admin();
}


