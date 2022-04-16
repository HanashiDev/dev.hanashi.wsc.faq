<?php

namespace wcf\system\worker;

use wcf\data\faq\QuestionList;
use wcf\data\language\item\LanguageItemList;
use wcf\system\search\SearchIndexManager;
use wcf\system\WCF;

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
        foreach ($this->objectList as $object) {
            if (substr($object->answer, 0, 23) === 'wcf.faq.question.answer') {
                if (!isset($languageCache[$object->answer])) {
                    continue;
                }

                foreach ($languageCache[$object->answer] as $languageID => $answer) {
                    $title = '';
                    if (
                        substr($object->question, 0, 25) === 'wcf.faq.question.question' &&
                        isset($languageCache[$object->question][$languageID])
                    ) {
                        $title = $languageCache[$object->question][$languageID];
                    } elseif (substr($object->question, 0, 25) !== 'wcf.faq.question.question') {
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
            } else {
                if (substr($object->question, 0, 25) === 'wcf.faq.question.question') {
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
                        '',
                        null
                    );
                }
            }
        }
    }

    protected function getLanguageCache()
    {
        $languageVariables = [];
        foreach ($this->objectList as $question) {
            if (substr($question->question, 0, 25) === 'wcf.faq.question.question') {
                $languageVariables[] = $question->question;
            }
            if (substr($question->answer, 0, 23) === 'wcf.faq.question.answer') {
                $languageVariables[] = $question->answer;
            }
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
