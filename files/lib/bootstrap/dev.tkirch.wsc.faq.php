<?php

use wcf\acp\form\FaqCategoryAddForm;
use wcf\acp\form\FaqQuestionAddForm;
use wcf\acp\page\FaqCategoryListPage;
use wcf\acp\page\FaqQuestionListPage;
use wcf\event\acp\menu\item\ItemCollecting;
use wcf\event\endpoint\ControllerCollecting;
use wcf\event\worker\RebuildWorkerCollecting;
use wcf\system\endpoint\controller\faq\questions\search\GetSearch;
use wcf\system\endpoint\controller\faq\questions\search\RenderSearch;
use wcf\system\event\EventHandler;
use wcf\system\menu\acp\AcpMenuItem;
use wcf\system\request\LinkHandler;
use wcf\system\style\FontAwesomeIcon;
use wcf\system\WCF;
use wcf\system\worker\FaqQuestionSearchIndexRebuildDataWorker;

return static function (): void {
    EventHandler::getInstance()->register(ItemCollecting::class, static function (ItemCollecting $event) {
        $event->register(
            new AcpMenuItem(
                'wcf.acp.menu.link.faq',
                '',
                'wcf.acp.menu.link.content'
            )
        );

        if (WCF::getSession()->getPermission('admin.faq.canViewCategory')) {
            $event->register(
                new AcpMenuItem(
                    'wcf.acp.menu.link.faq.categories.list',
                    '',
                    'wcf.acp.menu.link.faq',
                    LinkHandler::getInstance()->getControllerLink(FaqCategoryListPage::class)
                )
            );

            if (WCF::getSession()->getPermission('admin.faq.canAddCategory')) {
                $event->register(
                    new AcpMenuItem(
                        'wcf.acp.menu.link.faq.categories.add',
                        '',
                        'wcf.acp.menu.link.faq.categories.list',
                        LinkHandler::getInstance()->getControllerLink(FaqCategoryAddForm::class),
                        FontAwesomeIcon::fromString('plus;false')
                    )
                );
            }
        }

        if (WCF::getSession()->getPermission('admin.faq.canViewQuestion')) {
            $event->register(
                new AcpMenuItem(
                    'wcf.acp.menu.link.faq.questions.list',
                    '',
                    'wcf.acp.menu.link.faq',
                    LinkHandler::getInstance()->getControllerLink(FaqQuestionListPage::class)
                )
            );

            if (WCF::getSession()->getPermission('admin.faq.canAddQuestion')) {
                $event->register(
                    new AcpMenuItem(
                        'wcf.acp.menu.link.faq.questions.add',
                        '',
                        'wcf.acp.menu.link.faq.questions.list',
                        LinkHandler::getInstance()->getControllerLink(FaqQuestionAddForm::class),
                        FontAwesomeIcon::fromString('plus;false')
                    )
                );
            }
        }
    });

    EventHandler::getInstance()->register(
        RebuildWorkerCollecting::class,
        static function (RebuildWorkerCollecting $event) {
            $event->register(FaqQuestionSearchIndexRebuildDataWorker::class, 200);
        }
    );

    EventHandler::getInstance()->register(
        ControllerCollecting::class,
        static function (ControllerCollecting $event) {
            $event->register(new RenderSearch());
            $event->register(new GetSearch());
        }
    );
};
