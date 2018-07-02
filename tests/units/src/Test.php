<?php

namespace Norsys\SecurityBundle\Tests\Units;

use mageekguy\atoum;
use mageekguy\atoum\mock;

class Test extends atoum
{
    function beforeTestMethod($method)
    {
        mock\controller::disableAutoBindForNewMock();

        $this->mockGenerator
            ->allIsInterface()
            ->eachInstanceIsUnique()
        ;

        return parent::beforeTestMethod($method);
    }
}
