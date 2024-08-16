import WoltlabCoreDialogElement from "WoltLabSuite/Core/Element/woltlab-core-dialog";
import { CKEditor } from "WoltLabSuite/Core/Component/Ckeditor";
import { listenToCkeditor } from "WoltLabSuite/Core/Component/Ckeditor/Event";
import { dialogFactory } from "WoltLabSuite/Core/Component/Dialog";
import DomUtil from "WoltLabSuite/Core/Dom/Util";
import * as Language from "WoltLabSuite/Core/Language";
import { searchQuestions } from "./Api/Questions/GetSearch";
import { renderSearch } from "./Api/Questions/RenderSearch";

export class FaqBBCode {
  private dialog: WoltlabCoreDialogElement;

  constructor(selector: string) {
    const element = document.getElementById(selector);
    if (element === null) {
      return;
    }

    listenToCkeditor(element).setupConfiguration(({ configuration }) => {
      configuration.woltlabBbcode?.push({
        icon: "circle-question;false",
        name: "faq",
        label: Language.getPhrase("wcf.faq.bbcode.faqEntry"),
      });
    });

    listenToCkeditor(element).ready(({ ckeditor }) => {
      listenToCkeditor(element).bbcode(({ bbcode }) => {
        if (bbcode !== "faq") {
          return false;
        }

        void this.openDialog(ckeditor);

        return true;
      });
    });
  }

  private async openDialog(ckeditor: CKEditor) {
    const inputResponse = await renderSearch();
    if (!inputResponse.ok) {
      return;
    }

    this.dialog = dialogFactory().fromHtml(inputResponse.value.template).withoutControls();
    this.dialog.show(Language.getPhrase("wcf.faq.question.search"));

    const searchInput = document.getElementById("wcfUiFaqSearchInput");
    if (searchInput == null) {
      return;
    }
    searchInput.focus();
    searchInput.addEventListener("keyup", (event: KeyboardEvent) => {
      void this.search(event, ckeditor);
    });
    searchInput.nextElementSibling?.addEventListener("click", (event: MouseEvent) => {
      void this.search(event, ckeditor);
    });
  }

  private async search(event: Event, ckeditor: CKEditor) {
    if (event instanceof KeyboardEvent && event.key !== "Enter") {
      return;
    }
    event.preventDefault();

    const searchInput = document.getElementById("wcfUiFaqSearchInput") as HTMLInputElement | null;
    if (searchInput == null) {
      return;
    }

    const inputContainer = searchInput.parentNode as HTMLDivElement | null;
    const value = searchInput.value.trim();
    if (inputContainer != null) {
      if (value.length < 3) {
        DomUtil.innerError(inputContainer, Language.getPhrase("wcf.faq.question.search.error.tooShort"));
        return;
      } else {
        DomUtil.innerError(inputContainer, false);
      }
    }

    const inputResponse = await searchQuestions(value);
    if (!inputResponse.ok) {
      return;
    }

    const resultContainer = document.getElementById("wcfUiFaqSearchResultContainer");
    const resultList = document.getElementById("wcfUiFaqSearchResultList");
    if (resultContainer === null || resultList === null) {
      return;
    }

    resultList.innerHTML = inputResponse.value.template;
    DomUtil.show(resultContainer);

    resultList.querySelectorAll(".faqQuestionResultEntry").forEach((resultEntry: HTMLElement) => {
      resultEntry.addEventListener("click", (event: MouseEvent) => {
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

export default FaqBBCode;
