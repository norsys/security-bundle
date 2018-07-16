<?php

namespace Norsys\SecurityBundle\Tests\Units;

use mageekguy\atoum;
use mageekguy\atoum\mock;

/**
 * Class Test
 *
 * @package Norsys\SecurityBundle\Tests\Units
 */
class Test extends atoum
{
    public function beforeTestMethod($method)
    {
        mock\controller::disableAutoBindForNewMock();

        $this->mockGenerator
            ->allIsInterface()
            ->eachInstanceIsUnique();

        return parent::beforeTestMethod($method);
    }
}
