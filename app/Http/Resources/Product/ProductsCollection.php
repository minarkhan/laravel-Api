<?php

namespace App\Http\Resources\Product;

use Illuminate\Http\Resources\Json\JsonResource;

class ProductsCollection extends JsonResource
{
    /**
     * Transform the resource collection into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'name' => $this->name,   
            'stock' => $this->stock == 0 ? 'out of stock' : $this->stock,
          'discount' => $this->discount,
            'totalPrice' => round((1 - $this->discount/100) * $this->price, 2),
            'rating' => $this->reviews->count() > 0 ? round($this->reviews->sum('star')/$this->reviews->count(),2) : 'No review yet',
            'href' => [
                'link' => route('products.show', $this->id)
            ],
        ];
    }
}
