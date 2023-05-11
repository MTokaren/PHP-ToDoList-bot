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
                'callback_data'=>'note-set'
            ],
            [
                'text'=>"\u{23F0}" . ' Set a reminder',
                'callback_data'=>'remind-set'
            ],
        ],
        [
            [
                'text'=>"\u{1F4D3}" . "\u{1F50D}" . ' Search a note',
                'callback_data'=>'note-search'
            ],
            [
                'text'=>"\u{1F4D3}" . "\u{274C}" . ' Delete a note',
                'callback_data'=>'note-delete'
            ]
        ],
        [
            [
                'text'=>"\u{23F0}" . "\u{1F527}" .' Change reminder',
                'callback_data'=>'remind-change'
            ],
            [
                'text'=>"\u{23F0}" . "\u{274C}" .' Delete reminder',
                'callback_data'=>'remind-delete'
            ],
            
        ],
        [
            [
                'text'=>"\u{1F55B}" . "\u{2753}". "\u{1F550}" .' Get reminder list between',
                'callback_data'=>'remind-between'
            ]
        ]
    ] ];

}
