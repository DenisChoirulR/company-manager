<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class TestController extends Controller
{
    public function index(Request $request)
    {
        $numbers = $request->input('number');

        $data = [
            3 => 'fizz',
            5 => 'buzz',
            7 => 'bar'
        ];

        for ($i = 1; $i <= $numbers; $i++) {
            $text = "";
            foreach ($data as $key => $value) {
                if (is_int($i/$key)) {
                    $text = $text . $value;
                }
            }

            echo ($text != "" ? $text : $i) . '<br>' ;
        }
    }

    public function test(Request $request)
    {
        $text = $request->input('text');
        $length = mb_strlen($text);

        $flipped = '';
        for ($i = $length-1; $i >= 0; $i--) {
            $n = mb_substr($text, $i, 1);
            $flipped = $flipped . $n;
        }

        echo ($text == $flipped);
    }
}
