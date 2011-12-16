<?php
/**
* Admin page to manage articles
*
* List, add, edit and delete article objects
*
* @copyright	Copyright Madfish (Simon Wilkinson) 2011
* @license		http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU General Public License (GPL)
* @since		1.0
* @author		Madfish (Simon Wilkinson) <simon@isengard.biz>
* @package		news
* @version		$Id$
*/

/**
 * Edit an Article
 *
 * @param int $article_id Articleid to be edited
*/
function editarticle($article_id = 0)
{
	global $news_article_handler, $icmsUser, $icmsAdminTpl;
	
	$articleObj = $newsModule = $sform = '';
	
	$newsModule = icms_getModuleInfo(basename(dirname(dirname(__FILE__))));
	$articleObj = $news_article_handler->get($article_id);

	if (!$articleObj->isNew()){
		$articleObj->loadTags();
		$newsModule->displayAdminMenu(0, _AM_NEWS_ARTICLES . " > " . _CO_ICMS_EDITING);
		$sform = $articleObj->getForm(_AM_NEWS_ARTICLE_EDIT, 'addarticle');
		$sform->assign($icmsAdminTpl);

	} else {
		$newsModule->displayAdminMenu(0, _AM_NEWS_ARTICLES . " > " . _CO_ICMS_CREATINGNEW);
		$articleObj->setVar('submitter', $icmsUser->uid());
		// Reduce the date field by 10 minutes to compensate for the submission form jumping forward
		// to the next 10 minute increment. This ensures that the publication date is in the past
		// (unless the user changes it), thereby preventing the article from being embargoed
		// for several minutes after submission, which is annoying and confusing.
		$articleObj->setVar('date', (time() - 600));
		$sform = $articleObj->getForm(_AM_NEWS_ARTICLE_CREATE, 'addarticle');
		$sform->assign($icmsAdminTpl);

	}
	$icmsAdminTpl->display('db:news_admin_article.html');
}

include_once("admin_header.php");

// initialise
$clean_article_id = $clean_tag_id = $clean_op = $valid_op = '';
$news_article_handler = icms_getModuleHandler('article', basename(dirname(dirname(__FILE__))),
	'news');

/** Create a whitelist of valid values, be sure to use appropriate types for each value
 * Be sure to include a value for no parameter, if you have a default condition
 */
$valid_op = array ('mod','changedField','addarticle','del','view','changeStatus',
	'changeFederation',	'');

if (isset($_GET['op'])) $clean_op = htmlentities($_GET['op']);
if (isset($_POST['op'])) $clean_op = htmlentities($_POST['op']);

$clean_article_id = isset($_GET['article_id']) ? (int) $_GET['article_id'] : 0 ;
$clean_tag_id = isset($_GET['tag_id']) ? intval($_GET['tag_id']) : 0 ;

if (in_array($clean_op,$valid_op,true)){
  switch ($clean_op) {
  	case "mod":
  	case "changedField":
  		icms_cp_header();
  		editarticle($clean_article_id);
		
  		break;
	
  	case "addarticle":
        include_once ICMS_ROOT_PATH."/kernel/icmspersistablecontroller.php";		
        $controller = new IcmsPersistableController($news_article_handler);
		$controller->storeFromDefaultForm(_AM_NEWS_ARTICLE_CREATED, _AM_NEWS_ARTICLE_MODIFIED);
		
  		break;

  	case "del":
		$controller = '';
  	    include_once ICMS_ROOT_PATH."/kernel/icmspersistablecontroller.php";
        $controller = new IcmsPersistableController($news_article_handler);
  		$controller->handleObjectDeletion();

  		break;

  	case "view" :
  		$articleObj = $news_article_handler->get($clean_article_id);
  		icms_cp_header();
  		$articleObj->displaySingleObject();
		
  		break;
	
	case "changeStatus":
			$status = $ret = '';
			$status = $news_article_handler->changeOnlineStatus($clean_article_id, 'online_status');
			$ret = '/modules/' . basename(dirname(dirname(__FILE__))) . '/admin/article.php';
			if ($status == 0) {
				redirect_header(ICMS_URL . $ret, 2, _AM_NEWS_ARTICLE_OFFLINE);
			} else {
				redirect_header(ICMS_URL . $ret, 2, _AM_NEWS_ARTICLE_ONLINE);
			}
			
		break;
		
	case "changeFederation":
			$status = $ret = '';
			$status = $news_article_handler->changeOnlineStatus($clean_article_id, 'federated');
			$ret = '/modules/' . basename(dirname(dirname(__FILE__))) . '/admin/article.php';
			if ($status == 0) {
				redirect_header(ICMS_URL . $ret, 2, _AM_NEWS_ARTICLE_FEDERATION_DISABLED);
			} else {
				redirect_header(ICMS_URL . $ret, 2, _AM_NEWS_ARTICLE_FEDERATION_ENABLED);
			}
			
		break;
		
  	default:

  		icms_cp_header();

  		$newsModule->displayAdminMenu(0, _AM_NEWS_ARTICLES);
		
		// if no op is set, but there is a (valid) soundtrack_id, display a single object
		if ($clean_article_id) {
			$articleObj = $news_article_handler->get($clean_article_id);
			if ($articleObj->id()) {
				$lead_image = $articleObj->getVar('lead_image', 'e');
				if ($lead_image) {
					$lead_image = '<img src="/uploads/' . basename(dirname(dirname(__FILE__))) 
						. '/article/' . $lead_image . '" alt="' . $articleObj->title() . '" />';
					$articleObj->setVar('lead_image', $lead_image);
				}
				$articleObj->displaySingleObject();
			}
		}
		
		// display a tag select filter (if the Sprockets module is installed)
		$newsModule = icms_getModuleInfo(basename(dirname(dirname(__FILE__))));
		$sprocketsModule = icms_getModuleInfo('sprockets');
		
		if ($sprocketsModule) {
			
			$tag_select_box = '';
			$taglink_array = $tagged_article_list = array();
			$sprockets_tag_handler = icms_getModuleHandler('tag', $sprocketsModule->dirname(),
				'sprockets');
			$sprockets_taglink_handler = icms_getModuleHandler('taglink', $sprocketsModule->dirname(),
				'sprockets');
			
			$tag_select_box = $sprockets_tag_handler->getTagSelectBox('article.php', $clean_tag_id,
				_AM_NEWS_ARTICLE_ALL_ARTICLES);
			if (!empty($tag_select_box)) {
				echo '<h3>' . _AM_NEWS_ARTICLE_FILTER_BY_TAG . '</h3>';
				echo $tag_select_box;
			}
			
			if ($clean_tag_id) {
				
				// get a list of article IDs belonging to this tag
				$criteria = new CriteriaCompo();
				$criteria->add(new Criteria('tid', $clean_tag_id));
				$criteria->add(new Criteria('mid', $newsModule->mid()));
				$criteria->add(new Criteria('item', 'article'));
				$taglink_array = $sprockets_taglink_handler->getObjects($criteria);
				foreach ($taglink_array as $taglink) {
					$tagged_article_list[] = $taglink->getVar('iid');
				}
				$tagged_article_list = "('" . implode("','", $tagged_article_list) . "')";
				
				// use the list to filter the persistable table
				$criteria = new CriteriaCompo();
				$criteria->add(new Criteria('article_id', $tagged_article_list, 'IN'));
			}
		}

		if (empty($criteria)) {
			$criteria = null;
		}

  		include_once ICMS_ROOT_PATH."/kernel/icmspersistabletable.php";
  		$objectTable = new IcmsPersistableTable($news_article_handler, $criteria);
		
		$objectTable->addQuickSearch('title');
		$objectTable->addColumn(new IcmsPersistableColumn('online_status', 'center', true));
  		$objectTable->addColumn(new IcmsPersistableColumn('title'));
		$objectTable->addColumn(new IcmsPersistableColumn('creator'));
		$objectTable->addColumn(new IcmsPersistableColumn('counter'));
		$objectTable->addColumn(new IcmsPersistableColumn('date'));
		$objectTable->setDefaultSort('date');
		$objectTable->setDefaultOrder('DESC');
		if ($sprocketsModule) {
			$objectTable->addColumn(new IcmsPersistableColumn('federated'));
			$objectTable->addFilter('federated', 'federation_filter');
		}
		$objectTable->addFilter('online_status', 'online_status_filter');
		if ($sprocketsModule) {$objectTable->addFilter('rights', 'rights_filter');}
  		$objectTable->addIntroButton('addarticle', 'article.php?op=mod', _AM_NEWS_ARTICLE_CREATE);
		
  		$icmsAdminTpl->assign('news_article_table', $objectTable->fetch());
  		$icmsAdminTpl->display('db:news_admin_article.html');
		
  		break;
  }
  icms_cp_footer();
}
/**
 * If you want to have a specific action taken because the user input was invalid,
 * place it at this point. Otherwise, a blank page will be displayed
 */