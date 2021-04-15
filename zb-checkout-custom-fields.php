<?php
/**
 * Plugin Name: ZB Checkout Custom Field
 * Description: Plugin for custom field adding - textarea Additionally Info
 */

// Add field on checkout page
add_action( 'woocommerce_after_order_notes', 'zb_add_custom_checkout_field' );
function zb_add_custom_checkout_field( $checkout ) {
	echo '<div id="zb_custom_checkout_field"><h2>' . __( 'Additionally', 'zb_plugin' ) . '</h2>';
	woocommerce_form_field( 'zb_text_add_field', array(
		'type'        => 'textarea',
		'class'       => array( 'zb-text-field form-row-wide' ),
		'label'       => __( 'Fill out this field', 'zb_plugin' ),
		'placeholder' => __( 'Enter the text here', 'zb_plugin' ),
		),
		$checkout->get_value( 'zb_text_add_field' )
	);
	echo '</div>';
}

// Update custom filed
add_action( 'woocommerce_checkout_update_order_meta', 'zb_custom_checkout_field_update_order_meta' );
function zb_custom_checkout_field_update_order_meta( $order_id ) {
	if ( ! empty( $_POST['zb_text_add_field'] ) ) {
		update_post_meta( $order_id, 'zb_text_add_field', sanitize_textarea_field( wp_unslash( $_POST['zb_text_add_field'] ) ) );
	}
}

// Add custom field on admin order page
add_action( 'woocommerce_admin_order_data_after_order_details', 'zb_custom_checkout_field_display_admin_order_meta', 10, 1 );
function zb_custom_checkout_field_display_admin_order_meta( $order ) {
	echo '<p class="form-field form-field-wide"><strong>' . __( 'Additionally', 'zb_plugin' ) . ':</strong> ' .
		esc_html( get_post_meta( $order->id, 'zb_text_add_field', true ) ) . '</p>';
}

// Add custom field on thankyou page
add_action( 'woocommerce_thankyou', 'add_custom_field_on_thanks_page', 10, 1 );
function add_custom_field_on_thanks_page( $order_id ) {

	if ( $custom_field_value = get_post_meta( $order_id, 'zb_text_add_field', true ) ) {
		echo '<h2>' . __( 'Additionally', 'zb_plugin' ) . '</h2>';
		echo '<p>' . esc_html( $custom_field_value ) . '</p>';
	}
}

// Add a custom field to the emails
add_filter( 'woocommerce_email_order_meta_fields', 'custom_woocommerce_email_order_meta_fields', 10, 3 );
function custom_woocommerce_email_order_meta_fields( $fields, $sent_to_admin, $order ) {
	if ( $custom_field_value = get_post_meta( $order->id, 'zb_text_add_field', true ) ) {
		$fields['zb_text_add_field'] = array(
			'label' => __( 'Additionally' ),
			'value' => $custom_field_value,
		);
	}

	return $fields;
}
