<?php
/**
 * Bootstraps the Arbictus Sync for e-Financials and WooCommerce plugin.
 *
 * @package Arbictus\EFinancialsPlugin
 *
 * @copyright 2026 Pilvepank OÜ
 * @license   Proprietary / Pilvepank OÜ
 *
 * @wordpress-plugin
 * Plugin Name: Arbictus Sync for e-Financials and WooCommerce
 * Plugin URI: https://pilvepank.ee/plugins/arbictus-sync
 * Description: Bookkeeping sync between your shop and e-Financials (E-arveldaja liidestus).
 * Version: 1.0.0
 * Author: Pilvepank OÜ
 * Author URI: https://pilvepank.ee
 *
 * Requires at least: 6.5
 * Tested up to: 7.0
 * Requires PHP: 8.2
 * Requires Plugins: woocommerce
 * WC requires at least: 8.2
 * WC tested up to: 11.0
 *
 * License: Proprietary
 * License: Proprietary / Copyright (c) 2026 Pilvepank OÜ. All rights reserved.
 *
 * Text Domain: arbictus-sync-for-e-financials-woocommerce
 */

declare(strict_types=1);

namespace Aanndryyyy\EFinancialsPlugin;

use Aanndryyyy\EFinancialsPlugin\Main\Main;
use Aanndryyyy\EFinancialsPlugin\Plugin;
use Automattic\WooCommerce\Utilities\FeaturesUtil;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

require_once __DIR__ . '/vendor/autoload.php';

/**
 * Declare HPOS compatibility early (before WooCommerce init).
 */
\add_action(
	'before_woocommerce_init',
	static function (): void {

		if ( ! \class_exists( FeaturesUtil::class ) ) {
			return;
		}

		FeaturesUtil::declare_compatibility( 'custom_order_tables', Plugin::file(), true );
	}
);

/**
 * Begins execution of the plugin once WooCommerce is available.
 */
\add_action(
	'plugins_loaded',
	static function (): void {

		if ( ! \class_exists( Main::class ) || ! \class_exists( 'WooCommerce' ) ) {
			return;
		}

		// Composer caches the loader, so this hands back the instance created above.
		$loader = require __DIR__ . '/vendor/autoload.php';

		( new Main( $loader->getPrefixesPsr4(), __NAMESPACE__ ) )->register();
	},
	20
);
