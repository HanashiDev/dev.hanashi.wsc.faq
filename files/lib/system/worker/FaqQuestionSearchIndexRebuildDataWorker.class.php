<?php

namespace wcf\system\worker;

use Override;
use wcf\data\faq\Question;
use wcf\data\faq\QuestionList;
use wcf\data\language\item\LanguageItemList;
use wcf\system\search\SearchIndexManager;

final class FaqQuestionSearchIndexRebuildDataWorker extends AbstractRebuildDataWorker
{
    /**
     * class name for DatabaseObjectList
     * @var string
     */
    protected $objectListClassName = QuestionList::class;

    /**
     * @inheritDoc
     */
    protected $limit = 100;

    #[Override]
    public function execute()
    {
        parent::execute();

        if (!$this->loopCount) {
            // reset search index
            SearchIndexManager::getInstance()->reset('dev.tkirch.wsc.faq.question');
        }

        if (!\count($this->objectList)) {
            return;
        }

        $languageCache = $this->getLanguageCache();
        /** @var Question $object */
        foreach ($this->objectList as $object) {
            if (\str_starts_with($object->answer, 'wcf.faq.question.answer')) {
                if (!isset($languageCache[$object->answer])) {
                    continue;
                }

                foreach ($languageCache[$object->answer] as $languageID => $answer) {
                    $title = '';
                    if (
                        isset($languageCache[$object->question][$languageID])
                        && \str_starts_with($object->question, 'wcf.faq.question.question')
                    ) {
                        $title = $languageCache[$object->question][$languageID];
                    } elseif (\str_starts_with($object->question, 'wcf.faq.question.question')) {
                        $title = $object->question;
                    }

                    SearchIndexManager::getInstance()->set(
                        'dev.tkirch.wsc.faq.question',
                        $object->questionID,
                        $answer,
                        $title,
                        \TIME_NOW,
                        0,
                        '',
                        $languageID ?: null
                    );
                }
            } elseif (\str_starts_with($object->question, 'wcf.faq.question.question')) {
                if (!isset($languageCache[$object->question])) {
                    continue;
                }

                foreach ($languageCache[$object->question] as $languageID => $question) {
                    SearchIndexManager::getInstance()->set(
                        'dev.tkirch.wsc.faq.question',
                        $object->questionID,
                        $object->answer,
                        $question,
                        \TIME_NOW,
                        0,
                        '',
                        $languageID ?: null
                    );
                }
            } else {
                SearchIndexManager::getInstance()->set(
                    'dev.tkirch.wsc.faq.question',
                    $object->questionID,
                    $object->answer,
                    $object->question,
                    \TIME_NOW,
                    0,
                    ''
                );
            }
        }
    }

    private function getLanguageCache(): array
    {
        $languageVariables = [];
        /** @var Question $question */
        foreach ($this->objectList as $question) {
            if (\str_starts_with($question->question, 'wcf.faq.question.question')) {
                $languageVariables[] = $question->question;
            }
            if (\str_starts_with($question->answer, 'wcf.faq.question.answer')) {
                $languageVariables[] = $question->answer;
            }
        }

        if (empty($languageVariables)) {
            return [];
        }

        $list = new LanguageItemList();
        $list->getConditionBuilder()->add('languageItem IN (?)', [$languageVariables]);
        $list->readObjects();

        $cache = [];
        foreach ($list as $languageItem) {
            $cache[$languageItem->languageItem][$languageItem->languageID] = $languageItem->languageItemValue;
        }

        return $cache;
    }
}
