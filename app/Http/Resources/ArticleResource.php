<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ArticleResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
        
                'type' => 'articles',
                'id' => (string)$this->resource->getRouteKey(),
                'attributes' => [
                    'title' => $this->resource->title,
                    'slug' => $this->resource->slug,
                    'content' => $this->resource->content,
                ],
                'links' => [
                    'self' => route('api.articles.show',$this->resource)
                ]
                ];
        
    }

    public function toResponse($request)
    {
        return parent::toResponse($request)->withHeaders([
            'Location' => route('api.articles.show',$this->resource)
        ]);
    }
}
