<?php

namespace Spy\Timeline\Spread\Entry;

use Spy\Timeline\Model\ComponentInterface;

/**
 * Entry
 *
 * @author Stephane PY <py.stephane1@gmail.com>
 */
class Entry implements EntryInterface
{
    /**
     * @var ComponentInterface
     */
    protected $subject;

    /**
     * @param ComponentInterface $subject subject
     */
    public function __construct(ComponentInterface $subject)
    {
        $this->subject = $subject;
    }

    /**
     * {@inheritdoc}
     */
    public function getIdent()
    {
        return $this->subject->getHash();
    }

    /**
     * {@inheritdoc}
     */
    public function getSubject()
    {
        return $this->subject;
    }
}
