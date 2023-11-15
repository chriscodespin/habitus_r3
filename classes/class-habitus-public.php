<?php

/**
 * The public-facing functionality of the plugin.
 *
 * @link       chris.com
 * @since      1.0.0
 *
 * @package    Habitus
 * @subpackage Habitus/public
 */

/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the public-facing stylesheet and JavaScript.
 *
 * @package    Habitus
 * @subpackage Habitus/public
 * @author     Chris <post@example.com>
 */
class Habitus_Theme_Public {

	function __construct() {
        require_once 'class-habitus-permissions.php';
		require_once 'class-habitus-email.php';
    }



	public function habitus_register_news_post_type() {
	
		$labels = array(
			'name' => __( 'News', 'habitus' ),
			'singular_name' => __( 'News Item', 'habitus' ),
			'add_new' => __( 'New News Item', 'habitus' ),
			'add_new_item' => __( 'Add New News Item', 'habitus' ),
			'edit_item' => __( 'Edit News Item', 'habitus' ),
			'new_item' => __( 'New News Item', 'habitus' ),
			'view_item' => __( 'View News', 'habitus' ),
			'search_items' => __( 'Search News', 'habitus' ),
			'not_found' =>  __( 'No News Found', 'habitus' ),
			'not_found_in_trash' => __( 'No News found in Trash', 'habitus' ),
		   );

		   $args = array(
			'labels' => $labels,
			'has_archive' => true,
			'public' => true,
			'hierarchical' => false,
			'supports' => array(
			'author',
			 'title',
			 'editor',
			 'excerpt',
			 'custom-fields',
			 'thumbnail',
			 'page-attributes'
			),
			'taxonomies' => array( 'category'),
			'rewrite'   => array( 'slug' => 'habitus_news' ),
			'show_in_rest' => true
		   );

		  register_post_type( 'habitus_news', $args );
	}

	public function habitus_register_issue_type() {
	
		$labels = array(
			'name' => __( 'Issues', 'habitus' ),
			'singular_name' => __( 'Issue Item', 'habitus' ),
			'add_new' => __( 'New Issue Item', 'habitus' ),
			'add_new_item' => __( 'Add New Issue Item', 'habitus' ),
			'edit_item' => __( 'Edit Issue Item', 'habitus' ),
			'new_item' => __( 'New Issue Item', 'habitus' ),
			'view_item' => __( 'View Issue', 'habitus' ),
			'search_items' => __( 'Search Issue', 'habitus' ),
			'not_found' =>  __( 'No Issue Found', 'habitus' ),
			'not_found_in_trash' => __( 'No Issue found in Trash', 'habitus' ),
		   );

		   $args = array(
			'labels' => $labels,
			'has_archive' => true,
			'public' => true,
			'hierarchical' => false,
			'supports' => array(
			'author',
			 'title',
			 'editor',
			 'custom-fields',
			 'thumbnail',
			 'page-attributes'
			),
			'taxonomies' => array( ),
			'rewrite'   => array( 'slug' => 'habitus_issues' ),
			'show_in_rest' => true
		   );

		  register_post_type( 'habitus_issue', $args );
	}

	public function set_local_timezone( $user_login, $user) {

		//Set object to local time
		if(isset($_POST['local-timezone']) && !empty($_POST['local-timezone'])) {
			date_default_timezone_set($_POST['local-timezone']);
			update_user_meta($user->ID, 'time_zone', $_POST['local-timezone']);
		}
	}

	public function my_new_challenge_form_init() {

		// Check function exists.
		if( function_exists('acf_register_form') ) {
	
			// Register new challenge form.
			acf_register_form(array(
				'id'       => 'new-challenge',
				'post_id'  => 'new_post',
				'new_post' => array(
					'post_type'   => 'post',
					'post_status' => 'publish'
				),
				'post_title'  => true,
				'post_content'=> false,
				'submit_value' => __("Game On!", 'acf'),
				'return' => '%post_url%'
			));
		}
	}

	public function my_new_issue_form_init() {

		// Check function exists.
		if( function_exists('acf_register_form') ) {
	
			// Register new challenge form.
			acf_register_form(array(
				'id'       => 'new-issue',
				'post_id'  => 'new_post',
				'new_post' => array(
					'post_type'   => 'habitus_issue',
					'post_status' => 'publish'
				),
				'post_title'  => true,
				'post_content'=> true,
				'fields' => array(-1),
				'submit_value' => __("Submit", 'acf'),
				'return' => home_url( 'got-it' ) 
			));
		}
	}


	
	//POST DELETE REDIRECT
	public function the_dramatist_redirect_after_post_delete() {
		if (  $_GET['frontend'] == 'true' ) {
			wp_redirect( home_url( '/habitus-dashboard/' ) );
			exit();
		}
		
	}

	public function redirect_after_activation() {
		bp_core_redirect(home_url( '/habitus-dashboard/' ) );
	}
	
	public function my_page_template_redirect() {
	
		if ( is_singular('post') && in_the_loop() && is_main_query()  ) {
			$permissions = new Habitus_Theme_Permissions();
			$permissions->is_users_post();
			return;
		}
		
		if ( (is_page('active-challenges'))||
		 ( is_page('new-challenge') ) ||
		 ( is_page('edit-challenge') ) ) {
			$permissions = new Habitus_Theme_Permissions();
			$permissions->login_redirect();
			return;
		}

		if (!is_ssl()) {
			wp_redirect('https://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'], 301);
			exit();
		}
	}

	//MAIN LOOP FILTER
	public function filter_the_content_in_the_main_loop( $content ) {

		$permissions = new Habitus_Theme_Permissions();
		$post_id = get_the_id();
		// Check if we're inside the main loop in a single Post.
		if ( is_singular('post') && in_the_loop() && is_main_query() ) {
			if (!$permissions->can_user_view_post($post_id)) {
				$content = 'You are not authorized to view this post.';
				return $content;
			}
		} elseif (is_page('habitus-dashboard')) {
			$permissions->login_redirect();
		
		 } elseif ( is_page('new-challenge') ) {
			$permissions->login_redirect();
			//$this->habitus_create_challenge_form();
		
		} elseif ( is_page('edit-challenge') ) {
			$permissions->is_users_post();
			//$this->habitus_edit_challenge_form ($_GET['post_id']);
		}
		elseif (is_page('active-challenges')) {
			
			$permissions->login_redirect();
			$this->list_active_challenges(); 
		}
		
		return $content;
	}

	//FILTER THE TITLE
	function filter_the_title( $title, $id = null ) {
 
		//REGISTER PAGE THRU EMAIL INVITE, SAY HOWDY.
		if ($title == "Create an Account"  && 
			is_page('register') &&
			(isset($_GET['post_id']) && !empty($_GET['post_id']) )
			 ) {
			return '';
		}
	 
		return $title;
	}

	function add_registration_fields() { 

		if (isset($_GET['post_id']) && !empty($_GET['post_id']) ) {
			$post_id = $_GET['post_id'];?>
			<input type="hidden" name="post_id" value="<?php echo $post_id?>"><?php
		} ?>

		<input type="hidden" name="local-timezone" id="form-timezone">
		<?
	} 

	function display_challenge_pre_registration() {
		global $bp;
		$post_id = "";
		//$post;

		//Check if this is a challenge registration invite
		//and make sure it's not the confirmation stage of registration
		if (isset($_GET['post_id']) && !empty($_GET['post_id'])
			&& $bp->signup->step == "request-details" ) {

			$post_id = $_GET['post_id'];

			$query = new WP_Query( array( 'p' => $post_id ) );
			// Check that we have query results.
			if ( $query->have_posts() ) {
				// Start looping over the query results.
				while ( $query->have_posts() ) {
					$query->the_post(); ?>
						<h1>Howdy there <?php the_author(); ?> friend!</h1>
						<p>You think you can handle <?php the_title(); ?> ? </p>
						<p>Well then...Register Below and Thrive!!</p>	<?php
				}
			} 
			
			// Restore original post data.
			wp_reset_postdata();		
		}
	}

	function get_page_by_slug($slug) {
		$post = get_page_by_path($slug);
		return get_permalink($post->ID);
	  }

	public function list_active_challenges() {

		$user_id = get_current_user_id();
		$args = array(	'author'	=> $user_id);
		$user_posts_query = new WP_Query( $args );
		$related_ids = array_map( function( $v ) {
			return $v->ID;
			}, $user_posts_query->posts );

		$groups = groups_get_user_groups( $user_id );
			if ( empty( $groups['groups'] ) ) {
				$groups = array( 'groups' => 0 );
			}
		$args2 = array(
				'numberposts'	=> -1,
				'post_type'		=> 'post',
				'meta_query'	=> array(
					array(
						'key'	 	=> 'group_id',
						'value'	  	=> $groups['groups'],
						'compare' 	=> 'IN',
					)
					),
					'post__not_in'   =>  $related_ids
				
				);

		$group_posts_query = new WP_Query( $args2 );
		$new_query = new WP_Query();

		$new_query->posts =  array_merge_recursive($group_posts_query->posts, $user_posts_query->posts) ;
		//For whatever reason, post_count returns 0 after merge
		$new_query->post_count = count($new_query->posts);
		
		$this->display_active_challenges($user_posts_query, 'My'); 
		$this->display_active_challenges($group_posts_query, 'Group'); 
		//$this->display_active_challenges($new_query, 'Combined');		
	}


	public function display_active_challenges($the_query, $description) {
		if( $the_query->have_posts() ): ?>
			<h3><?php echo $description ?> Challenges</h3>
				<ul>
				<?php while( $the_query->have_posts() ) : $the_query->the_post(); ?>
					<li>
						<a href="<?php the_permalink(); ?>">
							<img src="<?php the_field('event_thumbnail'); ?>" />
							<?php the_title(); ?>
						</a>
					</li>
				<?php endwhile; ?>
				</ul>
		<?php endif; ?>
	
		<?php wp_reset_query();	 // Restore global post data stomped by the_post(). 
	}
		
	public function acf_save_post($post_id) {
		//global $post;
		$post = get_post($post_id);
		$group_id = 0;
		$friends = [];
		$group_members = [];
		$newGroup = '';
		$usersCombined = [];
		$new_group_id = 0;
		$email_args = [];
		$email_type = 'new_challenge_invite';
		$description = get_field('description', $post_id);
		$invite_link = '' ;
		$member_message_args = [];
		$msg_content = '';
		$author_name = get_the_author_meta('user_firstname', $post->post_author) . ' ' . get_the_author_meta('user_lastname', $post->post_author);

		$group_id = get_field('group_id', $post_id);
		$friends = (get_field('select_friends', $post_id));
		if (is_null ($friends)) {
			$friends = [];
		}

		$newGroup = get_field('new_group_name', $post_id);

		//if new post or clone post do everything.
		if ($_POST['_acf_post_id'] == 'new_post' || 
			(isset($_POST['cloned_post_back']) && !empty($_POST['cloned_post_back']))
		) {
			
			// check if is a group challenge.
			if ($group_id > 0) {
				if ( bp_group_has_members(array('group_id' => $group_id,
										'exclude_admins_mods' => 0) )) {

				while ( bp_group_members() ) : bp_group_the_member(); 
					array_push($group_members, bp_get_group_member_id());
				endwhile;
					
				}
			}

			//merge friends and group
			$usersCombined = array_unique(array_merge($friends, $group_members));

			//if new group create group from combined
			$newGroup = sanitize_text_field($newGroup );
			if (strlen(trim($newGroup)) > 0 && $group_id < 0) {

				$new_group_args = array(
					'group_id'     => 0,
					'creator_id'   => bp_loggedin_user_id(),
					'name'         => $newGroup,
					'slug'         => sanitize_title($newGroup),
					'status'       => 'public',
					'enable_forum' => 0,
					'date_created' => bp_core_current_time()
				);

				//create group with members
				if ($new_group_id = groups_create_group( $new_group_args )) {

					//update post with group_id
					update_field('group_id', $new_group_id, $post_id);

					//add selected users to group
					if (count($usersCombined) > 0) {
						foreach ($usersCombined as $group_member_id) {
							groups_join_group( $new_group_id, $group_member_id ) ;
						}
					}
				}
			}

			if (count($usersCombined) > 0) {

				//remove author from list of recipients
				// if (in_array($post->post_author, $usersCombined)) {
				// 	unset($usersCombined[array_search($post->post_author, $usersCombined)]);
				// }

				//CREATE DEFAULT TRACKING META
				$post_progress = 0;
				$habitus_tracking = new Habitus_Theme_Tracking();

				foreach ($usersCombined as $iuser) {
					$habitus_tracking->update_tracking_progress($post_id, $iuser, $post_progress );
				}

				$post_link = get_permalink($post);

				//load email html template
				$my_temp_path = wp_normalize_path(get_template_directory() . '/templates/email/habitus-new-challenge-message.php');
				ob_start();
					include $my_temp_path;
				$msg_content = ob_get_contents();

				//SEND EXISTING MEMBERS A MESSAGE
				$args = array(
					'recipients' => $usersCombined,
					'subject'    => $post->post_title,
					'content'    => $msg_content,
					'error_type' => 'wp_error');

				//SUPPRESS BP EMAIL NOTIFICATION FOR THIS MESSAGE
				remove_action( 'messages_message_sent', 'messages_notification_new_message', 10 );

				messages_new_message( $args );	

				//ADD BACK BP EMAIL NOTIFICATION MESSAGE
				add_action( 'messages_message_sent', 'messages_notification_new_message', 10 );

				//SEND EXISTING MEMBERS AN EMAIL
				$email_args = array(
					'tokens' => array(
						'site.name' => get_bloginfo( 'name' ),
						'challenger.name' => get_the_author_meta( 'user_nicename' , $post->post_author ),
						'post.title' => get_the_title($post_id ),
						'invite.acceptlink' => $post_link,
						'post.description' => $description
					),
				);

				$bp_send_email_return;
				foreach ($usersCombined as $iuser) {
					$bp_send_email_return = bp_send_email( $email_type, (int)$iuser, $email_args);
				} 
			}


			//INVITE EMAIL LIST
			if (isset($_POST['acf']['field_5fa42d4887adb']) &&  !empty($_POST['acf']['field_5fa42d4887adb']) ) {
				$input = $_POST['acf']['field_5fa42d4887adb'];
				$emails = explode("\n", str_replace("\r", "", $input));

				$invite_link = add_query_arg( array(
					'post_id' => $post_id
				), site_url('/register') );

				$habitus_email_class = new Habitus_Theme_Email();
				$habitus_email_class->email_new_challenge_invite($emails, $post, $post_id);
			}
		}
		
	}

	function et_get_original_footer_credits() {
		return sprintf( __( 'Designed by %1$s | Powered by %2$s', 'Hot habby' ), '<a href="http://www.elegantthemes.com" title="Premium WordPress Themes">Elegant Themes</a>', '<a href="http://www.wordpress.org">WordPress</a>' );
	}


public function pippin_login_fail( $username ) {
     $referrer = $_SERVER['HTTP_REFERER'];  // where did the post submission come from?
     // if there's a valid referrer, and it's not the default log-in screen
     if ( !empty($referrer) && !strstr($referrer,'wp-login') && !strstr($referrer,'wp-admin') ) {
          wp_redirect(home_url() . '/?login=failed' );  // let's append some information (login=failed) to the URL for the theme to use
          exit;
     }
}

	// Function to change email address
	function wpb_sender_email( $original_email_address ) {
		return 'post@infoladen.com';
	}
 
	// Function to change sender name
	function wpb_sender_name( $original_email_from ) {
		return 'Habitus for You';
	}

}//end class


