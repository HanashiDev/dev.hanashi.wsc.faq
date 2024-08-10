<?php

namespace wcf\data\faq\category;

use Override;
use wcf\data\category\CategoryNode;

/**
 * @method  FaqCategory getDecoratedObject()
 * @mixin   FaqCategory
 */
final class FaqCategoryNode extends CategoryNode
{
    /**
     * @inheritDoc
     */
    protected static $baseClass = FaqCategory::class;

    protected int $questions;

    #[Override]
    public function getItems(): int
    {
        if (!isset($this->questions)) {
            $this->questions = FaqCategoryCache::getInstance()->getQuestions($this->categoryID);
        }

        return $this->questions;
    }
}
