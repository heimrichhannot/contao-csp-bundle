<?php

namespace HeimrichHannot\CspBundle\EventListener\Contao;

use Contao\CoreBundle\DependencyInjection\Attribute\AsHook;
use Contao\PageModel;

#[AsHook("loadPageDetails")]
class LoadPageDetailsListener
{
    public function __invoke(array $parentModels, PageModel $page): void
    {
        if (empty($parentModels) && $page->type != 'root') {
            return;
        }

        $rootPage = $parentModels[array_key_last($parentModels)] ?? null;
        if (!$rootPage || $rootPage->type == 'root') {
            return;
        }

        $page->enableCsp = $rootPage->enableCsp;
        $page->csp = $rootPage->csp;
        $page->cspReportOnly = $rootPage->cspReportOnly;
        $page->cspReportLog = $rootPage->cspReportLog;
    }

}