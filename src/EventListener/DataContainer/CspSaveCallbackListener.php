<?php

namespace HeimrichHannot\CspBundle\EventListener\DataContainer;

use Contao\CoreBundle\DependencyInjection\Attribute\AsCallback;
use HeimrichHannot\CspBundle\Csp\CspParser;

#[AsCallback('tl_page', 'fields.csp.save')]
class CspSaveCallbackListener
{
    public function __construct(private readonly CspParser $cspParser)
    {
    }

    public function __invoke(mixed $value): mixed
    {
        try {
            $this->cspParser->parseHeader((string) $value);
        } catch (\InvalidArgumentException $e) {
            throw new \RuntimeException($e->getMessage(), 0, $e);
        }

        return $value;
    }
}