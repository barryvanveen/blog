<?php

declare(strict_types=1);

namespace App\Infrastructure\Console\Commands;

use App\Application\Interfaces\MarkdownConverterInterface;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class ImportData extends Command
{
    protected $signature = 'import-data';

    protected $description = 'Import and convert articles and pages from remote mysql';

    /** @var MarkdownConverterInterface */
    private $markdownConverter;

    public function __construct(
        MarkdownConverterInterface $markdownConverter
    ) {
        parent::__construct();

        $this->markdownConverter = $markdownConverter;
    }

    public function handle()
    {
        $articles = $this->refreshArticles();
        $this->line("Imported $articles articles.");

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
                'content' =>  $this->markdownConverter->convertToHtml($blog->text),
                'description' =>  $this->markdownConverter->convertToHtml($blog->summary),
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

    private function refreshPages(): int
    {
        $remotePages = DB::connection('remote_mysql')
            ->table('pages')
            ->get();

        $pages = $remotePages->map(function (object $page) {
            return [
                'content' =>  $this->markdownConverter->convertToHtml($page->text),
                'description' =>  $this->markdownConverter->convertToHtml($page->text),
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

    private function mapPageSlugs(string $originalSlug)
    {
        switch ($originalSlug) {
            case 'about-me':
                return 'about';
            default:
                return false;
        }
    }
}
