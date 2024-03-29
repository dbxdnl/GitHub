<?php
/**
 * @version		$Id: index.php 18398 2010-08-12 10:03:26Z infograf768 $
 * @package		Joomla.Administrator
 * @subpackage	templates.hathor
 * @copyright	Copyright (C) 2005 - 2010 Open Source Matters, Inc. All rights reserved.
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 * @since		1.6
 */

// no direct access
defined('_JEXEC') or die;

$app = JFactory::getApplication();
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="<?php echo  $this->language; ?>" lang="<?php echo  $this->language; ?>" dir="<?php echo  $this->direction; ?>">
<head>
<jdoc:include type="head" />

<!-- Load system style CSS -->
<link rel="stylesheet" href="templates/system/css/system.css" type="text/css" />

<!-- Load Template CSS -->
<link href="templates/<?php echo  $this->template ?>/css/template.css" rel="stylesheet" type="text/css" />
<?php if ($this->params->get('altMenu')) : ?>
	<link href="templates/<?php echo  $this->template ?>/css/menu2.css" rel="stylesheet" type="text/css" />
<?php else : ?>
	<link href="templates/<?php echo  $this->template ?>/css/menu.css" rel="stylesheet" type="text/css" />
<?php endif; ?>

<!-- Load additional CSS styles for rtl sites -->
<?php if ($this->direction == 'rtl') : ?>
	<link href="templates/<?php echo  $this->template ?>/css/template_rtl.css" rel="stylesheet" type="text/css" />
	<?php if ($this->params->get('altMenu')) : ?>
		<link href="templates/<?php echo  $this->template ?>/css/menu2_rtl.css" rel="stylesheet" type="text/css" />
	<?php else : ?>
		<link href="templates/<?php echo  $this->template ?>/css/menu_rtl.css" rel="stylesheet" type="text/css" />
	<?php endif; ?>
<?php endif; ?>

<!-- Load additional CSS styles for High Contrast colors -->
<?php if ($this->params->get('highContrast')) : ?>
	<link href="templates/<?php echo $this->template ?>/css/highcontrast.css" rel="stylesheet" type="text/css" />
	<link href="templates/<?php echo $this->template ?>/css/menu_hc.css" rel="stylesheet" type="text/css" />
<?php  endif; ?>

<!-- Load additional CSS styles for bold Text -->
<?php if ($this->params->get('boldText')) : ?>
	<link href="templates/<?php echo $this->template ?>/css/boldtext.css" rel="stylesheet" type="text/css" />
<?php  endif; ?>

<!-- Load additional CSS styles for Internet Explorer -->
<!--[if IE 7]>
	<link href="templates/<?php echo  $this->template ?>/css/ie7.css" rel="stylesheet" type="text/css" />
<![endif]-->
<!--[if lte IE 6]>
	<link href="templates/<?php echo  $this->template ?>/css/ie6.css" rel="stylesheet" type="text/css" />
<![endif]-->

<!-- Load JavaScript for menu -->
<?php if ($this->params->get('altMenu')) : ?>
	<script type="text/javascript" src="templates/<?php  echo  $this->template  ?>/js/menu2.js"></script>
<?php else : ?>
	<script type="text/javascript" src="templates/<?php  echo  $this->template  ?>/js/menu.js"></script>
<?php endif; ?>

<!-- Load Template JavaScript -->
<script type="text/javascript" src="templates/<?php  echo  $this->template  ?>/js/template.js"></script>

</head>

<body id="minwidth-body">
<div id="containerwrap">

	<!-- Header Logo & Status -->
	<div id="header">

		<!-- Site Title and Skip to Content -->
		<div class="title-ua">
			<h1 class="title"><?php echo $this->params->get('showSiteName') ? $app->getCfg('sitename'). " " . JText::_('JADMINISTRATION') : JText::_('JADMINISTRATION') ; ?></h1>
			<div id="skiplinkholder"><p><a id="skiplink" href="#skiptarget"><?php echo JText::_('TPL_HATHOR_SKIP_TO_MAIN_CONTENT'); ?></a></p></div>
		</div>

	</div><!-- end header -->

	<!-- Main Menu Navigation -->
	<div id="nav">
		<div id="module-menu">
			<h2 class="element-invisible"><?php echo JText::_('TPL_HATHOR_MAIN_MENU'); ?></h2>
			<jdoc:include type="modules" name="menu"/>
		</div>
		<div class="clr"></div>
	</div><!-- end nav -->

	<!-- Status Module -->
	<div id="module-status">
		<jdoc:include type="modules" name="status"/>
		<?php
			//Display an harcoded logout
			$task = JRequest::getCmd('task');
			if ($task == 'edit' || $task == 'editA' || JRequest::getInt('hidemainmenu')) {
				$logoutLink = '';
			} else {
				$logoutLink = JRoute::_('index.php?option=com_login&task=logout');
			}
			$hideLinks	= JRequest::getBool('hidemainmenu');
			$output = array();
			// Print the logout link.
			$output[] = '<span class="logout">' .($hideLinks ? '' : '<a href="'.$logoutLink.'">').JText::_('JLOGOUT').($hideLinks ? '' : '</a>').'</span>';
			// Reverse rendering order for rtl display.
			if ($this->direction == "rtl") :
				$output = array_reverse($output);
			endif;
			// Output the items.
			foreach ($output as $item) :
			echo $item;
			endforeach;
		?>
	</div>

	<!-- Content Area -->
	<div id="content">

		<!-- Component Title -->
		<jdoc:include type="modules" name="title" />

		<!-- System Messages -->
		<jdoc:include type="message" />

		<!-- Sub Menu Navigation -->
		<div class="subheader">
			<?php if (!JRequest::getInt('hidemainmenu')): ?>
				<h3 class="element-invisible"><?php echo JText::_('TPL_HATHOR_SUB_MENU'); ?></h3>
				<jdoc:include type="modules" name="submenu" style="xhtmlid" id="submenu-box" />
			<?php echo " " ?>
			<?php else : ?>
				<div id="no-submenu"></div>
			<?php endif; ?>
		</div>

		<!-- Toolbar Icon Buttons -->
		<div class="toolbar-box">
			<jdoc:include type="modules" name="toolbar" />
			<div class="clr"></div>
		</div>

		<!-- Beginning of Actual Content -->
		<div id="element-box">
			<p id="skiptargetholder"><a id="skiptarget" name="skiptarget" class="skip" tabindex="-1"></a></p>

			<!-- The main component -->
			<jdoc:include type="component" />

			<div class="clr"></div>
		</div><!-- end of element-box -->

		<noscript>
			<?php echo  JText::_('JGLOBAL_WARNJAVASCRIPT') ?>
		</noscript>

		<div class="clr"></div>

	</div><!-- end of content -->

	<div class="clr"></div>
</div><!-- end of containerwrap -->

<!-- Footer -->
<div id="footer">
	<p class="copyright">
		<?php $joomla= '<a href="http://www.joomla.org">Joomla!</a>';
			echo JText::sprintf('JGLOBAL_ISFREESOFTWARE', $joomla) ?>
		<span class="version"><?php echo  JText::_('JVERSION') ?> <?php echo  JVERSION; ?></span>
	</p>
</div>

</body>
</html>
