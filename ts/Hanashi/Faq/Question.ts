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
