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
 Файл: clean.php
-----------------------------------------------------
 Назначение: оптимизация базы данных
=====================================================
*/

@error_reporting ( E_ALL ^ E_WARNING ^ E_NOTICE );
@ini_set ( 'display_errors', true );
@ini_set ( 'html_errors', false );
@ini_set ( 'error_reporting', E_ALL ^ E_WARNING ^ E_NOTICE );

define('DATALIFEENGINE', true);
define( 'ROOT_DIR', substr( dirname(  __FILE__ ), 0, -12 ) );
define( 'ENGINE_DIR', ROOT_DIR . '/engine' );

include ENGINE_DIR.'/data/config.php';

date_default_timezone_set ( $config['date_adjust'] );

if ($config['http_home_url'] == "") {

	$config['http_home_url'] = explode("engine/ajax/clean.php", $_SERVER['PHP_SELF']);
	$config['http_home_url'] = reset($config['http_home_url']);
	$config['http_home_url'] = "http://".$_SERVER['HTTP_HOST'].$config['http_home_url'];

}

require_once ENGINE_DIR.'/classes/mysql.php';
require_once ENGINE_DIR.'/data/dbconfig.php';
require_once ENGINE_DIR.'/inc/include/functions.inc.php';

dle_session();

$selected_language = $config['langs'];

if (isset( $_COOKIE['selected_language'] )) { 

	$_COOKIE['selected_language'] = trim(totranslit( $_COOKIE['selected_language'], false, false ));

	if ($_COOKIE['selected_language'] != "" AND @is_dir ( ROOT_DIR . '/language/' . $_COOKIE['selected_language'] )) {
		$selected_language = $_COOKIE['selected_language'];
	}

}

if ( file_exists( ROOT_DIR.'/language/'.$selected_language.'/adminpanel.lng' ) ) {
	require_once ROOT_DIR.'/language/'.$selected_language.'/adminpanel.lng';
} else die("Language file not found");

$config['charset'] = ($lang['charset'] != '') ? $lang['charset'] : $config['charset'];

require_once ENGINE_DIR.'/modules/sitelogin.php';

if(($member_id['user_group'] != 1)) {die ("error");}

if ($_REQUEST['user_hash'] == "" OR $_REQUEST['user_hash'] != $dle_login_hash) {

	  die ("error");

}

$_TIME = time ();

if ($_REQUEST['step'] == 10) {
	$_REQUEST['step'] = 11;
	$db->query("TRUNCATE TABLE " . PREFIX . "_logs");
	$db->query("TRUNCATE TABLE " . PREFIX . "_comment_rating_log");
	$db->query("TRUNCATE TABLE " . USERPREFIX . "_lostdb");
	$db->query("TRUNCATE TABLE " . PREFIX . "_flood");
	$db->query("TRUNCATE TABLE " . PREFIX . "_poll_log");
	$db->query( "INSERT INTO " . USERPREFIX . "_admin_logs (name, date, ip, action, extras) values ('".$db->safesql($member_id['name'])."', '{$_TIME}', '{$_IP}', '18', '')" );


}

if ($_REQUEST['step'] == 8) {
	$_REQUEST['step'] = 9;
	$db->query("TRUNCATE TABLE " . USERPREFIX . "_pm");
	$db->query("UPDATE " . USERPREFIX . "_users set pm_all='0', pm_unread='0'");
	$db->query( "INSERT INTO " . USERPREFIX . "_admin_logs (name, date, ip, action, extras) values ('".$db->safesql($member_id['name'])."', '{$_TIME}', '{$_IP}', '17', '')" );

}

if ($_REQUEST['step'] == 6) {
		$_REQUEST['step'] = 7;

		$db->query("UPDATE " . USERPREFIX . "_users, " . PREFIX . "_post SET " . USERPREFIX . "_users.news_num = (SELECT COUNT(*) FROM " . PREFIX . "_post WHERE " . PREFIX . "_post.autor = " . USERPREFIX . "_users.name ) WHERE " . USERPREFIX . "_users.name = " . PREFIX . "_post.autor");
		$db->query("UPDATE " . USERPREFIX . "_users, " . PREFIX . "_comments SET " . USERPREFIX . "_users.comm_num = (SELECT COUNT(*) FROM " . PREFIX . "_comments WHERE " . PREFIX . "_comments.user_id = " . USERPREFIX . "_users.user_id ) WHERE " . USERPREFIX . "_users.user_id = " . PREFIX . "_comments.user_id");

}


if ($_REQUEST['step'] == 4) {
	if ((@strtotime($_REQUEST['date']) === -1) OR (@strtotime($_REQUEST['date']) === false) OR (trim($_REQUEST['date']) == ""))
		$_REQUEST['step'] = 3;
	else {

		$_REQUEST['step'] = 5;
		$_REQUEST['date'] = $db->safesql( $_REQUEST['date'] );
		$thisdate = strtotime($_REQUEST['date']);

		$sql = $db->query("SELECT COUNT(*) as count, post_id FROM " . PREFIX . "_comments WHERE date < '{$_REQUEST['date']}' GROUP BY post_id");

		while($row = $db->get_row($sql)){

			$db->query("UPDATE " . PREFIX . "_post SET comm_num=comm_num-{$row['count']} WHERE id='{$row['post_id']}'");

		}

		$db->free ($sql);

	    $db->query("DELETE FROM " . PREFIX . "_comments WHERE date < '{$_REQUEST['date']}'");

		$db->query( "SELECT id, name FROM " . PREFIX . "_comments_files WHERE date < '{$thisdate}'" );
		
		while ( $row = $db->get_row() ) {
			$url_image = explode( "/", $row['name'] );
			
			if( count( $url_image ) == 2 ) {
				
				$folder_prefix = $url_image[0] . "/";
				$image = $url_image[1];
						
			} else {
				
				$folder_prefix = "";
				$image = $url_image[0];
			
			}
	
			$image = totranslit($image);					
	
			@unlink( ROOT_DIR . "/uploads/posts/" . $folder_prefix . $image );
			@unlink( ROOT_DIR . "/uploads/posts/" . $folder_prefix . "thumbs/" . $image );
				
		}
		
		$db->query( "DELETE FROM " . PREFIX . "_comments_files WHERE date < '{$thisdate}'" );
	
	    $db->query("UPDATE " . PREFIX . "_post SET comm_num=0 WHERE comm_num='65535'");
	    $db->query( "INSERT INTO " . USERPREFIX . "_admin_logs (name, date, ip, action, extras) values ('".$db->safesql($member_id['name'])."', '{$_TIME}', '{$_IP}', '16', '{$_REQUEST['date']}')" );

	    clear_cache();
	}
}


if ($_REQUEST['step'] == 2) {
	if ((@strtotime($_REQUEST['date']) === -1) OR (@strtotime($_REQUEST['date']) === false) OR (trim($_REQUEST['date']) == ""))
		$_REQUEST['step'] = 1;
	else {
		$_REQUEST['step'] = 3;
		$_REQUEST['date'] = $db->safesql( $_REQUEST['date'] );

		$sql = $db->query("SELECT id FROM " . PREFIX . "_post WHERE date < '{$_REQUEST['date']}'");

		while($row = $db->get_row($sql)){
			deletenewsbyid( $row['id'] );
		}

		$db->query( "INSERT INTO " . USERPREFIX . "_admin_logs (name, date, ip, action, extras) values ('".$db->safesql($member_id['name'])."', '{$_TIME}', '{$_IP}', '15', '{$_REQUEST['date']}')" );


	   $db->free ($sql);
	   clear_cache();
	}
}

if ($_REQUEST['step'] == 11) {

$rs = $db->query("SHOW TABLE STATUS FROM `".DBNAME."`");
			while ($r = $db->get_array($rs)) {
			$db->query("OPTIMIZE TABLE  ". $r['Name']);
			}
$db->free ($rs);

$db->query("SHOW TABLE STATUS FROM `".DBNAME."`");
			$mysql_size = 0;
			while ($r = $db->get_array()) {
			if (strpos($r['Name'], PREFIX."_") !== false)
			$mysql_size += $r['Data_length'] + $r['Index_length'] ;
			}

$lang['clean_finish'] = str_replace ('{db-alt}', '<span style="color:red;">'.formatsize($_REQUEST['size']).'</span>', $lang['clean_finish']);
$lang['clean_finish'] = str_replace ('{db-new}', '<span style="color:red;">'.formatsize($mysql_size).'</span>', $lang['clean_finish']);
$lang['clean_finish'] = str_replace ('{db-compare}', '<span style="color:red;">'.formatsize($_REQUEST['size'] - $mysql_size).'</span>', $lang['clean_finish']);

$buffer = <<<HTML
{$lang['clean_finish']}
<br /><br />
HTML;

}

if ($_REQUEST['step'] == 9) {
$buffer = <<<HTML
{$lang['clean_logs']}
<br /><br /><span style="color:red;"><span id="status"></span></span><br /><br />
		<input id = "next_button" onclick="start_clean('10', '{$_REQUEST['size']}'); return false;" class="btn bg-teal btn-sm btn-raised position-left" type="button" value="{$lang['edit_next']}">
		<input id = "skip_button" onclick="start_clean('11', '{$_REQUEST['size']}'); return false;" class="btn bg-slate-600 btn-sm btn-raised" type="button" value="{$lang['clean_skip']}">
HTML;
}

if ($_REQUEST['step'] == 7) {
$buffer = <<<HTML
{$lang['clean_pm']}
<br /><br /><span style="color:red;"><span id="status"></span></span><br /><br />
		<input id = "next_button" onclick="start_clean('8', '{$_REQUEST['size']}'); return false;" class="btn bg-teal btn-sm btn-raised position-left" type="button" value="{$lang['edit_next']}">
		<input id = "skip_button" onclick="start_clean('9', '{$_REQUEST['size']}'); return false;" class="btn bg-slate-600 btn-sm btn-raised" type="button" value="{$lang['clean_skip']}">
HTML;
}

if ($_REQUEST['step'] == 5) {
$buffer = <<<HTML
{$lang['clean_users']}
<br /><br /><span style="color:red;"><span id="status"></span></span><br /><br />
		<input id = "next_button" onclick="start_clean('6', '{$_REQUEST['size']}'); return false;" class="btn bg-teal btn-sm btn-raised position-left" type="button" value="{$lang['edit_next']}">
		<input id = "skip_button" onclick="start_clean('7', '{$_REQUEST['size']}'); return false;" class="btn bg-slate-600 btn-sm btn-raised" type="button" value="{$lang['clean_skip']}">
HTML;
}

if ($_REQUEST['step'] == 3) {
$buffer = <<<HTML
{$lang['clean_comments']}<br /><br />{$lang['addnews_date']}&nbsp;<input data-rel="calendardate" type="text" name="date" id="f_date_c" class="form-control" style="width:190px;" autocomplete="off">
<script type="text/javascript">
	$('[data-rel=calendardate]').datetimepicker({
	  format:'Y-m-d',
	  closeOnDateSelect:true,
	  dayOfWeekStart: 1,
	  timepicker:false,
	  i18n: cal_language
	});
</script>
<br /><br /><span style="color:red;"><span id="status"></span></span><br /><br />
		<input id = "next_button" onclick="start_clean('4', '{$_REQUEST['size']}'); return false;" class="btn bg-teal btn-sm btn-raised position-left" type="button" value="{$lang['edit_next']}">&nbsp;
		<input id = "skip_button" onclick="start_clean('5', '{$_REQUEST['size']}'); return false;" class="btn bg-slate-600 btn-sm btn-raised" type="button" value="{$lang['clean_skip']}">
HTML;
}

if ($_REQUEST['step'] == 1) {
$buffer = <<<HTML
{$lang['clean_news']}<br /><br />{$lang['addnews_date']}&nbsp;<input data-rel="calendardate" type="text" name="date" id="f_date_c" class="form-control" style="width:190px;" autocomplete="off">
<script type="text/javascript">
	$('[data-rel=calendardate]').datetimepicker({
	  format:'Y-m-d',
	  closeOnDateSelect:true,
	  dayOfWeekStart: 1,
	  timepicker:false,
	  i18n: cal_language
	});
</script>
<br /><br /><span style="color:red;"><span id="status"></span></span><br /><br />
		<input id = "next_button" onclick="start_clean('2', '{$_REQUEST['size']}'); return false;" class="btn bg-teal btn-sm btn-raised position-left" type="button" value="{$lang['edit_next']}">
		<input id = "skip_button" onclick="start_clean('3', '{$_REQUEST['size']}'); return false;" class="btn bg-slate-600 btn-sm btn-raised" type="button" value="{$lang['clean_skip']}">
HTML;
}

@header("Content-type: text/html; charset=".$config['charset']);
echo $buffer;
?>