<?php

namespace SanRatul\ShopAssist\Support;

class AIResponse
{

    public function __construct(
        public readonly string $content,
        public readonly ?string $provider = null,
        public readonly ?string $model = null,
        public readonly ?string $tokens = null,
        public readonly array $metaData = [],
    )
    {}

    public function toArray(): array
    {
        return [
            'content' => $this->content,
            'provider' => $this->provider,
            'model' => $this->model,
            'tokens' => $this->tokens,
            'metaData' => $this->metaData,
        ];
    }

}