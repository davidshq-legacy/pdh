<?php
/**
 * Plugin Name: PDH
 * Author: Plugin Author
 * Text Domain: pdh
 * Domain Path: /languages
 *
 * Internationalization in WordPress
 *
 * "Internationalization is the process of developing a plugin so it can easily be translated into other languages."
 *
 * Internationalization is often referred to as i18n, the eighteen being the number of letters in the word between
 * i and n.
 *
 * Localization on the other hand is the process of taking a plugin that has been internationalized and translating it
 * into a specific language.
 *
 * Localization is often called i10n as there are 10 letters in the word between i and n.
 *
 * Portable Object Template (POT) Files - Contains the original strings (in English) in your plugin.
 *  - Can be generated using WP-CLI: wp i18n make-pot path/to/your/directory
 *  - Poedit (poedit.net) provides a GUI for writing these files (open source/freemium).
 *  - You can get a basic template for a PO file from fxbenard: https://github.com/fxbenard/Blank-WordPress-Pot
 *  - Several other methods documented in handbook.
 * Portable Object (PO) Files - A single file is created for each translation, it is formatted like the above
 * but uses the desired language.
 * Machine Object (MO) Files - A machine-readable binary file that is read by WP using the gettext functions.
 *  - MO files are built using msgfmt.
 *
 * Generating the MO File
 *
 * Included with gettext is msgfmt, the command on both Windows and Unix is: msgfmt -o filename.mo filename.po
 *   - See handbook on how to batch script this process.
 *
 * msgfmt is also included with poedit.
 *
 * The language can be changed in WordPress' General Settings.
 *
 * Handbook: https://developer.wordpress.org/plugins/internationalization/
 * Handbook: https://developer.wordpress.org/plugins/internationalization/localization/
 * Reference: https://developer.wordpress.org/cli/commands/i18n/make-pot/
 */

/**
 * Using gettext
 *
 * gettext is a free package from GNU. It is used by (among many others) PHP to handle internationalization. PHP
 * gettext functions look like _() while WP has customized gettext for WP specifically using functions that look like
 * __() (there are two underscores instead of one).
 *
 * Any code providing internationalization needs to include a "Text Domain" - this makes it easy for WP to automatically
 * distinguish between different translations provided from different sources (e.g. another plugin). It must match
 * the slug of the plugin.
 *
 * Since WP 4.6 the text domain, unless specifically provided, is set to the plugin slug. This means one could drop
 * the text domain from one's code and WP will automatically associate it with your plugin's language files. However,
 * users on versions of WP prior to 4.6 will not be able to use this functionality without the explicit text domain in
 * code.
 *
 * Note: Do not use \r (ASCII Code 13) in translatable strings, use \n instead.
 * Note: Do not internationalize empty strings, if absolutely necessary include a context (explained below).
 * Note: Escape all strings, this helps prevent translators from running malicious code. A few escape functions are
 * integrated into the internationalization functions.
 *
 * Basic Functions:
 *  - __() - https://developer.wordpress.org/reference/functions/__/
 *  - _e() - https://developer.wordpress.org/reference/functions/_e/
 *  - _x() - https://developer.wordpress.org/reference/functions/_x/
 *  - _ex() - https://developer.wordpress.org/reference/functions/_ex/
 *  - _n() - https://developer.wordpress.org/reference/functions/_n/
 *  - _nx() - https://developer.wordpress.org/reference/functions/_nx/
 *  - _n_noop() - https://developer.wordpress.org/reference/functions/_n_noop/
 *  - _nx_noop() - https://developer.wordpress.org/reference/functions/_nx_noop/
 *  - translate_nooped_plural - https://developer.wordpress.org/reference/functions/translate_nooped_plural()/
 *
 * Translate & Escape Functions
 *
 *  - esc_html_() - https://developer.wordpress.org/reference/functions/esc_html__/
 *  - esc_html_e() - https://developer.wordpress.org/reference/functions/esc_html_e/
 *  - esc_html_x() - https://developer.wordpress.org/reference/functions/esc_html_x/
 *  - esc_attr__() - https://developer.wordpress.org/reference/functions/esc_attr__/
 *  - esc_attr_e() - https://developer.wordpress.org/reference/functions/esc_attr_e/
 *  - esc_attr_x() - https://developer.wordpress.org/reference/functions/esc_attr_x/
 *
 * Date & Number Functions
 *  - number_format_i18n() - https://developer.wordpress.org/reference/functions/number_format_i18n
 *  - date_i18n() - https://developer.wordpress.org/reference/functions/date_i18n
 *
 * Handbook: https://developer.wordpress.org/plugins/internationalization/how-to-internationalize-your-plugin/
 * Handbook: https://developer.wordpress.org/plugins/internationalization/security/
 * gettext Official: https://www.gnu.org/software/gettext/
 * Codex: https://codex.wordpress.org/WordPress_in_Your_Language
 * Meta Handbook: https://make.wordpress.org/meta/handbook/documentation/translations/
 */

/**
 * Internationalization with Explicit Text Domain
 *
 * Note: If WP Core also internationalizes the same string your string will not be used unless you add a text domain.
 * TODO: Is above true? If so, then one should just always include the text domain.
 *
 */
// This returns the text in the localized file.
// __( $some_string, $plugin_slug );
$something = __( 'String text to be internationalized', 'pdh' );

/**
 * Internationalization using slug as Text Domain
 *
 * Both with this implicit and the above explicit method you need to add the Text Domain and Domain Path to the header,
 * see header of this file for example. Note that the Domain Path is the path from the plugin base directory to the
 * folder containing the language files.
 */
// This returns the text in the localized file.
// __( $some_string );
$something = __( 'String text to be internationalized' );

/**
 * Echoing Translations Quicker
 *
 * Use _e() instead of __() if you want to echo (output) the translated text.
 */
// _e( $string, $text_domain );
_e( 'Something', 'pdh' );

/**
 * Using Variables in Internationalized Strings
 *
 * If you use a variable in your translated string, don't use _e, use __ and then use printf to output
 */
$city = 'New York';
printf(
	/* translators: %s: Name of a city */
	__( 'Your city is %s.', 'pdh' ), $city
);

/**
 * Using Variable Swapping for Internationalized Strings
 *
 * If you have a string that has multiple variables in it, use argument swapping.
 *
 * WARNING: You must use single quotes around your string.
 *
 * PHP Reference: http://www.php.net/manual/en/function.sprintf.php#example-4829
 * PHP Reference: http://php.net/sprintf
 */
$zipcode = '11111';

printf(
	/* translators: 1: Name of a city 2: ZIP code */
		__( 'Your zip code is %2$s, and your city is %1$s.', 'pdh' ), $city, $zipcode
);

/**
 * Pluralization
 *
 * Some words change when the quantity change, e.g.: "One comment", "Two comments".
 *
 * Reference: https://developer.wordpress.org/reference/functions/_n/
 */

// printf( _n( $singular, $plural, $count_english, $text_domain ), $count_internationalized );
printf(
	_n(
		'%s comment',
		'%s comments',
		get_comments_number(),
		'pdh'
	),
	/**
	 * Translating Numbers
	 */
	// number_format_i18n( $count_english );
	number_format_i18n( get_comments_number() )
);

/**
 * Storing Internationalization for Delayed Translation
 *
 * You could also delay the internationalization to a later time.
 *
 * Reference: https://developer.wordpress.org/reference/functions/_n_noop/
 * Reference: https://developer.wordpress.org/reference/functions/_nx_noop/
 */
// $comments_plural = _n_noop( $singular, $plural );
$comments_plural = _n_noop( // alternatively one could use _nx_noop
	'%s comment.',
	'%s comments.'
	);

/**
 * Reading Stored Internationalization
 *
 * Reference: https://developer.wordpress.org/reference/functions/translate_nooped_plural/
 */
// translate_nooped_plural( $comments_plural, $english_count, $text_domain ), number_format_i18n( $english_count );
printf(
	translate_nooped_plural(
		$comments_plural,
		get_comments_number(),
		'pdh'
	),
	number_format_i18n( get_comments_number() )
);

/**
 * Disambiguation by Context
 *
 * Sometimes words can be used in multiple contexts - e.g., as a noun or a verb. One needs to provide translators
 * with the option to replace the same word in different ways in different contexts.
 */
// _x( $string, $context, $text_domain );
_x( 'Post', 'noun', 'pdh' );
_x( 'Post', 'verb', 'pdh' );

// One could also echo using _ex():
// _ex( $string, $context, $text_domain );

/**
 * Explaining Strings for Translators
 *
 * You can provide details about how a string is to be understood by using code comments, immediately before the WP
 * gettext function (after all other comments) and prefaced with translators:
 *
 */
// /* translators: $explanation */
/* translators: draft saved date format, see http://php.net/date */
$saved_date_format = __( 'g:i:s a' );

/* translators: 1: WordPress version number, 2: plural number of bugs. */
$output = _n_noop( '<strong>Version %1$s</strong> addressed %2$s bug.',
					'<strong>Version #1$s</strong> addressed %2$s bugs.'
);

/**
 * Adding Translated Strings / Server-Side Data to a Previously Enqueued Script
 *
 * TODO: Add working example
 *
 * Reference: https://developer.wordpress.org/reference/functions/wp_localize_script/
 */

/**
 * Adding text domains after coding.
 *
 * If you don't enter the text domain as one is coding it can become quite laborious to add it later. You can
 * automatically add a text domain to every gettext call.
 *
 * Download this script: https://develop.svn.wordpress.org/trunk/tools/i18n/add-textdomain.php
 *
 * Command looks like:
 * php \path\to\add-textdomain.php $plugin-name $plugin-name.php > new-$plugin-name.php
 *
 * To copy over the existing file:
 * php add-textdomain.php -i $plugin-name $plugin-name.php
 *
 * To change all files in a directory:
 * php add-textdomain.php -i $plugin-name $plugin-directory
 */

/**
 * Loading the Text Domain
 *
 * You need to load the MO file with the plugin's translations. The below loads {text-domain}-{locale}.mo from
 * plugin's base directory.
 *
 * Note: Technically since 4.6 this isn't necessary if you've used translate.wordpress.org but this will require
 * any user to be running WP 4.6+.
 *
 * Reference: https://developer.wordpress.org/plugins/internationalization/how-to-internationalize-your-plugin/
 */
function pdh_load_plugin_textdomain() {
	load_plugin_textdomain( 'pdh', FALSE, basename( dirname( __FILE__ ) ) . '/languages/' );
}
add_action( 'plugins_loaded', 'pdh_load_plugin_textdomain' );