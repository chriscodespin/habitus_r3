<?php

class Habitus_Theme_Admin {

	function create_habitus_email_templates() {
		$this->new_challenge_invite_email_message();
		$this->registration_invite_email_via_challenge();
		$this->new_tracking_entry_email();
	}

	function new_challenge_invite_email_message() {
 
		// Do not create if it already exists and is not in the trash
		$post_exists = post_exists( '[{{{site.name}}}] You have been invited to a new Challenge!' );
	 
		if ( $post_exists != 0 && get_post_status( $post_exists ) == 'publish' )
		   return;
	  
		// Create post object
		$my_post = array(
		  'post_title'    => __( '{{challenger.name}} You have been invited to a new Challenge!', 'buddypress' ),
		  'post_content'  => __( file_get_contents(__DIR__ .'/partials/new_challenge_invite_email.html'), 'buddypress' ),  // HTML email content.
		  'post_excerpt'  => __( '{{challenger.name}} Has invited you to {{post.title}}. - via plain text', 'buddypress' ),  // Plain text email content.
		  'post_status'   => 'publish',
		  'post_type' => bp_get_email_post_type() // this is the post type for emails
		);
	 
		// Insert the email post into the database
		$post_id = wp_insert_post( $my_post );
	 
		if ( $post_id ) {
		// add our email to the taxonomy term 'post_received_comment'
			// Email is a custom post type, therefore use wp_set_object_terms
	 
			$tt_ids = wp_set_object_terms( $post_id, 'new_challenge_invite', bp_get_email_tax_type() );
			foreach ( $tt_ids as $tt_id ) {
				$term = get_term_by( 'term_taxonomy_id', (int) $tt_id, bp_get_email_tax_type() );
				wp_update_term( (int) $term->term_id, bp_get_email_tax_type(), array(
					'description' => 'User enters/selects Members or a Group to invite to challange.',
				) );
			}
		}
	 
	}


	function new_tracking_entry_email() {
 
		// Do not create if it already exists and is not in the trash
		$post_exists = post_exists( '{{site.user}} tracked {{post.title}} !' );
	 
		if ( $post_exists != 0 && get_post_status( $post_exists ) == 'publish' )
		   return;
	  
		// Create post object
		$my_post = array(
		  'post_title'    => __( '{{site.user}} hit {{post.title}} !', 'buddypress' ),
		  'post_content'  => __( 'put html here', 'buddypress' ),  // HTML email content.
		  'post_excerpt'  => __( '{{site.user}} hit {{post.title}}.', 'buddypress' ),  // Plain text email content.
		  'post_status'   => 'publish',
		  'post_type' => bp_get_email_post_type() // this is the post type for emails
		);
	 
		// Insert the email post into the database
		$post_id = wp_insert_post( $my_post );
	 
		if ( $post_id ) {
		// add our email to the taxonomy term 'post_received_comment'
			// Email is a custom post type, therefore use wp_set_object_terms
	 
			$tt_ids = wp_set_object_terms( $post_id, 'new_tracking_entry', bp_get_email_tax_type() );
			foreach ( $tt_ids as $tt_id ) {
				$term = get_term_by( 'term_taxonomy_id', (int) $tt_id, bp_get_email_tax_type() );
				wp_update_term( (int) $term->term_id, bp_get_email_tax_type(), array(
					'description' => 'User creates new tracking entry.',
				) );
			}
		}
	}

	function registration_invite_email_via_challenge() {
 
		// Do not create if it already exists and is not in the trash
		$post_exists = post_exists( '[{{{site.name}}}] You have been invited to a new Challenge!' );
	 
		if ( $post_exists != 0 && get_post_status( $post_exists ) == 'publish' )
		   return;
	  
		// Create post object
		$my_post = array(
		  'post_title'    => __( '{{challenger.name}} has invited you to {{post.title}}', 'buddypress' ),
		  'post_content'  => __( file_get_contents(__DIR__ .'/partials/email_registration_invite.html'), 'buddypress' ),  // HTML email content.
		  'post_excerpt'  => __( '{{challenger.name}} Has invited you to {{post.title}}. - via plain text', 'buddypress' ),  // Plain text email content.
		  'post_status'   => 'publish',
		  'post_type' => bp_get_email_post_type() // this is the post type for emails
		);
	 
		// Insert the email post into the database
		$post_id = wp_insert_post( $my_post );
	 
		if ( $post_id ) {
		// add our email to the taxonomy term 'post_received_comment'
			// Email is a custom post type, therefore use wp_set_object_terms
	 
			$tt_ids = wp_set_object_terms( $post_id, 'registration_invite_email_via_challenge', bp_get_email_tax_type() );
			foreach ( $tt_ids as $tt_id ) {
				$term = get_term_by( 'term_taxonomy_id', (int) $tt_id, bp_get_email_tax_type() );
				wp_update_term( (int) $term->term_id, bp_get_email_tax_type(), array(
					'description' => 'User enters email address to invite to challange.',
				) );
			}
		}
	 
	}
	
}
