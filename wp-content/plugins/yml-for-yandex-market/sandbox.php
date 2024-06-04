<?php defined( 'ABSPATH' ) || exit;
/**
 * Sandbox function
 * 
 * @since	0.1.0
 * @version 4.0.0 (29-08-2023)
 *
 * @return	void
 */
function y4ym_run_sandbox() {
	$x = false; // установите true, чтобы использовать песочницу
	if ( true === $x ) {
		printf( '%s<br/>',
			__( 'The sandbox is working. The result will appear below', 'yml-for-yandex-market' )
		);
		/* вставьте ваш код ниже */
		// Example:
		// $product = wc_get_product(8303);
		// echo $product->get_price();

		/* дальше не редактируем */
		printf( '<br/>%s',
			__( 'The sandbox is working correctly', 'yml-for-yandex-market' )
		);
	} else {
		printf( '%s sanbox.php',
			__( 'The sandbox is not active. To activate, edit the file', 'yml-for-yandex-market' )
		);
	}
}