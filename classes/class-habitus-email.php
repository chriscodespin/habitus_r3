<?php
class Habitus_Theme_Email {

    function email_new_challenge_invite( $emails, $post, $post_id ) {

		$invite_link = "";
		$email_args = "";

		$invite_link = add_query_arg( array(
				'post_id' => $post_id
			), site_url('/register') );
 
			$email_args = array(
				'tokens' => array(
					'site.name' => get_bloginfo( 'name' ),
					'challenger.name' => get_the_author_meta( 'user_nicename' , $post->post_author ),
					'post.title' => $post->post_title,
					'invite.acceptlink' => $invite_link
				),
			);
			// send args and user ID to receive email
			foreach ($emails as $email) {
				bp_send_email( 'registration_invite_email_via_challenge', $email, $email_args );
			}
			
		
	}

    public function new_challenge_email_settings() {

        if ( !$send_requests = bp_get_user_meta( bp_displayed_user_id(), 'new_challenge_invite_request', true ) )
            $send_requests   = 'yes';
    
        if ( !$accept_requests = bp_get_user_meta( bp_displayed_user_id(), 'new_challenge_invite_accepted', true ) )
            $accept_requests = 'yes'; ?>
            
            <table class="notification-settings" id="activity-notification-settings">
                <thead>
                    <tr>
                        <th class="icon">&nbsp;</th>
                        <th class="title"><?php _e( 'Challenge', 'buddypress' ) ?></th>
                        <th class="yes"><?php _e( 'Yes', 'buddypress' ) ?></th>
                        <th class="no"><?php _e( 'No', 'buddypress' )?></th>
                    </tr>
                </thead>
    
                <tbody>
                    <tr id="new-challenge-invite-request">
                        <td></td>
                        <td><?php _ex( 'A member Invites or adds you to a new challenge', 'Friend settings on notification settings page', 'buddypress' ) ?></td>
                        <td class="yes"><input type="radio" name="notifications[new_challenge_invite_request]" id="new-challenge-invite-request-yes" value="yes" <?php checked( $send_requests, 'yes', true ) ?>/><label for="new-challenge-invite-request-yes" class="bp-screen-reader-text"><?php
                            /* translators: accessibility text */
                            _e( 'Yes, send email', 'buddypress' );
                        ?></label></td>
                        <td class="no"><input type="radio" name="notifications[new_challenge_invite_request]" id="new-challenge-invite-request-no" value="no" <?php checked( $send_requests, 'no', true ) ?>/><label for="new-challenge-invite-request-no" class="bp-screen-reader-text"><?php
                            /* translators: accessibility text */
                            _e( 'No, do not send email', 'buddypress' );
                        ?></label></td>
                    </tr>
                    <tr id="new-challenge-invite-accepted">
                        <td></td>
                        <td><?php _ex( 'A member accepts your challenge request', 'Friend settings on notification settings page', 'buddypress' ) ?></td>
                        <td class="yes"><input type="radio" name="notifications[new_challenge_invite_accepted]" id="new-challenge-invite-accepted-yes" value="yes" <?php checked( $accept_requests, 'yes', true ) ?>/><label for="new-challenge-invite-accepted-yes" class="bp-screen-reader-text"><?php
                            /* translators: accessibility text */
                            _e( 'Yes, send email', 'buddypress' );
                        ?></label></td>
                        <td class="no"><input type="radio" name="notifications[new_challenge_invite_accepted]" id="new-challenge-invite-accepted-no" value="no" <?php checked( $accept_requests, 'no', true ) ?>/><label for="new-challenge-invite-accepted-no" class="bp-screen-reader-text"><?php
                            /* translators: accessibility text */
                            _e( 'No, do not send email', 'buddypress' );
                        ?></label></td>
                    </tr>
                </tbody>
            </table>	
                
                
                <?php
        }

         // Function to change email address
	function wpb_sender_email( $original_email_address ) {
		return 'post@infoladen.com';
	}
 
	// Function to change sender name
	function wpb_sender_name( $original_email_from ) {
		return 'Habitus for You';
	}


}