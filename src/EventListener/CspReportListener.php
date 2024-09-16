<?php

namespace HeimrichHannot\CspBundle\EventListener;

use Contao\CoreBundle\Monolog\ContaoContext;
use Nelmio\SecurityBundle\ContentSecurityPolicy\Violation\ReportEvent;
use Psr\Log\LoggerInterface;
use Symfony\Component\EventDispatcher\Attribute\AsEventListener;

#[AsEventListener]
class CspReportListener
{
    public function __construct(
        private readonly LoggerInterface $logger,
    )
    {
    }

    public function __invoke(ReportEvent $event): void
    {
        $report = $event->getReport();

        $context = new ContaoContext(
            __METHOD__,
            ContaoContext::ERROR,
            browser: $report->getUserAgent(),

        );

        $msg = sprintf('Content-Security-Policy violation reported for "%s"', $report->getDirective());

        if (null !== ($line = ($report->getData()['line-number'] ?? null))) {
            $msg .= ' on line '.$line;
        }

        if (null !== ($url = ($report->getData()['document-uri'] ?? null))) {
            $msg .= ' in '.$url;
        }

        $this->logger->error($msg, ['contao' => $context]);
    }
}