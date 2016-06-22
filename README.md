# Relaxedws/merge [![Build Status](https://travis-ci.org/relaxedws/merge.svg?branch=master)](https://travis-ci.org/relaxedws/merge)

<<<<<<< HEAD
A Library to perform recursive 3 way merge algorithm
=======
A Library to perform recursive 3-way merge algorithm
>>>>>>> 014615c9b8cd6a0a289dc80e03b131e1784893e1
on associative arrays, written in PHP.

## Insight

<<<<<<< HEAD
This library is built to perform a recursive 3 way merge algorithm. It takes 3 parameters which are arrays representing base entity, local entity and remote entity. It compares each of these entities with other entities line by line. 
If only one out of remote or local is updated out of these 3, the final revision will have all the unchanged data in it along with the update data from the update entity(Either remote or local). If more than one entity is updated on the same line, it'd throw a `ConflictException`.
=======
This library is built to perform a recursive 3-way merge algorithm. It takes 3 parameters which are arrays representing base array, local array and remote array. It compares each of these entities with other arrays line-wise.
If only one out of remote or local is updated out of these 3, the final revision will have all the unchanged data in it along with the update data from the update array (Either remote or local). If more than one array is updated on the same line, it'd throw a `ConflictException`.
>>>>>>> 014615c9b8cd6a0a289dc80e03b131e1784893e1


## Install

The library can be installed via [composer](http://getcomposer.org).

````JSON
{
<<<<<<< HEAD
  "name": "relaxedws/merge",
  "description": "Library used to perform merges between normalized array structures.",
  "require-dev": {
    "phpunit/phpunit": "~4.0"
  },
  "autoload": {
    "psr-4": {
      "Relaxed\\Merge\\": "src/"
    }
  },
  "autoload-dev": {
    "psr-4": {
      "Relaxed\\Merge\\Test\\": "tests/"
    }
=======
  "name": "myorg/mylib",
  "description": "A library depending on 3-way merge",
  "require": {
    "relaxedws/merge": "dev-master",
>>>>>>> 014615c9b8cd6a0a289dc80e03b131e1784893e1
  }
}
````

## Example

After [installation](#install), we can perform a merge the following way:

````php
<?php

namespace testing;

require __DIR__ ."/vendor/autoload.php";

use Relaxed\Merge\ThreeWayMerge;

$original = [
    'keyA' => [
        0 => [
            'keyB' => 'This is honey
            like this',
            'keyC' => 'This is however, not apple',
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
            'keyB' => 'This is honeybb
            like ti',
            'keyC' => 'This is however, not apple',
        ],
        1 => [
            'keyB' => 'This is milky milky',
            'keyC' => 'This is mango',
        ],
        2 => 'a little coffee'
    ]
];
$remote = [
    'keyA' => [
        0 => [
            'keyB' => 'This is honey
            like this',
            'keyC' => 'This is however, not apple',
        ],
        1 => [
            'keyB' => 'This is milk',
            'keyC' => 'This is changed because of remote',
        ],
        2 => 'a little sugar',
    ]
];

<<<<<<< HEAD
$multiline = new ThreeWayMerge();
$new_arr = $multiline->performMerge($original, $local, $remote);
=======
$merge = new ThreeWayMerge();
$updated_revision = $merge->performMerge($original, $local, $remote);
>>>>>>> 014615c9b8cd6a0a289dc80e03b131e1784893e1
````

## Contributing

<<<<<<< HEAD
We welcome all developers to come forward for use/test of this library.
Though it's working well for almost all possible scenarios but we all know there are bugs
in every code. Although we keep updating and testing the library regulary, but still if
you encounter any bugs, please feel free to drop us a comment with as much explaination as 
possible to reproduce the errors or you can even patch that.

Other than directly working with the code, you can also help us with better documentation, fixing typo errors
or adding more content to the `readme` file.

We are open to feedback, suggestions and questions.
We are always present at #drupal8-ports on irc.freenode.net .

=======
We welcome anyone to use, test, or contribute back to this project.
We have extensive test coverage, but as we all know there's always bugs in software.
Please file issues or pull requests with your comments or suggestions.
>>>>>>> 014615c9b8cd6a0a289dc80e03b131e1784893e1
