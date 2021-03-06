<?php

class CGC_FOLLOW_DB {


	private $table;
	private $db_version;

	function __construct() {

		global $wpdb;

		$this->table   		= $wpdb->base_prefix . 'cgc_follow';
		$this->db_version = '1.0';

	}

	/**
	*	Add a single follower
	*
	*	@since 5.0
	*/
	public function add_follower( $args = array() ) {

		global $wpdb;

		$defaults = array(
			'user_id'		=> '',
			'follower'		=> ''
		);

		$args = wp_parse_args( $args, $defaults );

		$add = $wpdb->query(
			$wpdb->prepare(
				"INSERT INTO {$this->table} SET
					`user_id`  		= '%d',
					`follower`  	= '%d'
				;",
				absint( $args['user_id'] ),
				absint( $args['follower'] )
			)
		);

		do_action( 'cgc_follower_added', $args, $wpdb->insert_id );

		if ( $add )
			return $wpdb->insert_id;

		return false;
	}

	/**
	*	Remove a follower
	*
	*	@since 5.0
	*/
	public function remove_follower( $args = array() ) {

		global $wpdb;

		if( empty( $args['user_id'] ) || empty( $args['follower'] )  )
			return;

		do_action( 'cgc_follower_removed', $args );

 		$remove = $wpdb->query( $wpdb->prepare( "DELETE FROM {$this->table} WHERE `user_id` = '%d' AND `follower` = '%d' ;", absint( $args['user_id'] ), absint( $args['follower'] ) ) );

	}

	/**
	*	Get the number of followers for a specific user_id
	*
	*	@since 5.0
	*/
	public function get_followers( $user_id = 0 ) {

		global $wpdb;

		$result = $wpdb->get_results( $wpdb->prepare( "SELECT follower FROM {$this->table} WHERE `user_id` = '%d'; ", absint( $user_id ) ) );

		return $result;
	}

	/**
	*	Get the number of users a user_id is following
	*
	*	@since 5.0
	*/
	public function get_following( $user_id = 0 ) {

		global $wpdb;

		$result = $wpdb->get_results( $wpdb->prepare( "SELECT user_id FROM {$this->table} WHERE `follower` = '%d'; ", absint( $user_id ) ) );

		return $result;
	}

	/**
	*	Check if a user is following someone else
	*
	*	@since 5.0
	*/
	public function is_following( $user_to_check, $current_user = 0 ){

		global $wpdb;

		$result = $wpdb->get_col( $wpdb->prepare( "SELECT user_id FROM {$this->table} WHERE `user_id` = '%d' AND `follower` = '%d'; ", absint( $user_to_check ), absint( $current_user ) ) );

		return $result;

	}

}