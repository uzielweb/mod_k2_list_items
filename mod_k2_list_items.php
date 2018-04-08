<?php

/**

 * @copyright	Copyright © 2018 - All rights reserved.

 * @license		GNU General Public License v2.0

 * @generator	http://xdsoft/joomla-module-generator/

 */

defined('_JEXEC') or die;



$doc = JFactory::getDocument();

/* Available fields:"category","items_total","items_per_column", */

// Include assets

$doc->addStyleSheet(JURI::root()."modules/mod_k2_list_items/assets/css/style.css");
$doc->addScript(JURI::root()."modules/mod_k2_list_items/assets/js/script.js");
// $width 			= $params->get("width");



/**

    $db = JFactory::getDBO();

    $db->setQuery("SELECT * FROM #__mod_k2_list_items where del=0 and module_id=".$module->id);

    $objects = $db->loadAssocList();

*/

require JModuleHelper::getLayoutPath('mod_k2_list_items', $params->get('layout', 'default'));

