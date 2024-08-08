<?php
/**
 * PDF Pass: Body - Ticket Info - Attendee Details
 *
 * Override this template in your own theme by creating a file at:
 * [your-theme]/tribe/tickets-plus/tickets-wallet-plus/pdf/pass/body/ticket-info/attendee-details.php
 *
 * See more documentation about our views templating system.
 *
 * @link https://evnt.is/event-tickets-wallet-plus-tpl Help article for Wallet Plus template files.
 *
 * @since 1.0.0
 *
 * @version 1.0.0
 */

$title_height = empty( $qr_enabled ) ? 20 : 80;

?>
<tr>
	<td class="tec-tickets__wallet-plus-pdf-attendee-details-wrapper">
		<table class="tec-tickets__wallet-plus-pdf-attendee-details-table">
			<tr>
				<td width="180" height="<?php echo $title_height; ?>">
					<?php $this->template( 'pdf/pass/body/ticket-info/attendee-details/name' ); ?>
					<?php $this->template( 'pdf/pass/body/ticket-info/attendee-details/ticket-title' ); ?>
				</td>
				<?php $this->template( 'pdf/pass/body/ticket-info/attendee-details/qr-image' ); ?>
			</tr>
			<tr>
				<td width="180">
					<?php $this->template( 'pdf/pass/body/ticket-info/attendee-details/security-code' ); ?>
				</td>
			</tr>
		</table>
	</td>
</tr>
