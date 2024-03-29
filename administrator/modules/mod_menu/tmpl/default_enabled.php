<?php
/**
 * @version		$Id:mod_menu.php 2463 2006-02-18 06:05:38Z webImagery $
 * @package		Joomla.Administrator
 * @subpackage	mod_menu
 * @copyright	Copyright (C) 2005 - 2010 Open Source Matters, Inc. All rights reserved.
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 */

// No direct access.
defined('_JEXEC') or die;

$shownew 	= (boolean)$params->get('shownew', 1);
$showhelp 	= $params->get('showhelp', 1);

//
// Site SubMenu
//
$menu->addChild(
new JMenuNode(JText::_('JSITE'), '#'), true
);
$menu->addChild(
new JMenuNode(JText::_('MOD_MENU_CONTROL_PANEL'), 'index.php', 'class:cpanel')
);

$menu->addSeparator();

if ($user->authorise('core.admin')) {
	$menu->addChild(new JMenuNode(JText::_('MOD_MENU_CONFIGURATION'), 'index.php?option=com_config', 'class:config'));
	$menu->addSeparator();
}

$chm = $user->authorise('core.manage', 'com_checkin');
$cam = $user->authorise('core.manage', 'com_cache');

if ($chm || $cam )
{
	$menu->addChild(
	new JMenuNode(JText::_('MOD_MENU_SITE_MAINTENANCE'), '#', 'class:maintenance'), true
	);

	if ($chm)
	{
		$menu->addChild(new JMenuNode(JText::_('MOD_MENU_GLOBAL_CHECKIN'), 'index.php?option=com_checkin', 'class:checkin'));
		$menu->addSeparator();
	}
	if ($cam)
	{
		$menu->addChild(new JMenuNode(JText::_('MOD_MENU_CLEAR_CACHE'), 'index.php?option=com_cache', 'class:clear'));
		$menu->addChild(new JMenuNode(JText::_('MOD_MENU_PURGE_EXPIRED_CACHE'), 'index.php?option=com_cache&view=purge', 'class:purge'));
	}

	$menu->getParent();
}

$menu->addSeparator();
$menu->addChild(
new JMenuNode(JText::_('MOD_MENU_SYSTEM_INFORMATION'), 'index.php?option=com_admin&view=sysinfo', 'class:info')
);
$menu->addSeparator();

$menu->addChild(new JMenuNode(JText::_('MOD_MENU_LOGOUT'), 'index.php?option=com_login&task=logout', 'class:logout'));

$menu->getParent();


//
// Users Submenu
//
if ($user->authorise('core.manage', 'com_users'))
{
	$menu->addChild(
	new JMenuNode(JText::_('MOD_MENU_COM_USERS_USERS'), '#'), true
	);
	$createUser = $shownew && $user->authorise('core.create', 'com_users');

	$menu->addChild(
	new JMenuNode(JText::_('MOD_MENU_COM_USERS_USER_MANAGER'), 'index.php?option=com_users&view=users', 'class:user'), $createUser
	);
	if ($createUser) {
		$menu->addChild(
		new JMenuNode(JText::_('MOD_MENU_COM_USERS_ADD_USER'), 'index.php?option=com_users&task=user.add', 'class:newarticle')
		);
		$menu->getParent();
	}

	$menu->addChild(
	new JMenuNode(JText::_('MOD_MENU_COM_USERS_GROUPS'), 'index.php?option=com_users&view=groups', 'class:groups'), $createUser
	);
	if ($createUser) {
		$menu->addChild(
		new JMenuNode(JText::_('MOD_MENU_COM_USERS_ADD_GROUP'), 'index.php?option=com_users&task=group.add', 'class:newarticle')
		);
		$menu->getParent();
	}
	$menu->addChild(
	new JMenuNode(JText::_('MOD_MENU_COM_USERS_LEVELS'), 'index.php?option=com_users&view=levels', 'class:levels'), $createUser
	);
	if ($createUser) {
		$menu->addChild(
		new JMenuNode(JText::_('MOD_MENU_COM_USERS_ADD_LEVEL'), 'index.php?option=com_users&task=level.add', 'class:newarticle')
		);
		$menu->getParent();
	}

	$menu->addSeparator();
	$menu->addChild(
	new JMenuNode(JText::_('MOD_MENU_MASS_MAIL_USERS'), 'index.php?option=com_users&view=mail', 'class:massmail')
	);

	$menu->getParent();
}

//
// Menus Submenu
//
if ($user->authorise('core.manage', 'com_menus'))
{
	$menu->addChild(
	new JMenuNode(JText::_('MOD_MENU_MENUS'), '#'), true
	);
	$createMenu = $shownew && $user->authorise('core.create', 'com_menus');

	$menu->addChild(
	new JMenuNode(JText::_('MOD_MENU_MENU_MANAGER'), 'index.php?option=com_menus&view=menus', 'class:menumgr'), $createMenu
	);
	if ($createMenu) {
		$menu->addChild(
		new JMenuNode(JText::_('MOD_MENU_MENU_MANAGER_NEW_MENU'), 'index.php?option=com_menus&view=menu&layout=edit', 'class:newarticle')
		);
		$menu->getParent();
	}
	$menu->addSeparator();

	// Menu Types
	foreach (ModMenuHelper::getMenus() as $menuType)
	{	
		$titleicon = $menuType->home ? ' <span>'.JHTML::_('image','menu/icon-16-default.png', NULL, NULL, true).'</span>' : '';
		$menu->addChild(
		new JMenuNode($menuType->title,	'index.php?option=com_menus&view=items&menutype='.$menuType->menutype, 'class:menu', null, null, $titleicon), $createMenu
				);

		if ($createMenu) {
				$menu->addChild(
				new JMenuNode(JText::_('MOD_MENU_MENU_MANAGER_NEW_MENU_ITEM'), 'index.php?option=com_menus&view=item&layout=edit&menutype='.$menuType->menutype, 'class:newarticle')
				);
				$menu->getParent();
		}
	}
	$menu->getParent();
}

//
// Content Submenu
//
if ($user->authorise('core.manage', 'com_content'))
{
	$menu->addChild(
	new JMenuNode(JText::_('MOD_MENU_COM_CONTENT'), '#'), true
	);
	$createContent =  $shownew && $user->authorise('core.create', 'com_content');
	$menu->addChild(
	new JMenuNode(JText::_('MOD_MENU_COM_CONTENT_ARTICLE_MANAGER'), 'index.php?option=com_content', 'class:article'), $createContent
	);
	if ($createContent) {
		$menu->addChild(
		new JMenuNode(JText::_('MOD_MENU_COM_CONTENT_NEW_ARTICLE'), 'index.php?option=com_content&task=article.add', 'class:newarticle')
		);
		$menu->getParent();
	}

	$menu->addChild(
	new JMenuNode(JText::_('MOD_MENU_COM_CONTENT_CATEGORY_MANAGER'), 'index.php?option=com_categories&extension=com_content', 'class:category'), $createContent
	);
	if ($createContent) {
		$menu->addChild(
		new JMenuNode(JText::_('MOD_MENU_COM_CONTENT_NEW_CATEGORY'), 'index.php?option=com_categories&task=category.add&extension=com_content', 'class:newarticle')
		);
		$menu->getParent();
	}
	$menu->addChild(
	new JMenuNode(JText::_('MOD_MENU_COM_CONTENT_FEATURED'), 'index.php?option=com_content&view=featured', 'class:featured')
	);
	$menu->addSeparator();
	if ($user->authorise('core.manage', 'com_media')) {
		$menu->addChild(new JMenuNode(JText::_('MOD_MENU_MEDIA_MANAGER'), 'index.php?option=com_media', 'class:media'));
	}

	$menu->getParent();
}

//
// Components Submenu
//
$menu->addChild(new JMenuNode(JText::_('MOD_MENU_COMPONENTS'), '#'), true);

// Get the authorised components and sub-menus.
$components = ModMenuHelper::getComponents( true );

foreach ($components as &$component)
{
	if (!empty($component->submenu))
	{
		// This component has a db driven submenu.
		$menu->addChild(new JMenuNode($component->text, $component->link, $component->img), true);
		foreach ($component->submenu as $sub)
		{
			$menu->addChild(new JMenuNode($sub->text, $sub->link, $sub->img));
		}
		$menu->getParent();
	}
	else {
		$menu->addChild(new JMenuNode($component->text, $component->link, $component->img));
	}
}
$menu->getParent();

//
// Extensions Submenu
//
$im = $user->authorise('core.manage', 'com_installer');
$mm = $user->authorise('core.manage', 'com_modules');
$pm = $user->authorise('core.manage', 'com_plugins');
$tm = $user->authorise('core.manage', 'com_templates');
$lm = $user->authorise('core.manage', 'com_languages');

if ($im || $mm || $pm || $tm || $lm)
{
	$menu->addChild(new JMenuNode(JText::_('MOD_MENU_EXTENSIONS_EXTENSIONS'), '#'), true);

	if ($im)
	{
		$menu->addChild(new JMenuNode(JText::_('MOD_MENU_EXTENSIONS_EXTENSION_MANAGER'), 'index.php?option=com_installer', 'class:install'));
		$menu->addSeparator();
	}
	if ($mm) {
		$menu->addChild(new JMenuNode(JText::_('MOD_MENU_EXTENSIONS_MODULE_MANAGER'), 'index.php?option=com_modules', 'class:module'));
	}
	if ($pm) {
		$menu->addChild(new JMenuNode(JText::_('MOD_MENU_EXTENSIONS_PLUGIN_MANAGER'), 'index.php?option=com_plugins', 'class:plugin'));
	}
	if ($tm) {
		$menu->addChild(new JMenuNode(JText::_('MOD_MENU_EXTENSIONS_TEMPLATE_MANAGER'), 'index.php?option=com_templates', 'class:themes'));
	}
	if ($lm) {
		$menu->addChild(new JMenuNode(JText::_('MOD_MENU_EXTENSIONS_LANGUAGE_MANAGER'), 'index.php?option=com_languages', 'class:language'));
	}
	$menu->getParent();
}

//
// Help Submenu
//
if ($showhelp == 1) {
	$menu->addChild(
	new JMenuNode(JText::_('MOD_MENU_HELP'), '#'), true
	);
	$menu->addChild(
	new JMenuNode(JText::_('MOD_MENU_HELP_JOOMLA'), 'index.php?option=com_admin&view=help', 'class:help')
	);
	$menu->addSeparator();

	$menu->addChild(
	new JMenuNode(JText::_('MOD_MENU_HELP_SUPPORT_FORUM'), 'http://forum.joomla.org', 'class:help-forum', false, '_blank')
	);
	$menu->addChild(
	new JMenuNode(JText::_('MOD_MENU_HELP_DOCUMENTATION'), 'http://docs.joomla.org', 'class:help-docs', false, '_blank')
	);
	$menu->addSeparator();
	$menu->addChild(
	new JMenuNode(JText::_('MOD_MENU_HELP_LINKS'), '#', 'class:weblinks'), true
	);
	$menu->addChild(
	new JMenuNode(JText::_('MOD_MENU_HELP_EXTENSIONS'), 'http://extensions.joomla.org', 'class:help-jed', false, '_blank')
	);
	$menu->addChild(
	new JMenuNode(JText::_('MOD_MENU_HELP_TRANSLATIONS'), 'http://community.joomla.org/translations.html', 'class:help-trans', false, '_blank')
	);
	$menu->addChild(
	new JMenuNode(JText::_('MOD_MENU_HELP_RESOURCES'), 'http://resources.joomla.org', 'class:help-jrd', false, '_blank')
	);
	$menu->addChild(
	new JMenuNode(JText::_('MOD_MENU_HELP_COMMUNITY'), 'http://community.joomla.org', 'class:help-community', false, '_blank')
	);
	$menu->addChild(
	new JMenuNode(JText::_('MOD_MENU_HELP_SECURITY'), 'http://developer.joomla.org/security.html', 'class:help-security', false, '_blank')
	);
	$menu->addChild(
	new JMenuNode(JText::_('MOD_MENU_HELP_DEVELOPER'), 'http://developer.joomla.org', 'class:help-dev', false, '_blank')
	);
	$menu->addChild(
	new JMenuNode(JText::_('MOD_MENU_HELP_SHOP'), 'http://shop.joomla.org', 'class:help-shop', false, '_blank')
	);
	$menu->getParent();
	$menu->getParent();
}
