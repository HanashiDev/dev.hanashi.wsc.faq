<?php

namespace wcf\system\worker;

use wcf\data\faq\Question;
use wcf\data\faq\QuestionList;
use wcf\data\language\item\LanguageItemList;
use wcf\system\search\SearchIndexManager;

class FaqQuestionSearchIndexRebuildDataWorker extends AbstractRebuildDataWorker
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

    /**
     * @inheritDoc
     */
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
            if (\strpos($object->answer, 'wcf.faq.question.answer') === 0) {
                if (!isset($languageCache[$object->answer])) {
                    continue;
                }

                foreach ($languageCache[$object->answer] as $languageID => $answer) {
                    $title = '';
                    if (
                        isset($languageCache[$object->question][$languageID])
                        && \strpos($object->question, 'wcf.faq.question.question') === 0
                    ) {
                        $title = $languageCache[$object->question][$languageID];
                    } elseif (\strpos($object->question, 'wcf.faq.question.question') !== 0) {
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
            } elseif (\strpos($object->question, 'wcf.faq.question.question') === 0) {
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

    protected function getLanguageCache()
    {
        $languageVariables = [];
        /** @var Question $question */
        foreach ($this->objectList as $question) {
            if (\strpos($question->question, 'wcf.faq.question.question') === 0) {
                $languageVariables[] = $question->question;
            }
            if (\strpos($question->answer, 'wcf.faq.question.answer') === 0) {
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
