{capture assign='__contentHeader'}
	<header class="contentHeader">
		<div class="contentHeaderTitle">
			<h1 class="contentTitle">{$__wcf->getActivePage()->getTitle()}</h1>
		</div>

		<nav class="contentHeaderNavigation">
			<ul>
				{if $__wcf->session->getPermission('admin.faq.canAddQuestion')}
					<li><a href="{link controller='FaqQuestionAdd'}{/link}" class="button">{icon name='plus' size=16} <span>{lang}wcf.acp.menu.link.faq.questions.add{/lang}</span></a></li>
				{/if}

				{event name='contentHeaderNavigation'}
			</ul>
		</nav>
	</header>
{/capture}

{include file='header' contentHeader=$__contentHeader}

{if $faqs|count}
	{foreach from=$faqs item=faq}
		{if ($faq['questions']|isset && $faq['questions']|count) || ($faq['sub']|isset && $faq['sub']|count)}
			{assign var='attachmentList' value=$faq['attachments']}

			<div class="section faq jsObjectActionContainer" data-object-action-class-name="wcf\data\faq\QuestionAction">
				<h2>{$faq['title']}</h2>

				{if $faq['questions']|isset}
					{foreach from=$faq['questions'] item=question}
						{include file='__faqQuestionListEntry'}
					{/foreach}
				{/if}

				{if $faq['sub']|isset && $faq['sub']|count}
					{foreach from=$faq['sub'] item=sub}
						{if $sub['questions']|isset && $sub['questions']|count}
							{assign var='attachmentList' value=$sub['attachments']}

							<div class="sub">
								<h2>{$sub['title']}</h2>

								{foreach from=$sub['questions'] item=question}
									{include file='__faqQuestionListEntry'}
								{/foreach}
							</div>
						{/if}
					{/foreach}
				{/if}
			</div>
		{/if}
	{/foreach}
{else}
	<p class="info">{lang}wcf.global.noItems{/lang}</p>
{/if}

<footer class="contentFooter">
	{hascontent}
		<nav class="contentFooterNavigation">
			<ul>
				{content}{event name='contentFooterNavigation'}{/content}
			</ul>
		</nav>
	{/hascontent}
</footer>

<script data-relocate="true">
	document.addEventListener('DOMContentLoaded', () => {
		document.querySelectorAll('.collapsibleQuestion').forEach(collapsibleQuestion => {
			collapsibleQuestion.addEventListener('click', event => {
				// do not collapse when clicking action buttons
				if (!collapsibleQuestion.isEqualNode(event.target)) {
					return;
				}

				let currentAnswer = collapsibleQuestion.nextElementSibling;
				let isOpen = collapsibleQuestion.parentElement.classList.contains('open');

				document.querySelectorAll('.answer').forEach(answer => {
					let questionContainer = answer.parentElement;

					if (answer.isEqualNode(currentAnswer) && !isOpen) {
						questionContainer.classList.add('open');
						answer.style.display = 'block';
					} else {
						questionContainer.classList.remove('open');
						answer.style.display = 'none';
					}
				});
			});
		});
	});
</script>

{include file='faqQuestionAddDialog'}

{include file='footer'}