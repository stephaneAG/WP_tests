<?php
  // on inclus Wordpress
  define('WP_USE_THEMES', false);
  require('../STEPHANEADAMGARNIER/wp-blog-header.php');
  //require('http://stephaneadamgarnier.com/STEPHANEADAMGARNIER/wp-blog-header.php');
  
?>

<?php 

	//normalement, je dois maintenant avoir un full access a ma bdd wordpress
	//et aux différentes fctions du moteur de wp ( > sweet ;p )
	
	
	///////////////////////////////////////////////////////////////////////////////
	//LISTING DES DIFFERENTS POSTS TYPES DE NOTRE INTALL WP////////////////////////
	/*
	$post_types = get_post_types('','names');
	foreach ( $post_types as $post_type ){
		
		echo '<p>' . $post_type . '</p>';
		
	}
	// works! >next ;p
	*/
	
?>

<?php
	
	//on récupère les vars en POST en provenance de ma pitite App mobile
	
	
	$post_title                 								 = $_POST['title'];
	
	$post_meta_categorie_produit_input							 = $_POST['metamaincatformobiletablerow'];
	$post_meta_level_one_category_for_mobile_table_row_input	 = $_POST['metalvlonecatformobiletablerow'];
	$post_meta_level_two_category_for_mobile_table_row_input	 = $_POST['metalvltwocatformobiletablerow'];
	
	$post_meta_reference_produit_input  						 = $_POST['metareferenceproduit'];
	$post_meta_nom_boutique_produit_input                 		 = $_POST['metanomboutiqueproduit'];
	$post_meta_categorie_produit_input                 			 = $_POST['metacategorieproduit'];
	$post_meta_type_produit_input                 				 = $_POST['metatypeproduit'];
	
	
	/*
	$post_title                 								 = 'title goood ';
	
	$post_meta_categorie_produit_input							 = 'metamaincatformobiletablerow';
	$post_meta_level_one_category_for_mobile_table_row_input	 = 'metalvlonecatformobiletablerow';
	$post_meta_level_two_category_for_mobile_table_row_input	 = 'metalvltwocatformobiletablerow';
	
	$post_meta_reference_produit_input  						 = 'metareferenceproduit';
	$post_meta_nom_boutique_produit_input                 		 = 'metanomboutiqueproduit';
	$post_meta_categorie_produit_input                 			 = 'metacategorieproduit';
	$post_meta_type_produit_input                 				 = 'metatypeproduit';
	*/
?>

<?php


	//insertion d'un post
	
	/*
	$my_post = array(
		
		'post_title' => 'mon scripted post title',
     	'post_content' => 'mon scripted post content',
     	'post_status' => 'publish',
     	'post_author' => 1
     	//'post_category' => array(8,39)
		
	);
	wp_insert_post($my_post);
	
	*/
	
	/////////////////// OU BIEN ///////////
	
	$user_id = 'tef';
	//$post_title = 'mon scripted post title3';
	//$post_content = 'mon kj';
	//$cat = 'Uncategorized';
	
	
	$my_other_post = wp_insert_post( array(
			'post_type' 	=> 'produit',  
            'post_author'   => $user_id,  
            'post_title'    => $post_title,  
            //'post_content'  => $post_content,  
            //'post_category' => $cat,  
            'post_status'   => 'publish',  
            //'tags_input'    => $post_tags  
        ) );
	
	//$customMeta = 'pouif';
	//insertion des custom fields ( y compris ceux inclus ds les metaboxes) du post
	//add_post_meta($my_other_post, 'customMeta', $customMeta, true);
	//insertion des custom fields ( y compris ceux inclus ds les metaboxes) du post
	add_post_meta($my_other_post, '_main_category_for_mobile_table_row', $post_meta_categorie_produit_input, true);
	add_post_meta($my_other_post, '_level_one_category_for_mobile_table_row', $post_meta_level_one_category_for_mobile_table_row_input, true);
	add_post_meta($my_other_post, '_level_two_category_for_mobile_table_row', $post_meta_level_two_category_for_mobile_table_row_input, true);
	
	add_post_meta($my_other_post, '_reference_produit', $post_meta_reference_produit_input, true);
	add_post_meta($my_other_post, '_nom_boutique_produit', $post_meta_nom_boutique_produit_input, true);
	add_post_meta($my_other_post, '_categorie_produit', $post_meta_categorie_produit_input, true);
	add_post_meta($my_other_post, '_type_produit', $post_meta_type_produit_input, true);
	


?>

<?php
	 	
 	//DEBUGGING
 	$response = array('insert' => true);
	echo json_encode($response);

?>

