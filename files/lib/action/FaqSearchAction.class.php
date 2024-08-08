<?php

namespace wcf\action;

use Laminas\Diactoros\Response\HtmlResponse;
use LogicException;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use wcf\data\faq\QuestionList;
use wcf\http\Helper;
use wcf\system\WCF;

class FaqSearchAction implements RequestHandlerInterface
{
    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        if ($request->getMethod() === 'GET' || $request->getMethod() === 'POST') {
            $postParameters = Helper::mapQueryParameters(
                $request->getParsedBody(),
                <<<'EOT'
                    array {
                        searchString?: string
                    }
                    EOT
            );
            if (!isset($postParameters['searchString'])) {
                return new HtmlResponse(WCF::getTPL()->fetch('faqQuestionSearchDialog', 'wcf', [], true));
            }

            $sql = "
                SELECT          faq_questions.questionID
                FROM            wcf1_faq_questions faq_questions
                LEFT JOIN       wcf1_language_item language_item
                            ON	language_item.languageItem = faq_questions.question
                WHERE           faq_questions.question LIKE ?
                            OR	(
                                    language_item.languageItemValue LIKE ?
                                AND	language_item.languageID = ?
                                )
                ORDER BY		faq_questions.question
            ";
            $statement = WCF::getDB()->prepare($sql, 5);
            $statement->execute([
                '%' . $postParameters['searchString'] . '%',
                '%' . $postParameters['searchString'] . '%',
                WCF::getLanguage()->languageID,
            ]);

            $questionIDs = [];
            while ($questionID = $statement->fetchColumn()) {
                $questionIDs[] = $questionID;
            }

            $questionList = new QuestionList();
            $questionList->setObjectIDs($questionIDs);
            $questionList->readObjects();

            return new HtmlResponse(WCF::getTPL()->fetch('faqQuestionSearchResult', 'wcf', [
                'questions' => $questionList->getObjects(),
            ], true));
        } else {
            throw new LogicException('Unreachable');
        }
    }
}
