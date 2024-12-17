<?php

namespace App\Controllers;

class BaseController
{
    protected function view($view, $data = [])
    {
        return view($view, $data);
    }

    protected function redirect($url)
    {
        return redirect($url);
    }

    protected function json($data)
    {
        header('Content-Type: application/json');
        return json_encode($data);
    }
} 