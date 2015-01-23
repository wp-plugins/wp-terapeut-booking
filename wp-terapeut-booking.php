<?php
 /**
 * @package WP_TerapeutBooking
 * @version 3.2
 */
/*
Plugin Name: WP Terapeut Booking
Plugin URI: http://wordpress.org/plugins/wp-terapeut-booking/
Description: Dette plugin gør det muligt at indsætte [wpterapeut] et vilkårligt sted på din hjemmeside, hvilket gør at der indsættes et link til online booking.
Author: Terapeut Booking
Version: 3.2
Author URI: http://www.terapeutbooking.dk
*/

/*  Copyright 2013  Terapeut Booking - Bo Møller  (email : bo@terapeutbooking.dk)

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

function terapeutbooking_func( $atts ) {
    
	//Plugins
	
    $clicktext = get_option('wpterapeut_clicktext');
    $booklink = get_option('wpterapeut_booklink');
    
	if(isset($atts['link'])){
		if ($atts['link'] != ""){
			$booklink = $atts['link'];
		}
	}
    
	if(isset($atts['text'])){
		if ($atts['text'] != ""){
			$clicktext = $atts['text'];
		}
	}
    
    $bookscript = '<script src="https://system.terapeutbooking.dk/online_book/onlinebook.js?url=https%3A%2F%2Fsystem.terapeutbooking.dk&affix=/book/' . rawurlencode($booklink) . '&txt=' . rawurlencode($clicktext) . '" id="terapeutbookingonlinebooking" ></script>';
    
    return $bookscript;
}
add_shortcode('wpterapeut', 'terapeutbooking_func');


// create custom plugin settings menu
add_action('admin_menu', 'terapeutbooking_create_menu');

function terapeutbooking_create_menu() {

	//create new top-level menu
	add_menu_page('Terapeut Booking Plugin Settings', 'Terapeut Book..', 'administrator', __FILE__, 'terapeutbooking_settings_page',plugins_url('/img/wpterapeut_small.png', __FILE__));

	//call register settings function
	add_action( 'admin_init', 'register_mysettings' );
}


function register_mysettings() {
	//register our settings
	register_setting( 'terapeutbooking-settings-group', 'clicktext' );
	register_setting( 'terapeutbooking-settings-group', 'link' );
}

function terapeutbooking_settings_page() {
?>
<?php 
if (isset($_POST["update_settings"])) {
    // Saving the clicktext
    $clicktext = esc_attr($_POST["wpterapeut_clicktext"]);     
    update_option("wpterapeut_clicktext", $clicktext);
    
    // Saving the book link
    $booklink = esc_attr($_POST["wpterapeut_booklink"]);     
    update_option("wpterapeut_booklink", $booklink);
    
    ?>  
    <div id="message" class="updated">Settings saved</div>
    
<?php
}
?>

<div class="wrap">
<h2>Terapeut Booking</h2>
<p>
    WP Terapeut Booking er et plugin til dig som bruger <a href="http://www.terapeutbooking.dk">Terapeut Booking</a> til administration af bookings.
</p>    
<p>Dit plugin er nu aktivt, hvilket
    betyder at du på enhver side i WordPress kan indsætte [wpterapeut] hvorefter dette plugin vil udskifte [wpterapeut] med et link til din bookingformular.
</p>
<p>
    Når brugeren klikker på linket, vil han/hun få vist en fin formular direkte på din hjemmeside.
</p>

<hr>

<form method="post" action="">

<h2>Link tekst</h2>
<p>Indsæt teksten der skal bruges som link til bookingformularen. F.eks. "Klik her for at booke tid."</p>
<input type="text" style="width:600px;" name="wpterapeut_clicktext" value="<?php echo get_option('wpterapeut_clicktext', 'Klik her for at booke tid.'); ?>" />
         
<h2>Adresse</h2>
<p>Indsæt den sidste del af adressen til din bookingformular præcis som den ser ud i Terapeut Booking.</p>
<p>Hvis din adresse eksempelvis ser således ud <span style="font-style:italic;">https://system.terapeutbooking.dk/book/terapeuten-bo</span> er det <span style="font-style:italic">terapeuten-bo du skal indsætte herunder.</span></p>
<input type="text" style="width:600px;" name="wpterapeut_booklink" value="<?php echo get_option('wpterapeut_booklink', 'Indsæt dit eget link her.'); ?>" />

<input type="hidden" name="update_settings" value="Y" />  
<?php submit_button(); ?>

</form>
</div>
<?php } ?>