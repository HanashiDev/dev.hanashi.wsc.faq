{if $questions|count}
    {foreach from=$questions item=question}
        <li>
            <div class="containerHeadline pointer faqQuestionResultEntry" data-question-id="{$question->questionID}">
                <h3>{$question->getTitle()}</h3>
            </div>
        </li>
    {/foreach}
{else}
    <p class="info">{lang}wcf.global.noItems{/lang}</p>
{/if}
