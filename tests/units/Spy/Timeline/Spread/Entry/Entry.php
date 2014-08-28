<?php

namespace tests\units\Spy\Timeline\Spread\Entry;

require_once __DIR__.'/../../../../../../vendor/autoload.php';

use Spy\Timeline\Spread\Entry\Entry as TestedModel;
use mageekguy\atoum;

class Entry extends atoum\test
{
    public function testGetIdent()
    {
        $this->if($this->mockClass('\Spy\Timeline\Model\ComponentInterface', '\Mock'))
            ->and($component = new \Mock\ComponentInterface())
            ->and($component->getMockController()->getHash = 'myhash')
            ->and($entry = new TestedModel($component))
            ->string($entry->getIdent())->isEqualTo('myhash')
            ->mock($component)
                ->call('getHash')
                ->once()
        ;
    }
}
