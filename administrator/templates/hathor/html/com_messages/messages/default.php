<?php
/**
 * @version		$Id: default.php 18730 2010-08-31 06:45:26Z infograf768 $
 * @package		Joomla.Administrator
 * @subpackage	templates.hathor
 * @copyright	Copyright (C) 2005 - 2010 Open Source Matters, Inc. All rights reserved.
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 * @since		1.6
 */

// No direct access.
defined('_JEXEC') or die;

// Include the component HTML helpers.
JHtml::addIncludePath(JPATH_COMPONENT.'/helpers/html');
JHtml::_('behavior.tooltip');

$user	= JFactory::getUser();
$listOrder	= $this->state->get('list.ordering');
$listDirn	= $this->state->get('list.direction');
?>

<form action="<?php echo JRoute::_('index.php?option=com_messages&view=messages'); ?>" method="post" name="adminForm" id="adminForm">
	<fieldset id="filter-bar">
	<legend class="element-invisible"><?php echo JText::_('JSEARCH_FILTER_LABEL'); ?></legend>
		<div class="filter-search">
			<label class="filter-search-lbl" for="filter_search"><?php echo JText::_('JSEARCH_FILTER_LABEL'); ?></label>
			<input type="text" name="filter_search" id="filter_search" value="<?php echo $this->state->get('filter.search'); ?>" title="<?php echo JText::_('COM_MESSAGES_SEARCH_IN_SUBJECT'); ?>" />
			<button type="submit"><?php echo JText::_('JSEARCH_FILTER_SUBMIT'); ?></button>
			<button type="button" onclick="document.id('filter_search').value='';this.form.submit();"><?php echo JText::_('JSEARCH_FILTER_CLEAR'); ?></button>
		</div>
		<div class="filter-select">
			<label class="selectlabel" for="filter_state">
				<?php echo JText::_('JOPTION_SELECT_PUBLISHED'); ?>
			</label>
			<select name="filter_state" id="filter_state" class="inputbox">
				<option value=""><?php echo JText::_('JOPTION_SELECT_PUBLISHED');?></option>
				<?php echo JHtml::_('select.options', MessagesHelper::getStateOptions(), 'value', 'text', $this->state->get('filter.state'));?>
			</select>

			<button type="button" id="filter-go" onclick="this.form.submit();">
				<?php echo JText::_('JSUBMIT'); ?></button>

		</div>
	</fieldset>
	<div class="clr"> </div>

	<table class="adminlist">
		<thead>
			<tr>
				<th class="checkmark-col">
					<input type="checkbox" name="checkall-toggle" value="" title="<?php echo JText::_('TPL_HATHOR_CHECKMARK_ALL'); ?>" onclick="checkAll(this)" />
				</th>
				<th class="title">
					<?php echo JHtml::_('grid.sort',  'COM_MESSAGES_HEADING_SUBJECT', 'a.subject', $listDirn, $listOrder); ?>
				</th>
				<th class="width-5">
					<?php echo JHtml::_('grid.sort', 'COM_MESSAGES_HEADING_READ', 'a.state', $listDirn, $listOrder); ?>
				</th>
				<th class="width-15">
					<?php echo JHtml::_('grid.sort', 'COM_MESSAGES_HEADING_FROM', 'a.user_id_from', $listDirn, $listOrder); ?>
				</th>
				<th class="nowrap width-20">
					<?php echo JHtml::_('grid.sort', 'JDATE', 'a.date_time', $listDirn, $listOrder); ?>
				</th>
			</tr>
		</thead>

		<tbody>
		<?php foreach ($this->items as $i => $item) :
			$canChange	= $user->authorise('core.edit.state', 'com_messages');
			?>
			<tr class="row<?php echo $i % 2; ?>">
				<td>
					<?php echo JHtml::_('grid.id', $i, $item->message_id); ?>
				</td>
				<td>
					<a href="<?php echo JRoute::_('index.php?option=com_messages&view=message&message_id='.(int) $item->message_id); ?>">
						<?php echo $this->escape($item->subject); ?></a>
				</td>
				<td class="center">
					<?php echo JHtml::_('messages.state', $item->state, $i, $canChange); ?>
				</td>
				<td>
					<?php echo $item->user_from; ?>
				</td>
				<td>
					<?php echo JHtml::_('date',$item->date_time, JText::_('DATE_FORMAT_LC2')); ?>
				</td>
			</tr>
			<?php endforeach; ?>
		</tbody>
	</table>

	<?php echo $this->pagination->getListFooter(); ?>

	<input type="hidden" name="task" value="" />
	<input type="hidden" name="boxchecked" value="0" />
	<input type="hidden" name="filter_order" value="<?php echo $listOrder; ?>" />
	<input type="hidden" name="filter_order_Dir" value="<?php echo $listDirn; ?>" />
	<?php echo JHtml::_('form.token'); ?>
</form>