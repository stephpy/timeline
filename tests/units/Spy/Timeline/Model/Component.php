<?php

namespace tests\units\Spy\Timeline\Model;

require_once __DIR__.'/../../../../../vendor/autoload.php';

use Spy\Timeline\Model\Component as TestedModel;
use mageekguy\atoum;

class Component extends atoum\test
{
    public function testBuildHash()
    {
        $this->if($component = new TestedModel())
            ->and($component->setModel('chuck'))
            ->and($component->setIdentifier('norris'))
            ->when($component->buildHash()) // should be already called on setModel or setIdentifier
            ->string($component->getHash())->isEqualTo('chuck#s:6:"norris";')
            ->and($component->setIdentifier(array('norris', 'testa')))
            ->when($component->buildHash()) // should be already called on setModel or setIdentifier
            ->string($component->getHash())->isEqualTo('chuck#a:2:{i:0;s:6:"norris";i:1;s:5:"testa";}')
        ;
    }

    public function testCreateFromHash()
    {
        $this->if($component = new TestedModel())
            ->exception(function () use ($component) {
                $component->createFromHash('invalidhash');
            })
            ->isInstanceOf('\InvalidArgumentException')
            ->hasMessage('Invalid hash, must be formatted {model}#{hash or identifier}')
            // real hash
            ->when(function () use ($component) {
                $component->createFromHash('model#id');
            })
            ->error()->exists()
            // ok
            ->when($component->createFromHash('model#s:5:"chuck";'))
            ->string($component->getModel())->isEqualTo('model')
            ->string($component->getIdentifier())->isEqualTo('chuck')
            // composite
            ->when($component->createFromHash('model#a:2:{i:0;s:5:"chuck";i:1;s:5:"testa";}'))
            ->string($component->getModel())->isEqualTo('model')
            ->array($component->getIdentifier())->isEqualTo(array('chuck', 'testa'))
        ;
    }

    public function testSetModel()
    {
        $this->if($component = new TestedModel())
            ->variable($component->getHash())->isNull()
            ->and($component->setModel('chuck'))
            ->variable($component->getHash())->isNull()
            // reinit object
            ->if($component = new TestedModel())
            ->and($component->setIdentifier('norris'))
            ->and($component->setModel('chuck'))
            ->string($component->getHash())->isEqualTo('chuck#s:6:"norris";')
        ;
    }

    public function testSetIdentifier()
    {
        $this->if($component = new TestedModel())
            ->variable($component->getHash())->isNull()
            ->and($component->setIdentifier('norris'))
            ->variable($component->getHash())->isNull()
            // reinit object
            ->if($component = new TestedModel())
            ->and($component->setModel('chuck'))
            ->and($component->setIdentifier('norris'))
            ->string($component->getHash())->isEqualTo('chuck#s:6:"norris";')
        ;
    }
}
