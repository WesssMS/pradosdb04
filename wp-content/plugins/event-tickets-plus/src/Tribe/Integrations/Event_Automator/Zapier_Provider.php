<?php
/**
 * The Zapier service provider.
 *
 * @since 6.0.0
 * @package Tribe\Tickets\Plus\Integrations\Event_Automator
 */

namespace Tribe\Tickets\Plus\Integrations\Event_Automator;

use TEC\Common\Contracts\Service_Provider;
use TEC\Event_Automator\Zapier\REST\V1\Endpoints\Actions\Find_Attendees;
use TEC\Event_Automator\Zapier\REST\V1\Endpoints\Actions\Find_Tickets;
use TEC\Event_Automator\Zapier\REST\V1\Endpoints\Attendees;
use TEC\Event_Automator\Zapier\REST\V1\Endpoints\Checkin;
use TEC\Event_Automator\Zapier\REST\V1\Endpoints\Orders;
use TEC\Event_Automator\Zapier\REST\V1\Endpoints\Refunded_Orders;
use TEC\Event_Automator\Zapier\REST\V1\Endpoints\Updated_Attendees;
use TEC\Event_Automator\Zapier\Settings;

use TEC\Event_Automator\Zapier\REST\V1\Endpoints\Actions\Create_Events;
use TEC\Event_Automator\Zapier\REST\V1\Endpoints\Actions\Update_Events;
use TEC\Event_Automator\Zapier\REST\V1\Endpoints\Actions\Find_Events;
use TEC\Event_Automator\Zapier\REST\V1\Endpoints\Canceled_Events;
use TEC\Event_Automator\Zapier\REST\V1\Endpoints\New_Events;
use TEC\Event_Automator\Zapier\REST\V1\Endpoints\Updated_Events;

/**
 * Class Zapier_Provider
 *
 * @since 6.0.0
 *
 * @package Tribe\Events\Pro\Integrations\Event_Automator
 */
class Zapier_Provider extends Service_Provider {
	/**
	 * Binds and sets up implementations.
	 *
	 * @since 6.0.0
	 */
	public function register() {
		if ( ! self::is_active() ) {
			return;
		}

		$this->add_actions();
		$this->add_filters();
	}

	/**
	 * Returns whether the event status should register, thus activate, or not.
	 *
	 * @since 6.0.0
	 *
	 * @return bool Whether the event status should register or not.
	 */
	public static function is_active() {
		return \TEC\Event_Automator\Zapier\Zapier_Provider::is_active();
	}

	/**
	 * Adds the actions required for event status.
	 *
	 * @since 6.0.0
	 */
	protected function add_actions() {
		add_action( 'rest_api_init', [ $this, 'register_endpoints' ] );

		// Add endpoints to settings dashboard.
		add_action( 'admin_init', [ $this, 'add_endpoints_to_dashboard' ] );
	}

	/**
	 * Adds the filters required by Zapier.
	 *
	 * @since 6.0.0
	 */
	protected function add_filters() {
		add_filter( 'tec_tickets_plus_integrations_tab_fields', [ $this, 'filter_et_integrations_tab_fields' ], 30 );
	}

	/**
	 * Registers the REST API endpoints for Zapier
	 *
	 * @since 6.0.0
	 */
	public function register_endpoints() {
		$this->container->make( Attendees::class )->register();
		$this->container->make( Updated_Attendees::class )->register();
		$this->container->make( Orders::class )->register();
		$this->container->make( Refunded_Orders::class )->register();
		$this->container->make( Checkin::class )->register();
		$this->container->make( Find_Attendees::class )->register();
		$this->container->make( Find_Tickets::class )->register();
	}

	/**
	 * Adds the endpoint to the Zapier endpoint dashboard filter.
	 *
	 * @since 6.0.0
	 */
	public function add_endpoints_to_dashboard() {
		$this->container->make( Attendees::class )->add_to_dashboard();
		$this->container->make( Updated_Attendees::class )->add_to_dashboard();
		$this->container->make( Checkin::class )->add_to_dashboard();
		$this->container->make( Orders::class )->add_to_dashboard();
		$this->container->make( Refunded_Orders::class )->add_to_dashboard();
		$this->container->make( Find_Attendees::class )->add_to_dashboard();
		$this->container->make( Find_Tickets::class )->add_to_dashboard();

		$this->container->make( New_Events::class )->add_to_dashboard();
		$this->container->make( Canceled_Events::class )->add_to_dashboard();
		$this->container->make( Updated_Events::class )->add_to_dashboard();
		$this->container->make( Create_Events::class )->add_to_dashboard();
		$this->container->make( Update_Events::class )->add_to_dashboard();
		$this->container->make( Find_Events::class )->add_to_dashboard();
	}

	/**
	 * Filters the fields in the Tickets > Settings > Integrations tab to Zapier settings.
	 *
	 * @since 6.0.0
	 *
	 * @param array<string,array> $fields The current fields.
	 *
	 * @return array<string,array> The fields, as updated by the settings.
	 */
	public function filter_et_integrations_tab_fields( $fields ) {
		if ( ! is_array( $fields ) ) {
			return $fields;
		}

		return tribe( Settings::class )->add_fields_et( $fields );
	}
}
