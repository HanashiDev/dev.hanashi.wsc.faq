<script data-relocate="true">
	require(['Hanashi/Faq/BBCode'], function ({ FaqBBCode }) {
		{jsphrase name='wcf.faq.question.search'}
		{jsphrase name='wcf.faq.question.search.error.tooShort'}
		{jsphrase name='wcf.faq.bbcode.faqEntry'}

		new FaqBBCode('{unsafe:$wysiwygSelector|encodeJS}');
	})
</script>
