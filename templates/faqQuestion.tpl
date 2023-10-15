{assign var='objectID' value=$question->questionID}
{assign var='attachmentList' value=$question->getAttachments()}

{capture assign='contentHeaderNavigation'}
	{if $__wcf->session->getPermission('admin.faq.canEditQuestion')}
		<li><a href="{link controller='FaqQuestionEdit' object=$question}{/link}" class="button">{icon name='pencil' size=16} <span>{lang}wcf.global.button.edit{/lang}</span></a></li>
	{/if}
	<li><a href="{link controller='FaqQuestionList'}{/link}" class="button">{icon name='list' size=16} <span>{lang}wcf.faq.list{/lang}</span></a></li>
{/capture}

{include file='header' pageTitle=$question->getTitle() contentTitle=$question->getTitle()}

<div class="section">
	<div class="htmlContent">
		{@$question->getFormattedOutput()}
	</div>

	{include file='attachments'}
</div>

{include file='footer'}
