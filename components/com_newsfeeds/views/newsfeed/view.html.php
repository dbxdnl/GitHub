<?php
/**
 * version $Id: view.html.php 18650 2010-08-26 13:28:49Z ian $
 * @package		Joomla
 * @subpackage	Newsfeeds
 * @copyright	Copyright (C) 2005 - 2010 Open Source Matters, Inc. All rights reserved.
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 *
 */

// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die;

jimport('joomla.application.component.view');

/**
 * HTML View class for the Newsfeeds component
 *
 * @static
 * @package		Joomla
 * @subpackage	Newsfeeds
 * @since 1.0
 */
class NewsfeedsViewNewsfeed extends JView
{
	/**
	 * @var		object
	 * @since	1.6
	 */
	protected $state;

	/**
	 * @var		object
	 * @since	1.6
	 */
	protected $item;

	/**
	 * @var		boolean
	 * @since	1.6
	 */
	protected $print;

	/**
	 * @since	1.6
	 */
	function display($tpl = null)
	{
		// Initialise variables.
		$app		= JFactory::getApplication();
		$user		= JFactory::getUser();
		$dispatcher	= JDispatcher::getInstance();

		// Get view related request variables.
		$print = JRequest::getBool('print');

		// Get model data.
		$state = $this->get('State');
		$item = $this->get('Item');

		if ($item) {
		// Get Category Model data
		$categoryModel = JModel::getInstance('Category', 'NewsfeedsModel', array('ignore_request' => true));
		$categoryModel->setState('category.id', $item->catid);
		$categoryModel->setState('list.ordering', 'a.title');
		$categoryModel->setState('list.direction', 'asc');		
		$items = $categoryModel->getItems();
		}
		
		// Check for errors.
		// @TODO Maybe this could go into JComponentHelper::raiseErrors($this->get('Errors'))
		if (count($errors = $this->get('Errors'))) {
			JError::raiseWarning(500, implode("\n", $errors));

			return false;
		}

		// Add router helpers.
		$item->slug = $item->alias ? ($item->id . ':' . $item->alias) : $item->id;
		$item->catslug = $item->category_alias ? ($item->catid . ':' . $item->category_alias) : $item->catid;
		$item->parent_slug = $item->category_alias ? ($item->parent_id . ':' . $item->parent_alias) : $item->parent_id;

		// check if cache directory is writeable
		$cacheDir = JPATH_BASE.DS.'cache'.DS;

		if (!is_writable($cacheDir)) {
			echo JText::_('CACHE_DIRECTORY_UNWRITABLE');
			return;
		}

		// Merge newsfeed params. If this is single-newsfeed view, menu params override newsfeed params
		// Otherwise, newsfeed params override menu item params
		$params = $state->get('params');
		$newsfeed_params = new JRegistry;
		$newsfeed_params->loadJSON($item->params);
		$active = $app->getMenu()->getActive();
		$temp = clone ($params);

		if ($active) {
			$currentLink = $active->link;

			if (strpos($currentLink, 'view=newsfeed')) {
				$newsfeed_params->merge($temp);
				$item->params = $newsfeed_params;
			}
			else {
				$temp->merge($newsfeed_params);
				$item->params = $temp;
			}
		}
		else {
			$temp->merge($newsfeed_params);
			$item->params = $temp;
		}

		$offset = $state->get('list.offset');

		// Check the access to the newsfeed
		$levels = $user->authorisedLevels();

		if (!in_array($item->access, $levels) OR ((in_array($item->access,$levels) AND (!in_array($item->category_access, $levels))))) {
			JError::raiseWarning(403, JText::_('JERROR_ALERTNOAUTHOR'));

			return;
		}

		// Override the layout.
		if ($layout = $params->get('layout')) {
			$this->setLayout($layout);
		}

		// Get the current menu item
		$menus	= $app->getMenu();
		$menu	= $menus->getActive();
		$params	= $app->getParams();

		// Get the newsfeed
		$newsfeed = $item;

		$temp = new JRegistry();
		$temp->loadJSON($item->params);
		$params->merge($temp);

		//  get RSS parsed object
		$options = array();
		$options['rssUrl']		= $newsfeed->link;
		$options['cache_time']	= $newsfeed->cache_time;

		$rssDoc = JFactory::getXMLparser('RSS', $options);

		if ($rssDoc == false) {
			$msg = JText::_('COM_NEWSFEEDS_ERRORS_FEED_NOT_RETRIEVED');
			$app->redirect(NewsFeedsHelperRoute::getCategoryRoute($newsfeed->catslug), $msg);
			return;
		}
		$lists = array();

		// channel header and link
		$newsfeed->channel['title']			= $rssDoc->get_title();
		$newsfeed->channel['link']			= $rssDoc->get_link();
		$newsfeed->channel['description']	= $rssDoc->get_description();
		$newsfeed->channel['language']		= $rssDoc->get_language();

		// channel image if exists
		$newsfeed->image['url']		= $rssDoc->get_image_url();
		$newsfeed->image['title']	= $rssDoc->get_image_title();
		$newsfeed->image['link']	= $rssDoc->get_image_link();
		$newsfeed->image['height']	= $rssDoc->get_image_height();
		$newsfeed->image['width']	= $rssDoc->get_image_width();

		// items
		$newsfeed->items = $rssDoc->get_items();

		// feed elements
		$newsfeed->items = array_slice($newsfeed->items, 0, $newsfeed->numarticles);

		$this->assignRef('params'  , $params  );
		$this->assignRef('newsfeed', $newsfeed);
		$this->assignRef('state', $state);
		$this->assignRef('item', $item);
		$this->assignRef('user', $user);
		$this->assign('print', $print);

		$this->_prepareDocument();

		parent::display($tpl);
	}

	/**
	 * Prepares the document
	 *
	 * @return	void
	 * @since	1.6
	 */
	protected function _prepareDocument()
	{
		$app		= JFactory::getApplication();
		$menus		= $app->getMenu();
		$pathway	= $app->getPathway();
		$title		= null;

		// Because the application sets a default page title,
		// we need to get it from the menu item itself
		$menu = $menus->getActive();
		if ($menu) {
			$this->params->def('page_heading', $this->params->get('page_title', $menu->title));
		}
		else {
			$this->params->def('page_heading', JText::_('JGLOBAL_NEWSFEEDS'));
		}

		$title = $this->params->get('page_title', '');
		if (empty($title)) {
			$title = htmlspecialchars_decode($app->getCfg('sitename'));
		}
		else if ($app->getCfg('sitename_pagetitles', 0)) {
			$title = JText::sprintf('JPAGETITLE', htmlspecialchars_decode($app->getCfg('sitename')), $title);
		}
		$this->document->setTitle($title);

		if ($menu && $menu->query['view'] != 'newsfeed') {
			$id = (int) @$menu->query['id'];
			$path = array($this->item->name  => '');
			$category = JCategories::getInstance('Newsfeeds')->get($this->item->catid);
			if ($category){
				while ($id != $category->id && $category->id > 1)
				{
					$path[$category->title] = NewsfeedHelperRoute::getCategoryRoute($category->id);
					$category = $category->getParent();
				}
				$path = array_reverse($path);
 			}
		}

		if (empty($title)) {
			$title = $this->item->title;
		}
		$this->document->setTitle($title);

		if ($this->item->metadesc) {
			$this->document->setDescription($this->item->metadesc);
		}

		if ($this->item->metakey) {
			$this->document->setMetadata('keywords', $this->item->metakey);
		}

		if ($app->getCfg('MetaTitle') == '1') {
			$this->document->setMetaData('title', $this->item->name);
		}

		if ($app->getCfg('MetaAuthor') == '1') {
			$this->document->setMetaData('author', $this->item->author);
		}

		$mdata = $this->item->metadata->toArray();
		foreach ($mdata as $k => $v)
		{
			if ($v) {
				$this->document->setMetadata($k, $v);
			}
		}
	}
}