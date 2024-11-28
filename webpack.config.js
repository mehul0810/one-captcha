const path = require('path');
const { CleanWebpackPlugin } = require( 'clean-webpack-plugin' );
const MiniCSSExtractPlugin = require( 'mini-css-extract-plugin' );
const WebpackRTLPlugin = require( 'webpack-rtl-plugin' );
const wpPot = require( 'wp-pot' );

const inProduction = ( 'production' === process.env.NODE_ENV );
const mode = inProduction ? 'production' : 'development';

const config = {
	mode,
	entry: {
		admin: [ './assets/src/scss/admin.scss', './assets/src/js/admin.js' ],
	},
	output: {
		path: path.join(__dirname, 'assets/dist/'),
		filename: 'js/[name].min.js',
	},
	module: {
		rules: [
			// Create RTL styles.
			{
				test: /\.css$/,
				use: [
					'style-loader',
					'css-loader',
				],
			},

			// SASS to CSS.
			{
				test: /\.scss$/,
				use: [
					MiniCSSExtractPlugin.loader,
					{
						loader: 'css-loader',
						options: {
							sourceMap: true,
						},
					},
					{
						loader: 'sass-loader',
						options: {
							sourceMap: true,
						},
					} ],
			},
		],
	},
	plugins: [
		// Removes the "dist" folder before building.
		new CleanWebpackPlugin({
			cleanOnceBeforeBuildPatterns: [ 'assets/dist' ]
		}),

		new MiniCSSExtractPlugin( {
			filename: 'css/[name].css',
		} ),
	],
};

if ( inProduction ) {
	// Create RTL css.
	config.plugins.push( new WebpackRTLPlugin( {
		suffix: '-rtl',
		minify: true,
	} ) );

	// POT file.
	wpPot( {
		package: 'One Captcha',
		domain: 'one-captcha',
		destFile: 'languages/one-captcha.pot',
		relativeTo: './',
		src: [ './**/*.php', '!./includes/libraries/**/*', '!./vendor/**/*' ],
		bugReport: 'https://github.com/mehul0810/one-captcha/issues/new',
		team: 'PerformWP <hello@performwp.com>',
	} );
}

module.exports = config;
