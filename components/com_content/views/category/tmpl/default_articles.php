<?php
/**
 * @version		$Id: default_articles.php 18936 2010-09-17 14:31:19Z infograf768 $
 * @package		Joomla.Site
 * @subpackage	com_content
 * @copyright	Copyright (C) 2005 - 2010 Open Source Matters, Inc. All rights reserved.
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 */

// no direct access
defined('_JEXEC') or die;

// Get the user object.
//$user = JFactory::getUser();
$params = &$this->item->params;
// Check if user is allowed to add/edit based on content permissions.

//var_dump($this->items);die;
JHtml::addIncludePath(JPATH_COMPONENT.'/helpers/html');
JHtml::_('behavior.tooltip');
JHtml::core();

$n = count($this->items);
$listOrder	= $this->state->get('list.ordering');
$listDirn	= $this->state->get('list.direction');
?>

<?php if (empty($this->items)) : ?>
		<?php if ($this->params->get('show_no_articles',1)) : ?>
		<p><?php echo JText::_('COM_CONTENT_NO_ARTICLES'); ?></p>
		<?php endif; ?>
<?php else : ?>
<form action="<?php echo JFilterOutput::ampReplace(JFactory::getURI()->toString()); ?>" method="post" name="adminForm" id="adminForm">
	<?php if ($this->params->get('filter_field') != 'hide') :?>
	<fieldset class="filters">
		<legend class="hidelabeltxt">
			<?php echo JText::_('JGLOBAL_FILTER_LABEL'); ?>
		</legend>

		<div class="filter-search">
			<label class="filter-search-lbl" for="filter-search"><?php echo JText::_('COM_CONTENT_'.$this->params->get('filter_field').'_FILTER_LABEL').'&#160;'; ?></label>
			<input type="text" name="filter-search" id="filter-search" value="<?php echo $this->escape($this->state->get('list.filter')); ?>" class="inputbox" onchange="document.adminForm.submit();" title="<?php echo JText::_('COM_CONTENT_FILTER_SEARCH_DESC'); ?>" />
		</div>
		<?php endif; ?>

		<?php if ($this->params->get('show_pagination_limit')) : ?>
		<div class="display-limit">
			<?php echo JText::_('JGLOBAL_DISPLAY_NUM'); ?>&#160;
			<?php echo $this->pagination->getLimitBox(); ?>
		</div>
		<?php endif; ?>

	<?php if ($this->params->get('filter_field') != 'hide') :?>
	</fieldset>
	<?php endif; ?>

	<table class="category" border="1">
		<?php if ($this->params->get('show_headings')) :?>
		<thead>
			<tr>

				<th class="list-title" id="tableOrdering">
					<?php  echo JHTML::_('grid.sort', 'JGLOBAL_TITLE', 'a.title', $listDirn, $listOrder) ; ?>
				</th>


				<?php if ($date = $this->params->get('list_show_date')) : ?>
				<th class="list-date" id="tableOrdering2">
					<?php echo JHTML::_('grid.sort', 'COM_CONTENT_'.$date.'_DATE', 'a.created', $listDirn, $listOrder); ?>
				</th>
				<?php endif; ?>

				<?php if ($this->params->get('list_show_author',1)) : ?>
				<th class="list-author" id="tableOrdering3">
					<?php echo JHTML::_('grid.sort', 'JAUTHOR', 'author', $listDirn, $listOrder); ?>
				</th>
				<?php endif; ?>

				<?php if ($this->params->get('list_show_hits',1)) : ?>
				<th class="list-hits" id="tableOrdering4">
					<?php echo JHTML::_('grid.sort', 'JGLOBAL_HITS', 'a.hits', $listDirn, $listOrder); ?>
				</th>
				<?php endif; ?>
			</tr>
		</thead>
		<?php endif; ?>

		<tbody>

			<?php foreach ($this->items as $i => $article) : ?>
			<tr class="cat-list-row<?php echo $i % 2; ?>">

				<?php if (in_array($article->access, $this->user->authorisedLevels())) : ?>

					<td class="list-title">
				
						<a href="<?php echo JRoute::_(ContentHelperRoute::getArticleRoute($article->slug, $article->catid)); ?>">
						<?php echo $this->escape($article->title); ?></a>
						<?php $canEdit = $this->user->authorise('core.edit', 'com_content.category.'.$article->id); ?>
						<?php $canCreate = $this->user->authorise('core.create', 'com_content.category.'); ?>

						<?php if ($canEdit) : ?>
							<ul class="actions">
								<li class="edit-icon">
									<?php echo JHtml::_('icon.edit',$article, $params); ?>
								</li>
							</ul>
						<?php endif; ?>
						
					</td>

					<?php if ($this->params->get('list_show_date')) : ?>
					<td class="list-date">
						<?php echo JHTML::_('date',$article->displayDate, $this->escape(
						$this->params->get('date_format', JText::_('DATE_FORMAT_LC3')))); ?>
					</td>
					<?php endif; ?>

					<?php if ($this->params->get('list_show_author',1)) : ?>
					<td class="list-author">
						<?php echo $this->params->get('link_author', 0) ? JHTML::_('link',JRoute::_('index.php?option=com_users&view=profile&id='.$article->created_by),$article->author) : $article->author; ?>
					</td>
					<?php endif; ?>

					<?php if ($this->params->get('list_show_hits',1)) : ?>
					<td class="list-hits">
						<?php echo $article->hits; ?>
					</td>
					<?php endif; ?>

				<?php else : ?>
				<td>
					<?php
						echo $this->escape($article->title).' : ';
						$menu		= JFactory::getApplication()->getMenu();
						$active		= $menu->getActive();
						$itemId		= $active->id;
						$link = JRoute::_('index.php?option=com_users&view=login&Itemid='.$itemId);
						$returnURL = JRoute::_(ContentHelperRoute::getArticleRoute($article->slug));
						$fullURL = new JURI($link);
						$fullURL->setVar('return', base64_encode($returnURL));
					?>
					<a href="<?php echo $fullURL; ?>" class="register">
						<?php echo JText::_( 'COM_CONTENT_REGISTER_TO_READ_MORE' ); ?></a>
				</td>
				<?php endif; ?>
				
			</tr>
			<?php endforeach; ?>
		</tbody>
	</table>
<?php // Code to add a link to submit an article. ?>
<?php if ($canCreate) : ?>
<span class="hasTip" title="<?php echo JText::_('COM_CONTENT_CREATE_ARTICLE'); ?>"><a href="<?php echo JRoute::_('index.php?option=com_content&task=article.add');?>">
	<img src="media/system/images/edit.png" alt="Edit"></img></a></span>
<?php  endif; ?>
	<?php if (($this->params->def('show_pagination', 2) == 1  || ($this->params->get('show_pagination') == 2)) && ($this->pagination->get('pages.total') > 1)) : ?>
	<div class="pagination">

		<?php if ($this->params->def('show_pagination_results', 1)) : ?>
		 	<p class="counter">
				<?php echo $this->pagination->getPagesCounter(); ?>
			</p>
		<?php endif; ?>

		<?php echo $this->pagination->getPagesLinks(); ?>
	</div>
	<?php endif; ?>

	<div>
		<!-- @TODO add hidden inputs -->
		<input type="hidden" name="filter_order" value="" />
		<input type="hidden" name="filter_order_Dir" value="" />
		<input type="hidden" name="limitstart" value="" />
	</div>
</form>
<?php endif; ?>