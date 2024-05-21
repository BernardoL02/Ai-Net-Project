<?php

namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class defaultButton extends Component
{
    /**
     * Create a new component instance.
     */
    public function __construct(
        public string $text = '',
        public string $href = '#',
        public string $type = 'dark',
    ) {
        $this->type = strtolower($type);
        if (!in_array($this->type, ['primary', 'secondary', 'success', 'danger', 'warning', 'info', 'light', 'dark', 'link'], true)) {
            $this->type = 'dark';
        }
        $this->text = trim($text) ?: ucfirst($this->type);
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.default-button');
    }
}
