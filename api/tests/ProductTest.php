<?php

use App\Models\{User,Product};
use Firebase\JWT\JWT;

class ProductTest extends TestCase
{
    /**
     * /product [GET]
     */
    public function testShowReturnAllProducts()
    {
        $user =  (new User())->where('email', '=', 'rstroman@littel.biz')->first();
        $token = $this->auth($user);

        $this->get('product?token='.$token, []);
        $this->seeStatusCode(200);
        $this->seeJsonStructure([
            'products' => ['*' =>[
                'id',
                'name',
                'brand',
                'description',
                'value',
                'qty_stock',
                'created_at',
                'updated_at',
            ]]
        ]);
    }

    /**
     * /product [GET]
     */
    public function testShowReturnOneProducts()
    {
        $user =  (new User())->where('email', '=', 'rstroman@littel.biz')->first();
        $token = $this->auth($user);

        $product = Product::find(1);

        $this->get('product/'.$product->id.'?token='.$token, []);
        $this->seeStatusCode(200);
        $this->seeJsonStructure([
            'product' => [
                'id',
                'name',
                'brand',
                'description',
                'value',
                'qty_stock',
                'created_at',
                'updated_at',
            ]
        ]);
    }

    /**
     * /product [POST]
     */
    public function testCreateAndShowReturnProduct()
    {
        $user =  (new User())->where('email', '=', 'rstroman@littel.biz')->first();
        $token = $this->auth($user);

        $payload = [
            "name" => "Seringa Test " . rand(1, 10000),
            "brand" => "Medical Lage",
            "value" => "10.60",
            "qty_stock" => 10
        ];

        $this->post('product?token='.$token, $payload,[]);
        $this->seeStatusCode(201);
        $this->seeJsonStructure([
            'product' => [
                'id',
                'name',
                'brand',
                'description',
                'value',
                'qty_stock',
                'created_at',
                'updated_at',
            ]
        ]);
    }

    /**
     * /product [PUT]
     */
    public function testEditAndShowReturnProduct()
    {
        $user =  (new User())->where('email', '=', 'rstroman@littel.biz')->first();
        $token = $this->auth($user);

        $product = Product::find(1);

        $payload = [
            "name" => "Seringa Test ",
            "brand" => "Medical Lage",
            "value" => "10.60",
            "qty_stock" => 15
        ];

        $this->put('product/'.$product->id.'?token='.$token, $payload,[]);
        $this->seeStatusCode(201);
        $this->seeJsonStructure([
            'product' => [
                'id',
                'name',
                'brand',
                'description',
                'value',
                'qty_stock',
                'created_at',
                'updated_at',
            ]
        ]);
    }

    /**
     * /product [DELETE]
     */
    public function testDeleteProduct()
    {
        $user =  (new User())->where('email', '=', 'rstroman@littel.biz')->first();
        $token = $this->auth($user);

        $product = Product::find(12);

        $this->delete('product/'.$product->id.'?token='.$token, [],[]);
        $this->seeStatusCode(200);
        $this->seeJsonStructure(['*' => [
            'message' => 'Product destroyed',
        ]]);
    }

    /**
     * /product [GET]
     */
    public function testSearchOneProductNotExists()
    {
        $user =  (new User())->where('email', '=', 'rstroman@littel.biz')->first();
        $token = $this->auth($user);

        $this->get('product/1000?token='.$token, []);
        $this->seeStatusCode(404);
        $this->seeJsonStructure([
            'message' => 'Not found product',
        ]);
    }

    /**
     * /product [POST]
     */
    public function testCreateProductNameEqual()
    {
        $user =  (new User())->where('email', '=', 'rstroman@littel.biz')->first();
        $token = $this->auth($user);

        $payload = [
            "name" => "Seringa Test ",
            "brand" => "Medical Lage",
            "value" => "10.60",
            "qty_stock" => 10
        ];

        $this->post('product?token='.$token, $payload,[]);
        $this->seeStatusCode(400);
        $this->seeJsonStructure([
            'message' => 'The name has already been taken.',
        ]);
    }

    /**
     * /product [POST]
     */
    public function testCreateProductNotParamRequired()
    {
        $user =  (new User())->where('email', '=', 'rstroman@littel.biz')->first();
        $token = $this->auth($user);

        $payload = [
            "brand" => "Medical Lage",
            "value" => "10.60"
        ];

        $this->post('product?token='.$token, $payload,[]);
        $this->seeStatusCode(400);
        $this->seeJsonStructure([
            'message' => 'The qty stock field is required.',
        ]);
    }

    /**
     * /product [PUT]
     */
    public function testEditProductNotExists()
    {
        $user =  (new User())->where('email', '=', 'rstroman@littel.biz')->first();
        $token = $this->auth($user);

        $this->put('product/900000?token='.$token, [],[]);
        $this->seeStatusCode(201);
        $this->seeJsonStructure([
            'message' => 'Not found product',
        ]);
    }

    private function auth($user)
    {
        $payload = [
            'iss' => "lumen-jwt",
            'sub' => $user->id,
            'iat' => time(),
            'exp' => time() + 60 * 60
        ];

        return JWT::encode($payload, env('JWT_SECRET'));
    }
}
