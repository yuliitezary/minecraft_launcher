<?php
/*
=====================================================
 DataLife Engine - by SoftNews Media Group 
-----------------------------------------------------
 http://dle-news.ru/
-----------------------------------------------------
 Copyright (c) 2004-2018 SoftNews Media Group
=====================================================
 Данный код защищен авторскими правами
=====================================================
 Файл: templates.class.php
-----------------------------------------------------
 Назначение: Парсинг шаблонов
=====================================================
*/

if (! defined ( 'DATALIFEENGINE' )) {
	die ( "Hacking attempt!" );
}

@ini_set('pcre.recursion_limit', 10000000 );
@ini_set('pcre.backtrack_limit', 10000000 );
@ini_set('pcre.jit', false);

require_once ROOT_DIR . '/engine/classes/mobiledetect.class.php';

class dle_template {
	
	var $dir = '';
	var $template = null;
	var $copy_template = null;
	var $desktop = true;
	var $smartphone = false;
	var $tablet = false;
	var $data = array ();
	var $block_data = array ();
	var $result = array ('info' => '', 'vote' => '', 'speedbar' => '', 'content' => '' );
	var $allow_php_include = true;
	var $include_mode = 'tpl';
	var $category_tree = false;
	
	var $template_parse_time = 0;

    function __construct(){

		$this->dir = ROOT_DIR . '/templates/';

		$mobile_detect = new Mobile_Detect;

		if ( $mobile_detect->isMobile() ) {
			$this->smartphone = true;
			$this->desktop = false;
		}

		if ( $mobile_detect->isTablet() ) {
			$this->smartphone = false;
			$this->desktop = false;
			$this->tablet = true;
		}
	}
	
	function set($name, $var) {
		
		if( is_array( $var ) ) {
			if( count( $var ) ) {
				foreach ( $var as $key => $key_var ) {
					$this->set( $key, $key_var );
				}
			}
			return;
		}
		
		$var = str_ireplace( "{include", "&#123;include",  $var );
		$var = str_ireplace( "{custom", "&#123;custom",  $var );
		$var = str_ireplace( "{content", "&#123;content",  $var );
		$var = str_ireplace( "{title", "&#123;title",  $var );
		$var = str_ireplace( "[xf", "&#91;xf",  $var );
			
		$this->data[$name] = $var;
		
	}
	
	function set_block($name, $var) {
		
		if( is_array( $var ) ) {
			if( count( $var ) ) {
				foreach ( $var as $key => $key_var ) {
					$this->set_block( $key, $key_var );
				}
			}
			return;
		}
		
		$var = str_ireplace( "{custom", "&#123;custom",  $var );
		$var = str_ireplace( "{include", "&#123;include",  $var );
		$var = str_ireplace( "{content", "&#123;content",  $var );
		$var = str_ireplace( "{title", "&#123;title",  $var );
		$var = str_ireplace( "[xf", "&#91;xf",  $var );
			
		$this->block_data[$name] = $var;
	}
	
	function load_template($tpl_name) {
		global $category_id, $cat_info, $page_header_info;

		$time_before = $this->get_real_time();
		
		$tpl_name = str_replace(chr(0), '', $tpl_name);

		$url = @parse_url ( $tpl_name );

		$file_path = dirname ($this->clear_url_dir($url['path']));
		$tpl_name = pathinfo($url['path']);

		$tpl_name = totranslit($tpl_name['basename']);
		$type = explode( ".", $tpl_name );
		$type = strtolower( end( $type ) );

		if ($type != "tpl") {
			$this->template = "Not Allowed Template Name: " .str_replace(ROOT_DIR, '', $this->dir)."/".$tpl_name ;
			$this->copy_template = $this->template;
			return "";

		}

		if ($file_path AND $file_path != ".") $tpl_name = $file_path."/".$tpl_name;

		if( stripos ( $tpl_name, ".php" ) !== false ) {
			$this->template = "Not Allowed Template Name: " .str_replace(ROOT_DIR, '', $this->dir)."/".$tpl_name ;
			$this->copy_template = $this->template;
			return "";
		}

		if( $tpl_name == '' || !file_exists( $this->dir . "/" . $tpl_name ) ) {
			$this->template = "Template not found: " .str_replace(ROOT_DIR, '', $this->dir)."/".$tpl_name ;
			$this->copy_template = $this->template;
			return "";
		}

		$this->template = file_get_contents( $this->dir . "/" . $tpl_name );
		
		if (strpos ( $this->template, "{*" ) !== false) {
			$this->template = preg_replace("'\\{\\*(.*?)\\*\\}'si", '', $this->template);
		}

		if (stripos ( $this->template, "page-title" ) !== false OR stripos( $this->template, "page-description" ) !== false) {
			
			$this->template = str_ireplace( array('{page-title}', '{page-description}'), array($page_header_info['title'], $page_header_info['description']), $this->template );
		
			if( $page_header_info['title'] ) {
				$this->template = preg_replace( "'\\[not-page-title\\](.*?)\\[/not-page-title\\]'is", "", $this->template );
				$this->template = str_ireplace( "[page-title]", "", $this->template );
				$this->template = str_ireplace( "[/page-title]", "", $this->template );
			} else {
				$this->template = preg_replace( "'\\[page-title\\](.*?)\\[/page-title\\]'is", "", $this->template );
				$this->template = str_ireplace( "[not-page-title]", "", $this->template );
				$this->template = str_ireplace( "[/not-page-title]", "", $this->template );
			}
			if( $page_header_info['description'] ) {
				$this->template = preg_replace( "'\\[not-page-description\\](.*?)\\[/not-page-description\\]'is", "", $this->template );
				$this->template = str_ireplace( "[page-description]", "", $this->template );
				$this->template = str_ireplace( "[/page-description]", "", $this->template );
			} else {
				$this->template = preg_replace( "'\\[page-description\\](.*?)\\[/page-description\\]'is", "", $this->template );
				$this->template = str_ireplace( "[not-page-description]", "", $this->template );
				$this->template = str_ireplace( "[/not-page-description]", "", $this->template );
			}
		}
		
		$this->template = $this->check_module($this->template);
		
		if (strpos ( $this->template, "[group=" ) !== false OR strpos ( $this->template, "[not-group=" ) !== false) {
			$this->template = $this->check_group($this->template);
		}
		
		if (strpos ( $this->template, "[page-count=" ) !== false) {
			$this->template = preg_replace_callback ( "#\\[(page-count)=(.+?)\\](.*?)\\[/page-count\\]#is", array( &$this, 'check_page'), $this->template );
		}

		if (strpos ( $this->template, "[not-page-count=" ) !== false) {
			$this->template = preg_replace_callback ( "#\\[(not-page-count)=(.+?)\\](.*?)\\[/not-page-count\\]#is", array( &$this, 'check_page'), $this->template );
		}

		if (strpos ( $this->template, "[tags=" ) !== false) {
			$this->template = preg_replace_callback ( "#\\[(tags)=(.+?)\\](.*?)\\[/tags\\]#is", array( &$this, 'check_tag'), $this->template );
		}


		if (strpos ( $this->template, "[not-tags=" ) !== false) {
			$this->template = preg_replace_callback ( "#\\[(not-tags)=(.+?)\\](.*?)\\[/not-tags\\]#is", array( &$this, 'check_tag'), $this->template );
		}

		if (strpos ( $this->template, "[news=" ) !== false) {
			$this->template = preg_replace_callback ( "#\\[(news)=(.+?)\\](.*?)\\[/news\\]#is", array( &$this, 'check_tag'), $this->template );
		}

		if (strpos ( $this->template, "[not-news=" ) !== false) {
			$this->template = preg_replace_callback ( "#\\[(not-news)=(.+?)\\](.*?)\\[/not-news\\]#is", array( &$this, 'check_tag'), $this->template );
		}

		if (strpos ( $this->template, "[smartphone]" ) !== false) {
			$this->template = preg_replace_callback ( "#\\[(smartphone)\\](.*?)\\[/smartphone\\]#is", array( &$this, 'check_device'), $this->template );
		}

		if (strpos ( $this->template, "[not-smartphone]" ) !== false) {
			$this->template = preg_replace_callback ( "#\\[(not-smartphone)\\](.*?)\\[/not-smartphone\\]#is", array( &$this, 'check_device'), $this->template );
		}

		if (strpos ( $this->template, "[tablet]" ) !== false) {
			$this->template = preg_replace_callback ( "#\\[(tablet)\\](.*?)\\[/tablet\\]#is", array( &$this, 'check_device'), $this->template );
		}

		if (strpos ( $this->template, "[not-tablet]" ) !== false) {
			$this->template = preg_replace_callback ( "#\\[(not-tablet)\\](.*?)\\[/not-tablet\\]#is", array( &$this, 'check_device'), $this->template );
		}

		if (strpos ( $this->template, "[desktop]" ) !== false) {
			$this->template = preg_replace_callback ( "#\\[(desktop)\\](.*?)\\[/desktop\\]#is", array( &$this, 'check_device'), $this->template );
		}

		if (strpos ( $this->template, "[not-desktop]" ) !== false) {
			$this->template = preg_replace_callback ( "#\\[(not-desktop)\\](.*?)\\[/not-desktop\\]#is", array( &$this, 'check_device'), $this->template );
		}
		
		if (strpos ( $this->template, "{category-" ) !== false) {
			$cat_id = intval($category_id);
			
			$this->template = str_ireplace( "{category-id}", $cat_id, $this->template );
			$this->template = str_ireplace( "{category-title}", $cat_info[$cat_id]['name'], $this->template );
			$this->template = str_ireplace( "{category-description}", $cat_info[$cat_id]['fulldescr'], $this->template );			
		}
		
		if (strpos ( $this->template, "{catmenu" ) !== false) {
			$this->template = preg_replace_callback ( "#\\{catmenu(.*?)\\}#is", array( &$this, 'build_cat_menu'), $this->template );
		}
		
		if (strpos ( $this->template, "{catnewscount" ) !== false) {
			$this->template = preg_replace_callback ( "#\\{catnewscount id=['\"](.+?)['\"]\\}#i", array( &$this, 'catnewscount'), $this->template );
		}
		
		if( strpos( $this->template, "{include file=" ) !== false ) {
			$this->include_mode = 'tpl';			
			$this->template = preg_replace_callback( "#\\{include file=['\"](.+?)['\"]\\}#i", array( &$this, 'load_file'), $this->template );
		
		}

		$this->copy_template = $this->template;
		
		$this->template_parse_time += $this->get_real_time() - $time_before;
		return true;
	}

	function load_file( $matches=array() ) {
		global $db, $is_logged, $member_id, $cat_info, $config, $user_group, $category_id, $_TIME, $lang, $smartphone_detected, $dle_module;

		$name = $matches[1];

		$name = str_replace( chr(0), "", $name );
		$name = str_replace( '..', '', $name );

		$url = @parse_url ($name);
		$type = explode( ".", $url['path'] );
		$type = strtolower( end( $type ) );

		if ($type == "tpl") {

			return $this->sub_load_template( $name );

		}

		if ($this->include_mode == "php") {

			if ( !$this->allow_php_include ) return;

			if ($type != "php") return "To connect permitted only files with the extension: .tpl or .php";

			if ($url['path']{0} == "/" )
				$file_path = dirname (ROOT_DIR.$url['path']);
			else
				$file_path = dirname (ROOT_DIR."/".$url['path']);

			$file_name = pathinfo($url['path']);
			$file_name = $file_name['basename'];

			if ( stristr ( php_uname( "s" ) , "windows" ) === false )
				$chmod_value = @decoct(@fileperms($file_path)) % 1000;

			if ( stristr ( dirname ($url['path']) , "uploads" ) !== false )
				return "Include files from directory /uploads/ is denied";

			if ( stristr ( dirname ($url['path']) , "templates" ) !== false )
				return "Include files from directory /templates/ is denied";

			if ( stristr ( dirname ($url['path']) , "engine/data" ) !== false )
				return "Include files from directory /engine/data/ is denied";

			if ( stristr ( dirname ($url['path']) , "engine/cache" ) !== false )
				return "Include files from directory /engine/cache/ is denied";

			if ( stristr ( dirname ($url['path']) , "engine/inc" ) !== false )
				return "Include files from directory /engine/inc/ is denied";

			if ($chmod_value == 777 ) return "File {$url['path']} is in the folder, which is available to write (CHMOD 777). For security purposes the connection files from these folders is impossible. Change the permissions on the folder that it had no rights to the write.";

			if ( !file_exists($file_path."/".$file_name) ) return "File {$url['path']} not found.";

			$url['query'] = str_ireplace(array("file_path","file_name", "dle_login_hash", "_GET","_FILES","_POST","_REQUEST","_SERVER","_COOKIE","_SESSION") ,"Filtered", $url['query'] );

			if( substr_count ($this->template, "{include file=") < substr_count ($this->copy_template, "{include file=")) return "Filtered";

			if ( isset($url['query']) AND $url['query'] ) {

				$module_params = array();

				parse_str( $url['query'], $module_params );

				extract($module_params, EXTR_SKIP);

				unset($module_params);
				

			}

			ob_start();
			$tpl = new dle_template();
			$tpl->dir = TEMPLATE_DIR;
			include $file_path."/".$file_name;
			return ob_get_clean();

		}

		return '{include file="'.$name.'"}';


	}
	
	function sub_load_template( $tpl_name ) {
		global $category_id, $cat_info;
		
		$tpl_name = str_replace(chr(0), '', $tpl_name);
		
		$url = @parse_url ( $tpl_name );

		$file_path = dirname ($this->clear_url_dir($url['path']));
		$tpl_name = pathinfo($url['path']);
		$tpl_name = totranslit($tpl_name['basename']);
		$type = explode( ".", $tpl_name );
		$type = strtolower( end( $type ) );

		if ($type != "tpl") {

			return "Not Allowed Template Name: ". $tpl_name;

		}

		if ($file_path AND $file_path != ".") $tpl_name = $file_path."/".$tpl_name;

		if (strpos($tpl_name, '/templates/') === 0) {

			$tpl_name = str_replace('/templates/','',$tpl_name);
			$templatefile = ROOT_DIR . '/templates/'.$tpl_name;

		} else $templatefile = $this->dir . "/" . $tpl_name;

		if( $tpl_name == '' || !file_exists( $templatefile ) ) {

			$templatefile = str_replace(ROOT_DIR,'',$templatefile);
			return "Template not found: " . $templatefile ;
			return false;

		}

		if( stripos ( $templatefile, ".php" ) !== false ) return "Not Allowed Template Name: ". $tpl_name;

		$template = file_get_contents( $templatefile );

		if (strpos ( $template, "{*" ) !== false) {
			$template = preg_replace("'\\{\\*(.*?)\\*\\}'si", '', $template);
		}
		
		if (stripos ( $template, "page-title" ) !== false OR stripos( $template, "page-description" ) !== false) {
			
			$template = str_ireplace( array('{page-title}', '{page-description}'), array($page_header_info['title'], $page_header_info['description']), $template );
		
			if( $page_header_info['title'] ) {
				$template = preg_replace( "'\\[not-page-title\\](.*?)\\[/not-page-title\\]'is", "", $template );
				$template = str_ireplace( "[page-title]", "", $template );
				$template = str_ireplace( "[/page-title]", "", $template );
			} else {
				$template = preg_replace( "'\\[page-title\\](.*?)\\[/page-title\\]'is", "", $template );
				$template = str_ireplace( "[not-page-title]", "", $template );
				$template = str_ireplace( "[/not-page-title]", "", $template );
			}
			if( $page_header_info['description'] ) {
				$template = preg_replace( "'\\[not-page-description\\](.*?)\\[/not-page-description\\]'is", "", $template );
				$template = str_ireplace( "[page-description]", "", $template );
				$template = str_ireplace( "[/page-description]", "", $template );
			} else {
				$templatee = preg_replace( "'\\[page-description\\](.*?)\\[/page-description\\]'is", "", $template );
				$template = str_ireplace( "[not-page-description]", "", $template );
				$template = str_ireplace( "[/not-page-description]", "", $template );
			}
		}
		
		$template = $this->check_module($template);
		
		if (strpos ( $template, "[group=" ) !== false OR strpos ( $template, "[not-group=" ) !== false ) {
			$template = $this->check_group($template);
		}

		if (strpos ( $template, "[page-count=" ) !== false) {
			$template = preg_replace_callback ( "#\\[(page-count)=(.+?)\\](.*?)\\[/page-count\\]#is", array( &$this, 'check_page'), $template );
		}

		if (strpos ( $template, "[not-page-count=" ) !== false) {
			$template = preg_replace_callback ( "#\\[(not-page-count)=(.+?)\\](.*?)\\[/not-page-count\\]#is", array( &$this, 'check_page'), $template );
		}

		if (strpos ( $template, "[tags=" ) !== false) {
			$template = preg_replace_callback ( "#\\[(tags)=(.+?)\\](.*?)\\[/tags\\]#is", array( &$this, 'check_tag'), $template );
		}


		if (strpos ( $template, "[not-tags=" ) !== false) {
			$template = preg_replace_callback ( "#\\[(not-tags)=(.+?)\\](.*?)\\[/not-tags\\]#is", array( &$this, 'check_tag'), $template );
		}

		if (strpos ( $template, "[news=" ) !== false) {
			$template = preg_replace_callback ( "#\\[(news)=(.+?)\\](.*?)\\[/news\\]#is", array( &$this, 'check_tag'), $template );
		}


		if (strpos ( $template, "[not-news=" ) !== false) {
			$template = preg_replace_callback ( "#\\[(not-news)=(.+?)\\](.*?)\\[/not-news\\]#is", array( &$this, 'check_tag'), $template );
		}

		if (strpos ( $template, "[smartphone]" ) !== false) {
			$template = preg_replace_callback ( "#\\[(smartphone)\\](.*?)\\[/smartphone\\]#is", array( &$this, 'check_device'), $template );
		}

		if (strpos ( $template, "[not-smartphone]" ) !== false) {
			$template = preg_replace_callback ( "#\\[(not-smartphone)\\](.*?)\\[/not-smartphone\\]#is", array( &$this, 'check_device'), $template );
		}

		if (strpos ( $template, "[tablet]" ) !== false) {
			$template = preg_replace_callback ( "#\\[(tablet)\\](.*?)\\[/tablet\\]#is", array( &$this, 'check_device'), $template );
		}

		if (strpos ( $template, "[not-tablet]" ) !== false) {
			$template = preg_replace_callback ( "#\\[(not-tablet)\\](.*?)\\[/not-tablet\\]#is", array( &$this, 'check_device'), $template );
		}

		if (strpos ( $template, "[desktop]" ) !== false) {
			$template = preg_replace_callback ( "#\\[(desktop)\\](.*?)\\[/desktop\\]#is", array( &$this, 'check_device'), $template );
		}

		if (strpos ( $template, "[not-desktop]" ) !== false) {
			$template = preg_replace_callback ( "#\\[(not-desktop)\\](.*?)\\[/not-desktop\\]#is", array( &$this, 'check_device'), $template );
		}
		
		if (strpos ( $template, "{category-" ) !== false) {
			$cat_id = intval($category_id);
			
			$template = str_ireplace( "{category-id}", $cat_id, $template );
			$template = str_ireplace( "{category-title}", $cat_info[$cat_id]['name'], $template );
			$template = str_ireplace( "{category-description}", $cat_info[$cat_id]['fulldescr'], $template );
		}
		
		if (strpos ( $template, "{catnewscount" ) !== false) {
			$template = preg_replace_callback ( "#\\{catnewscount id=['\"](.+?)['\"]\\}#i", array( &$this, 'catnewscount'), $template );
		}
		
		if (strpos ( $template, "{catmenu" ) !== false) {
			$template = preg_replace_callback ( "#\\{catmenu(.*?)\\}#is", array( &$this, 'build_cat_menu'), $template );
		}
		
		return $template;
	}

	function clear_url_dir($var) {
		if ( is_array($var) ) return "";
	
		$var = str_ireplace( ".php", "", $var );
		$var = str_ireplace( ".php", ".ppp", $var );
		$var = trim( strip_tags( $var ) );
		$var = str_replace( "\\", "/", $var );
		$var = preg_replace( "/[^a-z0-9\/\_\-]+/mi", "", $var );
		$var = preg_replace( '#[\/]+#i', '/', $var );

		return $var;
	
	}

	function check_module($matches) {
		global $dle_module;
		
		$regex = '/\[(aviable|available|not-aviable|not-available)=(.*?)\]((?>(?R)|.)*?)\[\/\1\]/is';

		if (is_array($matches)) {
			
			$aviable = $matches[2];
			$block = $matches[3];
			
			if ($matches[1] == "aviable" OR $matches[1] == "available") $action = true; else $action = false;
			
			$aviable = explode( '|', $aviable );
			
			if( $action ) {
				
				if( ! (in_array( $dle_module, $aviable )) and ($aviable[0] != "global") ) $matches = '';
				else $matches = $block;
			
			} else {
				
				if( (in_array( $dle_module, $aviable )) ) $matches = '';
				else $matches = $block;
			}		
	
		}
	
		return preg_replace_callback($regex, array( &$this, 'check_module'), $matches);
	}

	function check_group( $matches ) {
		global $member_id;

		$regex = '/\[(group|not-group)=(.*?)\]((?>(?R)|.)*?)\[\/\1\]/is';

		if (is_array($matches)) {

			$groups = $matches[2];
			$block = $matches[3];
	
			if ($matches[1] == "group") $action = true; else $action = false;
			
			$groups = explode( ',', $groups );
			
			if( $action ) {
				
				if( ! in_array( $member_id['user_group'], $groups ) ) $matches = ''; else $matches = $block;
			
			} else {
				
				if( in_array( $member_id['user_group'], $groups ) ) $matches = ''; else $matches = $block;
			
			}
		}
		
		return preg_replace_callback($regex, array( &$this, 'check_group'), $matches);
	
	}

	function check_device( $matches=array() ) {

		$block = $matches[2];
		$device = $this->desktop;

		if ($matches[1] == "smartphone" OR $matches[1] == "tablet" OR $matches[1] == "desktop") $action = true; else $action = false;
		if ($matches[1] == "smartphone" OR $matches[1] == "not-smartphone") $device = $this->smartphone;
		if ($matches[1] == "tablet" OR $matches[1] == "not-tablet") $device = $this->tablet;

		if( $action ) {
			
			if( !$device ) return "";
		
		} else {
			
			if( $device ) return "";
		
		}

		return $block;
	}

	function declination( $matches=array() ) {

		$matches[1] = strip_tags($matches[1] );
	    $matches[1] = str_replace(' ', '', $matches[1] );

		$matches[1] = intval($matches[1]);
		$words = explode('|', trim($matches[2]));
		$parts_word = array();

		switch ( count($words) ) {
			case 1:
				$parts_word[0] = $words[0];
				$parts_word[1] = $words[0];
				$parts_word[2] = $words[0];
				break;
			case 2:
				$parts_word[0] = $words[0];
				$parts_word[1] = $words[0].$words[1];
				$parts_word[2] = $words[0].$words[1];
				break;
			case 3: 
				$parts_word[0] = $words[0];
				$parts_word[1] = $words[0].$words[1];
				$parts_word[2] = $words[0].$words[2];
				break;
			case 4: 
				$parts_word[0] = $words[0].$words[1];
				$parts_word[1] = $words[0].$words[2];
				$parts_word[2] = $words[0].$words[3];
				break;
		}
	
		$word = $matches[1]%10==1&&$matches[1]%100!=11?$parts_word[0]:($matches[1]%10>=2&&$matches[1]%10<=4&&($matches[1]%100<10||$matches[1]%100>=20)?$parts_word[1]:$parts_word[2]);
	
		return $word;
	}

	function check_page( $matches=array() ) {

		$pages = $matches[2];
		$block = $matches[3];

		if ($matches[1] == "page-count") $action = true; else $action = false;
	
		$pages = explode( ',', $pages );
		$page = intval($_GET['cstart']);

		if ( $page < 1 ) $page = 1;
		
		if( $action ) {
			
			if( !$this->_in_rangearray( $page, $pages ) ) return "";
		
		} else {
			
			if( $this->_in_rangearray( $page, $pages ) ) return "";
		
		}
		
		return $block;
	
	}

	function check_tag( $matches=array() ) {
		global $config;

		$params = $matches[2];
		$block = $matches[3];

		if ($matches[1] == "tags" OR $matches[1] == "news") $action = true; else $action = false;
		if ($matches[1] == "tags" OR $matches[1] == "not-tags") $tag = "tags";
		if ($matches[1] == "news" OR $matches[1] == "not-news") $tag = "news";
	
		$props = "";
		$params = trim($params);

		if ( $tag == "news" ) {

			if( defined( 'NEWS_ID' ) ) $props = NEWS_ID;
			$params = explode( ',', $params);
			
			if( $action ) {
				
				if( !$this->_in_rangearray( $props, $params ) ) return "";
			
			} else {
				
				if( $this->_in_rangearray( $props, $params ) ) return "";
			
			}
			
			return $block;
		
		} elseif ( $tag == "tags" ) {
		
			if( defined( 'CLOUDSTAG' ) ) {

				if( function_exists('mb_strtolower') ) {

					$params = mb_strtolower($params, $config['charset']);
					$props = trim(mb_strtolower(CLOUDSTAG, $config['charset']));

				} else {

					$params = strtolower($params);
					$props = trim(strtolower(CLOUDSTAG));

				}

			}

			$params = explode( ',', $params);

			if( $action ) {
				
				if( !in_array( $props, $params ) ) return "";
			
			} else {
				
				if( in_array( $props, $params ) ) return "";
			
			}
			
			return $block;
	
		} else return "";
	
	}
	
	function _in_rangearray($findvalue, $findarray) {
	
		$findvalue = trim($findvalue);
	
		foreach ($findarray as $value) {
			
			$value = trim($value);
			
			if( $value == $findvalue ) {
				
				return true;
			
			} elseif( count(explode('-', $value)) == 2 ) {
				
				list($min, $max) = explode('-', $value);
				
				$findvalue = intval($findvalue);
				$min = intval($min);
				$max = intval($max);
				
				if( $findvalue >= $min && $findvalue <= $max ) {
					return true;
				}
				
			}
		}
		
		return false;
	
	}
	
	function catnewscount( $matches=array() ) {
		global $cat_info;
		
		$id = intval($matches[1]);
		
		return intval($cat_info[$id]['newscount']);
	}

	function build_tree( $data ) {

		$tree = array();
		foreach ($data as $id=>&$node) {
			if ($node['parentid'] == 0) {
				$tree[$id] = &$node;
			} else {
				if (!isset($data[$node['parentid']]['children'])) $data[$node['parentid']]['children'] = array();
				$data[$node['parentid']]['children'][$id] = &$node;
			}
		}
		
		return $tree;

	}
	
	function recursive_array_search($needle, $haystack, $subcat = true, &$item = false) {
		
		if(!$item) $item = array();

		foreach($haystack as $key => $value) {

			if(in_array($key, $needle)) {
			
				if( $subcat === "only" ) {

					if(is_array( $value['children'] )) {
						
						foreach($value['children'] as $value2) {
							$item[$value2['id']] = $value2;
						}
						
					}
					
				} else $item[$key] = $value;
				
				if(!$subcat AND is_array( $value['children'] ) ) {
					unset($item[$key]['children']);
					$this->recursive_array_search($needle, $value['children'], $subcat, $item);
				}

			} elseif (is_array( $value['children'] ) ) {
				$this->recursive_array_search($needle, $value['children'], $subcat, $item);
			}
		}
		
		return $item;
	}

	function build_cat_menu( $matches=array() ) {
		global $cat_info, $config;

		if(!count($cat_info)) return "";

		if( !is_array($this->category_tree) ) {
			
			$this->category_tree = $this->build_tree($cat_info);
			
		}
		
		if(!count($this->category_tree)) return "";
		
		$param_str = trim($matches[1]);
		$allow_cache = $config['allow_cache'];
		$config['allow_cache'] = false;
		$catlist = $this->category_tree;
		$cache_id = md5($param_str);
		
		if( $config['category_newscount'] ) $cache_prefix = "news"; else $cache_prefix = "catmenu";
		
		if( preg_match( "#cache=['\"](.+?)['\"]#i", $param_str, $match ) ) {
			if( $match[1] == "yes" ) $config['allow_cache'] = 1;
		}
		
		$content = dle_cache( $cache_prefix, $cache_id );
		
		if( $content !== false ) {
			
			$config['allow_cache'] = $allow_cache;
			return $content;
		
		} else {
			
			if( preg_match( "#subcat=['\"](.+?)['\"]#i", $param_str, $match ) ) {
				
				$match[1] = trim($match[1]);
				
				if($match[1] == "yes") $subcat = true; else $subcat = false;
				
				if($match[1] == "only") $subcat = "only";
	
			} else $subcat = true;
			
			if( preg_match( "#id=['\"](.+?)['\"]#i", $param_str, $match ) ) {
	
				$temp_array = array();
		
				$match[1] = explode (',', $match[1]);
		
				foreach ($match[1] as $value) {
		
					if( count(explode('-', $value)) == 2 ) $temp_array[] = get_mass_cats($value);
					else $temp_array[] = intval($value);
		
				}
		
				$temp_array = implode(',', $temp_array);
			
				$catlist= $this->recursive_array_search( explode(',', $temp_array), $catlist, $subcat);
				
				if(!count($catlist)) return "";
				
			}
			
			if( preg_match( "#template=['\"](.+?)['\"]#i", $param_str, $match ) ) {
				$template_name = trim($match[1]);
			} else $template_name = "categorymenu";
	
			$template = $this->sub_load_template( $template_name . '.tpl' );
	
			$template = str_replace( "[root]", "", $template );
			$template = str_replace( "[/root]", "", $template );
			
			if( preg_match( "'\\[sub-prefix\\](.+?)\\[/sub-prefix\\]'si", $template, $match ) ) {
				$prefix = trim($match[1]);
				$template = str_replace( $match[0], "", $template );
			}
			
			if( preg_match( "'\\[sub-suffix\\](.+?)\\[/sub-suffix\\]'si", $template, $match ) ) {
				$suffix = trim($match[1]);
				$template = str_replace( $match[0], "", $template );
			}
			
			if($config['allow_cache']) {
				$template = preg_replace( "'\\[active\\](.+?)\\[/active\\]'si", "", $template );
				$template = str_replace( "[not-active]", "", $template );
				$template = str_replace( "[/not-active]", "", $template );
			}
			
			if( preg_match( "'\\[item\\](.+?)\\[/item\\]'si", $template, $match ) ) {
				$item = trim($match[1]);
				$template = str_replace( $match[0], "{items}", $template );
				
				$template = str_replace( "{items}", $this->compile_menu($catlist, $prefix, $item, $suffix, false, 0), $template );
				
			}
			
			create_cache( $cache_prefix, $template, $cache_id);
			
			$config['allow_cache'] = $allow_cache;
			
			return $template;
		
		}

	}

	function compile_menu( $nodes, $prefix, $item_template, $suffix, $sublevelmarker = false, $indent = 0 ) {
		
		$item = "";
		
		foreach ($nodes as $node) {
			
			$item .= $this->compile_item($node, $item_template);
			
			if (isset($node['children'])) {
				if ( stripos ( $item_template, "{sub-item}" ) !== false ) {
					$item = str_replace( "{sub-item}", $this->compile_menu($node['children'], $prefix, $item_template, $suffix, true, $indent+1), $item );
				} else {
					$item .= $this->compile_menu($node['children'], $prefix, $item_template, $suffix, true, $indent+1);
				}
			}
			
		}
		
		if( $sublevelmarker ) {
			
			$item =  $prefix.$item.$suffix;
			
		}
			
		
		return $item;
	}
	
	function compile_item( $row,  $template) {
		global $config, $category_id;
		
		$category = intval($category_id);
		
		$template = str_replace( "{id}", $row['id'], $template );
		$template = str_replace( "{name}", $row['name'], $template );
		$template = str_replace( "{icon}", $row['icon'], $template );
		$template = str_replace( "{url}", $config['http_home_url'] . get_url( $row['id'] ) . "/" , $template );
		$template = str_replace( "{news-count}", intval($row['newscount']), $template );
		
		if($category == $row['id']) {
			$template = str_replace( "[active]", "", $template );
			$template = str_replace( "[/active]", "", $template );
			$template = preg_replace( "'\\[not-active\\](.+?)\\[/not-active\\]'si", "", $template );
		} else {
			$template = str_replace( "[not-active]", "", $template );
			$template = str_replace( "[/not-active]", "", $template );
			$template = preg_replace( "'\\[active\\](.+?)\\[/active\\]'si", "", $template );
		}
		
	    if(!isset($row['children'])) {
			$template = str_replace( "{sub-item}", "", $template );
			$template = preg_replace( "'\\[isparent\\](.+?)\\[/isparent\\]'si", "", $template );
		} else {
			$template = str_replace( "[isparent]", "", $template );
			$template = str_replace( "[/isparent]", "", $template );
		}
		
		return $template;
		
	}
	
	function _clear() {
		
		$this->data = array ();
		$this->block_data = array ();
		$this->copy_template = $this->template;
	
	}
	
	function clear() {
		
		$this->data = array ();
		$this->block_data = array ();
		$this->copy_template = null;
		$this->template = null;
	
	}
	
	function global_clear() {
		
		$this->data = array ();
		$this->block_data = array ();
		$this->result = array ();
		$this->copy_template = null;
		$this->template = null;
	
	}
	
	function compile($tpl) {
		
		$time_before = $this->get_real_time();
		
		if( count( $this->block_data ) ) {
			foreach ( $this->block_data as $key_find => $key_replace ) {
				$find_preg[] = $key_find;
				$replace_preg[] = $key_replace;
			}
			
			$this->copy_template = preg_replace( $find_preg, $replace_preg, $this->copy_template );
		}

		foreach ( $this->data as $key_find => $key_replace ) {
			$find[] = $key_find;
			$replace[] = $key_replace;
		}
		
		$this->copy_template = str_ireplace( $find, $replace, $this->copy_template );
		
		if (strpos ( $this->copy_template, "[declination=" ) !== false) {
			$this->copy_template = preg_replace_callback ( "#\\[declination=(.+?)\\](.+?)\\[/declination\\]#is", array( &$this, 'declination'), $this->copy_template );
		}
		
		if( strpos( $this->copy_template, "{customcomments" ) !== false ) {		
			$this->copy_template = preg_replace_callback( "#\\{customcomments(.+?)\\}#i", "custom_comments", $this->copy_template );
		
		}
		
		if( strpos( $this->copy_template, "{custom" ) !== false ) {		
			$this->copy_template = preg_replace_callback( "#\\{custom(.+?)\\}#i", "custom_print", $this->copy_template );
		
		}
		
		if( strpos( $this->template, "{include file=" ) !== false ) {
			$this->include_mode = 'php';			
			$this->copy_template = preg_replace_callback( "#\\{include file=['\"](.+?)['\"]\\}#i", array( &$this, 'load_file'), $this->copy_template );
		
		}
		
		if( isset( $this->result[$tpl] ) ) $this->result[$tpl] .= $this->copy_template;
		else $this->result[$tpl] = $this->copy_template;
		
		$this->_clear();
		
		$this->template_parse_time += $this->get_real_time() - $time_before;
	}
	
	function get_real_time() {
		list ( $seconds, $microSeconds ) = explode( ' ', microtime() );
		return (( float ) $seconds + ( float ) $microSeconds);
	}
}
?>