<?php

namespace wcf\acp\form;

use Override;
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

    protected string $icon = '';

    #[Override]
    protected function createForm()
    {
        parent::createForm();

        $this->form->appendChildren([
            FormContainer::create('properties')
                ->label('Eigenschaften')
                ->appendChildren([
                    IconFormField::create('faqIcon')
                        ->label('Icon')
                        ->value($this->icon),
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
                }
            )
        );

        parent::finalizeForm();
    }
}
