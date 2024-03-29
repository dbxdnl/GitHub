<?php
/**
 * @version		$Id: edit.php 18923 2010-09-15 16:45:21Z infograf768 $
 * @package		Joomla.Administrator
 * @subpackage	templates.hathor
 * @copyright	Copyright (C) 2005 - 2010 Open Source Matters, Inc. All rights reserved.
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 * @since		1.6
 */

// No direct access.
defined('_JEXEC') or die;

JHtml::addIncludePath(JPATH_COMPONENT.'/helpers/html');
JHtml::_('behavior.tooltip');
JHtml::_('behavior.formvalidation');
JHtml::_('behavior.combobox');
$canDo		= ModulesHelper::getActions();

$hasContent = empty($this->item->module) || $this->item->module == 'custom' || $this->item->module == 'mod_custom';
?>
<script type="text/javascript">
	function submitbutton(task)
	{
		if (task == 'module.cancel' || document.formvalidator.isValid(document.id('module-form'))) {
			<?php
			if ($hasContent) :
				echo $this->form->getField('content')->save();
			endif;
			?>
			Joomla.submitform(task, document.getElementById('module-form'));
		}
		else {
			alert('<?php echo $this->escape(JText::_('JGLOBAL_VALIDATION_FORM_FAILED'));?>');
		}
	}
</script>

<form action="<?php JRoute::_('index.php?option=com_modules'); ?>" method="post" name="adminForm" id="module-form" class="form-validate">
	<div class="col main-section">
		<fieldset class="adminform">
			<legend><?php echo JText::_('JDETAILS');?>	</legend>
			<ul class="adminformlist">

			<li><?php echo $this->form->getLabel('title'); ?>
			<?php echo $this->form->getInput('title'); ?></li>

			<li><?php echo $this->form->getLabel('position'); ?>
			<?php echo $this->form->getInput('custom_position'); ?>
			<label id="jform_custom_position-lbl" for="jform_custom_position" class="element-invisible"><?php echo JText::_('TPL_HATHOR_COM_MODULES_CUSTOM_POSITION_LABEL');?></label>
			<?php echo $this->form->getInput('position'); ?></li>

			<?php if ($canDo->get('core.edit.state')) { ?>
				<?php if ((string) $this->item->xml->name != 'Login Form'): ?>
				<li><?php echo $this->form->getLabel('published'); ?>
				<?php echo $this->form->getInput('published'); ?></li>
				<?php endif; ?>
			<?php }?>

			<li><?php echo $this->form->getLabel('access'); ?>
			<?php echo $this->form->getInput('access'); ?></li>

			<li><?php echo $this->form->getLabel('ordering'); ?>
			<?php echo $this->form->getInput('ordering'); ?></li>

			<li><?php echo $this->form->getLabel('showtitle'); ?>
			<?php echo $this->form->getInput('showtitle'); ?></li>

			<li><?php echo $this->form->getLabel('note'); ?>
			<?php echo $this->form->getInput('note'); ?></li>

			<?php if ((string) $this->item->xml->name != 'Login Form'): ?>
			<li><?php echo $this->form->getLabel('publish_up'); ?>
			<?php echo $this->form->getInput('publish_up'); ?></li>

			<li><?php echo $this->form->getLabel('publish_down'); ?>
			<?php echo $this->form->getInput('publish_down'); ?></li>
			<?php endif; ?>

			<li><?php echo $this->form->getLabel('language'); ?>
			<?php echo $this->form->getInput('language'); ?></li>

			<?php if ($this->item->id) : ?>
			<li><?php echo $this->form->getLabel('id'); ?>
			<?php echo $this->form->getInput('id'); ?></li>
			<?php endif; ?>

			<li><?php echo $this->form->getLabel('module'); ?>
			<?php echo $this->form->getInput('module'); ?>
			<span class="faux-input"><?php if ($this->item->xml) echo ($text = (string) $this->item->xml->name) ? JText::_($text) : $this->item->module;else echo JText::_(MODULES_ERR_XML);?></span></li>

			<li><?php echo $this->form->getLabel('client_id'); ?>
			<input type="text" size="35" id="jform_client_id" value="<?php echo $this->item->client_id == 0 ? JText::_('JSITE') : JText::_('JADMINISTRATOR'); ?>	" class="readonly" readonly="readonly" />
			<?php echo $this->form->getInput('client_id'); ?></li>
			</ul>

			<?php if ($this->item->xml) : ?>
				<?php if ($text = trim($this->item->xml->description)) : ?>
					<span class="faux-label">
						<?php echo JText::_('COM_MODULES_MODULE_DESCRIPTION'); ?>
					</span>
					<div class="clr"></div>
					<div class="readonly mod-desc extdescript">
						<?php echo JText::_($text); ?>
					</div>
				<?php endif; ?>
			<?php else : ?>
				<?php echo JText::_('COM_MODULES_ERR_XML'); ?>
			<?php endif; ?>
			<div class="clr"></div>
		</fieldset>

		<div class="clr"></div>

		<?php if ($hasContent) : ?>
			<fieldset class="adminform">
				<legend><?php echo JText::_('COM_MODULES_CUSTOM_OUTPUT'); ?></legend>
				<ul class="adminformlist">
					<li><?php echo $this->form->getLabel('content'); ?>
				<div class="clr"></div>
					<?php echo $this->form->getInput('content'); ?></li>
				</ul>
			</fieldset>
		<?php endif; ?>

		<?php if ($this->item->client_id == 0) :?>
		<div>
			<?php echo $this->loadTemplate('assignment'); ?>
		</div>
		<?php endif; ?>

	</div>

	<div class="col options-section">
	<?php echo JHtml::_('sliders.start','plugin-sliders-'.$this->item->id); ?>

		<?php echo $this->loadTemplate('options'); ?>

		<div class="clr"></div>

	<?php echo JHtml::_('sliders.end'); ?>
	</div>



	<input type="hidden" name="task" value="" />
	<?php echo JHtml::_('form.token'); ?>
</form>