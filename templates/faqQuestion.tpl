{assign var='objectID' value=$question->questionID}
{assign var='attachmentList' value=$question->getAttachments()}

{capture assign='contentHeader'}
	<header class="contentHeader messageGroupContentHeader">
		<div class="contentHeaderTitle">
			<h1 class="contentTitle">{$question->getTitle()}</h1>
			{if $faqCategory|isset && $faqCategory !== null}
				<p class="contentHeaderDescription">{$faqCategory->getTitle()}</p>
			{/if}
		</div>

		<nav class="contentHeaderNavigation">
			<ul>
				{if $__wcf->session->getPermission('admin.faq.canEditQuestion')}
					<li><a href="{link controller='FaqQuestionEdit' object=$question}{/link}" class="button">{icon name='pencil' size=16} <span>{lang}wcf.global.button.edit{/lang}</span></a></li>
				{/if}
				<li><a href="{link controller='FaqQuestionList'}{/link}" class="button">{icon name='list' size=16} <span>{lang}wcf.faq.list{/lang}</span></a></li>
			</ul>
		</nav>
	</header>
{/capture}

{include file='header' pageTitle=$question->getTitle() contentTitle=$question->getTitle()}

<div class="section">
	<div class="htmlContent">
		{unsafe:$question->getFormattedOutput()}
	</div>

	{include file='attachments'}
</div>

{include file='footer'}
