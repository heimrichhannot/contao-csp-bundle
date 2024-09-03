<?php

namespace HeimrichHannot\CspBundle;

use Symfony\Component\HttpKernel\Bundle\Bundle;

class HeimrichHannotCspBundle extends Bundle
{
    public function getPath()
    {
        return \dirname(__DIR__);
    }


}