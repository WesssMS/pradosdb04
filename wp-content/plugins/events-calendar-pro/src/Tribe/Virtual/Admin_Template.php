<?php
/**
 * Provides a template instance specialized for the Virtual Event plugin to serve admin views.
 *
 * @since 7.0.0 Migrated to Events Pro from Events Virtual.
 *
 * @package Tribe\Events\Virtual
 */

namespace Tribe\Events\Virtual;

/**
 * Class Admin_Template
 *
 * @since 7.0.0 Migrated to Events Pro from Events Virtual.
 *
 * @package Tribe\Events\Virtual
 */
class Admin_Template extends \Tribe__Template {

	/**
	 * Template constructor.
	 *
	 * @since 7.0.0 Migrated to Events Pro from Events Virtual.
	 */
	public function __construct() {
		$this->set_template_origin( tribe( Plugin::class ) );
		$this->set_template_folder( 'src/admin-views' );

		// We specifically don't want to look up template files here.
		$this->set_template_folder_lookup( false );

		// Configures this templating class extract variables.
		$this->set_template_context_extract( true );
	}
}
