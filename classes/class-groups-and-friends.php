<?php
class Habitus_Theme_Groups_Friends {

    public function get_groups_by_current_user($field) {
		if (!is_admin() ) {
		
			global $wpdb;
			$sql = "SELECT g.id, g.name FROM `wp_bp_groups` g JOIN `wp_bp_groups_members` m ON g.id = m.group_id  
			WHERE m.user_id = " . get_current_user_id() . " 
			AND m.is_confirmed = 1";
			$groups = $wpdb->get_results( $sql, OBJECT );
			
			
			$defaultvalue = array('0' => 'Not a group challenge',
								'-1' => 'Create new group');
			$choices = [];
			foreach ($groups as $group) {
				$choices[$group->id] = $group->name;
			}
		
			$arr3 = $defaultvalue + $choices; 
		
			$field['choices'] = $arr3;
		}
	
		return $field;
	}

	public function habitus_list_group_challenges() {
		global $wpdb,$bp ;
		
		$guid = $bp->groups->current_group->id;
		//echo 'group ' . $guid;
			// args
			$args = array(
				'numberposts'	=> -1,
				'post_type'		=> 'post',
				'meta_query'	=> array(
					array(
						'key'	 	=> 'group_id',
						'value'	  	=> $guid
							)
						)
					);
			// query
		$the_query = new WP_Query( $args );?>
		<?php if( $the_query->have_posts() ): ?>
		<h3>Group Challenges</h3>
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

	function add_registered_user_to_group($user_id) {
		$post_id = 0;
		$group_id = 0;

		if (isset($_POST['post_id']) && !empty($_POST['post_id']) ) {
			$post_id = $_POST['post_id'];
			$group_id = get_field('group_id', $post_id );
			
			if ($group_id > 0) {
				groups_join_group( (int)$group_id, $user_id ) ;
			}
		}

		//Set object to local time
		if(isset($_POST['local-timezone']) && !empty($_POST['local-timezone'])) {
			date_default_timezone_set($_POST['local-timezone']);
			add_user_meta($user_id, 'time_zone', $_POST['local-timezone'], true );
		}
	}

	public function get_friends_by_current_user($field) {
		if (!is_admin() ) {
			$choices = [];

			if ( bp_has_members(  'user_id=' . bp_loggedin_user_id() ) ) {
				while ( bp_members() ) :
					bp_the_member();
						$choices[bp_get_member_user_id()] = bp_get_member_name();
				endwhile;

				$field['choices'] = $choices;
			}

		}
	
		return $field;
	}


}