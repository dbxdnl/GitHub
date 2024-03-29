<?php
/**
 * @version		$Id: view.html.php 17858 2010-06-23 17:54:28Z eddieajau $
 * @copyright	Copyright (C) 2005 - 2010 Open Source Matters, Inc. All rights reserved.
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 */

// No direct access
defined('_JEXEC') or die;

jimport('joomla.application.component.view');

/**
 * HTML View class for the Media component
 *
 * @package		Joomla.Administrator
 * @subpackage	com_media
 * @since 1.0
 */
class MediaViewMedia extends JView
{
	function display($tpl = null)
	{
		$app	= JFactory::getApplication();
		$config = JComponentHelper::getParams('com_media');

		$style = $app->getUserStateFromRequest('media.list.layout', 'layout', 'thumbs', 'word');

		$document = JFactory::getDocument();
		$document->setBuffer($this->loadTemplate('navigation'), 'modules', 'submenu');

		JHtml::_('behavior.framework', true);

		JHTML::_('script','media/mediamanager.js', true, true);
		JHTML::_('stylesheet','media/mediamanager.css', array(), true);

		JHtml::_('behavior.modal');
		$document->addScriptDeclaration("
		window.addEvent('domready', function() {
			document.preview = SqueezeBox;
		});");

		JHTML::_('script','system/mootree.js', true, true, false, false);
		JHTML::_('stylesheet','system/mootree.css', array(), true);

		if ($config->get('enable_flash', 1)) {
			$fileTypes = $config->get('image_extensions', 'bmp,gif,jpg,png,jpeg');
			$types = explode(',', $fileTypes);
			$displayTypes = '';		// this is what the user sees
			$filterTypes = '';		// this is what controls the logic
			$firstType = true;
			foreach($types AS $type) {
				if(!$firstType) {
					$displayTypes .= ', ';
					$filterTypes .= '; ';
				} else {
					$firstType = false;
				}
				$displayTypes .= '*.'.$type;
				$filterTypes .= '*.'.$type;
			}
			$typeString = '{ \'Images ('.$displayTypes.')\': \''.$filterTypes.'\' }';

			JHtml::_('behavior.uploader', 'upload-flash',
				array(
					'onBeforeStart' => 'function(){ Uploader.setOptions({url: $(\'uploadForm\').action + \'&folder=\' + $(\'mediamanager-form\').folder.value}); }',
					'onComplete' 	=> 'function(){ MediaManager.refreshFrame(); }',
					'targetURL' 	=> '\\$(\'uploadForm\').action',
					'typeFilter' 	=> $typeString,
					'fileSizeMax'	=> $config->get('upload_maxsize'),
				)
			);
		}

		if (DS == '\\')
		{
			$base = str_replace(DS,"\\\\",COM_MEDIA_BASE);
		} else {
			$base = COM_MEDIA_BASE;
		}

		$js = "
			var basepath = '".$base."';
			var viewstyle = '".$style."';
		" ;
		$document->addScriptDeclaration($js);

		/*
		 * Display form for FTP credentials?
		 * Don't set them here, as there are other functions called before this one if there is any file write operation
		 */
		jimport('joomla.client.helper');
		$ftp = !JClientHelper::hasCredentials('ftp');

		$this->assignRef('session', JFactory::getSession());
		$this->assignRef('config', $config);
		$this->assignRef('state', $this->get('state'));
		$this->assign('require_ftp', $ftp);
		$this->assign('folders_id', ' id="media-tree"');
		$this->assign('folders', $this->get('folderTree'));

		// Set the toolbar
		$this->addToolbar();

		parent::display($tpl);
		echo JHtml::_('behavior.keepalive');
	}

	/**
	 * Add the page title and toolbar.
	 *
	 * @since	1.6
	 */
	protected function addToolbar()
	{
		// Get the toolbar object instance
		$bar = JToolBar::getInstance('toolbar');

		// Set the titlebar text
		JToolBarHelper::title(JText::_('COM_MEDIA'), 'mediamanager.png');

		// Add a delete button
		$title = JText::_('JTOOLBAR_DELETE');
		$dhtml = "<a href=\"#\" onclick=\"MediaManager.submit('folder.delete')\" class=\"toolbar\">
					<span class=\"icon-32-delete\" title=\"$title\"></span>
					$title</a>";
		$bar->appendButton('Custom', $dhtml, 'delete');
		JToolBarHelper::divider();
		JToolBarHelper::preferences('com_media', 450, 800, 'JToolbar_Options', '', 'window.location.reload()');
		JToolBarHelper::divider();
		JToolBarHelper::help('JHELP_CONTENT_MEDIA_MANAGER');
	}

	function getFolderLevel($folder)
	{
		$this->folders_id = null;
		$txt = null;
		if (isset($folder['children']) && count($folder['children'])) {
			$tmp = $this->folders;
			$this->folders = $folder;
			$txt = $this->loadTemplate('folders');
			$this->folders = $tmp;
		}
		return $txt;
	}
}
