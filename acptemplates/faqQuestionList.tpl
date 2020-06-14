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
			{@SECURITY_TOKEN_INPUT_TAG}
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
	<div class="section tabularBox" id="questionTableContainer">
		<table class="table">
			<thead>
				<tr>
					<th class="columnID columnQuestionID{if $sortField == 'questionID'} active {@$sortOrder}{/if}" colspan="2"><a href="{link controller='FaqQuestionList'}pageNo={@$pageNo}&sortField=questionID&sortOrder={if $sortField == 'questionID' && $sortOrder == 'ASC'}DESC{else}ASC{/if}{@$linkParameters}{/link}">{lang}wcf.global.objectID{/lang}</a></th>
					<th class="columnText columnCategory{if $sortField == 'categoryID'} active {@$sortOrder}{/if}"><a href="{link controller='FaqQuestionList'}pageNo={@$pageNo}&sortField=categoryID&sortOrder={if $sortField == 'categoryID' && $sortOrder == 'ASC'}DESC{else}ASC{/if}{@$linkParameters}{/link}">{lang}wcf.acp.faq.category{/lang}</a></th>
					<th class="columnTitle columnQuestion">{lang}wcf.acp.faq.question.question{/lang}</th>
					<th class="columnDigits columnShowOrder{if $sortField == 'showOrder'} active {@$sortOrder}{/if}"><a href="{link controller='FaqQuestionList'}pageNo={@$pageNo}&sortField=showOrder&sortOrder={if $sortField == 'showOrder' && $sortOrder == 'ASC'}DESC{else}ASC{/if}{@$linkParameters}{/link}">{lang}wcf.global.showOrder{/lang}</a></th>

					{event name='columnHeads'}
				</tr>
			</thead>

			<tbody>
				{foreach from=$objects item=question}
					<tr class="jsQuestionRow">
						<td class="columnIcon">
							<a href="{link controller='FaqQuestionEdit' object=$question}{/link}" title="{lang}wcf.global.button.edit{/lang}" class="jsTooltip"><span class="icon icon16 fa-pencil"></span></a>
							<span class="icon icon16 fa-{if !$question->isDisabled}check-{/if}square-o jsToggleButton jsTooltip pointer" title="{lang}wcf.global.button.{if !$question->isDisabled}disable{else}enable{/if}{/lang}" data-object-id="{@$question->questionID}"></span>
							<span class="icon icon16 fa-times jsDeleteButton jsTooltip pointer" title="{lang}wcf.global.button.delete{/lang}" data-object-id="{@$question->questionID}" data-confirm-message-html="{lang __encode=true}wcf.acp.faq.question.delete.confirmMessage{/lang}"></span>

							{event name='rowButtons'}
						</td>
						<td class="columnID">{#$question->questionID}</td>
						<td class="columnText columnCategory">{$question->getCategory()->getTitle()}</td>
						<td class="columnTitle columnQuestion"><a href="{link controller='FaqQuestionEdit' object=$question}{/link}">{$question->getTitle()}</a></td>
						<td class="columnDigits columnShowOrder"><a href="{link controller='FaqQuestionEdit' object=$question}{/link}">{$question->showOrder}</a></td>

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
		new WCF.Action.Toggle('wcf\\data\\faq\\QuestionAction', '.jsQuestionRow');
		new WCF.Action.Delete('wcf\\data\\faq\\QuestionAction', '.jsQuestionRow');

		var options = { };
		{if $pages > 1}
			options.refreshPage = true;
			{if $pages == $pageNo}
				options.updatePageNumber = -1;
			{/if}
		{else}
			options.emptyMessage = '{lang}wcf.global.noItems{/lang}';
		{/if}

		new WCF.Table.EmptyTableHandler($('#questionTableContainer'), 'jsQuestionRow', options);
	});
</script>

{include file='footer'}
