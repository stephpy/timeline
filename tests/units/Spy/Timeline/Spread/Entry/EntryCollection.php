<?php

namespace tests\units\Spy\Timeline\Spread\Entry;

require_once __DIR__.'/../../../../../../vendor/autoload.php';

use Spy\Timeline\Spread\Entry\EntryCollection as TestedModel;
use mageekguy\atoum;

class EntryCollection extends atoum\test
{
    public function testAdd()
    {
        $this->if($collection = new TestedModel())
            ->and($this->mockClass('\Spy\Timeline\Spread\Entry\EntryInterface', '\Mock'))
            ->and($entry = new \Mock\EntryInterface())
            ->and($entry->getMockController()->getIdent = 'ident1')
            ->when($collection->add($entry, 'NOTGLOBAL'))
            ->object($collection->getIterator())
            ->isEqualTo(new \ArrayIterator(array(
                'NOTGLOBAL' => array(
                    'ident1' => $entry,
                ),
                'GLOBAL' => array(
                    'ident1' => $entry,
                )
            )))
            // send with global context
            ->and($entry2 = new \Mock\EntryInterface())
            ->and($entry2->getMockController()->getIdent = 'ident2')
            ->when($collection->add($entry2, 'GLOBAL'))
            ->object($collection->getIterator())
            ->isEqualTo(new \ArrayIterator(array(
                'NOTGLOBAL' => array(
                    'ident1' => $entry,
                ),
                'GLOBAL' => array(
                    'ident1' => $entry,
                    'ident2' => $entry2,
                )
            )))
            // not duplicate on global.
            ->and($collection->setDuplicateOnGlobal(false))
            ->and($entry3 = new \Mock\EntryInterface())
            ->and($entry3->getMockController()->getIdent = 'ident3')
            ->when($collection->add($entry3, 'OTHERCONTEXT'))
            ->object($collection->getIterator())
            ->isEqualTo(new \ArrayIterator(array(
                'OTHERCONTEXT' => array(
                    'ident3' => $entry3,
                ),
                'NOTGLOBAL' => array(
                    'ident1' => $entry,
                ),
                'GLOBAL' => array(
                    'ident1' => $entry,
                    'ident2' => $entry2,
                )
            )))
        ;
    }

    public function testLoadUnawareEntries()
    {
    }

    public function testClear()
    {
        $this->if($collection = new TestedModel())
            ->and($this->mockClass('\Spy\Timeline\Spread\Entry\EntryInterface', '\Mock'))
            ->and($entry = new \Mock\EntryInterface())
            ->and($entry->getMockController()->getIdent = 'ident1')
            ->when($collection->add($entry, 'NOTGLOBAL'))
            ->object($collection->getIterator())
            ->isEqualTo(new \ArrayIterator(array(
                'NOTGLOBAL' => array(
                    'ident1' => $entry,
                ),
                'GLOBAL' => array(
                    'ident1' => $entry,
                )
            )))
            // send with global context
            ->when($collection->clear())
            ->object($collection->getIterator())
            ->isEqualTo(new \ArrayIterator(array()))
        ;
    }
}
