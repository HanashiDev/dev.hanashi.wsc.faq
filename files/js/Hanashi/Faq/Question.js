define(["require", "exports", "tslib", "WoltLabSuite/Core/Dom/Util"], function (require, exports, tslib_1, Util_1) {
    "use strict";
    Object.defineProperty(exports, "__esModule", { value: true });
    exports.init = init;
    exports.initGallery = initGallery;
    Util_1 = tslib_1.__importDefault(Util_1);
    function init() {
        document.querySelectorAll(".collapsibleQuestion").forEach((question) => {
            question.addEventListener("click", (event) => {
                const target = event.target;
                if (target?.nodeName === "BUTTON" || target?.nodeName === "A") {
                    return;
                }
                const currentAnswer = question.nextElementSibling;
                const isOpen = question.parentElement?.classList.contains("open");
                document.querySelectorAll(".answer").forEach((answer) => {
                    const questionContainer = answer.parentElement;
                    if (answer.isEqualNode(currentAnswer) && !isOpen) {
                        questionContainer?.classList.add("open");
                        answer.style.display = "block";
                    }
                    else {
                        questionContainer?.classList.remove("open");
                        answer.style.display = "none";
                    }
                });
            });
        });
    }
    function initGallery() {
        document.querySelectorAll(".galleryButton").forEach((button) => {
            const id = button.dataset.id;
            if (id === undefined) {
                return;
            }
            button.addEventListener("click", () => {
                document
                    .querySelectorAll(".faqGallerySection:not(#faqSection" + id + ")")
                    .forEach((faqSection) => {
                    Util_1.default.hide(faqSection);
                });
                document.querySelectorAll('.buttonActive:not([data-id="15"])').forEach((buttonActive) => {
                    buttonActive.classList.remove("buttonActive");
                });
                button.classList.add("buttonActive");
                const faqSection = document.getElementById("faqSection" + id);
                if (faqSection === null) {
                    return;
                }
                Util_1.default.show(faqSection);
                faqSection.scrollIntoView();
            });
        });
    }
});
