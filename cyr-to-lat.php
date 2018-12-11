<?php
/**
 * Plugin Name: Cyr-To-Lat
 * Plugin URI: http://wordpress.org/extend/plugins/cyr2lat/
 * Description: Converts Cyrillic characters in post and term slugs to Latin characters. Useful for creating human-readable URLs. Based on the original plugin by Anton Skorobogatov.
 * Author: Sergey Biryukov
 * Author URI: http://ru.wordpress.org/
 * Version: 3.3
 */

/**
 * @param string $title post title.
 *
 * @return string|string[]|null
 */
function ctl_sanitize_title( $title ) {
	global $wpdb;

	$iso9_table = array(
		'А' => 'A',
		'Б' => 'B',
		'В' => 'V',
		'Г' => 'G',
		'Ѓ' => 'G`',
		'Ґ' => 'G`',
		'Д' => 'D',
		'Е' => 'E',
		'Ё' => 'YO',
		'Є' => 'YE',
		'Ж' => 'ZH',
		'З' => 'Z',
		'Ѕ' => 'Z',
		'И' => 'I',
		'Й' => 'J',
		'Ј' => 'J',
		'І' => 'I',
		'Ї' => 'YI',
		'К' => 'K',
		'Ќ' => 'K`',
		'Л' => 'L',
		'Љ' => 'L',
		'М' => 'M',
		'Н' => 'N',
		'Њ' => 'N`',
		'О' => 'O',
		'П' => 'P',
		'Р' => 'R',
		'С' => 'S',
		'Т' => 'T',
		'У' => 'U',
		'Ў' => 'U`',
		'Ф' => 'F',
		'Х' => 'H',
		'Ц' => 'TS',
		'Ч' => 'CH',
		'Џ' => 'DH',
		'Ш' => 'SH',
		'Щ' => 'SHH',
		'Ъ' => '',
		'Ы' => 'Y`',
		'Ь' => '',
		'Э' => 'E`',
		'Ю' => 'YU',
		'Я' => 'YA',
		'а' => 'a',
		'б' => 'b',
		'в' => 'v',
		'г' => 'g',
		'ѓ' => 'g',
		'ґ' => 'g',
		'д' => 'd',
		'е' => 'e',
		'ё' => 'yo',
		'є' => 'ye',
		'ж' => 'zh',
		'з' => 'z',
		'ѕ' => 'z',
		'и' => 'i',
		'й' => 'j',
		'ј' => 'j',
		'і' => 'i',
		'ї' => 'yi',
		'к' => 'k',
		'ќ' => 'k`',
		'л' => 'l',
		'љ' => 'l',
		'м' => 'm',
		'н' => 'n',
		'њ' => 'n`',
		'о' => 'o',
		'п' => 'p',
		'р' => 'r',
		'с' => 's',
		'т' => 't',
		'у' => 'u',
		'ў' => 'u`',
		'ф' => 'f',
		'х' => 'h',
		'ц' => 'ts',
		'ч' => 'ch',
		'џ' => 'dh',
		'ш' => 'sh',
		'щ' => 'shh',
		'ъ' => '',
		'ы' => 'y`',
		'ь' => '',
		'э' => 'e`',
		'ю' => 'yu',
		'я' => 'ya',
	);

	// Locales list - https://make.wordpress.org/polyglots/teams/
	$locale = get_locale();
	switch ( $locale ) {
		case 'bg_BG':
			$iso9_table['Щ'] = 'SHT';
			$iso9_table['щ'] = 'sht';
			$iso9_table['Ъ'] = 'A';
			$iso9_table['ъ'] = 'a';
			break;
		case 'uk':
			$iso9_table['И'] = 'Y';
			$iso9_table['и'] = 'y';
			break;
		case 'ka_GE':
			$iso9_table['áƒ'] = 'a';
			$iso9_table['áƒ‘'] = 'b';
			$iso9_table['áƒ’'] = 'g';
			$iso9_table['áƒ“'] = 'd';
			$iso9_table['áƒ”'] = 'e';
			$iso9_table['áƒ•'] = 'v';
			$iso9_table['áƒ–'] = 'z';
			$iso9_table['áƒ—'] = 'th';
			$iso9_table['áƒ˜'] = 'i';
			$iso9_table['áƒ™'] = 'k';
			$iso9_table['áƒš'] = 'l';
			$iso9_table['áƒ›'] = 'm';
			$iso9_table['áƒœ'] = 'n';
			$iso9_table['áƒ'] = 'o';
			$iso9_table['áƒž'] = 'p';
			$iso9_table['áƒŸ'] = 'zh';
			$iso9_table['áƒ '] = 'r';
			$iso9_table['áƒ¡'] = 's';
			$iso9_table['áƒ¢'] = 't';
			$iso9_table['áƒ£'] = 'u';
			$iso9_table['áƒ¤'] = 'ph';
			$iso9_table['áƒ¥'] = 'q';
			$iso9_table['áƒ¦'] = 'gh';
			$iso9_table['áƒ§'] = 'qh';
			$iso9_table['áƒ¨'] = 'sh';
			$iso9_table['áƒ©'] = 'ch';
			$iso9_table['áƒª'] = 'ts';
			$iso9_table['áƒ«'] = 'dz';
			$iso9_table['áƒ¬'] = 'ts';
			$iso9_table['áƒ­'] = 'tch';
			$iso9_table['áƒ®'] = 'kh';
			$iso9_table['áƒ¯'] = 'j';
			$iso9_table['áƒ°'] = 'h';
			break;
	}

	$is_term   = false;
	$backtrace = debug_backtrace();
	foreach ( $backtrace as $backtrace_entry ) {
		if ( 'wp_insert_term' == $backtrace_entry['function'] ) {
			$is_term = true;
			break;
		}
	}

	$term = $is_term ? $wpdb->get_var( $wpdb->prepare( "SELECT slug FROM {$wpdb->terms} WHERE name = %s", $title ) ) : '';

	if ( ! empty( $term ) ) {
		$title = $term;
	} else {
		$title = strtr( $title, apply_filters( 'ctl_table', $iso9_table ) );

		if ( function_exists( 'iconv' ) ) {
			$title = iconv( 'UTF-8', 'UTF-8//TRANSLIT//IGNORE', $title );
		}

		$title = preg_replace( "/[^A-Za-z0-9'_\-\.]/", '-', $title );
		$title = preg_replace( '/\-+/', '-', $title );
		$title = trim( $title, '-' );
	}

	return $title;
}
add_filter( 'sanitize_title', 'ctl_sanitize_title', 9 );
add_filter( 'sanitize_file_name', 'ctl_sanitize_title' );

/**
 * Convert Existing Slugs
 */
function ctl_convert_existing_slugs() {
	global $wpdb;

	$posts = $wpdb->get_results( "SELECT ID, post_name FROM {$wpdb->posts} WHERE post_name REGEXP('[^A-Za-z0-9\-]+') AND post_status IN ('publish', 'future', 'private')" );

	foreach ( (array) $posts as $post ) {
		$sanitized_name = ctl_sanitize_title( urldecode( $post->post_name ) );

		if ( $post->post_name != $sanitized_name ) {
			add_post_meta( $post->ID, '_wp_old_slug', $post->post_name );
			$wpdb->update( $wpdb->posts, array( 'post_name' => $sanitized_name ), array( 'ID' => $post->ID ) );
		}
	}

	$terms = $wpdb->get_results( "SELECT term_id, slug FROM {$wpdb->terms} WHERE slug REGEXP('[^A-Za-z0-9\-]+') " );

	foreach ( (array) $terms as $term ) {
		$sanitized_slug = ctl_sanitize_title( urldecode( $term->slug ) );

		if ( $term->slug != $sanitized_slug ) {
			$wpdb->update( $wpdb->terms, array( 'slug' => $sanitized_slug ), array( 'term_id' => $term->term_id ) );
		}
	}
}

function ctl_schedule_conversion() {
	add_action( 'shutdown', 'ctl_convert_existing_slugs' );
}
register_activation_hook( __FILE__, 'ctl_schedule_conversion' );

// eof;
