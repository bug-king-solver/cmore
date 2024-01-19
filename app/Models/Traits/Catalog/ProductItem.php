<?php

namespace App\Models\Traits\Catalog;

use App\Models\Tenant\Product;
use App\Models\User;
use Bavix\Wallet\Interfaces\Customer;
use Exception;

trait ProductItem
{
    public $productDetails;

    /**
     * Get the product from the catalog
     */
    public function product()
    {
        return Product::whereProductableType(self::class)->first();
    }

    public function getProduct()
    {
        if ($this->productDetails) {
            return $this->productDetails;
        }

        try {
            $product = $this->product;
        } catch (Exception $e) {
            $product = $this->product();
        }

        if ($product) {
            $product->is_payable = $this->isPayable();
        }

        $this->productDetails = $product;

        return $this->productDetails;
    }

    /**
     * Check if the model if payable
     */
    public function isPayable(): bool
    {
        return tenant()->has_wallet_feature;
    }

    public function getPriceProduct()
    {
        return $this->getProduct()->price;
    }

    /**
     * Required by the interface
     */
    public function getAmountProduct(null|Customer $customer): int|string
    {
        return 0;
    }

    public function getMetaProduct(): ?array
    {
        if (!auth()->check()) {
            $user = User::whereEmail("support@cmore.pt")->first();
        } else {
            $user = auth()->user()->getModel();
        }

        return [
            'product_id' => $this->getProduct()->id,
            'productable_type' => self::class,
            'productable_id' => $this->id,
            'title' => $this->getProduct()->title,
            'description' => __('Purchase of :product: #:id', ['product' => $this->getProduct()->title, 'id' => $this->id]),
            'user_type' => $user->getMorphClass(),
            'user_id' => $user->getKey(),
        ];
    }
}
