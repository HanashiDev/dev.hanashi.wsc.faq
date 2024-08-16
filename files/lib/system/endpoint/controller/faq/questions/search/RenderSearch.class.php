<?php

namespace wcf\system\endpoint\controller\faq\questions\search;

use Laminas\Diactoros\Response\JsonResponse;
use Override;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use wcf\system\endpoint\GetRequest;
use wcf\system\endpoint\IController;
use wcf\system\WCF;

#[GetRequest('/faq/questions/search/render')]
final class RenderSearch implements IController
{
    #[Override]
    public function __invoke(ServerRequestInterface $request, array $variables): ResponseInterface
    {
        return new JsonResponse([
            'template' => WCF::getTPL()->fetch('faqQuestionSearchDialog', 'wcf'),
        ]);
    }
}
