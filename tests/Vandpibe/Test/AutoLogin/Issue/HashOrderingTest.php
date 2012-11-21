<?php

namespace Vandpibe\Test\AutoLogin\Issue;

use Vandpibe\AutoLogin\Hasher;

class HashOrderingTest extends \PHPUnit_Framework_TestCase
{
    public function setUp()
    {
        $this->hasher = new Hasher('secret');
    }

    public function testHashParameterOrdering()
    {
        $hash = $this->hasher->hash(array('username' => 'henrikb', 'expiresAt' => 1353441361, 'b' => 'something', 'a' => 'something'));

        $this->assertEquals($hash, $this->hasher->hash(array('a' => 'something', 'b' => 'something', 'expiresAt' => '1353441361', 'username' => 'henrikb')));
    }
}
