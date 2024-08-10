<?php

namespace wcf\acp\form;

use Override;
use wcf\data\IStorableObject;
use wcf\system\form\builder\container\FormContainer;
use wcf\system\form\builder\data\processor\CustomFormDataProcessor;
use wcf\system\form\builder\field\IconFormField;
use wcf\system\form\builder\IFormDocument;

class FaqCategoryAddForm extends CategoryAddFormBuilderForm
{
    /**
     * @inheritDoc
     */
    public $activeMenuItem = 'wcf.acp.menu.link.faq.categories.add';

    /**
     * @inheritDoc
     */
    public string $objectTypeName = 'dev.tkirch.wsc.faq.category';

    #[Override]
    protected function createForm()
    {
        parent::createForm();

        $this->form->appendChildren([
            FormContainer::create('properties')
                ->label('wcf.acp.faq.properties')
                ->appendChildren([
                    IconFormField::create('faqIcon')
                        ->label('wcf.acp.faq.faqIcon'),
                ]),
        ]);
    }

    #[Override]
    protected function finalizeForm()
    {
        $this->form->getDataHandler()->addProcessor(
            new CustomFormDataProcessor(
                'icon',
                static function (IFormDocument $document, array $parameters) {
                    $parameters['additionalData']['faqIcon'] = $parameters['data']['faqIcon'];
                    unset($parameters['data']['faqIcon']);

                    return $parameters;
                },
                function (IFormDocument $document, array $data, IStorableObject $object) {
                    if (isset($this->formObject->additionalData['faqIcon'])) {
                        $data['faqIcon'] = $this->formObject->additionalData['faqIcon'];
                    }

                    return $data;
                }
            )
        );

        parent::finalizeForm();
    }
}
