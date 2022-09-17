<?php

namespace wcf\acp\form;

use wcf\data\faq\category\FaqCategoryNodeTree;
use wcf\data\faq\QuestionAction;
use wcf\data\faq\QuestionEditor;
use wcf\form\AbstractFormBuilderForm;
use wcf\system\exception\NamedUserException;
use wcf\system\form\builder\container\FormContainer;
use wcf\system\form\builder\container\wysiwyg\I18nWysiwygFormContainer;
use wcf\system\form\builder\field\BooleanFormField;
use wcf\system\form\builder\field\IntegerFormField;
use wcf\system\form\builder\field\SingleSelectionFormField;
use wcf\system\form\builder\field\TextFormField;
use wcf\system\WCF;

class FaqQuestionAddForm extends AbstractFormBuilderForm
{
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
    protected function createForm()
    {
        parent::createForm();

        $categoryTree = new FaqCategoryNodeTree('dev.tkirch.wsc.faq.category');
        $categoryTree->setMaxDepth(0);
        $categoryList = $categoryTree->getIterator();

        $categories = [];
        foreach ($categoryList as $category) {
            $categories[$category->categoryID] = $category;

            $childCategories = $category->getAllChildCategories();
            if (!\count($childCategories)) {
                continue;
            }

            foreach ($childCategories as $childCategory) {
                $childCategory->setTitle('&nbsp;&nbsp;' . $childCategory->getTitle());
                $categories[$childCategory->categoryID] = $childCategory;
            }
        }

        if (!\count($categories)) {
            throw new NamedUserException(
                WCF::getLanguage()->getDynamicVariable('wcf.acp.faq.question.error.noCategory')
            );
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
                ]),

            I18nWysiwygFormContainer::create('answer')
                ->label('wcf.acp.faq.question.answer')
                ->messageObjectType('dev.tkirch.wsc.faq.question')
                ->messageLanguageItemPattern('wcf.faq.question.answer\d+')
                ->attachmentData('dev.tkirch.wsc.faq.question')
                ->required(),
            FormContainer::create('position')
                ->label('wcf.category.position')
                ->appendChildren([
                    IntegerFormField::create('showOrder')
                        ->label('wcf.global.showOrder')
                        ->step(1)
                        ->minimum(1)
                        ->value(QuestionEditor::getShowOrder()),
                ]),
            FormContainer::create('settings')
                ->label('wcf.acp.faq.question.settings')
                ->appendChildren([
                    BooleanFormField::create('isDisabled')
                        ->label('wcf.acp.faq.question.isDisabled'),
                ]),
        ]);
    }
}
