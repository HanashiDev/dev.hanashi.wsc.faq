<?php
namespace wcf\form;
use wcf\form\AbstractFormBuilderForm;
use wcf\system\WCF;
use wcf\util\HeaderUtil;

class FaqQuestionAddForm extends \wcf\acp\form\FaqQuestionAddForm {

	/**
	 * @inheritDoc
	 */
	public function assignVariables() {
		parent::assignVariables();
		WCF::getTPL()->assign(['articleIsFrontend' => true]);
	}
}
