<script data-relocate="true">
	require(['Language', 'Hanashi/Faq/BBCode'], function (Language, { FaqBBCode }) {
		Language.addObject({
			'wcf.faq.question.search': '{jslang}wcf.faq.question.search{/jslang}',
			'wcf.faq.question.search.error.tooShort': '{jslang}wcf.faq.question.search.error.tooShort{/jslang}',
			'wcf.faq.bbcode.faqEntry': '{jslang}wcf.faq.bbcode.faqEntry{/jslang}',
		});

		{capture assign='faqEndpoints'}{link controller='FaqSearch' application='wcf'}{/link}{/capture}
		new FaqBBCode('{$wysiwygSelector|encodeJS}', '{$faqEndpoints|encodeJS}');
	})
</script>
