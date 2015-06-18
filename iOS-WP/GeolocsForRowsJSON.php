<?php
	
	// Wordpress JSON for table rows builder v1a by Stéphane Adam Garnier - 2011
	//echo ('<h3>WP JSON for table rows builder v1a  by Sag</h3>');
	
	//echo ('<h4>debug output:</h4>');
	
	//un mini debug de in ..
	// echo ('WP JSON builder: running..' . '<br/>');
	
?>

<?php
	
	//////////////// PARAMS DE INCLUDE WORDPRESS ICI ///////////////

	// on inclus Wordpress
	define('WP_USE_THEMES', false);
	require('../../STEPHANEADAMGARNIER/wp-blog-header.php');
	//require('http://stephaneadamgarnier.com/STEPHANEADAMGARNIER/wp-blog-header.php');
  
?>

<?php
	
	//////////////// PARAMS DE QUERY WORDPRESS ICI ///////////////
	// > pourront être remplacées par des queries sql (avec des jointures entre les tables ? ...^^)
	
	//etablissement du query_posts
	query_posts(array('post_type' => array('geolocation_point'), //custom type
				  	  'post_per_page' => -1 // tous les posts
	));

?>
					
<?

	//////////////// DEBUT CONSTRUCTION JSON ICI ///////////////////
	// echo ('WP JSON builder: starting JSON build' . '<br/>');
	
	//pas de définition de fichier pour le fichier JSON,on déclare juste un nouvel array englobant le premier node
	$geolocation_for_json = array(); //va englover tous les nodes JSON
	
	//définition du root du fichier JSON (le tt premier node)
	$pois = array(); // node <pois> <!> va permettre de stocker le résultat de la wp_query ( > en l'occurence, un array contenant les diffrents POIs )
	// nb: fin de construction/incrustation des tableaux entre eux plus bas (..)
	
?>

<?php 
	
	//on entame notre boucle WP

	if(have_posts()): while(have_posts()) : the_post();
	
	//on référencie nos variables pour un code un peu plus lisible ensuite au niveau du xml
	$ID_from_wp = $post->ID;
	$title_from_wp = get_post_meta($post->ID, "_title_for_xml", true);
	$description_from_wp = get_post_meta($post->ID, "_description_for_xml", true);
	
	$attached_images_from_wp = get_children( array('post_parent' => $post->ID,
		'post_status' => 'inherit',
		'post_type' => 'attachment',
		'post_mime_type' => 'image',
		//'exclude' =>  get_post_thumbnail_id(),
	) );
	
	if ($attached_images_from_wp) {
	
		//je tente un array et des pushs pour recup plusieurs images en mm tps
		$images_stack = array() ;
		
		foreach ($attached_images_from_wp as $image) {
			$img_url = wp_get_attachment_url($image->ID);
			array_push($images_stack,$img_url);
		}
	}
	
	
	//et maintenant , on recup ?
	//$image_from_wp = $img_url; // a l'air de marcher correctement
	$image_from_wp = $images_stack[0]; // l'utilisation d'un tableau se révèle plus pratique si lres images sont préalablement correctement ordonnées (..)
	
	$latitude_from_wp = get_post_meta($post->ID, "_latitude_for_xml", true);
	$longitude_from_wp = get_post_meta($post->ID, "_longitude_for_xml", true);
	$altitude_from_wp = get_post_meta($post->ID, "_altitude_for_xml", true);
	
	$latInStrng = (string)$latitude_from_wp;
	$lonInStrng = (string)$longitude_from_wp;
	$altInStrng = (string)$altitude_from_wp;
	
?>

<?php
	
	//////////////// CONSTRUCTION JSON ICI ///////////////////
	
	// définition des éléments de mon tableau $pois
	$pois[] = array( // node <pois> // sous-root de $layer
	  "poi" => array( //node(!) <poi> 
		
		"anchor" => array( //node(!) <poi> > <anchor>
			
			"geolocation" => array( // node(!) <poi> > <anchor> > <geolocation>
				
				"lat" => $latitude_from_wp, // node(!) <anchor> > <geolocation> > <lat>
				"lon" => $longitude_from_wp, // node(!) <anchor> > <geolocation> > <lon>
				"alt" => $altitude_from_wp // node(!) <anchor> > <geolocation> > <alt>
				
			) // end node(!) <poi> > <anchor> > <geolocation>
			
		), // end node(!) <poi> > <anchor>
		
		"id" => $ID_from_wp, // node(!) <poi> > <id>
		
		"imageURL" => $image_from_wp, // node(!) <poi> > <imageURL>
		
		"text" => array( //node(!) <poi> > <text>
			
			"description" => $description_from_wp, // node(!) <poi> > <text> > <description>
			"title" => $title_from_wp, // node(!) <anchor> > <poi> > <text> > <title>
			
		) // end node(!) <poi> > <text>
		
	  ) // end node(!) <poi>
	); // end node <pois> // sous-root de $layer
	
?>

<?php 
	
	//on finis notre boucle WP
	// echo ('WP JSON builder: added >   ' . $title_from_wp . ' with WP ID: ' . $ID_from_wp . '  to JSON file in progress' . '<br/>'); 
	
	endwhile; // fin du while
	endif; // fin du if
	wp_reset_query(); //reset de la boucle pour être propre
	
?>

<?php
	
	//////////////// FIN CONSTRUCTION JSON ICI ///////////////////
	$geolocation_for_json = $pois;
	//$geolocation_for_json['pois'] = $pois; // je me sers de $pois comme conteneur JSON ( > le JSON gééré ne possède pas de root unique, nb )
	// nb : interessant: si on ecrit " $geolocation_for_json = $pois; " ,cela nous remet au array ordinaires et non associatifs()
	
	
	// echo ('WP JSON builder: writing JSON' . '<br/>');
	$fp = @fopen('geolocalisations.json','w');
	
	if (!fp){
		
		die('Erreur de création du fichier JSON');
		
	}
		fwrite( $fp, json_encode($geolocation_for_json)  );
		//// echo ('WP JSON builder: writing JSON done, closing JSON file' . '<br/>');
		fclose($fp);
		// echo ('WP JSON builder: array data pushed in JSON file' . '<br/>');
	
?>

<?php
	
	// ..et de out ! 
	// echo ('WP JSON builder: done');
	
	$fakeJSONData = '[{"place":null,"retweet_count":0,"in_reply_to_screen_name":null,"geo":null,"coordinates":null,"retweeted":false,"created_at":"Wed Dec 08 08:26:59 +0000 2010","in_reply_to_status_id_str":null,"in_reply_to_user_id_str":null,"user":{"default_profile_image":true,"profile_background_tile":false,"time_zone":null,"friends_count":0,"protected":false,"follow_request_sent":null,"profile_sidebar_fill_color":"DDEEF6","name":"Mobiletuts+","is_translator":false,"statuses_count":1,"created_at":"Wed Dec 08 08:26:23 +0000 2010","followers_count":17,"profile_image_url":"http:\/\/a0.twimg.com\/sticky\/default_profile_images\/default_profile_1_normal.png","verified":false,"profile_background_image_url_https":"https:\/\/si0.twimg.com\/images\/themes\/theme1\/bg.png","utc_offset":null,"favourites_count":0,"profile_sidebar_border_color":"C0DEED","description":null,"screen_name":"mobtuts","following":null,"profile_use_background_image":true,"notifications":null,"profile_text_color":"333333","profile_image_url_https":"https:\/\/si0.twimg.com\/sticky\/default_profile_images\/default_profile_1_normal.png","listed_count":0,"contributors_enabled":false,"geo_enabled":false,"profile_background_image_url":"http:\/\/a0.twimg.com\/images\/themes\/theme1\/bg.png","location":null,"id_str":"224155324","profile_link_color":"0084B4","show_all_inline_media":false,"url":null,"id":224155324,"default_profile":true,"lang":"en","profile_background_color":"C0DEED"},"contributors":null,"in_reply_to_status_id":null,"id_str":"12422878532214784","truncated":false,"source":"web","in_reply_to_user_id":null,"favorited":false,"id":12422878532214784,"text":"Please follow @envatomobile"}]' ;
	
	
	
	$fakeJSONCustomData = '[{"poi":{"anchor":{"geolocation":{"lat":"48.8569407747","lon":"2.34125843356","alt":""}},"id":90,"imageURL":"http:\/\/stephaneadamgarnier.com\/STEPHANEADAMGARNIER\/wp-content\/uploads\/2011\/11\/GeoPoint3.jpg","text":{"description":"...o\u00f9 \u00e7a ne ch\u00f4me pas la nuit !\r\n\r\n  ( o\u00f9 tous les cons sont gris ? )","title":"Paris..."}}},{"poi":{"anchor":{"geolocation":{"lat":"52.36752912658409","lon":"4.892692565917969","alt":""}},"id":87,"imageURL":"http:\/\/stephaneadamgarnier.com\/STEPHANEADAMGARNIER\/wp-content\/uploads\/2011\/11\/GeoPoint21.jpg","text":{"description":"I wish i could go there again ...","title":"Crazy Town"}}},{"poi":{"anchor":{"geolocation":{"lat":"48.8387704452","lon":"2.3145532608","alt":""}},"id":85,"imageURL":"http:\/\/stephaneadamgarnier.com\/STEPHANEADAMGARNIER\/wp-content\/uploads\/2011\/11\/GeoPoint1.jpg","text":{"description":"This will be i hope the description of my first geolocation point from my custom WP custom type admin panel ... quite nice ,uh ? ^^","title":"Home sweet home"}}}]' ;
	
	
?>

<?php
	
	//auto close js function if user clicked the link from WP
	if ( isset( $_GET['userPrevLoc'] ) ){
		
		$user_previous_location = $_GET['userPrevLoc'];
		
		if ( $user_previous_location == 'wordpress' ) {
			//echo "<script language=javascript>alert('You came from Wordpress! I am glad to hear that ')</script>";
			echo "<script language=javascript> window.close(); </script>";
		}
		
	} else if ( isset( $_GET['rowsRequest'] ) ){
		
		print_r( $geolocation_for_json );
		
	} else if ( isset( $_GET['fakeJSON'] ) ){
	
		print_r( $fakeJSONData );
	
	} else if ( isset( $_GET['fakeJSON2'] ) ){
	
		print_r( $fakeJSONCustomData );
	
	} else if ( isset( $_GET['realJSON'] ) ){
		
		$realJSONData = file_get_contents('geolocalisations.json');
	
		if (!$realJSONData){
		
			die('Impossible de localiser le fichier JSON');
		
		} else {
			
			echo( $realJSONData );
			
		}
	
	}
	
?>
