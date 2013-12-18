<?php namespace Christina;

// For the sake of clarity we'll comment on some of the texts generated here.

// Shorthand.
$user = staticFunctionReference('Template::inlineUser');

// "<status> and [not ]held", such as "active and not held".
$status = $post->status . ' and ' . ($post->isHeld ? '' : 'not ') . 'held';

// Created "<some-date> by <some-user>".
$created = humanDate($post->created) . ' by ' . $user($post->owner);

// Indexed: either a date or the string "no".
$indexed = $post->isShownInIndex ? humanDate($post->indexed) : '<i>no</i>';

// Source, or "none".
$source = $post->source ? $post->source : '<i>none</i>';

// These lines mean either "never" or "<some-date> by <some-user>".

$commented = humanDate($post->commented, '<i>never</i>')
           . ($post->commented ? ' by ' . $user($post->lastComment()->user()) : '');
$noted     = humanDate($post->noted    , '<i>never</i>')
           . ($post->noted     ? ' by ' . $user($post->lastNote()->user()) : '');

// Approver of the post, if any.
$approver = $user($post->approver, '<i>nobody</i>');

// Post's children.
$children = $post->hasChildren ? plural($post->childCount(), 'post') : '<i>none</i>';

// Header of the original image; can be simplified if there's only the original.
$originalImageHeader = ($post->sample ? 'Original ' : '') . 'Image Properties';

// Now we'll output the tables.

echo Template::table('Post Information', [
    ['Status: '      , $status      ],
    ['Rating: '      , $post->rating],
    ['Score: '       , $post->score ],
    ['Source: '      , $source      ],
    ['Created: '     , $created     ],
    ['Indexed: '     , $indexed     ],
    ['Last comment: ', $commented   ],
    ['Last note: '   , $noted       ],
    ['Approved by: ' , $approver    ],
    ['Children: '    , $children    ]
]);

echo Template::table($originalImageHeader, [
    ['Resolution: ', $post->image->resolution()   ],
    ['Extension: ' , strtoupper($post->ext)       ],
    ['File size: ' , humanSize($post->image->size)],
    ['MD5: '       , $post->md5                   ]
]);

if ($post->sample) echo Template::table('Sample Image Properties', [
    ['Resolution: ', $post->sample->resolution()   ],
    ['File size: ' , humanSize($post->sample->size)]
]);

if ($post->sample) echo Template::table('JPEG Image Properties', [
    ['Resolution: ', $post->jpeg->resolution()   ],
    ['File size: ' , humanSize($post->jpeg->size)]
]);

if ($post->sample) echo Template::table('Thumbnail Properties', [
    ['Resolution: ', $post->thumb->resolution(false)]
]);
