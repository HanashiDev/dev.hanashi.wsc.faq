{assign var='objectID' value=$question->questionID}

<div class="question jsQuestion jsObjectActionObject" data-object-id="{$question->questionID}">
    <div class="collapsibleQuestion">
        <span class="collapseIcon">{icon name='angle-right' size=16}</span>
        {$question->getTitle()}
        <div class="actions">
            {if $__wcf->session->getPermission('admin.faq.canEditQuestion') || $__wcf->session->getPermission('admin.faq.canDeleteQuestion')}
                {if $__wcf->session->getPermission('admin.faq.canEditQuestion')}
                    {objectAction action="toggle" isDisabled=$question->isDisabled}
                    <a href="{link controller='FaqQuestionEdit' object=$question}{/link}" title="{lang}wcf.global.button.edit{/lang}" class="jsTooltip">{icon name='pencil' size=16}</a>
                {/if}
                {if $__wcf->session->getPermission('admin.faq.canDeleteQuestion')}
                    {objectAction action="delete" objectTitle=$question->getTitle()}
                {/if}
            {/if}
            <a href="{link controller='FaqQuestion' object=$question}{/link}" title="{lang}wcf.faq.detail{/lang}" class="jsTooltip">{icon name='magnifying-glass' size=16}</a>
        </div>
    </div>
    <div class="answer" id="answer-{$question->questionID}">
        <div class="htmlContent">
            {unsafe:$question->getFormattedOutput()}
        </div>

        {include file='attachments'}
    </div>
</div>
