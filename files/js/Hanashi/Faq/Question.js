define(["require", "exports"], function (require, exports) {
    "use strict";
    Object.defineProperty(exports, "__esModule", { value: true });
    exports.init = void 0;
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
    exports.init = init;
});
