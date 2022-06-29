<?php

namespace App\Services;

class View
{
    protected string $path;

    public function __construct(string $path)
    {
        $this->path = $path;
    }

    public function renderHtml(string $template, array $vars = [], int $code = 200)
    {
        http_response_code($code);
        extract($vars);

        ob_start();
        include $this->path . '/' . $template;
        $buffer = ob_get_contents();
        ob_end_clean();

        echo $buffer;
    }
}
