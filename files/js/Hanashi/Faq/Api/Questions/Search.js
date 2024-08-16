define(["require", "exports", "WoltLabSuite/Core/Ajax/Backend", "WoltLabSuite/Core/Api/Result"], function (require, exports, Backend_1, Result_1) {
    "use strict";
    Object.defineProperty(exports, "__esModule", { value: true });
    exports.renderSearch = renderSearch;
    exports.searchQuestions = searchQuestions;
    async function renderSearch() {
        const url = new URL(`${window.WSC_API_URL}index.php?api/rpc/faq/questions/search/render`);
        let response;
        try {
            response = (await (0, Backend_1.prepareRequest)(url).get().fetchAsJson());
        }
        catch (e) {
            return (0, Result_1.apiResultFromError)(e);
        }
        return (0, Result_1.apiResultFromValue)(response);
    }
    async function searchQuestions(query) {
        const url = new URL(`${window.WSC_API_URL}index.php?api/rpc/faq/questions/search`);
        url.searchParams.set("query", query);
        let response;
        try {
            response = (await (0, Backend_1.prepareRequest)(url).get().fetchAsJson());
        }
        catch (e) {
            return (0, Result_1.apiResultFromError)(e);
        }
        return (0, Result_1.apiResultFromValue)(response);
    }
});
