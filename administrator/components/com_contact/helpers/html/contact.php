<?php
/**
 * @version		$Id: contact.php 18467 2010-08-16 07:25:56Z eddieajau $
 * @package		Joomla.Administrator
 * @subpackage	com_contact
 * @copyright	Copyright (C) 2005 - 2010 Open Source Matters, Inc. All rights reserved.
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 */

// no direct access
defined('_JEXEC') or die;

/**
 * @package		Joomla.Administrator
 * @subpackage	com_contact
 */
abstract class JHtmlContact
{
	/**
	 * @param	int $value	The featured value
	 * @param	int $i
	 *
	 * @return	string	The anchor tag to toggle featured/unfeatured contacts.
	 * @since	1.6
	 */
	function featured($value = 0, $i)
	{
		// Array of image, task, title, action
		$states	= array(
			0	=> array('disabled.png', 'contacts.featured', 'COM_CONTACT_UNFEATURED', 'COM_CONTACT_TOGGLE_TO_FEATURE'),
			1	=> array('featured.png', 'contacts.unfeatured', 'JFEATURED', 'COM_CONTACT_TOGGLE_TO_UNFEATURE'),
		);
		$state	= JArrayHelper::getValue($states, (int) $value, $states[1]);
		$html	= '<a href="javascript:void(0);" onclick="return listItemTask(\'cb'.$i.'\',\''.$state[1].'\')" title="'.JText::_($state[3]).'">'
				. JHTML::_('image','admin/'.$state[0], JText::_($state[2]), NULL, true).'</a>';

		return $html;
	}


	/**
	 * Displays the publishing state legend for contacts.
	 *
	 * @return	void
	 * @since	1.6
	 */
	function Legend()
	{
		?>
		<table cellspacing="0" cellpadding="4" border="0" align="center">
		<tr align="center">
			<td>
			<?php echo JHTML::_('image','admin/publish_y.png', JText::_('Pending'), array('width' => 16, 'height' => 16, 'border' => 0), true); ?>
			</td>
			<td>
			<?php echo JText::_('PUBLISHED_BUT_IS'); ?> <u><?php echo JText::_('Pending'); ?></u> |
			</td>
			<td>
			<?php echo JHTML::_('image','admin/publish_g.png', JText::_('Visible'), array('width' => 16, 'height' => 16, 'border' => 0), true); ?>
			</td>
			<td>
			<?php echo JText::_('PUBLISHED_AND_IS'); ?> <u><?php echo JText::_('Current'); ?></u> |
			</td>
			<td>
			<?php echo JHTML::_('image','admin/publish_r.png', JText::_('Finished'), array('width' => 16, 'height' => 16, 'border' => 0), true); ?>
			</td>
			<td>
			<?php echo JText::_('PUBLISHED_BUT_HAS'); ?> <u><?php echo JText::_('Expired'); ?></u> |
			</td>
			<td>
			<?php echo JHTML::_('image','admin/publish_x.png', JText::_('Finished'), array('width' => 16, 'height' => 16, 'border' => 0), true); ?>
			</td>
			<td>
			<?php echo JText::_('NOT_PUBLISHED'); ?> |
			</td>
			<td>
			<?php echo JHTML::_('image','admin/disabled.png', JText::_('JARCHIVED'), array('width' => 16, 'height' => 16, 'border' => 0), true); ?>
			</td>
			<td>
			<?php echo JText::_('JARCHIVED'); ?>
			</td>
		</tr>
		<tr>
			<td colspan="10" align="center">
			<?php echo JText::_('JGLOBAL_CLICK_TO_TOGGLE_STATE'); ?>
			</td>
		</tr>
		</table>
		<?php
	}
}