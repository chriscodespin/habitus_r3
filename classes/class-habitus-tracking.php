<?php

class Habitus_Theme_Tracking {

    public  $hotvar;
    public $objTrackingDate;
    public $results;
    public $tracking_cnt;
    public $target_day_cnt;
    public $past_target_day_cnt;
    public $now;
    public $dt1; 
    public $dt2;
    public $today;
    public $objToday;
    public $track_id;
    public $comment;
    public $completed;
    public $offset;
    public $previous_link;
    public $next_link;
    public $user_timezone;


    public function __construct() {
        $this->init_vars();
        //$this->get_tracking();
    }
    
    private function init_vars() {
        $this->today =  date('Y-m-d');
        $this->objTrackingDate = date_create($this->today, $this->user_timezone);
        $this->objToday = date_create($this->today, $this->user_timezone);
        $this->results = null;
        $this->tracking_cnt = 0;
        $this->target_day_cnt = 0;
        $this->past_target_day_cnt = 0;
        $this->offset = 0;
        $this->now = new DateTime('now');
        $this->track_id = 0;
        $this->comment = '';
        $this->previous_link = 'disabled';
        $this->next_link = 'disabled';
    }

    private function week_between_two_dates($date1, $date2)
		{
			$first = DateTime::createFromFormat('m/d/Y', $date1);
			$second = DateTime::createFromFormat('m/d/Y', $date2);
			if($date1 > $date2) return $this->week_between_two_dates($date2, $date1);
			return floor($first->diff($second)->days/7);
		}
	
    private function total_days_between_two_dates($date1, $date2)
	{
		$first = DateTime::createFromFormat('m/d/Y', $date1);
		$second = DateTime::createFromFormat('m/d/Y', $date2);
		if($date1 > $date2) return $this->total_days_between_two_dates($date2, $date1);
		return $first->diff($second)->days;
	}
	
	// function active_days_between_two_dates($date1, $date2, $days_per_week)
	// {
	// 	if ($days_per_week == 0) return 0;
	// 	$first = DateTime::createFromFormat('m/d/Y', $date1);
	// 	$second = DateTime::createFromFormat('m/d/Y', $date2);
	// 	if($date1 > $date2) return active_days_between_two_dates($date2, $date1);
	// 	$weeks = $first->diff($second)->days/7;
	// 	return floor($weeks * $days_per_week);
	// }
	
	private function target_days_between_two_dates($begin = NULL)
	{
        $count = 0;

        if ($begin == NULL) {
            $begin = new DateTime($this->dt1);
        } 

		$end = new DateTime($this->dt2);
	
		$interval = DateInterval::createFromDateString('1 day');
		$period = new DatePeriod($begin, $interval, $end);
        $target_days = get_field('target_days');
		foreach ($period as $dt) {
            $day = $dt->format("D");

            //set day abreviation
            if (substr($day, 0,1) == "S") {
                $day = substr($day,0, 2);
            } else {
                $day = substr($day,0, 1);
            }

            in_array($day, $target_days) ? $count++ : 0 ;
            //in_array($day, $target_days) ? $this->target_day_cnt++ : 0 ;
            
		}

        return $count;
        
	}
	
	public function days_left() {
		$end = DateTime::createFromFormat('m/d/Y', $this->dt2);

        if ($this->now < $end) {
            return $this->now->diff($end)->days;
        } else {
            return 0;
        }
		
    }

    public function target_days_left(){
        return $this->target_days_between_two_dates($this->objTrackingDate);
    }
    
    private function set_tracking_date(){

        if(isset($_GET['date-offset']) && !empty($_GET['date-offset'])) {
            $this->offset = $_GET['date-offset'];
            date_modify($this->objTrackingDate, $this->offset . ' day');        
        }
    }

    public function get_group_tracking($post_id) {
    
        global $wpdb;
        $tracking_data = null;
    
        /*GET ALL TRACKING BY POST ID*/
        $user_sql = $wpdb->prepare("SELECT u.ID, u.user_nicename, DATE_FORMAT(t.tracking_date, '%a %c/%e/%Y') tracking_date, t.completed, t.comment 
                                    FROM `tracking` t
                                    INNER JOIN `wp_users` u ON  t.user_id = u.ID 
                                    WHERE t.post_id = %d 
                                    ORDER BY t.id DESC ", $post_id ); 
    
        $tracking_data = $wpdb->get_results( $user_sql, OBJECT );
    
        return $tracking_data;
    }

    public function get_group_tracking_meta($post_id) {
        global $wpdb;
        $result = null;

        /*Get user and tracking data */
        $user_sql = $wpdb->prepare("SELECT u.ID, u.user_nicename, t.progress FROM   `tracking_meta` t
                                    INNER JOIN `wp_users` u ON t.user_id = u.ID
                                    WHERE t.post_id = %d 
                                    ORDER BY t.progress DESC", $post_id); 

        $result = $wpdb->get_results( $user_sql, OBJECT );

        return $result;
    }

    public function get_tracking_meta($post_id) {
        global $wpdb;
        $result = null;

        /*Get user and tracking data */
        $user_sql = $wpdb->prepare("SELECT id, share_updates_and_comments,
                                            cc_email_tracking_comments,
                                            opt_out
                                    FROM   `tracking_meta` 
                                    WHERE post_id = %d 
                                    AND user_id = %d", $post_id, get_current_user_id()); 

    $result = $wpdb->get_row( $user_sql, OBJECT );

    return $result;
    }

    public function get_tracking() {
        global $wpdb;
        $sql = "SELECT id, completed, tracking_date, DATE_FORMAT(tracking_date, '%a %c/%e/%Y') tracking_date_display, comment FROM tracking 
                WHERE post_id = " . get_the_ID() . "
                AND user_id = " . get_current_user_id() . 
                " ORDER BY id DESC" ;
        $this->results = $wpdb->get_results( $sql, OBJECT );

        //count rows that were marked completed
        $i_tracking_cnt = 0; 
        foreach ($this->results as $v) {
            if ($v->completed == 1) {
                $i_tracking_cnt = ++$i_tracking_cnt;
            }
        }

        $this->tracking_cnt = $i_tracking_cnt;
        //$this->tracking_cnt = sizeof($this->results);

        $this->now = new DateTime('now');
        
        $this->dt1 = get_field('start_date');
        $this->dt2 = get_field('end_date');

        if ($this->objTrackingDate > date_create($this->dt1)) {
            $this->previous_link = '';
        }

        if ($this->objTrackingDate < $this->objToday) {
            $this->next_link = '';
        }

        $this->target_day_cnt =  $this->target_days_between_two_dates();

        //set vars for current tracking record
        $this->tracked();

    }

    public function send_group_tracking_message($args, $email_args){
        global $wpdb;
		 $group_members = [];

         $group_members = get_members_by_group_id($args['group_id']);

        //remove author from list of recipients
        $user_id = get_current_user_id();
        // if (in_array($user_id, $group_members)) {
        //     unset($group_members[array_search($user_id, $group_members)]);
        // }

        //insert challenge message record for each group member
        foreach ($group_members as $iuser) {
            $wpdb->insert( 
                'challenge_messages', 
                array( 
                    'sender_id' => $args['sender_id'], 
                    'recipient_id' => $iuser,
                    'tracking_id' => $args['tracking_id'],
                    'message_type_id' => 1,
                    'message' => $args['message']
                    ), 
                    array('%d', '%d', '%d', '%d', '%s')
                );
            }

            //send emails
            $bp_send_email_return = false;
            $email_type = 'new_tracking_entry';
            foreach ($group_members as $iuser) {
                $bp_send_email_return = bp_send_email( $email_type, (int)$iuser, $email_args);
            } 
    }

    public function update_tracking_meta($action, $args) {
        global $wpdb;

        if($action == 'update-settings' ) {
            //update meta
            $updated = $wpdb->update( 
                'tracking_meta', 
                array( 
                    'share_updates_and_comments' => $args['share_updates_and_comments'],
                    'cc_email_tracking_comments' => $args['cc_email_tracking_comments']
                    ), 
                array( 'id' => $args['meta_id'] ), 
                array('%d','%d'),
                array('%d')
                );
        }

        return $updated;

    }
    
    public function create_tracking_meta($post_id, $user_id) {
        global $wpdb;

        //insert new meta record with defaults
            $wpdb->insert( 
                'tracking_meta', 
                array( 
                    'post_id' => $post_id, 
                    'user_id' => $user_id,
                    'progress' => 0,
                    'share_updates_and_comments' => 1,
                    'cc_email_tracking_comments' => 1
                    ), 
                array('%d', '%d', '%d', '%d', '%d')
                );
        
    }

    public function update_tracking_progress($post_id, $user_id, $post_progress) {
        global $wpdb;

        //update meta
        $error = $wpdb->update( 
            'tracking_meta', 
            array( 
                'progress' => $post_progress
                ), 
            array( 'post_id' => $post_id, 'user_id' => $user_id ), 
            array('%d'),
            array('%d', '%d')
            );
     
        //insert new meta record with defaults
        if ($error == 0) {
            $wpdb->insert( 
                'tracking_meta', 
                array( 
                    'post_id' => $post_id, 
                    'user_id' => $user_id,
                    'progress' => $post_progress,
                    'share_updates_and_comments' => 1,
                    'cc_email_tracking_comments' => 1
                    ), 
                array('%d', '%d', '%d', '%d', '%d')
                );
        }
    }
    

    public function process_tracking () {

        global $wpdb;
        $tracking_comment = '';

        //completed or just a comment
        $completed = 0;
        if (!empty($_POST['cb_complete'])) {
            $completed = 1;
        }

        if(isset($_POST['comment']) && !empty($_POST['comment'])) {
            $tracking_comment = stripslashes_deep($_POST['comment']);
        }

        $this->set_tracking_date();

        if(isset($_POST['track']) && !empty($_POST['track'])) {
            //delete
            if (!empty($_POST['meta-id']) && empty($_POST['cb_complete']) && empty($_POST['comment'])) {
                $wpdb->delete( 'tracking', array('id' => $_POST['meta-id']), array( '%d' ) );
                
                //if it was a previously check tracking then decrement the progress
                if (isset($_POST['previously_tracked']) && $_POST['previously_tracked'] == 1) {
                    if(isset($_POST['tracking-cnt']) && !empty($_POST['tracking-cnt'])) {
                    $local_tracking_count = (int)($_POST['tracking-cnt']) - 1;

                    $post_progress = (int)(($local_tracking_count/$_POST['target-day-cnt']) * 100);
                    $this->update_tracking_progress( get_the_ID(), get_current_user_id(), $post_progress);
                    }
                }
                
            } 
            //INSERT
            elseif (empty($_POST['meta-id'])) {

                date_default_timezone_set($this->user_timezone);
                $d = new DateTime('now');

                    $error = $wpdb->insert( 
                        'tracking', 
                        array( 
                            'completed' => $completed,
                            'post_id' => get_the_ID(), 
                            'tracking_date' => $this->objTrackingDate->format('Y-m-d'),
                            'date_stamp' => $d->format('Y-m-d H:i:s'),
                            'comment' => $tracking_comment,
                            'user_id' => get_current_user_id()
                            ), 
                        array('%d','%d','%s','%s','%s','%d') 
                        );
                
                //buid args for challenge_message table
                if (0 < $group_id = get_field('group_id', the_ID())) {
                    $user = wp_get_current_user();

                    if (isset($_POST['share-comments']) && $_POST['share-comments'] == 1 ) {

                        $msgArgs = array(
                            'sender_id' => get_current_user_id(),
                            'group_id' => $group_id,
                            'tracking_id' => $wpdb->insert_id,
                            'message_type_id' => 1,
                            'message' => $user->display_name .  ' updated ' . get_the_title() 
                        );
    
                        //SEND EXISTING MEMBERS AN EMAIL
                        $email_args = array(
                            'tokens' => array(
                                'site.name' => get_bloginfo( 'name' ),
                                'site.user' => $user->display_name,
                                'post.title' => get_the_title(),
                                'post.link' => get_the_permalink(),
                                'post.date' => date('m-d-Y'),
                                'tracking.comment' => $tracking_comment
                            ),
                        );
    
                        $this->send_group_tracking_message($msgArgs, $email_args);
                    } 
        
                } // if 0 < $group_id

                //increment progress
                if ($completed) {
                    $local_tracking_count = (int)($_POST['tracking-cnt']) + 1;

                    $post_progress = (int)(($local_tracking_count/$_POST['target-day-cnt']) * 100);
                    //$post_progress = $_POST['post-progress'];
                    $this->update_tracking_progress( get_the_ID(), get_current_user_id(), $post_progress);
                }      
            } 
            //UPDATE
            elseif (!empty($_POST['meta-id']) ) {

                //increment progress
                if ($completed) {
                    if (isset($_POST['previously_tracked']) && $_POST['previously_tracked'] == 0) {
                        if(isset($_POST['tracking-cnt']) && !empty($_POST['tracking-cnt'])) {
                            $local_tracking_count = (int)($_POST['tracking-cnt']) + 1;
                            $post_progress = (int)(($local_tracking_count/$_POST['target-day-cnt']) * 100);
                            $this->update_tracking_progress( get_the_ID(), get_current_user_id(), $post_progress);             
                        }
                    }
                }

                //decrement progress
                if (!$completed) {
                    if (isset($_POST['previously_tracked']) && $_POST['previously_tracked'] == 1) {
                        if(isset($_POST['tracking-cnt']) && !empty($_POST['tracking-cnt'])) {
                            $local_tracking_count = (int)($_POST['tracking-cnt']) - 1;
                            $post_progress = (int)(($local_tracking_count/$_POST['target-day-cnt']) * 100);
                            $this->update_tracking_progress( get_the_ID(), get_current_user_id(), $post_progress);
                        }
                    }       
                }

                $error = $wpdb->update( 
                    'tracking', 
                    array( 
                        'completed' => $completed,
                        'comment' => $_POST['comment']
                        ), 
                    array( 'id' => $_POST['meta-id'] ), 
                    array('%s'),
                    array('%d')
                    );
             $wpdb->print_error();
            }
        else {
          //  echo 'no submitty <br>';
          $drop_here = 'drop here';
            }
        }
    }

    public function tracked() {
        $sDate = $this->objTrackingDate->format('Y-m-d');
        $this->track_id = 0;

        foreach ($this->results as $result) {
            if ($result->tracking_date == $sDate) {
                $this->track_id = $result->id;
                $this->comment =  $result->comment;
                if ( $result->completed == 1) {
                    $this->completed = 1;
                    return 1;
                } else {
                    $this->completed = 0;
                    return 0;
                }
            }
        }
        $this->completed = 0;
        return 0;
    }

    //REMOVE TRACKING RECORDS BEFORE DELETING POST
	public function delete_post_tracking($postid) {
        global $wpdb;
        $wpdb->delete( 'tracking_meta', array('post_id' => $postid), array( '%d' ) );
		$wpdb->delete( 'tracking', array('post_id' => $postid), array( '%d' ) );
        
    }


}