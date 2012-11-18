<?php
/*
Plugin Name: ISLY Pinterest
Plugin URI: http://christopheresplin.com/isly-pinterst
Description: This little plugin uses nothing but JavaScript to add Pinterest Pin-It functionality to all of your images.  Change your plugin settings via Settings -> Isly Pinterest
Version: 0.1
Author: Christopher Esplin
Author URI: http://christopheresplin.com
License: GPL2
*/

/*  Copyright YEAR  Christopher Esplin  (email : christopher.esplin@gmail.com)

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
	$alternateCSS = get_option('islyAlternateCSS');
	echo "<script type='text/javascript' src='".plugins_url('isly-pinterest/scripts/isly-pinterest.js')."'></script>";
//	echo "<script src='//ajax.googleapis.com/ajax/libs/jquery/1.8.2/jquery.min.js'></script>";
	echo "<script>window.jQuery || document.write('<script src=\"".plugins_url('isly-pinterest/scripts/jquery-1.8.2.min.js')."\"><\/script>')</script>";
	if (strlen($alternateCSS)) {
		echo "<style>".$alternateCSS."</style>";
	} else {
		echo "<link rel='stylesheet' href='".plugins_url('isly-pinterest/styles/isly-pinterest.css')."' type='text/css'/>";
	}
}
add_action('wp_footer', 'islyPinterestFooterInjection');
function islyPinterestFooterInjection() {
	$verticalOffset = 5;
	$horizontalOffset = 5;
	$minHeight = 100;
	$optionVerticalOffset = get_option('islyVerticalOffset');
	$optionHorizontalOffset = get_option('islyHorizontalOffset');
	$optionMinHeight = get_option('islyMinHeight');
	if (!empty($optionVerticalOffset)) {
		$verticalOffset = $optionVerticalOffset;
	}
	if (!empty($optionHorizontalOffset)) {
		$horizontalOffset = $optionHorizontalOffset;
	}
	if (!empty($optionMinHeight)) {
		$minHeight = $optionMinHeight;
	}
	echo <<<ISLY
<script>
	jQuery(document).ready(function() {
    	return new window.ISLY.IslyPinterest({
		  permalinkClass: '.isly-pinterest-permalink',
		  minHeight: $minHeight,
		  verticalOffset: $verticalOffset,
		  horizontalOffset: $horizontalOffset
    	});
	});
</script>
ISLY;


}

add_action('the_content', 'islyPinterestPermalink');
function islyPinterestPermalink($content) {
	echo "<a class='isly-pinterest-permalink' style='display: none !important;' href='";
	the_permalink();
	echo "' data-description='".get_bloginfo('name').': ';
	the_title();
	echo "'></a>";
	return $content;
}

//Create settings menu item
add_action('admin_menu', 'islyPinterestMenu');
function islyPinterestMenu() {
	add_options_page('Isly Pinterest Options', 'Isly Pinterest', 'manage_options', 'isly-pinterest', 'islyPinterestOptions');
	add_action('admin_init', 'registerIslyPinterestOptions');
}

function registerIslyPinterestOptions() {
	register_setting('isly-pinterest-options', 'islyVerticalOffset');
	register_setting('isly-pinterest-options', 'islyHorizontalOffset');
	register_setting('isly-pinterest-options', 'islyAlternateCSS');
	register_setting('isly-pinterest-options', 'islyMinHeight');
}

function islyPinterestOptions() {
	if (!current_user_can('manage_options')) {
		wp_die('You do not have sufficient privileges to access this page.');
	} ?>

	<style>
		#isly-pinterest p {
			max-width: 500px;
		}
		#isly-pinterest li {
			margin-bottom: 2em;
		}
		#isly-pinterest label {
			display: block;
			font-weight: bold;
		}
        #isly-pinterest input {
			display: block;
        }
		#isly-pinterest textarea {
			min-width: 500px;
			min-height: 500px;
		}
	</style>
	<div id="isly-pinterest" class="wrap">
		<?php screen_icon(); ?>
		<h2>Isly Pinterest Options</h2>
		<form method="post" action="options.php">
			<?php settings_fields('isly-pinterest-options'); ?>
			<?php do_settings_fields('isly-pinterest-options'); ?>
			<p>
				<b>Vertical Offsets:</b><br/>
				Nudge your rollover image down or to the right.  Don't be afraid of negative numbers.
			</p>
            <p>
                <b>Minimum Pinnable Height:</b><br/>
				You probably don't want to pin icons or other tiny images in your posts.  Filter images out by setting the minimum image height that will show a rollover.
            </p>
			<p>
				<b>Alternate CSS:</b><br/>
				This is where you gain full control over the look and feel of your rollover.
				The defaults should work for most... but go crazy and invent something awesome.
				Clearing all text from the Alternate CSS field and saving will reset to the default CSS, so there's
				nothing to lose.
			</p>
			<ul>
				<li>
					<label>Image Vertical Offset from Top (px)</label>
					<input type="number" step="1" name="islyVerticalOffset" value="<?php echo get_option('islyVerticalOffset'); ?>"/>
				</li>
                <li>
                    <label>Image Horizontal Offset from Left (px)</label>
                    <input type="number" step="1" name="islyHorizontalOffset" value="<?php echo get_option('islyHorizontalOffset'); ?>"/>
                </li>
                <li>
                    <label>Minimum Pinnable Height (px)</label>
                    <input type="number" step="1" name="islyMinHeight" value="<?php echo get_option('islyMinHeight'); ?>"/>
                </li>
                <li>
                    <label>Alternate CSS (Notice the background-image url... this is your chance to change your rollover image.)</label>
					<textarea name="islyAlternateCSS"><?php
							$alternateCSS = get_option('islyAlternateCSS');
							if (strlen($alternateCSS)) {
								echo $alternateCSS;
							} else {
								echo trim(file_get_contents(plugins_url('isly-pinterest/styles/isly-pinterest.css')));
							}
					?></textarea>
                </li>
			</ul>
			<?php submit_button(); ?>
		</form>
	</div>
<?php
}
?>
