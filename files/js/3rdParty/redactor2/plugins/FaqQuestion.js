$.Redactor.prototype.FaqQuestion = function() {
	"use strict";
	
	return {
		init: function() {
            const button = this.button.add('faqQuestion', '');
			
			require(['TKirch/Redactor/Faq/Question'], ({ RedactorFaqQuestion }) => {
				new RedactorFaqQuestion(this, button[0]);
			});
		}
	};
};
