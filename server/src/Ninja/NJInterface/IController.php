<?php

namespace Ninja\NJInterface;

interface IController
{
    public function index();
    public function show();
    public function create();
    public function store();
    public function edit();
    public function update();
    public function destroy();
}
