<?php

class Keyboards
{
    const START = ['inline_keyboard'=>[
        [
            [
                'text'=>"\u{1F4D3}" . ' Show my notes',
                'callback_data'=>'start-show'
            ],
            [
                'text'=>"\u{1F4D3}" . "\u{270F}" . ' Make a note',
                'callback_data'=>'set_note'
            ]
            
        ],
        [
            
            [
                'text'=>"\u{1F4D3}" . "\u{1F50D}" . ' Search a note',
                'callback_data'=>'search_note'
            ],
            [
                'text'=>"\u{1F4D3}" . "\u{274C}" . ' Delete a note',
                'callback_data'=>'delete_note'
            ]
            
        ],
        [
            [
                'text'=>"\u{23F0}" . "\u{1F527}" . ' Set a reminder',
                'callback_data'=>'set_remind'
            ],

            [
                'text'=>"\u{23F0}" . "\u{274C}" .' Delete reminder',
                'callback_data'=>'delete_remind'
            ]
            
        ],
        [
            [
                'text'=>"\u{1F55B}" . "\u{2753}". "\u{1F550}" .' Get reminder list between',
                'callback_data'=>'set_between'
            ]
        ],
        [
            [
                'text'=>"Show state",
                'callback_data'=>'show-state'
            ]
        ]
    ] ];

}
