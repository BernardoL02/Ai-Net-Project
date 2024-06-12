<?php

namespace App\View\Components\Purchases;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class FilterCard extends Component
{
     public array $listPayments;
     public array $listOptionPrice;

    public function __construct(
       // public array $types,
        public string $filterAction,
        public string $resetUrl,
        public ?int $date = null,
        public ?string $type = null,
        public ?int $price = null ,
        public ?string $priceOption=null,

    )
    {

        $this->listPayments = [
            null => 'Any method',
            'MBWAY' => 'MBWAY',
            'VISA' => 'VISA',
            'PAYPAL' => 'PAYPAL'
        ];

        $this->listOptionPrice = [

            0 => 'Above',
            1 => 'Below',

        ];
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {


        return view('components.purchases.filter-card');
    }
}
