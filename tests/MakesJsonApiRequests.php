<?php

namespace Tests;

use Closure;
use Illuminate\Testing\Assert as PHPUnit;
use Illuminate\Testing\TestResponse;
use Mockery\Expectation;
use Illuminate\Support\Str;
use PHPUnit\Framework\ExpectationFailedException;

trait MakesJsonApiRequests
{

    protected $formatJsonApiDocument = true;
    protected function setUp(): void
    {
        parent::setUp();
        TestResponse::macro(
            'assertJsonApiValidationErrors',
            $this -> assertJsonApiValidationErrors()
        );
            
    }

    protected function getFormanttedData($uri, array $data):array
    {
        $path = parse_url($uri) ['path'];
        $type = (string) Str::of($path) -> after('api/')->before('/');
        $id = (string) Str::of($path) -> after($type)->replace('/','');
  
        return [
            'data' => array_filter([
                'type' => $type,
                'id' => $id,
                'attributes' => $data
            ])
            ];
    }
    
    public function json($method, $uri, array $data = [], array $headers = []): TestResponse
    {
        $headers['accept'] = 'application/vnd.api+json';
        if($this -> formatJsonApiDocument){
            $formattedData = $this->getFormanttedData($uri,$data);
        }
        return parent::json($method,$uri,$formattedData ?? $data,$headers);
    }

    public function postJson($uri, array $data = [], array $headers = []): \Illuminate\Testing\TestResponse
    {
        $headers['content-type'] = 'application/vnd.api+json';
        return parent::postJson($uri,$data,$headers);
    }
    
    public function patchJson($uri, array $data = [], array $headers = []): \Illuminate\Testing\TestResponse
    {
        $headers['content-type'] = 'application/vnd.api+json';
        return parent::patchJson($uri,$data,$headers);
    }

    protected function assertJsonApiValidationErrors(): Closure
    {
        return function($attribute){
            /** @var TestResponse $this */

            $pointer =  Str::of($attribute)->startsWith('data')
            ? "/".str_replace('.','/',$attribute): 
            "/data/attributes/{$attribute}";

            try{

                $this->assertJsonFragment([
                    'source' => ['pointer' => $pointer]
                    ]);
                
            }catch (ExpectationFailedException $e){
                PHPUnit::fail("Failed to find a JSON:API validation error for key: '{$attribute}'"
                .PHP_EOL.PHP_EOL.
                $e->getMessage());
            }
           
            try{
                $this->assertJsonStructure([
                    'errors' => [
                        ['title','detail', 'source' => ['pointer']]
                    ]
                    ]);
            }catch(ExpectationFailedException $e){
                PHPUnit::fail("Failed to find a valid JSON:API error response"
                .PHP_EOL.PHP_EOL.
                $e->getMessage());
            }
        
            
                
            $this->assertHeader(
                    'content-type', 'application/vnd.api+json'
                )->assertStatus(422);
                
        };
    }
}