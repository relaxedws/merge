<?php

namespace Relaxed\Merge\Test;

use Exception;
use Relaxed\Merge\ThreeWayMerge\ThreeWayMerge;

class ThreeWayMergeTest extends \PHPUnit_Framework_TestCase
{
    /**
     * Test function for arrays when no conflict would arise.
     *
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
     * Test function for Recursive Key Value Pairs.
     *
     * @return Merged array.
     * @throws Exception
     */
    public function testRecursiveKeyValue()
    {
        $original = [
            'keyA' => [
                0 => [
                    'keyB' => 'This is honey',
                    'keyC' => 'This is apple',
                ],
                1 => [
                    'keyB' => 'This is milk',
                    'keyC' => 'This is mango',
                ],
                2 => 'a little sugar',
            ]
        ];

        $local = [
            'keyA' => [
                0 => [
                    'keyB' => 'This is honey',
                    'keyC' => 'This is however, not apple',
                ],
                1 => [
                    'keyB' => 'This is changed because of local',
                    'keyC' => 'This is mango',
                ],
                2 => 'a little sugar',
            ]
        ];

        $remote = [
            'keyA' => [
                0 => [
                    'keyB' => 'This is not honey',
                    'keyC' => 'This is apple',
                ],
                1 => [
                    'keyB' => 'This is milk',
                    'keyC' => 'This is changed because of remote',
                ],
                2 => 'a little new something',
            ]
        ];
        $merge = new ThreeWayMerge();
        $result = $merge->performMerge($original, $local, $remote);
        $expected = [
            'keyA' => [
                0 => [
                    'keyB' => 'This is not honey',
                    'keyC' => 'This is however, not apple',
                ],
                1 => [
                    'keyB' => 'This is changed because of local',
                    'keyC' => 'This is changed because of remote',
                ],
                2 => 'a little new something',
            ]
        ];
        $this->assertEquals($expected, $result);
    }

    /**
     * Test function for Same key but in different Order.
     *
     * @return Merged array.
     * @throws Exception
     */
    public function testKeyInDifferentOrder()
    {
        $original = [
            'keyA' => [
                0 => [
                    'keyB' => 'This is 0=>KeyB from original',
                    'keyC' => 'This is 0=>KeyC from original',
                ],
                1 => [
                    'keyB' => 'This is 1=>KeyB from original',
                    'keyC' => 'This is 1=>KeyC from original',
                ],
                2 => 'This is key 2',
            ]
        ];

        $local = [
            'keyA' => [
                0 => [
                    'keyB' => 'This is 0=>KeyB from original',
                    'keyC' => 'This is 0=>KeyC from local',
                ],
                2 => 'This is key 2 pretending not to be',
                1 => [
                    'keyB' => 'This is 1=>KeyB from local',
                    'keyC' => 'This is 1=>KeyC from different',
                ],
            ]
        ];

        $remote = [
            'keyA' => [
                2 => 'This is key 2',
                0 => [
                    'keyB' => 'This is 0=>KeyB from remote',
                    'keyC' => 'This is 0=>KeyC from original',
                ],
                1 => [
                    'keyB' => 'This is 1=>KeyB from original',
                    'keyC' => 'This is 1=>KeyC from different',
                ],
            ]
        ];
        $merge = new ThreeWayMerge();
        $result = $merge->performMerge($original, $local, $remote);
        $expected = [
            'keyA' => [
                0 => [
                    'keyB' => 'This is 0=>KeyB from remote',
                    'keyC' => 'This is 0=>KeyC from local',
                ],
                1 => [
                    'keyB' => 'This is 1=>KeyB from local',
                    'keyC' => 'This is 1=>KeyC from different',
                ],
                2 => 'This is key 2 pretending not to be',
            ]
        ];
        $this->assertEquals($expected, $result);
    }

    /**
     * Test function for cases when merge conflict would arise.
     *
     * @return Merged array.
     * @throws Exception
     */
    public function testConflict()
    {
        $original = [
            'title' => 'abc',
            'body' => "lorem ipsum",
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
        $merge->performMerge($original, $local, $remote);
        try {
            $merge->performMerge($original, $local, $remote);
            $this->fail('Exception was not thrown.');
        } catch (Exception $e) {
            $this->assertTrue(true);
        }
    }
}
