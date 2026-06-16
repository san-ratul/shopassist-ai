<?php

namespace SanRatul\ShopAssist\Components;

use Illuminate\View\Component;
use Illuminate\View\View;

class SettingsPage extends Component
{

    public function __construct(public $settings, public $providers)
    {
    }

    /**
     * @inheritDoc
     */
    public function render(): View
    {
        return view('shopassist::settings.index');
    }
}