<?php
namespace wcf\acp\form;
use wcf\data\faq\category\FaqCategoryNodeTree;
use wcf\data\faq\QuestionAction;
use wcf\form\AbstractFormBuilderForm;
use wcf\system\exception\NamedUserException;
use wcf\system\form\builder\container\FormContainer;
use wcf\system\form\builder\field\TextFormField;
use wcf\system\form\builder\field\MultilineTextFormField;
use wcf\system\form\builder\field\IntegerFormField;
use wcf\system\form\builder\field\SingleSelectionFormField;
use wcf\system\WCF;

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

		$categoryTree = new FaqCategoryNodeTree('dev.tkirch.wsc.faq.category');
		$categoryList = $categoryTree->getIterator();
		
		$categories = [];
		foreach ($categoryList as $category) {
			$categories[$category->categoryID] = $category;
		}

		if (!count($categories)) {
			throw new NamedUserException(WCF::getLanguage()->getDynamicVariable('wcf.acp.faq.question.error.noCategory'));
		}

        $this->form->appendChildren([
			FormContainer::create('general')
				->label('wcf.acp.faq.question.general')
				->appendChildren([
					SingleSelectionFormField::create('categoryID')
						->label('wcf.acp.faq.category')
						->options($categories)
						->required(),
					TextFormField::create('question')
						->label('wcf.acp.faq.question.question')
						->i18n()
						->languageItemPattern('wcf.faq.question.question\d+')
						->required(),
                    MultilineTextFormField::create('answer')
						->label('wcf.acp.faq.question.answer')
						->i18n()
						->languageItemPattern('wcf.faq.question.answer\d+')
						->required(),
				]),
			FormContainer::create('position')
				->label('wcf.category.position')
				->appendChildren([
					IntegerFormField::create('showOrder')
						->label('wcf.global.showOrder')
						->step(1)
						->minimum(1)
						->value(1)
				]),
			]);
	}
}