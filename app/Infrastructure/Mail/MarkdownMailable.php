<?php

declare(strict_types=1);

namespace App\Infrastructure\Mail;

use Illuminate\Mail\Mailable;

final class MarkdownMailable extends Mailable
{
    public function __construct(
        private string $template,
        string $subject,
        private array $variables
    ) {
        $this->subject($subject);
    }

    public function build(): self
    {
        return $this->markdown($this->template)
            ->with($this->variables);
    }
}
