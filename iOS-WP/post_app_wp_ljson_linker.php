<?php

	/*  
		V1a par StéphaneAG
		
		Le présent fichier php fait le lien entre une base de donnée wordpress et une application iphone native
		par le biais de fonctions qui génèrent des JSON a partir du contenu de la bdd
	*/
	
	/*
		
		URLs importants:
		
			..... .php?get_wp_posts_json
			..... .php?get_wp_posts_capabilities_json
			..... .php?get_wp_pages_json
			..... .php?get_wp_pages_capabilities_json
			..... .php?get_wp_pages_used_capabilities_json
		
		(R) Format de réponse utilisé par l'app:
		
 			$response = array('insert' => true);
			echo json_encode($response);
		
	*/
	
	/*
			//////////// " REMEMBER AND COPY PASTE " //////////////
	
	
			// SIMPLE ARRAY
			
			$arr = array("foo" => "bar", 12 => true);

			echo $arr["foo"]; // bar
			echo $arr[12];    // 1
			
			
			// TWO LEVEL ARRAY
			
			$arr = array("somearray" => array(6 => 5, 13 => 9, "a" => 42));

			echo $arr["somearray"][6];    // 5
			echo $arr["somearray"][13];   // 9
			echo $arr["somearray"]["a"];  // 42
	
	
	
	*/

?>

<?php
  // on inclus Wordpress
  define('WP_USE_THEMES', false);
  require('../STEPHANEADAMGARNIER/wp-blog-header.php');
  //require('http://stephaneadamgarnier.com/STEPHANEADAMGARNIER/wp-blog-header.php');
  
?>

<?php 

	//checks sur le motif de la requete:
	if ( isset( $_GET['get_wp_posts_json'] ) ){
		//echo('return: list of posts in JSON');
		get_wp_posts_json();
	} else if ( isset( $_GET['get_wp_posts_capabilities_json'] ) ){
		// echo('return: list of posts capabilities in JSON');
		get_wp_posts_capabilities_json();
	} else if ( isset( $_GET['get_wp_pages_json'] ) ){
		echo('return: list of pages in JSON');
	} else if ( isset( $_GET['get_wp_pages_capabilities_json'] ) ){
		echo('return: list of pages capabilities in JSON');
	} else if ( isset( $_GET['get_wp_pages_used_capabilities_json'] ) ){
		echo('return: list of pages capabilities in use in JSON');
	} else if ( isset( $_GET['processing_json_test'] ) ){
		
		$debugArray = array(
								"stef" => array(
													"name" => "St&eacute;phane Adam Garnier",
													"gender" => "male",
													"age" => 24
												),
								"manoun" => array(
													"name" => "Manon Ely",
													"gender" => "female",
													"age" => 24
												),
								"thom" => array(
													"name" => "Thomas Jourdan Gassin",
													"gender" => "male",
													"age" => 24
												),
							);
		
		//testing ... and remembering ! ^^
		//echo $debugArray["stef"]["name"];  // 
		//echo $debugArray["stef"]["gender"];  // 
		//echo $debugArray["stef"]["age"];  // 24
		$debugJson = json_encode($debugArray); // encodage en JSON
		echo $debugJson; //echo du JSON demandé par la requête
	
	} else if ( isset( $_GET['processing_json_groups'] ) ){
		
		$debugArray = array(
								"groups" => array(
													"group" => array(
																		"name" => "user",
																		"color" => "color",
																		"x" => 200,
																		"y" => 300,
																		"z" => 400,
																		"n" => 3,
																		"r" => 50,
																		"tweets" => array(
																							  "tweet" => array(
																							  
																							  					"title" => "titre1",
																							  					"text" => "text1"
																							  
																							  ),
																							  "tweet" => array(
																							  
																							  					"title" => "titre2",
																							  					"text" => "text2"
																							  
																							  ),
																							  "tweet" => array(
																							  
																							  					"title" => "titre3",
																							  					"text" => "text3"
																							  
																							  )
																							  
																						
																		)
													
													),
													"group" => array(
																		"name" => "followed",
																		"color" => "color",
																		"x" => 200,
																		"y" => 300,
																		"z" => 400,
																		"n" => 3,
																		"r" => 50,
																		"tweets" => array(
																							  "tweet" => array(
																							  
																							  					"title" => "titre1",
																							  					"text" => "text1"
																							  
																							  ),
																							  "tweet" => array(
																							  
																							  					"title" => "titre2",
																							  					"text" => "text2"
																							  
																							  )
																							  
																						
																		)
													
													),
													"group" => array(
																		"name" => "following",
																		"color" => "color",
																		"x" => 200,
																		"y" => 300,
																		"z" => 400,
																		"n" => 3,
																		"r" => 50,
																		"tweets" => array(
																							  "tweet" => array(
																							  
																							  					"title" => "titre1",
																							  					"text" => "text1"
																							  
																							  ),
																							  "tweet" => array(
																							  
																							  					"title" => "titre2",
																							  					"text" => "text2"
																							  
																							  ),
																							  "tweet" => array(
																							  
																							  					"title" => "titre3",
																							  					"text" => "text3"
																							  
																							  )
																							  
																						
																		)
													
													)
								)
		);
		
		//testing ... and remembering ! ^^
		//echo $debugArray["stef"]["name"];  // 
		//echo $debugArray["stef"]["gender"];  // 
		//echo $debugArray["stef"]["age"];  // 24
		$debugJson = json_encode($debugArray); // encodage en JSON
		echo $debugJson; //echo du JSON demandé par la requête
	
	} else if ( isset( $_GET['processing_json_debug'] ) ){
		
		$contenuDuJson = "{'points' : [{'x': 10, 'y': 10}, {'x': 190, 'y': 10}, {'x': 190, 'y': 190}, {'x': 10, 'y': 190}]}";
		echo $contenuDuJson;
		
	} else {
		echo 'DEBUG';
	}

?>

<?php
	/*
		Pour chaque function, ne pas oublier de spécifier la réponse a renvoyer a l'app
	*/
?>

<?php

	// function get_wp_posts_json()
	function get_wp_posts_json(){
		$post_types = get_post_types('','names'); // recup des posts types de wp
		$post_types_array = array(); // création d'un array
		foreach ( $post_types as $post_type ){ // on push chaque post type trouvé ds notre array
			$post_types_array[] = $post_type;
		}
		$custom_post_types_array = $post_types_array; // nouvelle var avant notre filtre de post types
		for ($i = 1; $i < 5 ; $i++) { // on enlève ttes les keys présentes autres que posts/custom posts
			unset($custom_post_types_array[$i]);
		}
		$custom_post_types_array = array_values($custom_post_types_array); // ré-indexation a partir de 0 du array
		$custom_post_types_json = json_encode($custom_post_types_array); // encodage en JSON
		
		//echo du JSON demandé par la requête
		//echo $custom_post_types_json . '<br/>';
		
		// on vient de recup nos custom post types dans un array k => v
		//on en profite donc pour recrée un array composé uniquement des values
		
		// petit test de mon récédent array
		/*
		foreach ($custom_post_types_array as $key => $val){ // je lance la boucle de construction de mon array a partir des value recup de mon précédent array 
			echo $val . '<br/>'; // on push un par un les différents nom de custom post types ds notre array
		}
		*/
		
		// construction du nouveau array
		$wp_post_types_array = array(); // je construis le array qui va accueillir les différentes values
		
		foreach ($custom_post_types_array as $key => $val){ // je lance la boucle de construction de mon array a partir des value recup de mon précédent array 
			$wp_post_types_array[] = $val; // on push un par un les différents nom de custom post types ds notre array
		} // on obtient normalement un array "simple" , composé uniquement de values et non de clées / values
		
		// test du tableau:
		/*
		foreach ($wp_post_types_array as $post_type){ // je lance la boucle de construction de mon array a partir des value recup de mon précédent array 
			echo $post_type. '<br/>'; // on push un par un les différents nom de custom post types ds notre array
		} // tableau good
		*/
		// array de test & debug
		//$debug_array = array('post', 'produit', 'portfolio'); // array "a la papatte" de test
		
		
		////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		/////////// MAINTENANT, FETCHING & ARRAY PUSH DES DIFFERENTS POSTS DANS LEURS SECTIONS RESPECTIVES /////////////////////////////////////////////////////////////////////////////////////////////////
		
		$full_stack = array(); // on définit l'array full_stack qui va contenir notre liste finale
		
		//$k => $v // ne pas oublier que l'on recup les valeurs d'un array PHP key > value
		
		foreach ($wp_post_types_array as $post_type){ // pour chaque post type trouvé dans l'array des custom post types
				
				$section = array(); // on crée un array pour chacune des sections
				$section_name = $post_type; // on crée un nom de section pour chacun de nos custom types
				$section_posts = array(); // on crée un array pour contenir les posts de chaque section
				
					////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
					//// BEGIN WORDPRESS QUERY POST ////////////////// /////////////////////////////////////////////////////////////////////////////	
				
					//query_posts(array('post_type' => array($custom_post_type), // établissement du query_post de chacun des custom post types
					query_posts(array('post_type' => array($post_type), 
								  'post_per_page' => -1 
					));
					if(have_posts()) : while(have_posts()) : the_post(); // tant que des posts de ce custom post type sont trouvés
				
							$single_post = array(); // array qui va stocker les infos de nos posts
					
								$post_title = get_the_title(); // premiere des différentes recups de db wordpress
								//echo( $post_title . '<br/>' );
								$post_ID = $post->ID; // l'ID de chacun des posts
								global $wp_query;
								$thePostID = $wp_query->post->ID;
								//$post_ID_string = (string)$post_ID;
								$this_post_type = $post_type; // le type de post ( que l'on récupère a nouveau  )
								
								// A AJOUTER ICI : UN IF AVEC LE LISTING DES DIFFERENTS CUSTOM FIELDS DU TYPE DE POST TYPE,
								// QUI RESULTERAS SUR DES $VAR EN FCTION DES CHAMPS DE LA METABOXE DU CUSTOM POST TYPE
								
								//$tmpl_used =  get_post_meta($post->ID,'_wp_page_template', true); // le de chacun des posts
						
							$single_post['post-title'] = $post_title;
							$single_post['post-ID'] = $thePostID;
							$single_post['post-type'] = $this_post_type;
				
					$section_posts[] = $single_post; // ajout de ce "single_post" ( array ) a notre array $section_posts ( qui correspond a chacun des types de post_types )
					
					endwhile; // on stoppe le loop et on réinitialise le query_posts
					endif;
					wp_reset_query();
				
					//// END WORDPRESS QUERY POST //////////////////////////////////////////////////////////////////////////////////////////////////
					////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
				
				$section['name'] = $section_name; // on définit le nom de chacune de nos sections
				
				$section['posts'] = $section_posts;	// on ajoute le contenu de l'arra ydes différents posts recup de la db wordpress a notr array $section
			
			$full_stack[] = $section; // on affecte notre array de section a notre array "full_stack"
			
			//array_push($posts_in_section,$single_post); // pour finir, on push notre notre section dans l'array "full_stack"
		}
		
		$posts_list = $full_stack; // on crée une nouvelle var a laquelle on affecte le contenu de full_stack
		$posts_list_in_json = json_encode($posts_list); // encodage en JSON
		
		///////// END OF JSON SECTION LIST CONSTRUCTION ////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		
		//echo array php (debug)
		//echo('list of posts:  <br/> <br/>');
		//echo $posts_list;
		
		//echo du JSON demandé par la requête
		//echo('list of posts in JSON:  <br/> <br/>');
		echo $posts_list_in_json;

		
		
	}

?>

<?php

	// function get_wp_posts_capabilities_json()
	function get_wp_posts_capabilities_json(){
		$post_types = get_post_types('','names'); // recup des posts types de wp
		$post_types_array = array(); // création d'un array
		foreach ( $post_types as $post_type ){ // on push chaque post type trouvé ds notre array
			$post_types_array[] = $post_type;
		}
		$custom_post_types_array = $post_types_array; // nouvelle var avant notre filtre de post types
		for ($i = 1; $i < 5 ; $i++) { // on enlève ttes les keys présentes autres que posts/custom posts
			unset($custom_post_types_array[$i]);
		}
		$custom_post_types_array = array_values($custom_post_types_array); // ré-indexation a partir de 0 du array
		$custom_post_types_json = json_encode($custom_post_types_array); // encodage en JSON
		
		//echo du JSON demandé par la requête
		echo $custom_post_types_json;
	}

?>
