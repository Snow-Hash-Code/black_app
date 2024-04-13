<?php

namespace Tests\Feature;

use App\Http\Middleware\ValidateJsonApiHeaders;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use Illuminate\Support\Facades\Route;

class ValidateJsonApiHeadersTest extends TestCase
{
    use RefreshDatabase;
    /** @test */
    protected function setUp(): void{
        parent::setUp();

        Route::any('test_route', fn()=> 'OK')
            ->middleware(ValidateJsonApiHeaders::class);

    }

    // /** @test */
    // public function accept_header_must_be_present_in_all_requests()
    // {
    //     Route::get('test_route', fn()=> 'OK')
    //         ->middleware(ValidateJsonApiHeaders::class);
        
    //     $this -> getJson('test_route')->dump()->assertStatus(406);

    //     $this->get('test_route', [
    //         'accept' => 'application/vnd.api+json'
    //     ])->assertSuccessful();  
    // }   

    /** @test */
    public function content_type_header_must_be_present_in_all_on_all_posts_request()
    {
      
        $this -> post('test_route',[],[
            'accept' => 'application/vnd.api+json'
        ])->assertStatus(415);

        $this -> post('test_route',[],[
            'accept' => 'application/vnd.api+json',
            'content-type' => 'application/vnd.api+json'
        ])->assertSuccessful();
        
    }  

    /** @test */
    public function content_type_header_must_be_present_in_all_on_all_pacth_request()
    {
     
      $this -> patch('test_route',[],[
          'accept' => 'application/vnd.api+json'
      ])->assertStatus(415);

      $this -> patch('test_route',[],[
          'accept' => 'application/vnd.api+json',
          'content-type' => 'application/vnd.api+json'
      ])->assertSuccessful();
      
    }   

    /** @test */
    function content_type_header_must_be_present_in_responses(){
        
        $this ->get('test_route',[
            'accept' => 'application/vnd.api+json'
        ])->assertHeader('content-type','application/vnd.api+json');

        $this ->post('test_route',[],[
            'accept' => 'application/vnd.api+json',
            'content-type' => 'application/vnd.api+json',
        ])->assertHeader('content-type','application/vnd.api+json');

        $this ->patch('test_route',[],[
            'accept' => 'application/vnd.api+json',
            'content-type' => 'application/vnd.api+json',
        ])->assertHeader('content-type','application/vnd.api+json');
    }

    /** @test */

    function content_type_header_must_not_be_present_in_empty_responses(){
        Route::any('empty_response',fn() => response()->noContent())
            ->middleware(ValidateJsonApiHeaders::class);

        $this->get('empty_response',[
            'accept' => 'application/vnd.api+json'
        ])->assertHeaderMissing('content-type');

        $this->post('empty_response',[],[
            'accept' => 'application/vnd.api+json',
            'content-type' => 'application/vnd.api+json',
        ])->assertHeaderMissing('content-type');

        $this->patch('empty_response',[],[
            'accept' => 'application/vnd.api+json',
            'content-type' => 'application/vnd.api+json',
        ])->assertHeaderMissing('content-type');

        $this->delete('empty_response',[],[
            'accept' => 'application/vnd.api+json',
            'content-type' => 'application/vnd.api+json',
        ])->assertHeaderMissing('content-type');
    }
}
