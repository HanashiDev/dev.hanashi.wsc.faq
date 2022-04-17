{assign var='objectID' value=$question->questionID}

<div class="question jsQuestion">
    <div class="collapsibleQuestion">{$question->getTitle()}
        {if $__wcf->session->getPermission('admin.faq.canEditQuestion') || $__wcf->session->getPermission('admin.faq.canDeleteQuestion')}
            <div class="actions">
                {if $__wcf->session->getPermission('admin.faq.canEditQuestion')}
                    <a href="{link controller='FaqQuestionEdit' object=$question}{/link}" title="{lang}wcf.global.button.edit{/lang}" class="jsTooltip"><span class="icon icon16 fa-pencil"></span></a>
                {/if}
                {if $__wcf->session->getPermission('admin.faq.canDeleteQuestion')}
                    <span class="icon icon16 fa-times jsDeleteButton jsTooltip pointer" title="{lang}wcf.global.button.delete{/lang}" data-object-id="{@$question->questionID}" data-confirm-message-html="{lang __encode=true}wcf.acp.faq.question.delete.confirmMessage{/lang}"></span>
                {/if}
            </div>
        {/if}
    </div>
    <div class="answer" id="answer-{$question->questionID}">
        <div class="htmlContent">
            {@$question->getFormattedOutput()}
        </div>

        {include file='attachments'}
    </div>
</div>
