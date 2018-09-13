<?php

require __DIR__ . '/../vendor/autoload.php';

use phpspider\core\selector;
use phpspider\core\phpspider;

/* Do NOT delete this comment */
/* 不要删除这段注释 */

define('WALLPAPERS_HOST', 'wallpaperscraft.com');

$configs              = array(
	'name'                => 'dota2',
	'log_show'            => true,
	'tasknum'             => 1,
	'save_running_state'  => false,
	'domains'             => array(
		WALLPAPERS_HOST,
		'www.' . WALLPAPERS_HOST,
	),
	'scan_urls'           => array(
		'https://wallpaperscraft.com/tag/dota%202/2560x1440'
	),
	'list_url_regexes'    => array(
		"https://wallpaperscraft.com/tag/dota%202/2560x1440/page\d+"
	),
	'content_url_regexes' => array(
		"https://wallpaperscraft.com/download/(.*)/2560x1440",
	),
	'max_try'             => 5,
	//'proxies' => array(
	//'http://H784U84R444YABQD:57A8B0B743F9B4D2@proxy.abuyun.com:9010'
	//),
	//'export' => array(
	//'type' => 'csv',
	//'file' => '../data/qiushibaike.csv',
	//),
	//'export' => array(
	//'type'  => 'sql',
	//'file'  => '../data/qiushibaike.sql',
	//'table' => 'content',
	//),
	//'export' => array(
	//'type' => 'db',
	//'table' => 'content',
	//),
	//'db_config' => array(
	//'host'  => '127.0.0.1',
	//'port'  => 3306,
	//'user'  => 'root',
	//'pass'  => 'root',
	//'name'  => 'qiushibaike',
	//),
	'queue_config'        => array(
		'host'    => '127.0.0.1',
		'port'    => 6379,
		'pass'    => 'eRh38WuffrRYbNvu8T7m1Q1jZiNoAI7LT4zbho8RB/A=',
		'db'      => 5,
		'prefix'  => 'phpspider',
		'timeout' => 30,
	),
	'fields'              => array(
		array(
			'name'     => "page_list",
			'selector' => "//ul[contains(@class,'wallpapers__list')]",
			'required' => true,
		),
		array(
			'name'     => 'wallpaper_image',
			'selector' => "//div[@class='wallpaper__placeholder']",
			'required' => true,
		),
	),
);
$spider               = new phpspider($configs);
$spider->on_list_page = function ($page, $content, $phpspider) {
	$page_links = selector::select($content, "//li[@class='pager__item']");
	if (!empty($page_links)) {
		//添加其他分页
		foreach ($page_links as $page_link) {
			$preg = '/<a .*?href="(.*?)".*?>/is';
			preg_match($preg, $page_link, $match);
			if (!empty($match)) {
				$phpspider->add_url($match[1]);
			}
		}
	}
	$links = selector::select($content, "//li[@class='wallpapers__item']");
	if (!empty($links)) {
		foreach ($links as $link) {
			$preg = '/<a .*?href="(.*?)".*?>/is';
			preg_match($preg, $link, $match);
			if (!empty($match)) {
				$content_url = WALLPAPERS_HOST . $match[1];
				$phpspider->add_url($content_url);
			}
		}
	}
	return true;
};

$spider->on_handle_img = function ($fieldname, $img) {
	$regex = '/src="(https?:\/\/.*?)"/i';
	preg_match($regex, $img, $rs);
	if (!$rs) {
		return $img;
	}
	$url      = $rs[1];
	$img      = $url;
	$pathinfo = pathinfo($url);
	$fileext  = $pathinfo['extension'];
	if (strtolower($fileext) == 'jpeg') {
		$fileext = 'jpg';
	}
	// 以纳秒为单位生成随机数
	$filename = uniqid() . "." . $fileext;
	// 在data目录下生成图片
	$filepath = __DIR__ . "/images/{$filename}";
	//// 用系统自带的下载器wget下载
	exec("wget -q {$url} -O {$filepath}");
	return $img;
};

$spider->start();



