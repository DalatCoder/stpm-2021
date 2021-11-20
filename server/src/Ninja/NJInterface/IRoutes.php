<?php

namespace Ninja\NJInterface;

interface IRoutes
{
    public function getRoutes(): array;
    public function getAuthentication(): ?\Ninja\Authentication;
    public function checkPermission($permission): ?bool;
}
