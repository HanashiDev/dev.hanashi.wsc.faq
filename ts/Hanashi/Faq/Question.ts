import DomUtil from "WoltLabSuite/Core/Dom/Util";

export function init() {
  document.querySelectorAll(".collapsibleQuestion").forEach((question: HTMLDivElement) => {
    question.addEventListener("click", (event: MouseEvent) => {
      const target = event.target as HTMLElement | null;
      if (target?.nodeName === "BUTTON" || target?.nodeName === "A") {
        return;
      }

      const currentAnswer = question.nextElementSibling;
      const isOpen = question.parentElement?.classList.contains("open");

      document.querySelectorAll(".answer").forEach((answer: HTMLDivElement) => {
        const questionContainer = answer.parentElement;

        if (answer.isEqualNode(currentAnswer) && !isOpen) {
          questionContainer?.classList.add("open");
          answer.style.display = "block";
        } else {
          questionContainer?.classList.remove("open");
          answer.style.display = "none";
        }
      });
    });
  });
}

export function initGallery() {
  document.querySelectorAll(".galleryButton").forEach((button: HTMLButtonElement) => {
    const id = button.dataset.id;
    if (id === undefined) {
      return;
    }

    button.addEventListener("click", () => {
      document
        .querySelectorAll(".faqGallerySection:not(#faqSection" + id + ")")
        .forEach((faqSection: HTMLDivElement) => {
          DomUtil.hide(faqSection);
        });
      document.querySelectorAll('.buttonActive:not([data-id="15"])').forEach((buttonActive: HTMLDivElement) => {
        buttonActive.classList.remove("buttonActive");
      });

      button.classList.add("buttonActive");

      const faqSection = document.getElementById("faqSection" + id) as HTMLDivElement | null;
      if (faqSection === null) {
        return;
      }

      DomUtil.show(faqSection);
      faqSection.scrollIntoView();
    });
  });
}
