<?php
/**
 * @version		$Id: default.php 18899 2010-09-15 00:03:23Z eddieajau $
 * @package		Joomla.Installation
 * @copyright	Copyright (C) 2005 - 2010 Open Source Matters. All rights reserved.
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

// Include the component HTML helpers.
JHtml::addIncludePath(JPATH_COMPONENT.'/helpers/html');

// Load the JavaScript behaviors.
JHtml::_('behavior.keepalive');
JHtml::_('behavior.tooltip');
JHtml::_('behavior.formvalidation');
JHtml::_('script', 'installation/template/js/installation.js', true, false, false, false);
?>

<script type="text/javascript">
<?php if ($this->sample_installed) : ?>

	window.addEvent('domready', function() {
		var el = document.getElementById('theDefault').children[0];
		el.setAttribute('disabled','disabled');
		el.setAttribute('value','<?php echo JText::_('INSTL_SITE_SAMPLE_LOADED', true); ?>');
	});
<?php endif; ?>
</script>

<div id="stepbar">
	<div class="t">
		<div class="t">
			<div class="t"></div>
		</div>
	</div>
	<div class="m">
		<?php echo JHtml::_('installation.stepbar', 6); ?>
		<div class="box"></div>
	</div>
	<div class="b">
		<div class="b">
			<div class="b"></div>
		</div>
	</div>
</div>

<div id="right">
	<div id="rightpad">
		<div id="step">
			<div class="t">
				<div class="t">
					<div class="t"></div>
				</div>
			</div>
			<div class="m">
				<div class="far-right">
<?php if ($this->document->direction == 'ltr') : ?>
					<div class="button1-right"><div class="prev"><a href="index.php?view=filesystem" title="<?php echo JText::_('JPrevious'); ?>"><?php echo JText::_('JPrevious'); ?></a></div></div>
					<div class="button1-left"><div class="next"><a onclick="validateForm(document.getElementById('adminForm'), 'setup.saveconfig');" title="<?php echo JText::_('JNext'); ?>"><?php echo JText::_('JNext'); ?></a></div></div>
<?php elseif ($this->document->direction == 'rtl') : ?>
					<div class="button1-right"><div class="prev"><a onclick="validateForm(document.getElementById('adminForm'), 'setup.saveconfig');" title="<?php echo JText::_('JNext'); ?>"><?php echo JText::_('JNext'); ?></a></div></div>
					<div class="button1-left"><div class="next"><a href="index.php?view=filesystem" title="<?php echo JText::_('JPrevious'); ?>"><?php echo JText::_('JPrevious'); ?></a></div></div>
<?php endif; ?>
				</div>
				<span class="step"><?php echo JText::_('INSTL_SITE'); ?></span>
			</div>
			<div class="b">
				<div class="b">
					<div class="b"></div>
				</div>
			</div>
		</div>
		<div id="installer">
			<div class="t">
				<div class="t">
					<div class="t"></div>
				</div>
			</div>
			<div class="m">
				<form action="index.php" method="post" id="adminForm" class="form-validate">
				<h2><?php echo JText::_('INSTL_SITE_NAME_TITLE'); ?></h2>
				<div class="install-text">
					<?php echo JText::_('INSTL_SITE_NAME_DESC'); ?>
				</div>
				<div class="install-body">
					<div class="t">
						<div class="t">
							<div class="t"></div>
						</div>
					</div>
					<div class="m">
						<h3 class="title-smenu" title="<?php echo JText::_('INSTL_BASIC_SETTINGS'); ?>">
							<?php echo JText::_('INSTL_BASIC_SETTINGS'); ?>
						</h3>
						<div class="section-smenu">
							<table class="content2">
								<tr>
									<td class="item">
										<?php echo $this->form->getLabel('site_name'); ?>
									</td>
									<td>
										<?php echo $this->form->getInput('site_name'); ?>
									</td>
								</tr></table>
								</div>

						<h3 class="title-smenu moofx-toggler" title="<?php echo JText::_('INSTL_SITE_META_ADVANCED_SETTINGS'); ?>">
							<?php echo JText::_('INSTL_SITE_META_ADVANCED_SETTINGS'); ?>
						</h3>
						<div class="section-smenu moofx-slider">
								<table class="content2">
								<tr>
									<td title="<?php echo JText::_('INSTL_SITE_METADESC_TITLE_LABEL'); ?>">
										<?php echo $this->form->getLabel('site_metadesc'); ?>
									</td>
									<td>
										<?php echo $this->form->getInput('site_metadesc'); ?>
									</td>
								</tr>
								<tr>
									<td title="<?php echo JText::_('INSTL_SITE_METAKEYS_TITLE_LABEL'); ?>">
										<?php echo $this->form->getLabel('site_metakeys'); ?>
									</td>
									<td>
										<?php echo $this->form->getInput('site_metakeys'); ?>
									</td>
								</tr>
							</table>
							</div>
					</div>
					<div class="b">
						<div class="b">
							<div class="b"></div>
						</div>
					</div>
					<div class="clr"></div>
				</div>

				<div class="newsection"></div>

				<h2><?php echo JText::_('INSTL_SITE_CONF_TITLE'); ?></h2>
				<div class="install-text">
					<?php echo JText::_('INSTL_SITE_CONF_DESC'); ?>
				</div>
				<div class="install-body">
					<div class="t">
						<div class="t">
							<div class="t"></div>
						</div>
					</div>
					<div class="m">
						<fieldset>
							<table class="content2">
								<tr>
									<td class="item">
										<?php echo $this->form->getLabel('admin_email'); ?>
									</td>
									<td>
										<?php echo $this->form->getInput('admin_email'); ?>
									</td>
								</tr>
								<tr>
									<td class="item">
										<?php echo $this->form->getLabel('admin_user'); ?>
									</td>
									<td>
										<?php echo $this->form->getInput('admin_user'); ?>
									</td>
								</tr>
								<tr>
									<td class="item">
										<?php echo $this->form->getLabel('admin_password'); ?>
									</td>
									<td>
										<?php echo $this->form->getInput('admin_password'); ?>
									</td>
								</tr>
								<tr>
									<td class="item">
										<?php echo $this->form->getLabel('admin_password2'); ?>
									</td>
									<td>
										<?php echo $this->form->getInput('admin_password2'); ?>
									</td>
								</tr>
							</table>
						</fieldset>
					</div>
					<div class="b">
						<div class="b">
							<div class="b"></div>
						</div>
					</div>
					<div class="clr"></div>
					<input type="hidden" name="task" value="" />
					<?php echo JHtml::_('form.token'); ?>
					<?php echo $this->form->getInput('sample_installed'); ?>
				</div>
			</form>

			<div class="clr"></div>

			<form enctype="multipart/form-data" action="index.php" method="post" id="filename">
				<h2><?php echo JText::_('INSTL_SITE_LOAD_SAMPLE_TITLE'); ?></h2>
				<div class="install-text">
					<p><?php echo JText::_('INSTL_SITE_LOAD_SAMPLE_DESC1'); ?></p>
					<p><?php echo JText::_('INSTL_SITE_LOAD_SAMPLE_DESC2'); ?></p>
					<p><?php echo JText::_('INSTL_SITE_LOAD_SAMPLE_DESC3'); ?></p>
					<p><?php echo JText::_('INSTL_SITE_LOAD_SAMPLE_DESC7'); ?></p>
				</div>
				<div class="install-body">
				<div class="t">
					<div class="t">
						<div class="t"></div>
					</div>
				</div>
				<div class="m">
					<fieldset>
						<table class="content2">
							<tr>
								<td class="item"></td>
								<td></td>
							</tr>
							<tr>
								<td>
									<span id="theDefault"><input class="button" type="button" name="instDefault" value="<?php echo JText::_('INSTL_SITE_INSTALL_SAMPLE_LABEL'); ?>" onclick="Install.sampleData(this);"/></span>
								</td>
								<td>
									<em><?php echo JText::_('INSTL_SITE_INSTALL_SAMPLE_DESC'); ?></em>
								</td>
							</tr>
						</table>
					</fieldset>
					<div class="message inlineError" id="theDefaultError" style="display: none">
						<dl>
							<dt class="error"><?php echo JText::_('JERROR'); ?></dt>
							<dd id="theDefaultErrorMessage"></dd>
						</dl>
					</div>
				</div>
				<div class="b">
					<div class="b">
						<div class="b"></div>
					</div>
				</div>
				<?php echo JHtml::_('form.token'); ?>
			</div>
		</form>

		<div class="clr"></div>
		</div>
		<div class="b">
			<div class="b">
				<div class="b"></div>
			</div>
		</div>
		</div>
	</div>
</div>

<div class="clr"></div>
