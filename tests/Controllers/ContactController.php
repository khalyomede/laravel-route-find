<?php

namespace Tests\Controllers;

use Tests\Controller;

final class ContactController extends Controller
{
    public function index(): string
    {
        return "foo";
    }

    public function store(): string
    {
        return "store";
    }
}
