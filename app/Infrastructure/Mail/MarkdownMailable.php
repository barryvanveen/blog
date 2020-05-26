<?php

declare(strict_types=1);

namespace App\Infrastructure\Mail;

use Illuminate\Mail\Mailable;

final class MarkdownMailable extends Mailable
{
    /** @var string */
    private $template;

    /** @var array */
    private $variables;

    public function __construct(
        string $template,
        string $subject,
        array $variables
    ) {
        $this->template = $template;
        $this->variables = $variables;

        $this->subject($subject);
    }

    public function build()
    {
        return $this->markdown($this->template)
            ->with($this->variables);
    }
}
