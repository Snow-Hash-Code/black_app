<?php

namespace Tests\Feature\Articles;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use App\Models\Article;
use Tests\TestCase;

class UpdateArticleTest extends TestCase
{
    use RefreshDatabase;
  
     /** @test */
/** @test */
public function can_update_articles()
{
    $article = Article::factory()->create();

    $response = $this->patchJson(route('api.articles.update', $article), [
        'title' => 'Update articulo',
        'slug' => 'Update-articulo',
        'content' => 'Update del articulo'
    ])->dump()->assertOk();

    $article->refresh();

    $response->assertExactJson([
        'data' => [
            'type' => 'articles',
            'id' => (string) $article->getRouteKey(),
            'attributes' => [
                'title' => 'Update articulo',
                'slug' => 'Update-articulo',
                'content' => 'Update del articulo'
            ],
            'links' => [
                'self' => route('api.articles.show', $article)
            ]
        ]
    ]);
}

/** @test */
public function title_is_required()
{
    $article = Article::factory()->create();
    $this->patchJson(route('api.articles.update',$article), [
        'slug' => 'nuevo-articulo',
        'content' => 'Contenido del articulo'
    ])->assertJsonApiValidationErrors('title');       
}

 /** @test */
public function titile_must_be_at_least_4_characters()
{
    $article = Article::factory()->create();
    $this -> patchJson(route('api.articles.update',$article),[
            'title' => 'ji',
            'slug' => 'nuevo-articulo',
            'content' => 'Contenido del articulo'      
    ])->assertJsonApiValidationErrors('title');
}

/** @test */
public function slug_is_required()
{
    $article = Article::factory()->create();
    $this -> patchJson(route('api.articles.update',$article),[
        'title' => 'nuevo-articulo',
        'content' => 'Contenido del articulo'
        ])->assertJsonApiValidationErrors('slug');
}

/** @test */
public function content_is_required()
{
    $article = Article::factory()->create();
    $this -> patchJson(route('api.articles.update',$article),[
        'title' => 'nuevo-articulo',
        'slug' => 'Contenido del articulo'
        ])->assertJsonApiValidationErrors('content');
}

}
