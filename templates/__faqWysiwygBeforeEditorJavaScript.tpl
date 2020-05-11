<script data-relocate="true">
	require(['Language'], function (Language) {
		Language.addObject({
			'wcf.faq.question.search': '{lang}wcf.faq.question.search{/lang}',
			'wcf.faq.question.search.error.tooShort': '{lang}wcf.faq.question.search.error.tooShort{/lang}',
			'wcf.faq.question.search.error.noResults': '{lang}wcf.faq.question.search.error.noResults{/lang}',
			'wcf.faq.question.search.name': '{lang}wcf.faq.question.search.name{/lang}',
			'wcf.faq.question.search.results': '{lang}wcf.faq.question.search.results{/lang}'
		});
	})
</script>

{capture append='__redactorJavaScript'}
	, '{@$__wcf->getPath()}js/3rdParty/redactor2/plugins/FaqQuestion.js?v={@LAST_UPDATE_TIME}'
{/capture}
{capture append='__redactorConfig'}
    buttonOptions.faqQuestion = { icon: 'fa-question-circle', title: '{lang}wcf.editor.button.faq{/lang}' };

    buttons.push('faqQuestion');

    config.plugins.push('FaqQuestion');
{/capture}
