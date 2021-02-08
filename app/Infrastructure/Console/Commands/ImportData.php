<?php

declare(strict_types=1);

namespace App\Infrastructure\Console\Commands;

use App\Application\Interfaces\MarkdownConverterInterface;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class ImportData extends Command
{
    /** @var string */
    protected $signature = 'import-data';

    /** @var string */
    protected $description = 'Import and convert articles and pages from remote mysql';

    private MarkdownConverterInterface $markdownConverter;

    public function __construct(
        MarkdownConverterInterface $markdownConverter
    ) {
        parent::__construct();

        $this->markdownConverter = $markdownConverter;
    }

    public function handle(): void
    {
        $articles = $this->refreshArticles();
        $this->line("Imported $articles articles.");

        $comments = $this->refreshComments();
        $this->line("Imported $comments comments.");

        $pages = $this->refreshPages();
        $this->line("Imported $pages pages.");
    }

    private function refreshArticles(): int
    {
        $remoteBlogs = DB::connection('remote_mysql')
            ->table('blogs')
            ->get();

        $articles = $remoteBlogs->map(function (object $blog) {
            return [
                'content' =>  $blog->text,
                'description' =>  $blog->summary,
                'published_at' => $blog->publication_date,
                'slug' => $blog->slug,
                'status' => $blog->online,
                'title' => $blog->title,
                'uuid' => $blog->id,
                'created_at' => $blog->created_at,
                'updated_at' => $blog->updated_at,
            ];
        });

        DB::connection('mysql')
            ->table('articles')
            ->truncate();

        $count = 0;

        foreach ($articles as $article) {
            DB::connection('mysql')
                ->table('articles')
                ->insert($article);

            $count ++;
        }

        return $count;
    }

    private function refreshComments(): int
    {
        $remoteComments = DB::connection('remote_mysql')
            ->table('comments')
            ->get();

        $comments = $remoteComments->map(function (object $comment) {
            return [
                'article_uuid' =>  $comment->blog_id,
                'content' =>  $comment->text,
                'email' =>  $comment->email,
                'ip' => $comment->ip,
                'name' => $comment->name,
                'status' => $comment->online,
                'uuid' => $comment->id,
                'created_at' => $comment->created_at,
                'updated_at' => $comment->updated_at,
            ];
        });

        DB::connection('mysql')
            ->table('comments')
            ->truncate();

        $count = 0;

        foreach ($comments as $comment) {
            DB::connection('mysql')
                ->table('comments')
                ->insert($comment);

            $count ++;
        }

        return $count;
    }

    private function refreshPages(): int
    {
        $remotePages = DB::connection('remote_mysql')
            ->table('pages')
            ->get();

        $pages = $remotePages->map(function (object $page) {
            return [
                'content' =>  $page->text,
                'description' =>  $page->text,
                'slug' => $this->mapPageSlugs($page->slug),
                'title' => $page->title,
                'created_at' => $page->created_at,
                'updated_at' => $page->updated_at,
            ];
        });

        DB::connection('mysql')
            ->table('pages')
            ->truncate();

        $count = 0;

        foreach ($pages as $page) {
            if ($page['slug'] === false) {
                continue;
            }

            DB::connection('mysql')
                ->table('pages')
                ->insert($page);

            $count ++;
        }

        return $count;
    }

    /**
     * @return false|string
     */
    private function mapPageSlugs(string $originalSlug)
    {
        switch ($originalSlug) {
            case 'about-me':
                return 'about';
            case 'books-that-i-have-read':
                return 'books';
            default:
                return false;
        }
    }
}
