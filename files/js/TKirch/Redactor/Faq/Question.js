define(['Tkirch/Ui/Faq/Search'], function(UiFaqSearch) {
	"use strict";
	
	if (!COMPILER_TARGET_DEFAULT) {
		var Fake = function() {};
		Fake.prototype = {
			init: function() {},
			_click: function() {},
			_insert: function() {}
		};
		return Fake;
	}
	
	function RedactorFaqQuestion(editor, button) { this.init(editor, button); }
	RedactorFaqQuestion.prototype = {
		init: function (editor, button) {
            this._editor = editor;
			
			button.addEventListener(WCF_CLICK_EVENT, this._click.bind(this));
		},
		
		_click: function (event) {
			event.preventDefault();
			
			UiFaqSearch.open(this._insert.bind(this));
		},
		
		_insert: function (questionID) {
			this._editor.buffer.set();
			
			this._editor.insert.text("[faq='" + questionID + "'][/faq]");
		}
	};
	
	return RedactorFaqQuestion;
});
