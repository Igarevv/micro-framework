<?php

namespace Igarevv\Micrame\Http\Response;

class RedirectResponse extends Response
{
    public function __construct(string $url)
    {
        parent::__construct('', 302, ['location' => $url]);
    }

    public function send(): void
    {
        header("Location: {$this->getHeader('location')}", true, $this->status);
    }

}