<?php

namespace HeimrichHannot\CspBundle\EventListener;

use HeimrichHannot\CspBundle\Csp\CspParser;
use HeimrichHannot\UtilsBundle\Util\Utils;
use Symfony\Component\EventDispatcher\Attribute\AsEventListener;
use Symfony\Component\HttpKernel\Event\ResponseEvent;
use Symfony\Component\Routing\Exception\RouteNotFoundException;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class ResponseListener
{
    public function __construct(
        private readonly UrlGeneratorInterface $urlGenerator,
        private readonly Utils $utils,
        private readonly CspParser $cspParser,
    )
    {
    }

    #[AsEventListener('kernel.response', priority: -11)]
    public function __invoke(ResponseEvent $event): void
    {
        if (!$event->isMainRequest() || !($pageModel = $this->utils->request()->getCurrentPageModel()) || !$pageModel->enableCsp) {
            return;
        }

        $directives = $this->cspParser->parseHeader((string) $pageModel->csp);
        $directives->setLevel1Fallback(false);

        if ($pageModel->cspReportLog) {
            try {
                $reportUri = $this->urlGenerator->generate('contao_csp_report', ['page' => $pageModel->id], UrlGeneratorInterface::ABSOLUTE_URL);
                $directives->setDirective('report-uri', $reportUri);
            } catch (RouteNotFoundException $e) {
            }
        }

        $headerName = 'Content-Security-Policy'.($pageModel->cspReportOnly ? '-Report-Only' : '');

        $response = $event->getResponse();
        $response->headers->set($headerName, $directives->buildHeaderValue($event->getRequest()));
    }
}