<?php

class Habitus_Theme_Permissions {

    public function is_users_post() {

        if ( !is_user_logged_in() || (get_the_author_meta( 'ID' ) != get_current_user_id()) ) {
            return false;
        }
        else {
            return true;
        } 
    }

    public function can_user_view_post($post) {
       
        $user_id = get_current_user_id();

        if ( !is_user_logged_in() ) {
            return false;
            
        }

        if ( ($post->post_author == $user_id) ) {
            return true;
            
        }

        $users_groups = groups_get_user_groups( $user_id );
			if ( empty( $users_groups['groups'] ) ) {
                $groups = array( 'groups' => 0 );
            }

        $post_group = get_field('group_id', $post->ID);

        if (in_array($post_group, $users_groups['groups'])) {
            return true;
        }

        __return_false(  );
    }

    public function login_redirect(){

        if ( !is_user_logged_in() ) {
            $redirect_to = '';
            if (isset($_SERVER['REQUEST_URI'])) {
                $redirect_to = site_url('','https') . '?redirect-to=' . $_SERVER['REQUEST_URI'];
            } else {
                $redirect_to = site_url('','https');
            } 
            wp_redirect($redirect_to);
            exit;
        }  
    }


}