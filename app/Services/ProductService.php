<?php

namespace App\Services;

use App\Models\Product;
use App\Models\ProductVariation;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;

class ProductService
{
    public function updateProduct(Product $product, array $data, array $files = null): Product
    {
        DB::beginTransaction();
        
        try {
            // Update product basic info
            $product->update($data);
            
            // Handle image uploads if files provided
            if ($files && isset($files['images'])) {
                foreach ($files['images'] as $image) {
                    if ($image instanceof UploadedFile) {
                        $product->addMedia($image)
                            ->toMediaCollection('product-images');
                    }
                }
            }
            
            // Handle variations
            if (isset($data['variations']) && is_array($data['variations'])) {
                $this->handleVariations($product, $data['variations']);
            }
            
            DB::commit();
            return $product;
            
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }
    
    private function handleVariations(Product $product, array $variations): void
    {
        foreach ($variations as $variationData) {
            if (isset($variationData['id'])) {
                $this->updateVariation($product, $variationData);
            } else {
                $this->createVariation($product, $variationData);
            }
        }
    }
    
    private function updateVariation(Product $product, array $data): void
    {
        $variation = ProductVariation::where('id', $data['id'])
            ->where('product_id', $product->id)
            ->first();
            
        if ($variation) {
            $variation->update([
                'size' => $data['size'] ?? null,
                'flavor' => $data['flavor'] ?? null,
                'quantity' => $data['quantity'] ?? null,
                'price' => $data['price'] ?? $product->prix,
                'stock_quantity' => $data['stock_quantity'] ?? $product->stock,
            ]);
        }
    }
    
    private function createVariation(Product $product, array $data): void
    {
        ProductVariation::create([
            'product_id' => $product->id,
            'size' => $data['size'] ?? null,
            'flavor' => $data['flavor'] ?? null,
            'quantity' => $data['quantity'] ?? null,
            'price' => $data['price'] ?? $product->prix,
            'stock_quantity' => $data['stock_quantity'] ?? $product->stock,
        ]);
    }
}
