const wpTextdomain = require( 'wp-textdomain' );

wpTextdomain( process.argv[ 2 ], {
	domain: 'onecaptcha',
	fix: true,
} );
