<?php
return [
    "files" => [
        /*
         * like filesystem, Where do you like to place pictures?
         */
        "root" => 'upload',
        /*
         * return public image path
         */
        "url" => env('APP_URL').'/storage/upload',
    ],
];