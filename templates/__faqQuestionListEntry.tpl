{assign var='objectID' value=$question->questionID}

<div class="question jsQuestion">
    <div class="collapsibleQuestion">{$question->getTitle()}
        <div class="actions">
            <a href="{link controller='FaqQuestion' object=$question}{/link}" title="{lang}wcf.faq.detail{/lang}" class="jsTooltip"><span class="icon icon16 fa-search"></span></a>
            {if $__wcf->session->getPermission('admin.faq.canEditQuestion') || $__wcf->session->getPermission('admin.faq.canDeleteQuestion')}
                {if $__wcf->session->getPermission('admin.faq.canEditQuestion')}
                    <a href="{link controller='FaqQuestionEdit' object=$question}{/link}" title="{lang}wcf.global.button.edit{/lang}" class="jsTooltip"><span class="icon icon16 fa-pencil"></span></a>
                    <span class="icon icon16 fa-{if !$question->isDisabled}check-{/if}square-o jsToggleButton jsTooltip pointer" title="{lang}wcf.global.button.{if !$question->isDisabled}disable{else}enable{/if}{/lang}" data-object-id="{@$question->questionID}"></span>
                {/if}
                {if $__wcf->session->getPermission('admin.faq.canDeleteQuestion')}
                    <span class="icon icon16 fa-times jsDeleteButton jsTooltip pointer" title="{lang}wcf.global.button.delete{/lang}" data-object-id="{@$question->questionID}" data-confirm-message-html="{lang __encode=true}wcf.acp.faq.question.delete.confirmMessage{/lang}"></span>
                {/if}
            {/if}
        </div>
    </div>
    <div class="answer" id="answer-{$question->questionID}">
        <div class="htmlContent">
            {@$question->getFormattedOutput()}
        </div>

        {include file='attachments'}
    </div>
</div>
