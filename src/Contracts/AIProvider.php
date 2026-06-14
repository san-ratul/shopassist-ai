<?php

namespace SanRatul\ShopAssist\Contracts;

use SanRatul\ShopAssist\Support\AIResponse;

interface AIProvider
{

    public function chat(array $messages): AIResponse;

}