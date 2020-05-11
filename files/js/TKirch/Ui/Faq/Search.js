define(['Ajax', 'EventKey', 'Language', 'StringUtil', 'Dom/Util', 'Ui/Dialog'], function(Ajax, EventKey, Language, StringUtil, DomUtil, UiDialog) {
	"use strict";
	
	if (!COMPILER_TARGET_DEFAULT) {
		var Fake = function() {};
		Fake.prototype = {
			open: function() {},
			_search: function() {},
			_click: function() {},
			_ajaxSuccess: function() {},
			_ajaxSetup: function() {},
			_dialogSetup: function() {}
		};
		return Fake;
	}
	
	var _callbackSelect, _resultContainer, _resultList, _searchInput = null;
	
	return {
		open: function(callbackSelect) {
			_callbackSelect = callbackSelect;
			
			UiDialog.open(this);
		},
		
		_search: function (event) {
			event.preventDefault();
			
			var inputContainer = _searchInput.parentNode;
			
			var value = _searchInput.value.trim();
			if (value.length < 3) {
				elInnerError(inputContainer, Language.get('wcf.faq.question.search.error.tooShort'));
				return;
			}
			else {
				elInnerError(inputContainer, false);
			}
			
			Ajax.api(this, {
				parameters: {
					searchString: value
				}
			});
		},
		
		_click: function (event) {
			event.preventDefault();
			
			_callbackSelect(elData(event.currentTarget, 'question-id'));
			
			UiDialog.close(this);
		},
		
		_ajaxSuccess: function(data) {
			var html = '', question;
			//noinspection JSUnresolvedVariable
			for (var i = 0, length = data.returnValues.length; i < length; i++) {
				//noinspection JSUnresolvedVariable
				question = data.returnValues[i];
				
				html += '<li>'
						+ '<div class="containerHeadline pointer" data-question-id="' + question.questionID + '">'
							+ '<h3>' + StringUtil.escapeHTML(question.question) + '</h3>'
						+ '</div>'
					+ '</li>';
			}
			
			_resultList.innerHTML = html;
			
			window[html ? 'elShow' : 'elHide'](_resultContainer);
			
			if (html) {
				elBySelAll('.containerHeadline', _resultList, (function(item) {
					item.addEventListener(WCF_CLICK_EVENT, this._click.bind(this));
				}).bind(this));
			}
			else {
				elInnerError(_searchInput.parentNode, Language.get('wcf.faq.question.search.error.noResults'));
			}
		},
		
		_ajaxSetup: function () {
			return {
				data: {
					actionName: 'search',
					className: 'wcf\\data\\faq\\QuestionAction'
				}
			};
		},
		
		_dialogSetup: function() {
			return {
				id: 'wcfUiFaqSearch',
				options: {
					onSetup: (function() {
						var callbackSearch = this._search.bind(this);
						
						_searchInput = elById('wcfUiFaqSearchInput');
						_searchInput.addEventListener('keydown', function(event) {
							if (EventKey.Enter(event)) {
								callbackSearch(event);
							}
						});
						
						_searchInput.nextElementSibling.addEventListener(WCF_CLICK_EVENT, callbackSearch);
						
						_resultContainer = elById('wcfUiFaqSearchResultContainer');
						_resultList = elById('wcfUiFaqSearchResultList');
					}).bind(this),
					onShow: function() {
						_searchInput.focus();
					},
					title: Language.get('wcf.faq.question.search')
				},
				source: '<div class="section">'
					+ '<dl>'
						+ '<dt><label for="wcfUiFaqSearchInput">' + Language.get('wcf.faq.question.search.name') + '</label></dt>'
						+ '<dd>'
							+ '<div class="inputAddon">'
								+ '<input type="text" id="wcfUiFaqSearchInput" class="long">'
								+ '<a href="#" class="inputSuffix"><span class="icon icon16 fa-search"></span></a>'
							+ '</div>'
						+ '</dd>'
					+ '</dl>'
				+ '</div>'
				+ '<section id="wcfUiFaqSearchResultContainer" class="section" style="display: none;">'
					+ '<header class="sectionHeader">'
						+ '<h2 class="sectionTitle">' + Language.get('wcf.faq.question.search.results') + '</h2>'
					+ '</header>'
					+ '<ol id="wcfUiFaqSearchResultList" class="containerList"></ol>'
				+ '</section>'
			};
		}
	};
});
