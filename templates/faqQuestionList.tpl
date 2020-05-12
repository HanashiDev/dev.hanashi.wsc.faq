{capture assign='pageTitle'}{lang}wcf.faq.list{/lang}{/capture}

{capture assign='__contentHeader'}
	<header class="contentHeader">
		<div class="contentHeaderTitle">
			<h1 class="contentTitle">{lang}wcf.faq.list{/lang}</h1>
		</div>

		<nav class="contentHeaderNavigation">
			<ul>
				{if $__wcf->session->getPermission('admin.faq.canAddQuestion')}
					<li><a href="{link controller='FaqQuestionAdd'}{/link}" class="button"><span class="icon icon16 fa-plus"></span> <span>{lang}wcf.acp.menu.link.faq.questions.add{/lang}</span></a></li>
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

			<div class="section faq">
				<h1>{$faq['title']}</h1>
				
				{if $faq['questions']|isset}
					{foreach from=$faq['questions'] item=question}
						{assign var='objectID' value=$question->questionID}

						<div class="question jsQuestion">
							<header>{$question->getTitle()}
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
							</header>
							<div class="answer" id="answer-{$question->questionID}">
								<div class="section htmlContent">
									{@$question->getFormattedOutput()}
								</div>

								{include file='attachments'}
							</div>
						</div>
					{/foreach}
				{/if}

				{if $faq['sub']|isset && $faq['sub']|count}
					{foreach from=$faq['sub'] item=sub}
						{if $sub['questions']|isset && $sub['questions']|count}
							{assign var='attachmentList' value=$sub['attachments']}

							<div class="sub">
								<h1>{$sub['title']}</h1>
								
								{foreach from=$sub['questions'] item=question}
									{assign var='objectID' value=$question->questionID}

									<div class="question jsQuestion">
										<header>{$question->getTitle()}
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
										</header>
										<div class="answer" id="answer-{$question->questionID}">
											<div class="section htmlContent">
												{@$question->getFormattedOutput()}
											</div>

											{include file='attachments'}
										</div>
									</div>
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
	$(document).ready(function(){
		$(".question > header").click(function(event){
			var setOpen = true;
			if($(this).parent().hasClass('open')) {
				setOpen = false
			}
			$(".answer").each(function(){
				$(this).hide(200);
				$(this).parent().removeClass('open');
			});
			if(setOpen) {
				$(this).parent().find(".answer").show(200);
				$(this).parent().addClass('open');
			}
		});;
	});
</script>

{if $__wcf->session->getPermission('admin.faq.canDeleteQuestion')}
	<script data-relocate="true">
		$(function() {
			new WCF.Action.Delete('wcf\\data\\faq\\QuestionAction', '.jsQuestion');
		});
	</script>
{/if}

{include file='footer'}