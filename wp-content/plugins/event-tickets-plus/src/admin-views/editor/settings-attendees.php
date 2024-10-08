<?php
/**
 * The attendees list checkbox for the settings section.
 *
 * @since 5.9.0 Show the post type dynamically.
 *
 * @version 5.9.0
 */

/** @var \Tribe__Editor $editor */
$editor = tribe( 'editor' );

// Don't show this setting if block editor is active.
if ( $editor->is_events_using_blocks() ) {
	return;
}

$post_id = get_the_ID();

// Get value from metadata.
$show_attendees = get_post_meta( $post_id, Tribe__Tickets_Plus__Attendees_List::HIDE_META_KEY, true );
// Sets the inverted value.
$show_attendees = ! empty( $show_attendees );

/**
 * Filters the default value for showing attendees on event page if no meta field saved
 *
 * @var boolean $show_attendees value of true|false
 */
if ( ! metadata_exists( 'post', $post_id, Tribe__Tickets_Plus__Attendees_List::HIDE_META_KEY ) ) {
	$show_attendees = apply_filters( 'tribe_tickets_plus_default_show_attendees_value', $show_attendees );
}

$post_type_object = get_post_type_object( get_post_type( $post_id ) );

$show_attendees_label = sprintf(
	/* translators: %s is the post type label */
	__( 'Show attendees list on %s page', 'event-tickets-plus' ),
	$post_type_object->labels->singular_name
);

// Add checkbox for attendee display
?>
<p>
	<label>
		<span class="tribe-strong-label"><?php echo esc_html( $show_attendees_label ); ?></span>
		<input
			type="checkbox"
			id="tribe_show_attendees"
			name="tribe-tickets[settings][show_attendees]"
			class="tribe_show_attendees settings_field"
			value="1"
			<?php checked( $show_attendees ); ?>
		>
	</label>
	<p class="description"><?php esc_html_e( 'Show a list of attendees, filtered by those who opt out during purchase.', 'event-tickets-plus' ); ?></p>
</p>
