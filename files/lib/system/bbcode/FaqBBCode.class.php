<?php
namespace wcf\system\bbcode;
use wcf\data\faq\Question;
use wcf\system\WCF;

class FaqBBCode extends AbstractBBCode {
	/**
	 * @inheritDoc
	 */
	public function getParsedTag(array $openingTag, $content, array $closingTag, BBCodeParser $parser) {
		$questionID = null;
		if (isset($openingTag['attributes'][0])) {
			$questionID = intval($openingTag['attributes'][0]);
		}
		
		if ($questionID === null) return;

		$question = new Question($questionID);
		if (!$question->questionID) return;

		$collapse = false;

		$doc = new \DOMDocument();
		$doc->loadHTML($question->getFormattedOutput());
		if ($doc->getElementsByTagName('p')->length > 5 || $doc->getElementsByTagName('br')->length > 5) {
			$collapse = true;
		}

		return WCF::getTPL()->fetch('faqBBCode', 'wcf', [
			'question' => $question,
			'collapseQuestion' => $collapse
		]);
	}
}
