<?php

namespace TEC\Tickets_Wallet_Plus\Passes\Apple_Wallet;

use \TEC\Tickets_Wallet_Plus\Admin\Settings\Wallet_Tab;
use TEC\Tickets_Wallet_Plus\Contracts\Settings\Settings_Abstract;

/**
 * Class Settings
 *
 * @since 6.0.0 Migrated to Event Tickets Plus from Wallet Plus
 *
 * @package \TEC\Tickets_Wallet_Plus\Passes\Apple_Wallet
 */
class Settings extends Settings_Abstract {

	/**
	 * Slug for the section.
	 *
	 * @since 6.0.0 Migrated to Event Tickets Plus from Wallet Plus
	 *
	 * @var string
	 */
	public static string $section_slug = 'apple-wallet';

	/**
	 * Add the settings section for Apple Wallet passes.
	 *
	 * @since 6.0.0 Migrated to Event Tickets Plus from Wallet Plus
	 *
	 * @param array $sections The list of sections.
	 *
	 * @return array $sections The modified list of sections.
	 */
	public function add_settings_section( $sections ): array {
		$sections[] = [
			'slug'    => '', // We keep this empty as it's the default section.
			'classes' => [],
			'url'     => tribe( Wallet_Tab::class )->get_url(),
			'text'    => __( 'Apple Wallet passes', 'event-tickets-plus' ),
		];

		return $sections;
	}

	/**
	 * Get the settings for Apple Wallet passes.
	 *
	 * @since 6.0.0 Migrated to Event Tickets Plus from Wallet Plus
	 *
	 * @param array  $fields  The fields.
	 * @param string $section The current section.
	 *
	 * @return array $fields The modified fields.
	 */
	public function get_settings( $fields, $section ): array {
		if ( ! empty( $section ) && $section !== self::$section_slug ) {
			return $fields;
		}

		$fields['tec-tickets-wallet-plus-apple-form-start'] = [
			'type' => 'html',
			'html' => '<div class="tribe-settings-form-wrap">',
		];

		/** @var Settings\Enable_Passes_Setting $enable_passes_setting */
		$enable_passes_setting = tribe( Settings\Enable_Passes_Setting::class );
		$is_passes_enabled     = tribe_is_truthy( $enable_passes_setting->get_value() );

		// `Enable Apple Wallet` is a specially stylized button. This starts the logic.
		$fields['apple-wallet-passes-header'] = [
			'type' => 'html',
			'html' => '<div class="tec-tickets__admin-settings-toggle-large-wrapper">
							<label class="tec-tickets__admin-settings-toggle-large">
								<input
									type="checkbox"
									name="' . $enable_passes_setting->get_key() . '"
									' . checked( $is_passes_enabled, true, false ) . '
									id="' . $enable_passes_setting->get_key() . ' -input"
									class="tec-tickets__admin-settings-toggle-large-checkbox tribe-dependency tribe-dependency-verified">
									<span class="tec-tickets__admin-settings-toggle-large-switch"></span>
									<span class="tec-tickets__admin-settings-toggle-large-label">' . $enable_passes_setting->get_label() . '</span>
							</label>
						</div>',

		];

		$fields['tickets-commerce-description'] = [
			'type' => 'html',
			'html' => '<div class="tec-tickets__admin-settings-tickets-commerce-description">' . $enable_passes_setting->get_tooltip() . '</div>',
		];

		$fields[ $enable_passes_setting->get_key() ] = [
			'type'            => 'hidden',
			'validation_type' => 'boolean',
		];

		// End `Enable Apple Wallet` stylized logic.

		$fields['tec-tickets-wallet-plus-apple-breaker'] = [
			'type' => 'html',
			'html' => '<p>',
		];

		$pass_logo_image_field                       = tribe( Settings\Pass_Logo_Image_Setting::class );
		$fields[ $pass_logo_image_field->get_key() ] = $pass_logo_image_field->get_definition();

		$pass_color                       = tribe( Settings\Pass_Color_Setting::class );
		$fields[ $pass_color->get_key() ] = $pass_color->get_definition();

		$text_color_field                       = tribe( Settings\Text_Color_Setting::class );
		$fields[ $text_color_field->get_key() ] = $text_color_field->get_definition();

		$qr_codes_setting                       = tribe( Settings\Qr_Codes_Setting::class );
		$fields[ $qr_codes_setting->get_key() ] = $qr_codes_setting->get_definition();

		$fields['tec-tickets-wallet-plus-apple-form-breaker-end'] = [
			'type' => 'html',
			'html' => '</p>',
		];

		/**
		 * Hook to modify the settings fields for Apple Wallet passes.
		 *
		 * @since 6.0.0 Migrated to Event Tickets Plus from Wallet Plus
		 *
		 * @param array[] $fields Top level settings.
		 */
		return apply_filters( 'tec_tickets_wallet_plus_apple_settings_fields', $fields );

	}

}
