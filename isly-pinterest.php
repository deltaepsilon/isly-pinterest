<?php
/*
Plugin Name: ISLY Pinterest
Plugin URI: http://christopheresplin.com/isly-pinterst
Description: This little plugin uses nothing but JavaScript to add Pinterst Pin-It functionality to all of your images
Version: 0.1
Author: Christopher Esplin
Author URI: http://christopheresplin.com
License: GPL2
*/

/*  Copyright YEAR  PLUGIN_AUTHOR_NAME  (email : PLUGIN AUTHOR EMAIL)

    This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License, version 2, as
    published by the Free Software Foundation.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program; if not, write to the Free Software
    Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/
add_action('wp_head', 'islyPinterestInjection');
function islyPinterestInjection() {
	echo "<script type='text/javascript' src='".plugins_url('isly-pinterest/scripts/isly-pinterest.js')."'></script>";
//	echo "<script src='//ajax.googleapis.com/ajax/libs/jquery/1.8.2/jquery.min.js'></script>";
	echo "<script>window.jQuery || document.write('<script src=\"".plugins_url('isly-pinterest/scripts/jquery-1.8.2.min.js')."\"><\/script>')</script>";
}

add_action('the_content', 'islyPinterestPermalink');
function islyPinterestPermalink($content) {
	echo "<a class='isly-pinterest-permalink' style='display: none !important;' href='";
	the_permalink();
	echo "' ></a>";
	return $content;
}
?>