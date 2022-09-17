<script data-relocate="true">
	require(['Language'], function (Language) {
		Language.addObject({
			'wcf.faq.question.search': '{jslang}wcf.faq.question.search{/jslang}',
			'wcf.faq.question.search.error.tooShort': '{jslang}wcf.faq.question.search.error.tooShort{/jslang}',
			'wcf.faq.question.search.error.noResults': '{jslang}wcf.faq.question.search.error.noResults{/jslang}',
			'wcf.faq.question.search.name': '{jslang}wcf.faq.question.search.name{/jslang}',
			'wcf.faq.question.search.results': '{jslang}wcf.faq.question.search.results{/jslang}'
		});
	})
</script>

{capture append='__redactorJavaScript'}
	, '{@$__wcf->getPath()}js/3rdParty/redactor2/plugins/FaqQuestion.js?v={@LAST_UPDATE_TIME}'
{/capture}
{capture append='__redactorConfig'}
    buttonOptions.faqQuestion = { icon: 'fa-question-circle', title: '{jslang}wcf.editor.button.faq{/jslang}' };

    buttons.push('faqQuestion');

    config.plugins.push('FaqQuestion');
{/capture}
