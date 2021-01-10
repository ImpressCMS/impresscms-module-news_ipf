<?php
/**
* News version infomation
*
* This file holds the configuration information of this module
*
* @copyright	Copyright Madfish (Simon Wilkinson) 2011
* @license		http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU General Public License (GPL)
* @since		1.0
* @author		Madfish (Simon Wilkinson) <simon@isengard.biz>
* @package		news
* @version		$Id$
*/

if (!defined("ICMS_ROOT_PATH")) die("ICMS root path not defined");

/**  General Information  */
$modversion = array(
  'name'=> _MI_NEWS_MD_NAME,
  'version'=> "1.18",
  'description'=> _MI_NEWS_MD_DESC,
  'author'=> "Madfish (Simon Wilkinson) & fiammybe (David Janssens)",
  'credits'=> "Functionality is based on the legacy News module, but this is a clean rewrite in IPF.",
  'help'=> "",
  'license'=> "GNU General Public License (GPL)",
  'official'=> 0,
  'dirname'=> basename(dirname(__FILE__ )),

/**  Images information  */
  'iconsmall'=> "images/icon_small.png",
  'iconbig'=> "images/icon_big.png",
  'image'=> "images/icon_big.png", /* for backward compatibility */

/**  Development information */
  'status_version'=> "1.18",
  'status'=> "Beta 2",
  'date'=> "26 September 2018",
  'author_word'=> "This module is best used with the Sprockets utility module also installed (2.01 and higher).",

/** Contributors */
  'developer_website_url' => "https://www.isengard.biz",
  'developer_website_name' => "Isengard.biz",
  'developer_email' => "simon@isengard.biz");

$modversion['people']['developers'][] = "Madfish (Simon Wilkinson)";
$modversion['people']['developers'][] = "fiammybe (David Janssens)";

/** Manual */
$modversion['manual']['wiki'][] = "<a href='https://www.impresscms.org/modules/simplywiki/index.php?title=News' target='_blank'>English</a>";

$modversion['warning'] = _CO_ICMS_WARNING_BETA;

/** Administrative information */
$modversion['hasAdmin'] = 1;
$modversion['adminindex'] = "admin/index.php";
$modversion['adminmenu'] = "admin/menu.php";

/** Database information */
$modversion['object_items'][1] = 'article';

$modversion["tables"] = icms_getTablesArray($modversion['dirname'], $modversion['object_items']);

/** Install and update informations */
$modversion['onInstall'] = "include/onupdate.inc.php";
$modversion['onUpdate'] = "include/onupdate.inc.php";

/** Search information */
$modversion['hasSearch'] = 1;
$modversion['search'] = array (
  'file' => "include/search.inc.php",
  'func' => "news_search");

/** Menu information */
$i = 1;
$modversion['hasMain'] = $i;
$sprocketsModule = icms_getModuleInfo('sprockets');
if (icms_get_module_status("sprockets")) {
	$modversion['sub'][$i]['name'] = _MI_NEWS_TOPICS_DIRECTORY;
	$modversion['sub'][$i]['url'] = "tags_directory.php";
	$i++;
}
$modversion['sub'][$i]['name'] = _MI_NEWS_ARCHIVE;
$modversion['sub'][$i]['url'] = "archive.php";
unset($i);

$modversion['blocks'][1] = array(
  'file' => 'news_article_recent.php',
  'name' => _MI_NEWS_ARTICLERECENT,
  'description' => _MI_NEWS_ARTICLERECENTDSC,
  'show_func' => 'news_article_recent_show',
  'edit_func' => 'news_article_recent_edit',
  // number articles | tag | date format | title length | spotlight | spotlighted article | image position | image width | list vs teaser mode | dynamic tag filtering
  'options' => '5|All|j/n/Y|90|0|0|2|150|0|0',
  'template' => 'news_article_recent.html');

/** Templates information */
$modversion['templates'][1] = array(
  'file' => 'news_header.html',
  'description' => 'Module Header.');

$modversion['templates'][] = array(
  'file' => 'news_footer.html',
  'description' => 'Module Footer.');

$modversion['templates'][] = array(
	'file' => 'news_rss.html',
	'description' => 'RSS feed, supports enclosures.');

$modversion['templates'][] = array(
	'file' => 'news_requirements.html',
	'description' => 'Module requirement warnings.');

$modversion['templates'][] = array(
  'file' => 'news_admin_article.html',
  'description' => 'Article admin Index.');

$modversion['templates'][] = array(
  'file' => 'news_article.html',
  'description' => 'Article Index.');

$modversion['templates'][] = array(
	'file' => 'news_archive.html',
	'description' => 'Archive Index.');

$modversion['templates'][] = array(
	'file' => 'news_topics.html',
	'description' => 'Topics directory.');

/** Preferences information */

$topic_image_default_options = array('0' => '_MI_NEWS_ARTICLE_NO', '1' => '_MI_NEWS_ARTICLE_LEFT',
	'2' => '_MI_NEWS_ARTICLE_RIGHT');
$topic_image_default_options = array_flip($topic_image_default_options);

$modversion['config'][1] = array(
  'name' => 'number_of_articles_per_page',
  'title' => '_MI_NEWS_NUMBER_ARTICLES_PER_PAGE',
  'description' => '_MI_NEWS_NUMBER_ARTICLES_PER_PAGEDSC',
  'formtype' => 'textbox',
  'valuetype' => 'int',
  'default' =>  '5');

$modversion['config'][] = array(
	'name' => 'show_tag_select_box',
	'title' => '_MI_NEWS_SHOW_TAG_SELECT_BOX',
	'description' => '_MI_NEWS_SHOW_TAG_SELECT_BOX_DSC',
	'formtype' => 'yesno',
	'valuetype' => 'int',
	'default' => '1');

$modversion['config'][] = array(
	'name' => 'show_breadcrumb',
	'title' => '_MI_NEWS_SHOW_BREADCRUMB',
	'description' => '_MI_NEWS_SHOW_BREADCRUMB_DSC',
	'formtype' => 'yesno',
	'valuetype' => 'int',
	'default' => '1');

$modversion['config'][] = array(
  'name' => 'display_topic_image',
  'title' => '_MI_NEWS_TOPIC_IMAGE_DEFAULT',
  'description' => '_MI_NEWS_TOPIC_IMAGE_DEFAULTDSC',
  'formtype' => 'select',
  'valuetype' => 'int',
  'options' => $topic_image_default_options,
  'default' =>  '0');

$modversion['config'][] = array(
  'name' => 'display_facebook_comments',
  'title' => '_MI_NEWS_DISPLAY_FACEBOOK_COMMENTS',
  'description' => '_MI_NEWS_DISPLAY_FACEBOOK_COMMENTSDSC',
  'formtype' => 'yesno',
  'valuetype' => 'int',
  'default' =>  '0');

$modversion['config'][] = array(
  'name' => 'facebook_comments_width',
  'title' => '_MI_NEWS_FACEBOOK_COMMENTS_WIDTH',
  'description' => '_MI_NEWS_FACEBOOK_COMMENTS_WIDTHDSC',
  'formtype' => 'text',
  'valuetype' => 'int',
  'default' =>  '470');

$modversion['config'][] = array(
  'name' => 'display_image',
  'title' => '_MI_NEWS_IMAGE_DEFAULT',
  'description' => '_MI_NEWS_IMAGE_DEFAULTDSC',
  'formtype' => 'select',
  'valuetype' => 'int',
  'options' => $topic_image_default_options,
  'default' =>  '0');

$modversion['config'][] = array(
  'name' => 'image_display_width',
  'title' => '_MI_NEWS_IMAGE_DISPLAY_WIDTH',
  'description' => '_MI_NEWS_IMAGE_DISPLAY_WIDTHDSC',
  'formtype' => 'text',
  'valuetype' => 'int',
  'default' =>  '300');

$modversion['config'][] = array(
	'name' => 'image_upload_height',
	'title' => '_MI_NEWS_IMAGE_UPLOAD_HEIGHT',
	'description' => '_MI_LIBRARY_IMAGE_UPLOAD_HEIGHTDSC',
	'formtype' => 'textbox',
	'valuetype' => 'int',
	'default' =>  '4000');

$modversion['config'][] = array(
	'name' => 'image_upload_width',
	'title' => '_MI_NEWS_IMAGE_UPLOAD_WIDTH',
	'description' => '_MI_LIBRARY_IMAGE_UPLOAD_WIDTHDSC',
	'formtype' => 'textbox',
	'valuetype' => 'int',
	'default' =>  '4000');

$modversion['config'][] = array(
	'name' => 'image_file_size',
	'title' => '_MI_NEWS_IMAGE_FILE_SIZE',
	'description' => '_MI_NEWS_IMAGE_FILE_SIZEDSC',
	'formtype' => 'textbox',
	'valuetype' => 'int',
	'default' =>  '2097152'); // 2MB max upload size

$modversion['config'][] = array(
	'name' => 'display_creator',
	'title' => '_MI_NEWS_DISPLAY_CREATOR',
	'description' => '_MI_NEWS_DISPLAY_CREATOR_DSC',
	'formtype' => 'yesno',
	'valuetype' => 'int',
	'default' => '1');

$modversion['config'][] = array(
	'name' => 'use_submitter_as_creator',
	'title' => '_MI_NEWS_USE_SUBMITTER_AS_CREATOR',
	'description' => '_MI_NEWS_USE_SUBMITTER_AS_CREATOR_DSC',
	'formtype' => 'yesno',
	'valuetype' => 'int',
	'default' => '0');

$modversion['config'][] = array(
	'name' => 'date_format',
	'title' => '_MI_NEWS_DATE_FORMAT',
	'description' => '_MI_NEWS_DATE_FORMAT_DSC',
	'formtype' => 'textbox',
	'valuetype' => 'text',
	'default' => 'j/n/Y');

$modversion['config'][] = array(
	'name' => 'display_counter',
	'title' => '_MI_NEWS_DISPLAY_COUNTER',
	'description' => '_MI_NEWS_DISPLAY_COUNTER_DSC',
	'formtype' => 'yesno',
	'valuetype' => 'int',
	'default' => '1');

$modversion['config'][] = array(
	'name' => 'display_rights',
	'title' => '_MI_NEWS_DISPLAY_RIGHTS',
	'description' => '_MI_NEWS_DISPLAY_RIGHTS_DSC',
	'formtype' => 'yesno',
	'valuetype' => 'int',
	'default' => '1');

$modversion['config'][] = array(
	'name' => 'number_rss_items',
	'title' => '_MI_NEWS_NUMBER_RSS_ITEMS',
	'description' => '_MI_NEWS_NUMBER_RSS_ITEMS_DSC',
	'formtype' => 'textbox',
	'valuetype' => 'int',
	'default' => '10');

$modversion['config'][] = array(
	'name' => 'default_syndication',
	'title' => '_MI_NEWS_DEFAULT_SYNDICATION',
	'description' => '_MI_NEWS_DEFAULT_SYNDICATION_DSC',
	'formtype' => 'yesno',
	'valuetype' => 'int',
	'default' => '1');

$modversion['config'][] = array(
	'name' => 'default_federation',
	'title' => '_MI_NEWS_DEFAULT_FEDERATION',
	'description' => '_MI_NEWS_DEFAULT_FEDERATION_DSC',
	'formtype' => 'yesno',
	'valuetype' => 'int',
	'default' => '1');

/** Comments information */
$modversion['hasComments'] = 1;

$modversion['comments'] = array(
  'itemName' => 'article_id',
  'pageName' => 'article.php',
  /* Comment callback functions */
  'callbackFile' => 'include/comment.inc.php',
  'callback' => array(
  'approve' => 'news_com_approve',
  'update' => 'news_com_update')
  );

/** Notification information */
$modversion['hasNotification'] = 1;

$modversion['notification'] = array (
  'lookup_file' => 'include/notification.inc.php',
  'lookup_func' => 'news_notify_iteminfo');

// notification categories
$modversion['notification']['category'][1] = array (
  'name' => 'global',
  'title' => _MI_NEWS_GLOBAL_NOTIFY,
  'description' => _MI_NEWS_GLOBAL_NOTIFY_DSC,
  'subscribe_from' => array('article.php'),
	'item_name' => '');

$modversion['notification']['category'][2] = array(
	'name' => 'article',
	'title' => _MI_NEWS_ARTICLE_NOTIFY,
	'description' => _MI_NEWS_ARTICLE_NOTIFY_DSC,
	'subscribe_from' => array('article.php'),
	'item_name' => 'article_id',
	'allow_bookmark' => 1);

// notification events
$modversion['notification']['event'][1] = array(
  'name' => 'article_published',
  'category'=> 'global',
  'title'=> _MI_NEWS_GLOBAL_ARTICLE_PUBLISHED_NOTIFY,
  'caption'=> _MI_NEWS_GLOBAL_ARTICLE_PUBLISHED_NOTIFY_CAP,
  'description'=> _MI_NEWS_GLOBAL_ARTICLE_PUBLISHED_NOTIFY_DSC,
  'mail_template'=> 'news_global_article_published',
  'mail_subject'=> _MI_NEWS_GLOBAL_ARTICLE_PUBLISHED_NOTIFY_SBJ);
