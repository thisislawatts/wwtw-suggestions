<?php
/*
Plugin Name: Suggestions
Version: 0.1-alpha
Description: Lighweight widget for handling user submissions
Author: Luke Watts
Author URI: http://thisis.la
Plugin URI: http://github.com/thisislawatts
Text Domain: wwtw
Domain Path: /languages
*/

require 'wwtw-widget.php';


class WWTW_Suggestions {

	public function __construct()
	{
		add_action('wp', array( $this, 'post' ) );

		add_action('wp_enqueue_scripts', array( $this, 'scripts') );

		add_action('wp_ajax_wwtw_suggestion', array( $this, 'ajax') );
	}

	public function ajax () {

		$response = array(
			'status' => 'error',
			'errors' => array()
		);

		if ( $_POST['action'] === 'wwtw_suggestion' ) {
			parse_str( $_POST['data'], $data );

			if ( $this->process( $data ) ) {
				$response['status'] = 'ok';
				$response['message'] = apply_filters( 'the_content', __( 'Thank\'s for sending in your suggestion.', 'wwtw') );
			} else {
				array_push( $response['errors'], 'email-failed' );
			}
		}

		echo json_encode( $response );
		exit();
	}

	public function post( $wp ) {
		if ( isset( $_POST['wwtw_suggestion'] ) )
			$this->process( $_POST );
	}

	public function process( $data )
	{

		if ( isset($data['wwtw_nonce']) ) {
			if ( wp_verify_nonce( $data['wwtw_nonce'] ) ) {

				$msg = $data['wwtw_suggestion'];

				if ( isset($data['wwtw_email']) && $data['wwtw_email'] !== '' )
					$msg .= "\n Suggested by " . $data['wwtw_email'];

				$headers = array(
					'From: Suggestions Widget<suggestions@' . $_SERVER['HTTP_HOST'] . '>'
				);

				$email = wp_mail( get_bloginfo( 'admin_email' ), __('Suggestion - ' . get_bloginfo( 'name' ), 'wwtw' ), $msg, implode("\r\n", $headers ) );
				return $email;
			}

		}
	}

	public function scripts() {
		wp_enqueue_script( 'wwtw-suggestion', plugins_url( 'js/script.js', __FILE__ ), array('jquery'), '0.1.0' , true );
		wp_localize_script( 'wwtw-suggestion', 'WWTWSuggestion', array(
			'ajax_url' => admin_url( 'admin-ajax.php' )
		) );
	}
}

new WWTW_Suggestions();