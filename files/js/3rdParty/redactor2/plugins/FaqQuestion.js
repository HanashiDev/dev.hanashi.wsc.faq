$.Redactor.prototype.FaqQuestion = function() {
	"use strict";
	
	return {
		init: function() {
            var button = this.button.add('faqQuestion', '');
			
			require(['Tkirch/Redactor/Faq/Question'], (function (RedactorFaqQuestion) {
				new RedactorFaqQuestion(this, button[0]);
			}).bind(this));
		}
	};
};
