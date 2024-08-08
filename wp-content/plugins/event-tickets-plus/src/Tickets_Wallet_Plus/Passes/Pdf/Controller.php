<?php
/**
 * Service Provider for Passes\Pdf functionality.
 *
 * @since 6.0.0 Migrated to Event Tickets Plus from Wallet Plus
 *
 * @package TEC\Tickets_Wallet_Plus\Passes\Pdf
 */

namespace TEC\Tickets_Wallet_Plus\Passes\Pdf;

use TEC\Tickets_Wallet_Plus\Admin\Settings\Wallet_Tab;
use TEC\Tickets_Wallet_Plus\Contracts\Passes\Controller_Abstract;
use TEC\Tickets_Wallet_Plus\Contracts\Passes\Modifier_Abstract;
use TEC\Tickets_Wallet_Plus\Contracts\Settings\Settings_Abstract;

/**
 * Class Controller
 *
 * @since 6.0.0 Migrated to Event Tickets Plus from Wallet Plus
 *
 * @package \TEC\Tickets_Wallet_Plus\Passes\Pdf
 */
class Controller extends Controller_Abstract {
	/**
	 * Stores all the modifiers for the pass.
	 *
	 * @since 6.0.0 Migrated to Event Tickets Plus from Wallet Plus
	 *
	 * @var array<string|Modifier_Abstract> The modifiers for the pass.
	 */
	protected array $modifiers = [
		Modifiers\Attendee_Table_Row_Actions::class,
		Modifiers\Handle_Pass_Redirect::class,
		Modifiers\Include_To_Attendee_Modal::class,
		Modifiers\Include_To_Rsvp::class,
		Modifiers\Attach_To_Emails::class,
		Modifiers\Include_To_My_Tickets::class,
		Modifiers\Include_To_Attendees_List::class,
	];

	/**
	 * Determines if this controller will register.
	 * This is present due to how UOPZ works, it will fail if method belongs to the parent/abstract class.
	 *
	 * @since 6.0.0 Migrated to Event Tickets Plus from Wallet Plus
	 *
	 * @return bool
	 */
	public function is_active(): bool {
		return true;
	}

	/**
	 * @inheritDoc
	 */
	public function is_enabled(): bool {
		$pass_enabled = $this->container->make( Settings\Enable_Pdf_Setting::class )->get_value();

		return tribe_is_truthy( $pass_enabled );
	}

	/**
	 * @inheritDoc
	 */
	public function do_register(): void {
		$this->container->singleton( Settings::class, Settings::class );
		$this->container->bind( Pass::class, Pass::class );

		parent::do_register();

		$this->add_actions();
	}

	/**
	 * @inheritDoc
	 */
	public function get_settings(): Settings_Abstract {
		return $this->container->make( Settings::class );
	}

	/**
	 * @inheritDoc
	 */
	public function add_actions(): void {
		add_action( 'tribe_settings_after_form_element_tab_' . Wallet_Tab::$slug, [ $this, 'render_sample_button' ] );
	}

	/**
	 * @inheritDoc
	 */
	public function remove_actions(): void {
		remove_action( 'tribe_settings_after_form_element_tab_' . Wallet_Tab::$slug, [ $this, 'render_sample_button' ] );
	}

	/**
	 * Render sample button.
	 *
	 * @since 6.0.0 Migrated to Event Tickets Plus from Wallet Plus
	 *
	 * @return void
	 */
	public function render_sample_button() {
		tribe( Sample::class )->render_button();
	}

	/**
	 * @inheritDoc
	 */
	public function get_slug(): string {
		return 'pdf';
	}

	/**
	 * @inheritDoc
	 */
	public function get_name(): string {
		return __( 'PDF tickets', 'event-tickets-plus' );
	}
}
