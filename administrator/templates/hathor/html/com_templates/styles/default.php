<?php
/**
 * @version		$Id: default.php 18586 2010-08-22 20:11:14Z ian $
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

$user = JFactory::getUser();
$listOrder	= $this->state->get('list.ordering');
$listDirn	= $this->state->get('list.direction');
?>
<form action="<?php echo JRoute::_('index.php?option=com_templates&view=styles'); ?>" method="post" name="adminForm" id="adminForm">
	<fieldset id="filter-bar">
	<legend class="element-invisible"><?php echo JText::_('JSEARCH_FILTER_LABEL'); ?></legend>
		<div class="filter-search">
			<label class="filter-search-lbl" for="filter_search"><?php echo JText::_('JSEARCH_FILTER_LABEL'); ?></label>
			<input type="text" name="filter_search" id="filter_search" value="<?php echo $this->state->get('filter.search'); ?>" title="<?php echo JText::_('COM_TEMPLATES_STYLES_FILTER_SEARCH_DESC'); ?>" />
			<button type="submit"><?php echo JText::_('JSEARCH_FILTER_SUBMIT'); ?></button>
			<button type="button" onclick="document.id('filter_search').value='';this.form.submit();"><?php echo JText::_('JSEARCH_FILTER_CLEAR'); ?></button>
		</div>
		<div class="filter-select">
			<label class="selectlabel" for="filter_client_id">
				<?php echo JText::_('JGLOBAL_FILTER_CLIENT'); ?>
			</label>
			<select name="filter_client_id" id="filter_client_id" class="inputbox">
				<option value="*"><?php echo JText::_('JGLOBAL_FILTER_CLIENT'); ?></option>
				<?php echo JHtml::_('select.options', TemplatesHelper::getClientOptions(), 'value', 'text', $this->state->get('filter.client_id'));?>
			</select>

			<label class="selectlabel" for="filter_template">
				<?php echo JText::_('COM_TEMPLATES_FILTER_TEMPLATE'); ?>
			</label>
			<select name="filter_template" id="filter_template" class="inputbox">
				<option value="0"><?php echo JText::_('COM_TEMPLATES_FILTER_TEMPLATE'); ?></option>
				<?php echo JHtml::_('select.options', TemplatesHelper::getTemplateOptions($this->state->get('filter.template')), 'value', 'text', $this->state->get('filter.template'));?>
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
					&#160;
				</th>
				<th>
					<?php echo JHtml::_('grid.sort', 'COM_TEMPLATES_HEADING_STYLE', 'a.title', $listDirn, $listOrder); ?>
				</th>
				<th class="width-10">
					<?php echo JHtml::_('grid.sort', 'JCLIENT', 'a.client_id', $listDirn, $listOrder); ?>
				</th>
				<th>
					<?php echo JHtml::_('grid.sort', 'COM_TEMPLATES_HEADING_TEMPLATE', 'a.template', $listDirn, $listOrder); ?>
				</th>
				<th class="width-5">
					<?php echo JHtml::_('grid.sort', 'COM_TEMPLATES_HEADING_DEFAULT', 'a.home', $listDirn, $listOrder); ?>
				</th>
				<th class="width-5">
					<?php echo JText::_('COM_TEMPLATES_HEADING_ASSIGNED'); ?>
				</th>
				<th class="nowrap id-col">
					<?php echo JHtml::_('grid.sort', 'JGRID_HEADING_ID', 'a.id', $listDirn, $listOrder); ?>
				</th>
			</tr>
		</thead>

		<tbody>
			<?php foreach ($this->items as $i => $item) :
				$canCreate	= $user->authorise('core.create',		'com_templates');
				$canEdit	= $user->authorise('core.edit',			'com_templates');
				$canChange	= $user->authorise('core.edit.state',	'com_templates');
			?>
			<tr class="row<?php echo $i % 2; ?>">
				<td class="center">
					<?php echo JHtml::_('grid.id', $i, $item->id); ?>
				</td>
				<td>
					<?php if ($canEdit) : ?>
					<a href="<?php echo JRoute::_('index.php?option=com_templates&task=style.edit&id='.(int) $item->id); ?>">
						<?php echo $this->escape($item->title);?></a>
					<?php else : ?>
						<?php echo $this->escape($item->title);?>
					<?php endif; ?>
				</td>
				<td class="center">
					<?php echo $item->client_id == 0 ? JText::_('JSITE') : JText::_('JADMINISTRATOR'); ?>
				</td>
				<td>
					<label for="cb<?php echo $i;?>">
						<?php echo $this->escape($item->template);?>
					</label>
				</td>
				<td class="center">
					<?php echo JHtml::_('jgrid.isdefault', $item->home, $i, 'styles.', $canChange && !$item->home);?>
				</td>
				<td class="center">
					<?php if ($item->assigned > 0) : ?>
							<?php echo JHTML::_('image','admin/tick.png', JText::plural('COM_TEMPLATES_ASSIGNED',$item->assigned), array('title'=>JText::plural('COM_TEMPLATES_ASSIGNED',$item->assigned)), true); ?>
					<?php else : ?>
							&#160;
					<?php endif; ?>
				</td>
				<td class="center">
					<?php echo (int) $item->id; ?>
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
