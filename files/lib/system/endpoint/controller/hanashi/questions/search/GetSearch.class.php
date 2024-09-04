<?php

namespace wcf\system\endpoint\controller\hanashi\questions\search;

use Laminas\Diactoros\Response\JsonResponse;
use Override;
use PDO;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use wcf\data\faq\Question;
use wcf\data\faq\QuestionList;
use wcf\http\Helper;
use wcf\system\endpoint\GetRequest;
use wcf\system\endpoint\IController;
use wcf\system\exception\UserInputException;
use wcf\system\WCF;

#[GetRequest('/hanashi/questions/search')]
final class GetSearch implements IController
{
    #[Override]
    public function __invoke(ServerRequestInterface $request, array $variables): ResponseInterface
    {
        $parameters = Helper::mapApiParameters($request, GetSearchParameters::class);
        if (\mb_strlen($parameters->query) < 3) {
            throw new UserInputException('query', 'tooShort');
        }

        $questionIDs = $this->getQuestionsIDs($parameters->query);

        return new JsonResponse([
            'template' => WCF::getTPL()->fetch('shared_faqQuestionSearchResult', 'wcf', [
                'questions' => $this->getQuestions($questionIDs),
            ]),
        ]);
    }

    /**
     * @return list<int>
     */
    private function getQuestionsIDs(string $query): array
    {
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
            '%' . WCF::getDB()->escapeLikeValue($query) . '%',
            '%' . WCF::getDB()->escapeLikeValue($query) . '%',
            WCF::getLanguage()->languageID,
        ]);

        return $statement->fetchAll(PDO::FETCH_COLUMN, 0);
    }

    /**
     * @return list<Question>
     */
    private function getQuestions(array $questionIDs): array
    {
        if ($questionIDs === []) {
            return [];
        }

        $questionList = new QuestionList();
        $questionList->setObjectIDs($questionIDs);
        $questionList->readObjects();

        return $questionList->getObjects();
    }
}

/** @internal */
final class GetSearchParameters // phpcs:ignore
{
    public function __construct(
        /** @var non-empty-string */
        public readonly string $query,
    ) {
    }
}
