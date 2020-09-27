<?php

declare(strict_types=1);

namespace Tests;

use App\Domain\Articles\Enums\ArticleStatus;
use App\Domain\Articles\Models\Article;
use App\Domain\Comments\Comment;
use App\Domain\Comments\CommentStatus;
use App\Domain\Pages\Models\Page;
use DateTimeImmutable;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use Nyholm\Psr7\Factory\Psr17Factory;
use Prophecy\PhpUnit\ProphecyTrait;
use Psr\Http\Message\ResponseFactoryInterface;
use Psr\Http\Message\StreamFactoryInterface;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication, ProphecyTrait;

    protected function getStreamFactory(): StreamFactoryInterface
    {
        return new Psr17Factory();
    }

    protected function getResponseFactory(): ResponseFactoryInterface
    {
        return new Psr17Factory();
    }

    protected function getArticle(array $attributes = []): Article
    {
        return new Article(
            $attributes['content'] ?? 'foo',
            $attributes['description'] ?? 'bar',
            $attributes['published_at'] ?? new DateTimeImmutable('now'),
            $attributes['slug'] ?? 'baz-baz',
            $attributes['status'] ?? ArticleStatus::published(),
            $attributes['title'] ?? 'Baz baz',
            $attributes['uuid'] ?? '123123'
        );
    }

    protected function getComment(array $attributes = []): Comment
    {
        return new Comment(
            $attributes['article_uuid'] ?? '987987',
            $attributes['content'] ?? 'foo',
            $attributes['created_at'] ?? new DateTimeImmutable('now'),
            $attributes['email'] ?? 'bar@baz.tld',
            $attributes['ip'] ?? '123.123.123.123',
            $attributes['name'] ?? 'Foo Bar',
            $attributes['status'] ?? CommentStatus::published(),
            $attributes['uuid'] ?? '123123'
        );
    }

    protected function getPage(array $attributes = []): Page
    {
        return new Page(
            $attributes['content'] ?? 'mycontent',
            $attributes['description'] ?? 'mydescription',
            $attributes['lastUpdated'] ?? new DateTimeImmutable('now'),
            $attributes['slug'] ?? 'myslug',
            $attributes['title'] ?? 'mytitle'
        );
    }
}
