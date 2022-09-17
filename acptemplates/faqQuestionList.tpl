{include file='header' pageTitle='wcf.acp.menu.link.faq.questions.list'}

<script data-relocate="true">
	require(['WoltLabSuite/Core/Ui/Sortable/List'], function (UiSortableList) {
		new UiSortableList({
			containerId: 'questionList',
			className: 'wcf\\data\\faq\\QuestionAction',
			offset: {@$startIndex}
		});
	});
</script>

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

<form method="post" action="{link controller='FaqQuestionList'}{/link}">
	<section class="section">
		<h2 class="sectionTitle">{lang}wcf.global.filter{/lang}</h2>
		
		<div class="row rowColGap formGrid">
			<dl class="col-xs-12 col-md-4">
				<dt></dt>
				<dd>
					<select name="categoryID" id="categoryID">
						<option value="0">{lang}wcf.acp.faq.category{/lang}</option>
						
						{foreach from=$categoryNodeList item=category}
							<option value="{@$category->categoryID}"{if $category->categoryID == $categoryID} selected{/if}>{if $category->getDepth() > 1}{@"&nbsp;&nbsp;&nbsp;&nbsp;"|str_repeat:($category->getDepth() - 1)}{/if}{$category->getTitle()}</option>
						{/foreach}
					</select>
				</dd>
			</dl>
			
			<dl class="col-xs-12 col-md-4">
				<dt></dt>
				<dd>
					<input type="text" id="question" name="question" value="{$question}" placeholder="{lang}wcf.faq.question.question.title{/lang}" class="long">
				</dd>
			</dl>

			<dl class="col-xs-12 col-md-4">
				<dt></dt>
				<dd>
					<input type="text" id="answer" name="answer" value="{$answer}" placeholder="{lang}wcf.faq.question.answer.title{/lang}" class="long">
				</dd>
			</dl>
			
			{event name='filterFields'}
		</div>
		
		<div class="formSubmit">
			<input type="submit" value="{lang}wcf.global.button.submit{/lang}" accesskey="s">
			{csrfToken}
		</div>
	</section>
</form>

{hascontent}
	<div class="paginationTop">
		{content}
			{assign var='linkParameters' value=''}
			{if $categoryID}{capture append=linkParameters}&categoryID={@$categoryID}{/capture}{/if}
			{if $question}{capture append=linkParameters}&question={@$question|rawurlencode}{/capture}{/if}
			{if $answer}{capture append=linkParameters}&answer={@$answer|rawurlencode}{/capture}{/if}
	
			{pages print=true assign=pagesLinks controller="FaqQuestionList" link="pageNo=%d&sortField=$sortField&sortOrder=$sortOrder$linkParameters"}
		{/content}
	</div>
{/hascontent}

{if $objects|count}
	<div class="section sortableListContainer" id="questionList">
		<ol class="sortableList jsObjectActionContainer jsReloadPageWhenEmpty" data-object-id="0" start="{@($pageNo - 1) * $itemsPerPage + 1}" data-object-action-class-name="wcf\data\faq\QuestionAction">
			{foreach from=$objects item=question}
				<li class="sortableNode sortableNoNesting jsQuestion jsObjectActionObject" data-object-id="{@$question->questionID}">
					<span class="sortableNodeLabel">
						({$question->getCategory()->getTitle()})&nbsp;
						<a href="{link controller='FaqQuestionEdit' object=$question}{/link}">{$question->getTitle()}</a>

						<span class="statusDisplay sortableButtonContainer">
							<span class="icon icon16 fa-arrows sortableNodeHandle"></span>
							{objectAction action="toggle" isDisabled=$question->isDisabled}
							<a href="{link controller='FaqQuestionEdit' object=$question}{/link}" title="{lang}wcf.global.button.edit{/lang}" class="jsTooltip"><span class="icon icon16 fa-pencil"></span></a>
							{objectAction action="delete" objectTitle=$question->getTitle()}

							{event name='itemButtons'}
						</span>
					</span>
				</li>
			{/foreach}
		</ol>
	</div>

	<div class="formSubmit">
		<button class="button buttonPrimary" data-type="submit">{lang}wcf.global.button.saveSorting{/lang}</button>
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

{include file='footer'}
