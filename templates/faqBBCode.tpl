{if $question->isAccessible()}
	<blockquote class="faqBBcodeBox collapsibleBbcode jsCollapsibleBbcode{if $collapseQuestion} collapsed{/if}">
		<div class="faqBBcodeBoxIcon">
			<span class="faqBBcodeBoxFaqSymbol">
				{icon name='circle-question' size=32}
			</span>
		</div>
		
		<div class="faqBBcodeBoxTitle">
			<span class="faqBBcodeBoxTitle">
				{lang}wcf.faq.bbcode.title{/lang} <a href="{$question->getLink()}">{$question->getTitle()}</a>
			</span>
		</div>
		
		<div class="faqBBcodeBoxContent">
			{@$question->getFormattedOutput()}
		</div>
		
		{if $collapseQuestion}
			<span class="toggleButton" data-title-collapse="{lang}wcf.bbcode.button.collapse{/lang}" data-title-expand="{lang}wcf.bbcode.button.showAll{/lang}">{lang}wcf.bbcode.button.showAll{/lang}</span>
			
			{if !$__overlongBBCodeBoxSeen|isset}
				{assign var='__overlongBBCodeBoxSeen' value=true}
				<script data-relocate="true">
					require(['WoltLabSuite/Core/Bbcode/Collapsible'], function(BbcodeCollapsible) {
						BbcodeCollapsible.observe();
					});
				</script>
			{/if}
		{/if}
	</blockquote>
{else}
	<p class="error">{lang}wcf.faq.bbcode.noPermissions{/lang}</p>
{/if}
