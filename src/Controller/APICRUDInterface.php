<?php

namespace App\Controller;

use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Validator\Validator\ValidatorInterface;

interface APICRUDInterface
{
    public function list(ManagerRegistry $doctrine);
    public function show(int $id, ManagerRegistry $doctrine);
    public function create(ManagerRegistry $doctrine, Request $request);
    public function update(int $id, ManagerRegistry $doctrine, Request $request);
    public function delete(int $id, ManagerRegistry $doctrine, Request $request);
}
