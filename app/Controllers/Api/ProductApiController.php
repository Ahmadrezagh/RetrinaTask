<?php

namespace App\Controllers\Api;

use App\Controllers\BaseController;
use App\Models\Product;

class ProductApiController extends BaseController
{
    /**
     * GET /api/products
     * List all products
     */
    public function index()
    {
        try {
            // TODO: Implement actual database fetching
            $products = []; // Product::all();
            
            return $this->jsonResponse([
                'status' => 'success',
                'data' => $products,
                'count' => count($products)
            ]);
        } catch (\Exception $e) {
            return $this->jsonError('Failed to fetch products', 500, 'FETCH_ERROR');
        }
    }

    /**
     * GET /api/products/{id}
     * Show specific product
     */
    public function show($id)
    {
        try {
            // TODO: Implement actual database fetching
            // $product = Product::find($id);
            
            // Mock data for demonstration
            if ($id == 1) {
                $product = [
                    'id' => 1,
                    'name' => 'Sample Product',
                    'created_at' => date('c'),
                    'updated_at' => date('c')
                ];
                
                return $this->jsonResponse([
                    'status' => 'success',
                    'data' => $product
                ]);
            }

            return $this->jsonError('Product not found', 404, 'RESOURCE_NOT_FOUND');
        } catch (\Exception $e) {
            return $this->jsonError('Failed to fetch product', 500, 'FETCH_ERROR');
        }
    }

    /**
     * POST /api/products
     * Create new product
     */
    public function store()
    {
        try {
            $input = $this->getJsonInput();
            
            // Basic validation
            $required = ['name']; // Add your required fields
            $missing = [];
            
            foreach ($required as $field) {
                if (!isset($input[$field]) || empty($input[$field])) {
                    $missing[] = $field;
                }
            }
            
            if (!empty($missing)) {
                return $this->jsonError('Missing required fields: ' . implode(', ', $missing), 400, 'VALIDATION_ERROR');
            }
            
            // TODO: Implement actual database creation
            // $product = Product::create($input);
            
            // Mock created resource
            $product = array_merge($input, [
                'id' => rand(100, 999),
                'created_at' => date('c'),
                'updated_at' => date('c')
            ]);
            
            return $this->jsonResponse([
                'status' => 'success',
                'message' => 'Product created successfully',
                'data' => $product
            ], 201);
        } catch (\Exception $e) {
            return $this->jsonError('Failed to create product', 500, 'CREATE_ERROR');
        }
    }

    /**
     * PUT /api/products/{id}
     * Update specific product
     */
    public function update($id)
    {
        try {
            $input = $this->getJsonInput();
            
            // TODO: Implement actual database update
            // $product = Product::find($id);
            // if (!$product) {
            //     return $this->jsonError('Product not found', 404, 'RESOURCE_NOT_FOUND');
            // }
            // $product->update($input);
            
            // Mock updated resource
            $product = array_merge($input, [
                'id' => (int)$id,
                'updated_at' => date('c')
            ]);
            
            return $this->jsonResponse([
                'status' => 'success',
                'message' => 'Product updated successfully',
                'data' => $product
            ]);
        } catch (\Exception $e) {
            return $this->jsonError('Failed to update product', 500, 'UPDATE_ERROR');
        }
    }

    /**
     * DELETE /api/products/{id}
     * Delete specific product
     */
    public function destroy($id)
    {
        try {
            // TODO: Implement actual database deletion
            // $product = Product::find($id);
            // if (!$product) {
            //     return $this->jsonError('Product not found', 404, 'RESOURCE_NOT_FOUND');
            // }
            // $product->delete();
            
            return $this->jsonResponse([
                'status' => 'success',
                'message' => 'Product deleted successfully',
                'deleted_id' => (int)$id
            ]);
        } catch (\Exception $e) {
            return $this->jsonError('Failed to delete product', 500, 'DELETE_ERROR');
        }
    }

    /**
     * Return JSON response
     */
    protected function jsonResponse($data, $statusCode = 200)
    {
        http_response_code($statusCode);
        header('Content-Type: application/json');
        echo json_encode($data, JSON_PRETTY_PRINT);
        exit;
    }

    /**
     * Return JSON error response
     */
    protected function jsonError($message, $statusCode = 400, $errorCode = null)
    {
        $data = [
            'status' => 'error',
            'message' => $message
        ];

        if ($errorCode) {
            $data['error_code'] = $errorCode;
        }

        return $this->jsonResponse($data, $statusCode);
    }

    /**
     * Validate JSON input
     */
    protected function getJsonInput()
    {
        $input = json_decode(file_get_contents('php://input'), true);
        
        if (json_last_error() !== JSON_ERROR_NONE) {
            $this->jsonError('Invalid JSON input', 400, 'INVALID_JSON');
        }

        return $input ?: [];
    }
}
