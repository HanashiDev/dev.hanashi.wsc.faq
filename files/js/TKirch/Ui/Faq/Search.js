define(["require", "exports", "tslib", "WoltLabSuite/Core/Ajax", "WoltLabSuite/Core/Language", "WoltLabSuite/Core/Ui/Dialog", "WoltLabSuite/Core/Dom/Util", "WoltLabSuite/Core/StringUtil"], function (require, exports, tslib_1, Ajax, Language, Dialog_1, Util_1, StringUtil) {
    "use strict";
    Object.defineProperty(exports, "__esModule", { value: true });
    exports.UiFaqSearch = void 0;
    Ajax = tslib_1.__importStar(Ajax);
    Language = tslib_1.__importStar(Language);
    Dialog_1 = tslib_1.__importDefault(Dialog_1);
    Util_1 = tslib_1.__importDefault(Util_1);
    StringUtil = tslib_1.__importStar(StringUtil);
    class UiFaqSearch {
        constructor(callbackSelect) {
            this.callbackSelect = callbackSelect;
        }
        open() {
            Dialog_1.default.open(this);
        }
        search(event) {
            if (event instanceof KeyboardEvent && event.key !== "Enter") {
                return;
            }
            event.preventDefault();
            const searchInput = document.getElementById("wcfUiFaqSearchInput");
            if (searchInput == null) {
                return;
            }
            const inputContainer = searchInput.parentNode;
            const value = searchInput.value.trim();
            if (inputContainer != null) {
                if (value.length < 3) {
                    Util_1.default.innerError(inputContainer, Language.get("wcf.faq.question.search.error.tooShort"));
                    return;
                }
                else {
                    Util_1.default.innerError(inputContainer, false);
                }
            }
            Ajax.api(this, {
                parameters: {
                    searchString: value,
                },
            });
        }
        click(event) {
            event.preventDefault();
            const target = event.currentTarget;
            if (target == null) {
                return;
            }
            const questionID = target.dataset.questionId;
            if (questionID == null) {
                return;
            }
            this.callbackSelect(parseInt(questionID));
            Dialog_1.default.close(this);
        }
        _ajaxSuccess(data) {
            let html = "";
            for (let i = 0, length = data.returnValues.length; i < length; i++) {
                const question = data.returnValues[i];
                html += "<li>";
                html += `<div class="containerHeadline pointer" data-question-id="${question.questionID}">`;
                html += `<h3>${StringUtil.escapeHTML(question.question)}</h3>`;
                html += "</div>";
                html += "</li>";
            }
            const resultContainer = document.getElementById("wcfUiFaqSearchResultContainer");
            const resultList = document.getElementById("wcfUiFaqSearchResultList");
            if (resultContainer == null || resultList == null) {
                return;
            }
            resultList.innerHTML = html;
            if (html) {
                resultContainer.style.removeProperty("display");
                const containerHeadlines = resultList.querySelectorAll(".containerHeadline");
                containerHeadlines.forEach((element) => {
                    element.addEventListener("click", (event) => this.click(event));
                });
            }
            else {
                resultContainer.style.setProperty("display", "none", "");
                const searchInput = document.getElementById("wcfUiFaqSearchInput");
                if (searchInput != null && searchInput.parentNode != null) {
                    Util_1.default.innerError(searchInput.parentNode, Language.get("wcf.faq.question.search.error.noResults"));
                }
            }
        }
        _ajaxSetup() {
            return {
                data: {
                    actionName: "search",
                    className: "wcf\\data\\faq\\QuestionAction",
                },
            };
        }
        _dialogSetup() {
            return {
                id: "wcfUiFaqSearch",
                options: {
                    onSetup: () => {
                        var _a;
                        const searchInput = document.getElementById("wcfUiFaqSearchInput");
                        if (searchInput == null) {
                            return;
                        }
                        searchInput.addEventListener("keyup", (event) => this.search(event));
                        (_a = searchInput.nextElementSibling) === null || _a === void 0 ? void 0 : _a.addEventListener("click", (event) => this.search(event));
                    },
                    onShow: () => {
                        const searchInput = document.getElementById("wcfUiFaqSearchInput");
                        if (searchInput == null) {
                            return;
                        }
                        searchInput.focus();
                    },
                    title: Language.get("wcf.faq.question.search"),
                },
                source: '<div class="section">' +
                    "<dl>" +
                    '<dt><label for="wcfUiFaqSearchInput">' +
                    Language.get("wcf.faq.question.search.name") +
                    "</label></dt>" +
                    "<dd>" +
                    '<div class="inputAddon">' +
                    '<input type="text" id="wcfUiFaqSearchInput" class="long">' +
                    '<a href="#" class="inputSuffix"><span class="icon icon16 fa-search"></span></a>' +
                    "</div>" +
                    "</dd>" +
                    "</dl>" +
                    "</div>" +
                    '<section id="wcfUiFaqSearchResultContainer" class="section" style="display: none;">' +
                    '<header class="sectionHeader">' +
                    '<h2 class="sectionTitle">' +
                    Language.get("wcf.faq.question.search.results") +
                    "</h2>" +
                    "</header>" +
                    '<ol id="wcfUiFaqSearchResultList" class="containerList"></ol>' +
                    "</section>",
            };
        }
    }
    exports.UiFaqSearch = UiFaqSearch;
    exports.default = UiFaqSearch;
});
