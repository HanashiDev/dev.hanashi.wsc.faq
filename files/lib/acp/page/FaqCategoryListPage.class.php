<?php
namespace wcf\acp\page;

class FaqCategoryListPage extends AbstractCategoryListPage {

    /**
	 * @inheritDoc
	 */
    public $activeMenuItem = 'wcf.acp.menu.link.faq.categories.list';

    /**
	 * @inheritDoc
	 */
	public $neededPermissions = ['admin.faq.canViewCategory'];
    
    /**
	 * @inheritDoc
	 */
	public $objectTypeName = 'dev.tkirch.wsc.faq.category';
}