{assign var='objectID' value=$question->questionID}
{assign var='attachmentList' value=$question->getAttachments()}

{capture assign='contentHeaderNavigation'}
	{if $__wcf->session->getPermission('admin.faq.canEditQuestion')}
		<li><a href="{link controller='FaqQuestionEdit' object=$question}{/link}" class="button"><span class="icon icon16 fa-pencil"></span> <span>{lang}wcf.global.button.edit{/lang}</span></a></li>
	{/if}
	<li><a href="{link controller='FaqQuestionList'}{/link}" class="button"><span class="icon icon16 fa-list"></span> <span>{lang}wcf.faq.list{/lang}</span></a></li>
{/capture}

{include file='header' pageTitle=$question->getTitle() contentTitle=$question->getTitle()}

<div class="section">
	<div class="htmlContent">
		{@$question->getFormattedOutput()}
	</div>

	{include file='attachments'}
</div>

{include file='footer'}
