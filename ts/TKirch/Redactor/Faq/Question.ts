import UiFaqSearch from "./../../Ui/Faq/Search";

export class RedactorFaqQuestion {
  private editor: any;

  constructor(editor: any, button: HTMLLinkElement) {
    this.editor = editor;

    button.addEventListener("click", (event: MouseEvent) => this.click(event));
  }

  private click(event: MouseEvent): void {
    event.preventDefault();

    const faqSearch = new UiFaqSearch((questionID: number) => this.insertBBCode(questionID));
    faqSearch.open();

    // UiFaqSearch.open(this._insert.bind(this));
  }

  private insertBBCode(questionID: number): void {
    this.editor.buffer.set();

    this.editor.insert.text(`[faq='${questionID}'][/faq]`);
  }
}

export default RedactorFaqQuestion;
