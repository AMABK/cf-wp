<?php
/*
Plugin Name: Contact Form
Plugin URI: https://optimuse-solutions.com
Description: WP Contact Form
Version: 1.01
Author: Mate
Author URI: https://www.optimuse-solutions.com
*/

function html_form_code() {
    echo '<h3>Contact Form</h3>';
	echo '<form action="' . esc_url( $_SERVER['REQUEST_URI'] ) . '" method="post">'
                . '<p>'
	. 'Name (*) <br/>'
	. '<input type="text" name="contact-name" pattern="[a-zA-Z0-9 ]+" value="' . ( isset( $_POST["contact-name"] ) ? esc_attr( $_POST["contact-name"] ) : '' ) . '" size="40" />'
	. '</p>'
	. '<p>'
	. 'Email (*) <br/>'
	. '<input type="email" name="contact-email" value="' . ( isset( $_POST["contact-email"] ) ? esc_attr( $_POST["contact-email"] ) : '' ) . '" size="40" />'
	. '</p>'
	. '<p>'
	. 'Subject (*) <br/>'
	. '<input type="text" name="contact-subject" pattern="[a-zA-Z ]+" value="' . ( isset( $_POST["contact-subject"] ) ? esc_attr( $_POST["contact-subject"] ) : '' ) . '" size="40" />'
	. '</p>'
	. '<p>'
	. 'Message (*) <br/>'
	. '<textarea rows="10" cols="35" name="contact-message">' . ( isset( $_POST["contact-message"] ) ? esc_attr( $_POST["contact-message"] ) : '' ) . '</textarea>'
	. '</p>'
	. '<p><input type="submit" name="contact-submitted" value="Submit"></p>'
	. '</form>';
}

function deliver_mail() {


	if ( isset( $_POST['contact-submitted'] ) ) {

		// sanitize form values
		$name    = sanitize_text_field( $_POST["contact-name"] );
		$email   = sanitize_email( $_POST["contact-email"] );
		$subject = sanitize_text_field( $_POST["contact-subject"] );
		$message = esc_textarea( $_POST["contact-message"] );

		// get reciever email
		$to = get_option( 'admin_email' );

		$headers = "From: $name <$email>" . "\r\n";

		// show respective message based on sending results
		if ( wp_mail( $to, $subject, $message, $headers ) ) {
			echo '<div>';
			echo '<p>Message successsfully sent.</p>';
			echo '</div>';
		} else {
			echo 'Error occured';
		}
	}
}

function contact_shortcode() {
	ob_start();
	deliver_mail();
	html_form_code();

	return ob_get_clean();
}

add_shortcode( 'contact_form', 'contact_shortcode' );

?>