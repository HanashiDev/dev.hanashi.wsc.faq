import * as Ajax from "WoltLabSuite/Core/Ajax";
import { AjaxCallbackSetup, AjaxCallbackObject, ResponseData } from "WoltLabSuite/Core/Ajax/Data";
import { DialogCallbackObject, DialogCallbackSetup } from "WoltLabSuite/Core/Ui/Dialog/Data";
import * as Language from "WoltLabSuite/Core/Language";
import UiDialog from "WoltLabSuite/Core/Ui/Dialog";
import DomUtil from "WoltLabSuite/Core/Dom/Util";
import * as StringUtil from "WoltLabSuite/Core/StringUtil";

export class UiFaqSearch implements AjaxCallbackObject, DialogCallbackObject {
  private callbackSelect: (questionID: number) => void;

  constructor(callbackSelect: (questionID: number) => void) {
    this.callbackSelect = callbackSelect;
  }

  public open(): void {
    UiDialog.open(this);
  }

  private search(event: Event): void {
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
        DomUtil.innerError(inputContainer, Language.get("wcf.faq.question.search.error.tooShort"));
        return;
      } else {
        DomUtil.innerError(inputContainer, false);
      }
    }

    Ajax.api(this, {
      parameters: {
        searchString: value,
      },
    });
  }

  private click(event: MouseEvent) {
    event.preventDefault();

    const target = event.currentTarget as HTMLElement | null;
    if (target == null) {
      return;
    }

    const questionID = target.dataset.questionId;
    if (questionID == null) {
      return;
    }

    this.callbackSelect(parseInt(questionID));

    UiDialog.close(this);
  }

  public _ajaxSuccess(data: ResponseData): void {
    let html = "";

    for (let i = 0, length = data.returnValues.length; i < length; i++) {
      const question: any = data.returnValues[i];

      html += "<li>";
      html += `<div class="containerHeadline pointer" data-question-id="${question.questionID}">`;
      html += `<h3>${StringUtil.escapeHTML(question.question)}</h3>`;
      html += "</div>";
      html += "</li>";
    }

    const resultContainer = document.getElementById("wcfUiFaqSearchResultContainer");
    const resultList = document.getElementById("wcfUiFaqSearchResultList") as HTMLOListElement | null;
    if (resultContainer == null || resultList == null) {
      return;
    }
    resultList.innerHTML = html;

    if (html) {
      resultContainer.style.removeProperty("display");

      const containerHeadlines = resultList.querySelectorAll(".containerHeadline");
      containerHeadlines.forEach((element: Element) => {
        element.addEventListener("click", (event: MouseEvent) => this.click(event));
      });
    } else {
      resultContainer.style.setProperty("display", "none", "");
      const searchInput = document.getElementById("wcfUiFaqSearchInput");
      if (searchInput != null && searchInput.parentNode != null) {
        DomUtil.innerError(
          searchInput.parentNode as HTMLDivElement,
          Language.get("wcf.faq.question.search.error.noResults"),
        );
      }
    }
  }

  public _ajaxSetup(): ReturnType<AjaxCallbackSetup> {
    return {
      data: {
        actionName: "search",
        className: "wcf\\data\\faq\\QuestionAction",
      },
    };
  }

  public _dialogSetup(): ReturnType<DialogCallbackSetup> {
    return {
      id: "wcfUiFaqSearch",
      options: {
        onSetup: () => {
          const searchInput = document.getElementById("wcfUiFaqSearchInput");
          if (searchInput == null) {
            return;
          }

          searchInput.addEventListener("keyup", (event: KeyboardEvent) => this.search(event));
          searchInput.nextElementSibling?.addEventListener("click", (event: MouseEvent) => this.search(event));
        },
        onShow: function () {
          const searchInput = document.getElementById("wcfUiFaqSearchInput");
          if (searchInput == null) {
            return;
          }
          searchInput.focus();
        },
        title: Language.get("wcf.faq.question.search"),
      },
      source:
        '<div class="section">' +
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

export default UiFaqSearch;
