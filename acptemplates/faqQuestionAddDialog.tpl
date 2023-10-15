<div id="articleAddDialog" style="display: none">
	<div class="section">
		<dl>
			<dt>{lang}wcf.acp.article.i18n{/lang}</dt>
			<dd>
				<label><input type="radio" name="isMultilingual" value="0" checked> {lang}wcf.acp.faq.i18n.none{/lang}</label>
				<small>{lang}wcf.acp.article.i18n.none.description{/lang}</small>
				<label><input type="radio" name="isMultilingual" value="1"> {lang}wcf.acp.faq.i18n.i18n{/lang}</label>
				<small>{lang}wcf.acp.article.i18n.i18n.description{/lang}</small>
			</dd>
		</dl>
		
		<div class="formSubmit">
			<button type="button" class="button buttonPrimary">{lang}wcf.global.button.next{/lang}</button>
		</div>
	</div>
</div>
<script data-relocate="true">
	require(['Language', 'WoltLabSuite/Core/Acp/Ui/Article/Add'], function(Language, AcpUiArticleAdd) {
		Language.addObject({
			'wcf.acp.article.add': '{jslang}wcf.acp.menu.link.faq.questions.add{/jslang}'
		});
		
		AcpUiArticleAdd.init('{link controller='FaqQuestionAdd' encode=false}{literal}isMultilingual={$isMultilingual}{/literal}{/link}');
		
		{if $showFaqAddDialog}
			window.setTimeout(function() {
				AcpUiArticleAdd.openDialog();
			}, 10);
		{/if}
	});
</script>
