<?php

namespace AppBundle\Tests\Units;

use mageekguy\atoum;
use mageekguy\atoum\mock;

/**
 * Class Test
 *
 * @package AppBundle\Tests\Units
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
