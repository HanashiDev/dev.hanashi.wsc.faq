{include file='header' pageTitle='wcf.acp.menu.link.faq.questions.list'}

<header class="contentHeader">
	<div class="contentHeaderTitle">
		<h1 class="contentTitle">{lang}wcf.acp.menu.link.faq.questions.list{/lang}</h1>
	</div>

	<nav class="contentHeaderNavigation">
		<ul>
			<li><a href="{link controller='FaqQuestionAdd'}{/link}" class="button"><span class="icon icon16 fa-plus"></span> <span>{lang}wcf.acp.menu.link.faq.questions.add{/lang}</span></a></li>

			{event name='contentHeaderNavigation'}
		</ul>
	</nav>
</header>

{hascontent}
	<div class="paginationTop">
		{content}{pages print=true assign=pagesLinks controller="FaqQuestionList" link="pageNo=%d&sortField=$sortField&sortOrder=$sortOrder"}{/content}
	</div>
{/hascontent}

{if $objects|count}
    <div class="section tabularBox" id="questionTableContainer">
    	<table class="table">
    		<thead>
    			<tr>
    				<th class="columnID columnQuestionID{if $sortField == 'questionID'} active {@$sortOrder}{/if}" colspan="2"><a href="{link controller='FaqQuestionList'}pageNo={@$pageNo}&sortField=questionID&sortOrder={if $sortField == 'questionID' && $sortOrder == 'ASC'}DESC{else}ASC{/if}{/link}">{lang}wcf.global.objectID{/lang}</a></th>
                    <th class="columnTitle columnQuestion{if $sortField == 'question'} active {@$sortOrder}{/if}"><a href="{link controller='FaqQuestionList'}pageNo={@$pageNo}&sortField=question&sortOrder={if $sortField == 'question' && $sortOrder == 'ASC'}DESC{else}ASC{/if}{/link}">{lang}wcf.acp.faq.question.question{/lang}</a></th>
                    
    				{event name='columnHeads'}
    			</tr>
    		</thead>

    		<tbody>
    			{foreach from=$objects item=question}
    				<tr class="jsPersonRow">
    					<td class="columnIcon">
    						<a href="{link controller='FaqQuestionEdit' object=$question}{/link}" title="{lang}wcf.global.button.edit{/lang}" class="jsTooltip"><span class="icon icon16 fa-pencil"></span></a>
    						<span class="icon icon16 fa-times jsDeleteButton jsTooltip pointer" title="{lang}wcf.global.button.delete{/lang}" data-object-id="{@$question->questionID}" data-confirm-message-html="{lang __encode=true}wcf.acp.faq.question.delete.confirmMessage{/lang}"></span>

    						{event name='rowButtons'}
    					</td>
    					<td class="columnID">{#$question->questionID}</td>
    					<td class="columnTitle columnquestion"><a href="{link controller='FaqQuestionEdit' object=$question}{/link}">{$question->getTitle()}</a></td>
                        
						{event name='columns'}
    				</tr>
    			{/foreach}
    		</tbody>
    	</table>
    </div>

    <footer class="contentFooter">
    	{hascontent}
    		<div class="paginationBottom">
    			{content}{@$pagesLinks}{/content}
    		</div>
    	{/hascontent}

    	<nav class="contentFooterNavigation">
    		<ul>
    			<li><a href="{link controller='FaqQuestionAdd'}{/link}" class="button"><span class="icon icon16 fa-plus"></span> <span>{lang}wcf.acp.menu.link.faq.questions.add{/lang}</span></a></li>

    			{event name='contentFooterNavigation'}
    		</ul>
    	</nav>
    </footer>
{else}
    <p class="info">{lang}wcf.global.noItems{/lang}</p>
{/if}

<script data-relocate="true">
	$(function() {
		new WCF.Action.Delete('wcf\\data\\faq\\QuestionAction', '.jsPersonRow');

		var options = { };
		{if $pages > 1}
			options.refreshPage = true;
			{if $pages == $pageNo}
				options.updatePageNumber = -1;
			{/if}
		{else}
			options.emptyMessage = '{lang}wcf.global.noItems{/lang}';
		{/if}

		new WCF.Table.EmptyTableHandler($('#questionTableContainer'), 'jsPersonRow', options);
	});
</script>

{include file='footer'}
