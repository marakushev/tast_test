<?php


namespace App\Service;


use App\Entity\Author;

class AuthorService
{
    public function createEntity()
    {
        return new Author();
    }
}