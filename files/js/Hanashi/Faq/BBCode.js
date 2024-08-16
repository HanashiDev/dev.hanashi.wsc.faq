define(["require", "exports", "tslib", "WoltLabSuite/Core/Component/Ckeditor/Event", "WoltLabSuite/Core/Component/Dialog", "WoltLabSuite/Core/Dom/Util", "WoltLabSuite/Core/Language", "./Api/Questions/GetSearch", "./Api/Questions/RenderSearch"], function (require, exports, tslib_1, Event_1, Dialog_1, Util_1, Language, GetSearch_1, RenderSearch_1) {
    "use strict";
    Object.defineProperty(exports, "__esModule", { value: true });
    exports.FaqBBCode = void 0;
    Util_1 = tslib_1.__importDefault(Util_1);
    Language = tslib_1.__importStar(Language);
    class FaqBBCode {
        dialog;
        constructor(selector) {
            const element = document.getElementById(selector);
            if (element === null) {
                return;
            }
            (0, Event_1.listenToCkeditor)(element).setupConfiguration(({ configuration }) => {
                configuration.woltlabBbcode?.push({
                    icon: "circle-question;false",
                    name: "faq",
                    label: Language.getPhrase("wcf.faq.bbcode.faqEntry"),
                });
            });
            (0, Event_1.listenToCkeditor)(element).ready(({ ckeditor }) => {
                (0, Event_1.listenToCkeditor)(element).bbcode(({ bbcode }) => {
                    if (bbcode !== "faq") {
                        return false;
                    }
                    void this.openDialog(ckeditor);
                    return true;
                });
            });
        }
        async openDialog(ckeditor) {
            const inputResponse = await (0, RenderSearch_1.renderSearch)();
            if (!inputResponse.ok) {
                return;
            }
            this.dialog = (0, Dialog_1.dialogFactory)().fromHtml(inputResponse.value.template).withoutControls();
            this.dialog.show(Language.getPhrase("wcf.faq.question.search"));
            const searchInput = document.getElementById("wcfUiFaqSearchInput");
            if (searchInput == null) {
                return;
            }
            searchInput.focus();
            searchInput.addEventListener("keyup", (event) => {
                void this.search(event, ckeditor);
            });
            searchInput.nextElementSibling?.addEventListener("click", (event) => {
                void this.search(event, ckeditor);
            });
        }
        async search(event, ckeditor) {
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
                    Util_1.default.innerError(inputContainer, Language.getPhrase("wcf.faq.question.search.error.tooShort"));
                    return;
                }
                else {
                    Util_1.default.innerError(inputContainer, false);
                }
            }
            const inputResponse = await (0, GetSearch_1.searchQuestions)(value);
            if (!inputResponse.ok) {
                return;
            }
            const resultContainer = document.getElementById("wcfUiFaqSearchResultContainer");
            const resultList = document.getElementById("wcfUiFaqSearchResultList");
            if (resultContainer === null || resultList === null) {
                return;
            }
            resultList.innerHTML = inputResponse.value.template;
            Util_1.default.show(resultContainer);
            resultList.querySelectorAll(".faqQuestionResultEntry").forEach((resultEntry) => {
                resultEntry.addEventListener("click", (event) => {
                    event.preventDefault();
                    const questionID = resultEntry.dataset.questionId;
                    if (questionID === undefined) {
                        return;
                    }
                    ckeditor.insertText(`[faq]${questionID}[/faq]`);
                    this.dialog.close();
                });
            });
        }
    }
    exports.FaqBBCode = FaqBBCode;
    exports.default = FaqBBCode;
});
