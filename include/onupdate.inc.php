<?php
/**
* File containing onUpdate and onInstall functions for the module
*
* This file is included by the core in order to trigger onInstall or onUpdate functions when needed.
* Of course, onUpdate function will be triggered when the module is updated, and onInstall when
* the module is originally installed. The name of this file needs to be defined in the
* icms_version.php
*
* <code>
* $modversion['onInstall'] = "include/onupdate.inc.php";
* $modversion['onUpdate'] = "include/onupdate.inc.php";
* </code>
*
* @copyright	Copyright Madfish (Simon Wilkinson) 2011
* @license		http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU General Public License (GPL)
* @since		1.0
* @author		Madfish (Simon Wilkinson) <simon@isengard.biz>
* @package		news
* @version		$Id$
*/

if (!defined("ICMS_ROOT_PATH")) die("ICMS root path not defined");

// this needs to be the latest db version
define('NEWS_DB_VERSION', 1);

/**
 * Updates news module
 *
 * @param object $module
 * @return bool
 */

function icms_module_update_news($module) {
    return TRUE;
}

/**
 * Conducts optional tasks on news module installation or update
 *
 * Checks that an upload directory is available (creates one if necessary) and authorises the
 * module to use common image mimetypes that will probably be required.
 *
 * @param object $module
 * @return boolean
 */
function icms_module_install_news($module) {

	// create an uploads directory for images
	$path = ICMS_ROOT_PATH . '/uploads/' . basename(dirname(dirname(__FILE__)));
	$directory_exists = $file_exists = $writeable = TRUE;

	// check if upload directory exists, make one if not, and write an empty index file
	if (!is_dir($path)) {
		$directory_exists = mkdir($path, 0777);
		$path .= '/index.html';

		// add an index file to prevent index lookups
		if (!is_file($path)) {
			$filename = $path;
			$contents = '<script>history.go(-1);</script>';
			$handle = fopen($filename, 'wb');
			$result = fwrite($handle, $contents);
			echo 'result is: ' . $result;
			fclose($handle);
			chmod($path, 0644);
		}
	}

	// authorise some audio mimetypes for convenience
	news_authorise_mimetypes();

	return TRUE;
}

/**
 * Authorises some common audio (and image) mimetypes on install
 *
 * Helps reduce the need for post-install configuration, its just a convenience for the end user.
 * It grants the module permission to use some common audio (and image) mimetypes that will
 * probably be needed for audio tracks and programme cover art.
 */
function news_authorise_mimetypes() {
	$dirname = basename(dirname(dirname(__FILE__)));
	$extension_list = array('png', 'gif', 'jpg');
	$system_mimetype_handler = icms_getModuleHandler('mimetype', 'system');
	foreach ($extension_list as $extension) {
		$allowed_modules = array();
		$mimetypeObj = '';

		$criteria = new icms_db_criteria_Compo();
		$criteria->add(new icms_db_criteria_Item('extension', $extension));
		$mimetypeObj = array_shift($system_mimetype_handler->getObjects($criteria));

		if ($mimetypeObj) {
			$allowed_modules = $mimetypeObj->getVar('dirname');
			if (empty($allowed_modules)) {
				$mimetypeObj->setVar('dirname', $dirname);
				$mimetypeObj->store();
			} else {
				if (!in_array($dirname, $allowed_modules)) {
					$allowed_modules[] = $dirname;
					$mimetypeObj->setVar('dirname', $allowed_modules);
					$mimetypeObj->store();
				}
			}
		}
	}
}