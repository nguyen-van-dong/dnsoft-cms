<?php

namespace Module\Cms\Http\Resources;

use DnSoft\Core\Utils\Core;
use Illuminate\Http\Resources\Json\JsonResource;

class PostResource extends JsonResource
{
  /**
   * Transform the resource into an array.
   *
   * @param  \Illuminate\Http\Request  $request
   * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
   */
  public function toArray($request)
  {
    return [
      'id' => $this->id,
      'name' => $this->name,
      'description' => $this->description,
      'url' => $this->url,
      'content' => $this->content,
      'image' => $this->thumbnail->url,
      'author' => $this->author->display_name ? $this->author->display_name : $this->author->name,
      'comments_count' => $this->comments->count(),
      'created_at' => $this->created_at->toFormattedDateString(),
      'count_like' => $this->like,
      'viewed' => $this->view_count,
      'slug' => Core::buildSlug($this->url),
    ];
  }
}