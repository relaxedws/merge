<?php

namespace Relaxed\Merge\Test;

use Exception;
use Relaxed\Merge\ThreeWayMerge\ThreeWayMerge;

class ThreeWayMergeTest extends \PHPUnit_Framework_TestCase
{
    /**
     * Test function for arrays when no conflict would arise.
     * @return Merged array.
     */
    public function testNoConflict()
    {
        $original = [
            'title' => 'abc',
            'body' => 'lorem ipsum',
        ];

        $local = [
            'title' => 'abc',
            'body' => 'dolor',
        ];

        $remote = [
            'title' => '123',
            'body' => 'lorem ipsum',
        ];

        $merge = new ThreeWayMerge();
        $result = $merge->performMerge($original, $local, $remote);
        $expected = [
            'title' => '123',
            'body' => 'dolor',
        ];
        $this->assertEquals($expected, $result);
    }

    /**
     * Test function for cases when merge conflict would arise.
     * @throws Exception
     */
    public function testConflict()
    {
        $original = [
            'title' => 'abc',
            'body' => 'lorem ipsum',
        ];

        $local = [
            'title' => 'abc',
            'body' => 'dolor',
        ];

        $remote = [
            'title' => '123',
            'body' => 'random',
        ];

        $merge = new ThreeWayMerge();
        try {
            $merge->performMerge($original, $local, $remote);
            $this->fail('Exception was not thrown.');
        } catch (Exception $e) {
            $this->assertTrue(true);
        }
    }
}
