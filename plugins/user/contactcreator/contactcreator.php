<?php
/**
 * @version	$Id: contactcreator.php 17851 2010-06-23 17:39:31Z eddieajau $
 *
 * Contact Creator
 * A tool to automatically create and synchronise contacts with a user
 * @copyright	Copyright (C) 2005 - 2009 Open Source Matters, Inc. All rights reserved.
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 * @package Contact_Creator
 */

// No direct access.
defined('_JEXEC') or die;

jimport('joomla.plugins.plugin');

/**
 * Class for Contact Creator
 * @package		Joomla.Plugins
 * @subpackage	user.contactcreator
 * @version		1.6
 */
class plgUserContactCreator extends JPlugin
{

	function onUserAfterSave($user, $isnew, $success, $msg)
	{
		if(!$success) {
			return false; // if the user wasn't stored we don't resync
		}

		// ensure the user id is really an int
		$user_id = (int)$user['id'];

		if(empty($user_id)) {
			die('invalid userid');
			return false; // if the user id appears invalid then bail out just in case
		}

		$category = $this->params->get('category', 0);
		if(empty($category))
		{
			JError::raiseWarning(41, JText::_('PLG_CONTACTCREATOR_ERR_NO_CATEGORY'));
			return false; // bail out if we don't have a category
		}

		$dbo = JFactory::getDBO();
		// grab the contact ID for this user; note $user_id is cleaned above
		$dbo->setQuery('SELECT id FROM #__contact_details WHERE user_id = '. $user_id );
		$id = $dbo->loadResult();

		JTable::addIncludePath(JPATH_ADMINISTRATOR.'/components/com_contact/tables');
		$contact = JTable::getInstance('contact', 'ContactTable');
		if(!$contact) {
			return false;
		}

		if($id) {
			$contact->load($id);
		} else if($this->params->get('autopublish', 0)) {
			$contact->published = 1;
		}

		$contact->name = $user['name'];
		$contact->user_id = $user_id;
		$contact->email_to = $user['email'];
		$contact->catid = $category;

		$autowebpage = $this->params->get('autowebpage', '');
		if(!empty($autowebpage))
		{
			// search terms
			$search_array = Array('[name]', '[username]','[userid]','[email]');
			// replacement terms, urlencoded
			$replace_array = array_map('urlencode', Array($user['name'], $user['username'],$user['id'],$user['email']));
			// now replace it in together
			$contact->webpage = str_replace($search_array, $replace_array, $autowebpage);
		}
		if($contact->check()) {
			$result = $contact->store();
		}
		if(!(isset($result)) || !$result) {
			JError::raiseError(42, JText::sprintf('PLG_CONTACTCREATOR_ERR_FAILED_UPDATE', $contact->getErrorMsg()));
		}
	}

}