<?php
/**
 * @package     Joomla.Site
 * @subpackage  com_contact
 *
 * @copyright   Copyright (C) 2005 - 2020 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

use Joomla\CMS\HTML\HTMLHelper;
use Joomla\CMS\Language\Text;
use Joomla\CMS\Router\Route;
use Joomla\CMS\Uri\Uri;
use Joomla\Component\Contact\Site\Helper\RouteHelper;

HTMLHelper::_('behavior.core');

$listOrder = $this->escape($this->state->get('list.ordering'));
$listDirn  = $this->escape($this->state->get('list.direction'));

?>

<div class="com-contact-featured__items">
	<?php if (empty($this->items)) : ?>
		<p class="com-contact-featured__message"> <?php echo Text::_('COM_CONTACT_NO_CONTACTS'); ?>	 </p>
	<?php else : ?>

	<form action="<?php echo htmlspecialchars(Uri::getInstance()->toString()); ?>" method="post" name="adminForm" id="adminForm">
		<fieldset class="com-contact-featured__filters filters">
		<legend class="hidelabeltxt"><?php echo Text::_('JGLOBAL_FILTER_LABEL'); ?></legend>
		<?php if ($this->params->get('show_pagination_limit')) : ?>
			<div class="display-limit">
				<?php echo Text::_('JGLOBAL_DISPLAY_NUM'); ?>&#160;
				<?php echo $this->pagination->getLimitBox(); ?>
			</div>
		<?php endif; ?>
		<input type="hidden" name="filter_order" value="<?php echo $listOrder; ?>">
			<input type="hidden" name="filter_order_Dir" value="<?php echo $listDirn; ?>">
		</fieldset>

		<table class="com-contact-featured__table table">
			<?php if ($this->params->get('show_headings')) : ?>
			<thead class="thead-default">
				<tr>
					<th class="item-num">
						<?php echo Text::_('JGLOBAL_NUM'); ?>
					</th>

					<th class="item-title">
						<?php echo HTMLHelper::_('grid.sort', 'COM_CONTACT_CONTACT_EMAIL_NAME_LABEL', 'a.name', $listDirn, $listOrder); ?>
					</th>

					<?php if ($this->params->get('show_position_headings')) : ?>
					<th class="item-position">
						<?php echo HTMLHelper::_('grid.sort', 'COM_CONTACT_POSITION', 'a.con_position', $listDirn, $listOrder); ?>
					</th>
					<?php endif; ?>

					<?php if ($this->params->get('show_email_headings')) : ?>
					<th class="item-email">
						<?php echo Text::_('JGLOBAL_EMAIL'); ?>
					</th>
					<?php endif; ?>

					<?php if ($this->params->get('show_telephone_headings')) : ?>
					<th class="item-phone">
						<?php echo Text::_('COM_CONTACT_TELEPHONE'); ?>
					</th>
					<?php endif; ?>

					<?php if ($this->params->get('show_mobile_headings')) : ?>
					<th class="item-phone">
						<?php echo Text::_('COM_CONTACT_MOBILE'); ?>
					</th>
					<?php endif; ?>

					<?php if ($this->params->get('show_fax_headings')) : ?>
					<th class="item-phone">
						<?php echo Text::_('COM_CONTACT_FAX'); ?>
					</th>
					<?php endif; ?>

					<?php if ($this->params->get('show_suburb_headings')) : ?>
					<th class="item-suburb">
						<?php echo HTMLHelper::_('grid.sort', 'COM_CONTACT_SUBURB', 'a.suburb', $listDirn, $listOrder); ?>
					</th>
					<?php endif; ?>

					<?php if ($this->params->get('show_state_headings')) : ?>
					<th class="item-state">
						<?php echo HTMLHelper::_('grid.sort', 'COM_CONTACT_STATE', 'a.state', $listDirn, $listOrder); ?>
					</th>
					<?php endif; ?>

					<?php if ($this->params->get('show_country_headings')) : ?>
					<th class="item-state">
						<?php echo HTMLHelper::_('grid.sort', 'COM_CONTACT_COUNTRY', 'a.country', $listDirn, $listOrder); ?>
					</th>
					<?php endif; ?>
				</tr>
			</thead>
			<?php endif; ?>

			<tbody>
				<?php foreach ($this->items as $i => $item) : ?>
					<tr class="<?php echo ($i % 2) ? 'odd' : 'even'; ?>" itemscope itemtype="https://schema.org/Person">
						<td class="item-num">
							<?php echo $i; ?>
						</td>

						<td class="item-title">
							<?php if ($this->items[$i]->published == 0) : ?>
								<span class="badge badge-warning"><?php echo Text::_('JUNPUBLISHED'); ?></span>
							<?php endif; ?>
							<a href="<?php echo Route::_(RouteHelper::getContactRoute($item->slug, $item->catid, $item->language)); ?>" itemprop="url">
								<span itemprop="name"><?php echo $item->name; ?></span>
							</a>
						</td>

						<?php if ($this->params->get('show_position_headings')) : ?>
							<td class="item-position" itemprop="jobTitle">
								<?php echo $item->con_position; ?>
							</td>
						<?php endif; ?>

						<?php if ($this->params->get('show_email_headings')) : ?>
							<td class="item-email" itemprop="email">
								<?php echo $item->email_to; ?>
							</td>
						<?php endif; ?>

						<?php if ($this->params->get('show_telephone_headings')) : ?>
							<td class="item-phone" itemprop="telephone">
								<?php echo $item->telephone; ?>
							</td>
						<?php endif; ?>

						<?php if ($this->params->get('show_mobile_headings')) : ?>
							<td class="item-phone" itemprop="telephone">
								<?php echo $item->mobile; ?>
							</td>
						<?php endif; ?>

						<?php if ($this->params->get('show_fax_headings')) : ?>
							<td class="item-phone" itemprop="faxNumber">
								<?php echo $item->fax; ?>
							</td>
						<?php endif; ?>

						<?php if ($this->params->get('show_suburb_headings')) : ?>
							<td class="item-suburb" itemprop="address" itemscope itemtype="https://schema.org/PostalAddress">
								<span itemprop="addressLocality"><?php echo $item->suburb; ?></span>
							</td>
						<?php endif; ?>

						<?php if ($this->params->get('show_state_headings')) : ?>
							<td class="item-state" itemprop="address" itemscope itemtype="https://schema.org/PostalAddress">
								<span itemprop="addressRegion"><?php echo $item->state; ?></span>
							</td>
						<?php endif; ?>

						<?php if ($this->params->get('show_country_headings')) : ?>
							<td class="item-state" itemprop="address" itemscope itemtype="https://schema.org/PostalAddress">
								<span itemprop="addressCountry"><?php echo $item->country; ?></span>
							</td>
						<?php endif; ?>
					</tr>
				<?php endforeach; ?>
			</tbody>
		</table>

	</form>
	<?php endif; ?>
</div>