import WoltlabCoreDialogElement from "WoltLabSuite/Core/Element/woltlab-core-dialog";
import { prepareRequest } from "WoltLabSuite/Core/Ajax/Backend";
import { CKEditor } from "WoltLabSuite/Core/Component/Ckeditor";
import { listenToCkeditor } from "WoltLabSuite/Core/Component/Ckeditor/Event";
import { dialogFactory } from "WoltLabSuite/Core/Component/Dialog";
import DomUtil from "WoltLabSuite/Core/Dom/Util";
import * as Language from "WoltLabSuite/Core/Language";

export class FaqBBCode {
  private endpoint: string;

  private dialog: WoltlabCoreDialogElement;

  constructor(selector: string, endpoint: string) {
    this.endpoint = endpoint;

    const element = document.getElementById(selector);
    if (element === null) {
      return;
    }

    listenToCkeditor(element).setupConfiguration(({ configuration }) => {
      configuration.woltlabBbcode?.push({
        icon: "question;false",
        name: "faq",
        label: "FAQ-Eintrag", // TODO: lang
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
    const request = prepareRequest(this.endpoint).get();
    const response = await request.fetchAsResponse();
    const template = await response?.text();

    if (template === undefined) {
      return;
    }

    this.dialog = dialogFactory().fromHtml(template).withoutControls();
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

    const request = prepareRequest(this.endpoint).post({
      searchString: value,
    });
    const response = await request.fetchAsResponse();
    const template = await response?.text();

    if (template === undefined) {
      return;
    }

    const resultContainer = document.getElementById("wcfUiFaqSearchResultContainer");
    const resultList = document.getElementById("wcfUiFaqSearchResultList");
    if (resultContainer === null || resultList === null) {
      return;
    }

    resultList.innerHTML = template;
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
