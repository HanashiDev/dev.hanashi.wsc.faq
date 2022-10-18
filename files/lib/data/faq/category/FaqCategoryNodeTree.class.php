<?php

namespace wcf\data\faq\category;

use wcf\data\category\CategoryNode;
use wcf\data\category\CategoryNodeTree;

class FaqCategoryNodeTree extends CategoryNodeTree
{
    /**
     * @inheritDoc
     */
    protected $nodeClassName = FaqCategoryNode::class;

    /**
     * @inheritDoc
     */
    public function isIncluded(CategoryNode $categoryNode)
    {
        /** @var FaqCategoryNode $categoryNode */
        return parent::isIncluded($categoryNode) && $categoryNode->isAccessible();
    }
}
