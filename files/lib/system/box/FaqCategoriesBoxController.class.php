<?php

namespace wcf\system\box;

use Override;
use wcf\data\category\AbstractDecoratedCategory;
use wcf\data\category\CategoryNodeTree;
use wcf\data\faq\category\FaqCategoryNodeTree;
use wcf\page\FaqQuestionListPage;
use wcf\page\FaqQuestionPage;
use wcf\system\request\LinkHandler;
use wcf\system\request\RequestHandler;

final class FaqCategoriesBoxController extends AbstractCategoriesBoxController
{
    #[Override]
    protected function getNodeTree(): CategoryNodeTree
    {
        return new FaqCategoryNodeTree('dev.tkirch.wsc.faq.category');
    }

    #[Override]
    protected function getActiveCategory(): ?AbstractDecoratedCategory
    {
        $activeCategory = null;
        if (RequestHandler::getInstance()->getActiveRequest() !== null) {
            if (
                RequestHandler::getInstance()->getActiveRequest()->getRequestObject() instanceof FaqQuestionListPage
                || RequestHandler::getInstance()->getActiveRequest()->getRequestObject() instanceof FaqQuestionPage
            ) {
                if (isset(RequestHandler::getInstance()->getActiveRequest()->getRequestObject()->category)) {
                    $activeCategory = RequestHandler::getInstance()->getActiveRequest()->getRequestObject()->category;
                }
            }
        }

        return $activeCategory;
    }

    #[Override]
    protected function getResetFilterLink(): string
    {
        return LinkHandler::getInstance()->getControllerLink(FaqQuestionListPage::class);
    }

    #[Override]
    public function hasContent()
    {
        return SIMPLE_FAQ_VIEW !== 'gallery';
    }
}
