<?php
/**
 * @version		$Id: edit.php 18871 2010-09-13 14:27:48Z chdemko $
 * @package		Joomla.Administrator
 * @subpackage	templates.hathor
 * @copyright	Copyright (C) 2005 - 2010 Open Source Matters, Inc. All rights reserved.
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 */

// no direct access
defined('_JEXEC') or die;

// Include the component HTML helpers.
JHtml::addIncludePath(JPATH_COMPONENT.'/helpers/html');
JHtml::_('behavior.tooltip');
JHtml::_('behavior.formvalidation');
$canDo		= ContactHelper::getActions();
?>
<script type="text/javascript">
	function submitbutton(task)
	{
		if (task == 'contact.cancel' || document.formvalidator.isValid(document.id('contact-form'))) {
			<?php echo $this->form->getField('misc')->save(); ?>
			Joomla.submitform(task, document.getElementByID('contact-form'));
		}
		else {
			alert('<?php echo $this->escape(JText::_('JGLOBAL_VALIDATION_FORM_FAILED'));?>');
		}
	}
</script>

<form action="<?php JRoute::_('index.php?option=com_contact'); ?>" method="post" name="adminForm" id="contact-form" class="form-validate">
	<div class="col main-section">
		<fieldset class="adminform">
			<legend><?php echo empty($this->item->id) ? JText::_('COM_CONTACT_NEW_CONTACT') : JText::sprintf('COM_CONTACT_EDIT_CONTACT', $this->item->id); ?></legend>
			<ul class="adminformlist">
			<li><?php echo $this->form->getLabel('name'); ?>
			<?php echo $this->form->getInput('name'); ?></li>

			<li><?php echo $this->form->getLabel('alias'); ?>
			<?php echo $this->form->getInput('alias'); ?></li>

			<li><?php echo $this->form->getLabel('user_id'); ?>
			<?php echo $this->form->getInput('user_id'); ?></li>

			<li><?php echo $this->form->getLabel('access'); ?>
			<?php echo $this->form->getInput('access'); ?></li>

			<?php if ($canDo->get('core.edit.state')) { ?>
				<li><?php echo $this->form->getLabel('published'); ?>
				<?php echo $this->form->getInput('published'); ?></li>
			<?php }?>
			<?php if ($canDo->get('core.edit.state')) { ?>
				<li><?php echo $this->form->getLabel('catid'); ?>
				<?php echo $this->form->getInput('catid'); ?></li>
			<?php }?>
			<li><?php echo $this->form->getLabel('ordering'); ?>
			<?php echo $this->form->getInput('ordering'); ?></li>

			<li><?php echo $this->form->getLabel('language'); ?>
			<?php echo $this->form->getInput('language'); ?></li>
			<?php if ($canDo->get('core.edit.state')) { ?>
				<li><?php echo $this->form->getLabel('featured'); ?>
				<?php echo $this->form->getInput('featured'); ?></li>
			<?php }?>

			<li><?php echo $this->form->getLabel('id'); ?>
			<?php echo $this->form->getInput('id'); ?></li>
			</ul>
			<div class="clr"> </div>
			<?php echo $this->form->getLabel('misc'); ?>
			<div class="clr"> </div>
			<?php echo $this->form->getInput('misc'); ?>
		</fieldset>
	</div>

	<div class="col options-section">
		<?php echo  JHtml::_('sliders.start', 'contact-slider'); ?>
			<?php echo JHtml::_('sliders.panel',JText::_('COM_CONTACT_CONTACT_DETAILS'), 'basic-options'); ?>
			<fieldset class="panelform">
			<legend class="element-invisible"><?php echo JText::_('COM_CONTACT_CONTACT_DETAILS'); ?></legend>
				<p><?php echo empty($this->item->id) ? JText::_('COM_CONTACT_DETAILS') : JText::sprintf('COM_CONTACT_EDIT_DETAILS', $this->item->id); ?></p>

				<ul class="adminformlist">
				<li><?php echo $this->form->getLabel('image'); ?>
				<?php echo $this->form->getInput('image'); ?></li>

				<li><?php echo $this->form->getLabel('con_position'); ?>
				<?php echo $this->form->getInput('con_position'); ?></li>

				<li><?php echo $this->form->getLabel('email_to'); ?>
				<?php echo $this->form->getInput('email_to'); ?></li>

				<li><?php echo $this->form->getLabel('address'); ?>
				<?php echo $this->form->getInput('address'); ?></li>

				<li><?php echo $this->form->getLabel('suburb'); ?>
				<?php echo $this->form->getInput('suburb'); ?></li>

				<li><?php echo $this->form->getLabel('state'); ?>
				<?php echo $this->form->getInput('state'); ?></li>

				<li><?php echo $this->form->getLabel('postcode'); ?>
				<?php echo $this->form->getInput('postcode'); ?></li>

				<li><?php echo $this->form->getLabel('country'); ?>
				<?php echo $this->form->getInput('country'); ?></li>

				<li><?php echo $this->form->getLabel('telephone'); ?>
				<?php echo $this->form->getInput('telephone'); ?></li>

				<li><?php echo $this->form->getLabel('mobile'); ?>
				<?php echo $this->form->getInput('mobile'); ?></li>

				<li><?php echo $this->form->getLabel('fax'); ?>
				<?php echo $this->form->getInput('fax'); ?></li>

				<li><?php echo $this->form->getLabel('webpage'); ?>
				<?php echo $this->form->getInput('webpage'); ?></li>

				<li><?php echo $this->form->getLabel('sortname1'); ?>
				<?php echo $this->form->getInput('sortname1'); ?></li>

				<li><?php echo $this->form->getLabel('sortname2'); ?>
				<?php echo $this->form->getInput('sortname2'); ?></li>

				<li><?php echo $this->form->getLabel('sortname3'); ?>
				<?php echo $this->form->getInput('sortname3'); ?></li>
				</ul>
			</fieldset>

			<?php echo $this->loadTemplate('params'); ?>

			<?php echo $this->loadTemplate('metadata'); ?>
		<?php echo JHtml::_('sliders.end'); ?>
		<input type="hidden" name="task" value="" />
		<?php echo JHtml::_('form.token'); ?>
	</div>
</form>