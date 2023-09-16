<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class GlobalResources extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function __construct($status, $message, $resource, $thirdparty)
    {

        parent::__construct($resource);
        $this->status  = $status;
        $this->message = $message;
        $this->thirdparty = $thirdparty;
    }

   public function toArray($request)
    {
        return [
            'success'   => $this->status,
            'message'   => $this->message,
            'data'      => $this->resource,
            'thirdparty'=> $this->thirdparty,
        ];
    }
}
