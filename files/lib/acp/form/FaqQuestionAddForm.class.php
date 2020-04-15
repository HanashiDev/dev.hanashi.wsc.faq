<?php
namespace wcf\acp\form;
use wcf\form\AbstractFormBuilderForm;
use wcf\system\WCF;
use wcf\system\form\builder\container\FormContainer;
use wcf\system\form\builder\field\TextFormField;
use wcf\system\form\builder\field\MultilineTextFormField;
use wcf\data\faq\QuestionAction;

class FaqQuestionAddForm extends AbstractFormBuilderForm {

	/**
	 * @inheritDoc
	 */
	public $activeMenuItem = 'wcf.acp.menu.link.faq.questions.add';
	
	/**
	 * @inheritDoc
	 */
	public $formAction = 'create';
	
	/**
	 * @inheritDoc
	 */
	public $neededPermissions = ['admin.faq.canAddQuestion'];
	
	/**
	 * @inheritDoc
	 */
	public $objectActionClass = QuestionAction::class;
		
	/**
	 * @inheritDoc
	 */
	protected function createForm() {
		parent::createForm();
		
        $this->form->appendChildren([
			FormContainer::create('general')
				->label('wcf.acp.faq.question.general')
				->appendChildren([
					TextFormField::create('question')
						->label('wcf.acp.faq.question.question')
						->required(),
                        
                    MultilineTextFormField::create('answer')
						->label('wcf.acp.faq.question.answer')
						->required()
				])
			]);
	}
}