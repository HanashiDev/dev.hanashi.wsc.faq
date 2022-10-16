define(["require", "exports", "tslib", "./../../Ui/Faq/Search"], function (require, exports, tslib_1, Search_1) {
    "use strict";
    Object.defineProperty(exports, "__esModule", { value: true });
    exports.RedactorFaqQuestion = void 0;
    Search_1 = tslib_1.__importDefault(Search_1);
    class RedactorFaqQuestion {
        constructor(editor, button) {
            this.editor = editor;
            button.addEventListener("click", (event) => this.click(event));
        }
        click(event) {
            event.preventDefault();
            const faqSearch = new Search_1.default((questionID) => this.insertBBCode(questionID));
            faqSearch.open();
        }
        insertBBCode(questionID) {
            this.editor.buffer.set();
            this.editor.insert.text(`[faq='${questionID}'][/faq]`);
        }
    }
    exports.RedactorFaqQuestion = RedactorFaqQuestion;
    exports.default = RedactorFaqQuestion;
});
