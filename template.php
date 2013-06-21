<?php

/**
 * @file
 * This file is empty by default because the base theme chain (Alpha & Omega) provides
 * all the basic functionality. However, in case you wish to customize the output that Drupal
 * generates through Alpha & Omega this file is a good place to do so.
 *
 * Alpha comes with a neat solution for keeping this file as clean as possible while the code
 * for your subtheme grows. Please read the README.txt in the /preprocess and /process subfolders
 * for more information on this topic.
 */



//tell omega to use my custom user-login and user-profile templates
function omega_nmu_theme() {
	$items = array();
	$items['user_login'] = array(
		'render element' => 'form',
		'template' => 'templates/user-login'
	);
	$items['user_profile'] = array(
		'render element' => 'form',
		'template' => 'templates/user-profile'
	);
	return $items;
}

//write the login form to be used for the 'boss mode' login
function omega_nmu_preprocess_user_login(&$variables) {
  $variables['login_link'] = t('saml_login');
  $variables['rendered'] = drupal_render_children($variables['form']);
}

function omega_nmu_preprocess_html(&$vars)
{
	//add a conditional stylesheet for IE8 and under
	drupal_add_css(path_to_theme() . '/css/ie-lt-9.css', array(
		'group' => CSS_THEME,
		'browsers' => array('IE' => 'lt IE 9', '!IE' => FALSE),
		'every_page' => TRUE)
	);

	$HPnodes = db_query("SELECT vid, nid FROM {node} WHERE type = 'panel'")->fetchAllKeyed(); //get the ids of all pages that use panels in a site
	$GetNodeID = str_replace('node/', '', $_GET['q']); //get the node id of the page being served up
	if(in_array($GetNodeID, $HPnodes) || strpos($_SERVER['REQUEST_URI'], 'panel_content')) //if it is a panel type page (or the panel_content preview page), add the home css
	{
		if($GLOBALS['conf']['syslog_identity'] == "Drupal") //this will only match on the main NMU homepage.
		{
			drupal_add_css(drupal_get_path('theme', 'omega_nmu') . '/css/modules/nmu-homepage.css'); //add the nmu homepage css to the start of the styles array
			$css = drupal_add_css(); // Rebuild Drupal's css array:
			$styles = drupal_get_css($css); // Apply that array to the $styles string to be printed in the <head> section of html.tpl.php
		}
	}

	if(isset($GLOBALS['conf']['syslog_identity']))  //use this area to set site-specific stylesheets
	{

		/* not used yet
		// bulletin css
		if(strpos($GLOBALS['conf']['syslog_identity'], 'Bulletin') !== false)  //match any site with Bulletin in its identity
		{
			drupal_add_css(drupal_get_path('theme', 'omega_nmu') . '/css/Custom/bulletin.css'); //add a bulletin css to the start of the styles array
			$css = drupal_add_css(); // Rebuild Drupal's css array:
			$styles = drupal_get_css($css); // Apply that array to the $styles string to be printed in the <head> section of html.tpl.php
		}
		if($GLOBALS['conf']['syslog_identity'] === 'DrupalCommunicationsAndMarketing')  //match any site with C&M in its identity
		{
			drupal_add_css(drupal_get_path('theme', 'omega_nmu') . '/css/Custom/comm_mktg.css');
			$css = drupal_add_css();
			$styles = drupal_get_css($css);
		}
		*/

	}
}