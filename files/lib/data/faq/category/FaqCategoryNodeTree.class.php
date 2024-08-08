<?php

namespace wcf\data\faq\category;

use Override;
use wcf\data\category\CategoryNode;
use wcf\data\category\CategoryNodeTree;

final class FaqCategoryNodeTree extends CategoryNodeTree
{
    /**
     * @inheritDoc
     */
    protected $nodeClassName = FaqCategoryNode::class;

    #[Override]
    public function isIncluded(CategoryNode $categoryNode): bool
    {
        /** @var FaqCategoryNode $categoryNode */
        return parent::isIncluded($categoryNode) && $categoryNode->isAccessible();
    }
}
