<?php

namespace App\Http\Controllers;

use App\Blog\Models\ArticleRepository;

final class BlogController extends Controller
{
    private $repository;

    public function __construct(ArticleRepository $repository)
    {
        $this->repository = $repository;
    }

    public function index()
    {
        return view('pages.blog', [
            'articles' => $this->repository->allPublishedAndOrdered(),
        ]);
    }
}
