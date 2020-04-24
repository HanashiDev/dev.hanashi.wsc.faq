{capture assign='pageTitle'}{lang}wcf.faq.list{/lang}{/capture}

{capture assign='contentTitle'}{lang}wcf.faq.list{/lang}{/capture}

{include file='header'}

{if $faqs|count}
	{foreach from=$faqs item=faq}
		<div class="section faq">
			<h1>{$faq['title']}</h1>
			
			{foreach from=$faq['questions'] item=question}
				<div class="question">
					<header>{$question->getTitle()}</header>
					<div class="answer" id="answer-{$question->questionID}">
						{$question->getAnswer()}
					</div>
				</div>
			{/foreach}
		</div>
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
		});
	});
</script>

{include file='footer'}