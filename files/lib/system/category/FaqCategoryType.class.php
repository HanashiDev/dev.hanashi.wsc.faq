<?php
namespace wcf\system\category;
use wcf\system\WCF;

class FAQCategoryType extends AbstractCategoryType  {
    
    /**
	 * @inheritDoc
	 */
    protected $forceDescription = true;
    
    /**
	 * @inheritDoc
	 */
    protected $langVarPrefix = 'wcf.faq.category';
    
    /**
	 * @inheritDoc
	 */
	protected $permissionPrefix = 'admin.faq';
}