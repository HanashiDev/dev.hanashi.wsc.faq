<?php
namespace wcf\form;
use wcf\system\WCF;

class FaqQuestionEditForm extends \wcf\acp\form\FaqQuestionEditForm {

	/**
	 * @inheritDoc
	 */
	public function assignVariables() {
		parent::assignVariables();
		WCF::getTPL()->assign(['articleIsFrontend' => true]);
	}
}