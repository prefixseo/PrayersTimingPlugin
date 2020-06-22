<?php
/**
 * Plugin Name: Prayer Timings
 * Plugin URI: http://fullmufta.com
 * Description: Print Prayer Time Table with [prayertime] shortcode anywhere
 * Version: 1.0
 * Author: Hello Dear Code
 * Author URI: http://heelodearcode.com
 * License: GPL2
 */


function getData($url){
$ch = curl_init();
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_URL,$url);
$result=curl_exec($ch);
curl_close($ch);
return $result;
}

// -- prayer timings table 
if(isset($_REQUEST['prayertime'])){
	ob_flush();
	$url = 'https://salah.com/get?lg=' . $_REQUEST['lg'] . '&lt=' . $_REQUEST['lt'];
	echo getData($url);
	exit;
}

// == Reverse Code geo location
if(isset($_REQUEST['geocodereversecode'])){
	ob_flush();
	$url = 'https://geocode.xyz/'.$_REQUEST['ltng'].'?geoit=json';
	$data = json_decode(getData($url),true);
	echo $data['city'].", ".$data['prov'];
	exit;
}


add_shortcode( 'prayertime', 'hdc_pt_prayer_timing' );
function hdc_pt_prayer_timing() {
    return ('
    <div>
    	<center><img src="'.plugins_url( 'assets/prayer_timings_icon.png', __FILE__ ).'"/></center>
    	<big id="prayerlocation">Current Location: Lahore, PB</big>
    	&nbsp;&nbsp;
    	<i class="fa fa-refresh btn-success" onclick="loadPrayerDate()" id="prayerdatareloader" style="cursor:pointer;"></i>
	</div>
    <table border="1" width="100%">
      <tr><th>Fajar</th><td id="Fajr">05:15 AM</td></tr>
      <tr><th>Sunrise</th><td id="Sunrise">06:19 AM</td></tr>
      <tr><th>Duhar</th><td id="Dhuhr">01:00 PM</td></tr>
      <tr><th>Asr</th><td id="Asr">05:00 PM</td></tr>
      <tr><th>Maghrib</th><td id="Maghrib">06:25 PM</td></tr>
      <tr><th>Isha</th><td id="Isha">08:00 PM</td></tr>
      <tr><th>Qiyam</th><td id="Qiyam">01:15 AM</td></tr>
  	</table>
    <script type="text/javascript">
    	loadPrayerDate()
    	function displayLocationInfo(position,long=\'\',lati=\'\') {
        	if(position != null){
        		var lng = position.coords.longitude;
            	var lat = position.coords.latitude;
    		}else{
    			var lng = "74.3583144";
            	var lat = "31.5063893";
    		}
            jQuery.getJSON(\''.home_url().'/?prayertime=yes&lg=\' + lng + \'&lt=\' + lat, function (data) {
            	jQuery("#prayerdatareloader").toggleClass("fa-spin");
            	if(data.location.length > 5){
	                jQuery(\'#prayerlocation\').html("Current Location:"+data.location.replace(\',\',\'\'));
	            }else{
	            	jQuery.ajax({url: \''.home_url().'/?geocodereversecode=yes&ltng=\'+lat+\',\'+lng, success: function(data2){
		            		jQuery(\'#prayerlocation\').html("Current Location: "+data2);
	            		}
	            	});
	            }
                jQuery(\'#Fajr\').html(data.times.Fajr);
                jQuery(\'#Sunrise\').html(data.times.Sunrise);
                jQuery(\'#Dhuhr\').html(data.times.Dhuhr);
                jQuery(\'#Asr\').html(data.times.Asr);
                jQuery(\'#Maghrib\').html(data.times.Maghrib);
                jQuery(\'#Isha\').html(data.times.Isha);
                jQuery(\'#Qiyam\').html(data.times.Qiyam);
            });
        }
	    function loadPrayerDate() {
	    	jQuery("#prayerdatareloader").toggleClass("fa-spin");
	        if (navigator.geolocation) {
	            navigator.geolocation.getCurrentPosition(displayLocationInfo);
	        }
	    }
    </script>
    ');
}


/*




*/
?>