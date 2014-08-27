<?php

namespace tests\units\Spy\Timeline\Spread\Entry;

require_once __DIR__.'/../../../../../../vendor/autoload.php';

use Spy\Timeline\Spread\Entry\EntryUnaware as TestedModel;
use mageekguy\atoum;

class EntryUnaware extends atoum\test
{
    public function testContruct()
    {
        $this->exception(function () {
                $entry = new TestedModel('model', new \stdClass());
            })
            ->isInstanceOf('\InvalidArgumentException')
            ->hasMessage('subjectId has to be a scalar or an array')
            // array
            ->if($entry = new TestedModel('model', array(1, 2)))
            ->array($entry->getSubjectId())->isEqualTo(array(1, 2))
            // scalar
            ->if($entry = new TestedModel('model', 'string'))
            ->string($entry->getSubjectId())->isEqualTo('string')
            ->if($entry = new TestedModel('model', 1))
            ->string($entry->getSubjectId())->isEqualTo('1')
            ->if($entry = new TestedModel('model', 1.01))
            ->string($entry->getSubjectId())->isEqualTo('1.01')
        ;
    }

    public function testGetIdent()
    {
        $this->if($entry = new TestedModel('model', 1))
            ->string($entry->getIdent())->isEqualTo('model#s:1:"1";')
            ->if($entry = new TestedModel('model', array(1, 2, 3)))
            ->string($entry->getIdent())->isEqualTo('model#a:3:{i:0;i:1;i:1;i:2;i:2;i:3;}');
        ;
    }

    public function testIsStrict()
    {
        $this->if($entry = new TestedModel('model', 1))
            ->boolean($entry->isStrict())->isFalse()
            ->if($entry = new TestedModel('model', 1, false))
            ->boolean($entry->isStrict())->isFalse()
            ->if($entry = new TestedModel('model', 1, true))
            ->boolean($entry->isStrict())->isTrue()
        ;
    }
}
