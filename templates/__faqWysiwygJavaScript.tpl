<script data-relocate="true">
	require(['Hanashi/Faq/BBCode'], function ({ FaqBBCode }) {
		{jsphrase name='wcf.faq.question.search'}
		{jsphrase name='wcf.faq.question.search.error.tooShort'}
		{jsphrase name='wcf.faq.bbcode.faqEntry'}

		{capture assign='faqEndpoints'}{link controller='FaqSearch' application='wcf'}{/link}{/capture}
		new FaqBBCode('{$wysiwygSelector|encodeJS}', '{$faqEndpoints|encodeJS}');
	})
</script>
