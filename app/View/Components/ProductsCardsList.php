<?php

namespace App\View\Components;

use App\Models\Product;
use Illuminate\View\Component;

class ProductsCardsList extends Component
{
    public $displayStatus = 'available';
    
    public function __construct($displayStatus)
    {
        $this->displayStatus = $displayStatus;
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        $products = Product::where('display_status', $this->displayStatus)->get();
        return view('components.products-cards-list', ['products' => $products]);
    }
}
