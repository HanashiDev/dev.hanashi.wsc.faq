<?php

namespace wcf\data\faq\category;

use wcf\data\category\CategoryNode;

/**
 * @method  FaqCategory getDecoratedObject()
 * @mixin   FaqCategory
 */
class FaqCategoryNode extends CategoryNode
{
    /**
     * @inheritDoc
     */
    protected static $baseClass = FaqCategory::class;
}
