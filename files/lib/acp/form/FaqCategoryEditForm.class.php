<?php
namespace wcf\acp\form;

class FaqCategoryEditForm extends AbstractCategoryEditForm {
	/**
	 * @inheritDoc
	 */
	public $activeMenuItem = 'wcf.acp.menu.link.faq.categories.list';

	/**
	 * @inheritDoc
	 */
	public $objectTypeName = 'dev.tkirch.wsc.faq.category';
}
