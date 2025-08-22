<?php

namespace Litepie\Users\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;

class UserCollection extends ResourceCollection
{
    /**
     * Transform the resource collection into an array.
     */
    public function toArray(Request $request): array
    {
        return [
            'data' => $this->collection,
            'meta' => [
                'total_users' => $this->collection->count(),
                'active_users' => $this->collection->where('status', 'active')->count(),
                'pending_users' => $this->collection->where('status', 'pending')->count(),
                'suspended_users' => $this->collection->where('status', 'suspended')->count(),
            ],
        ];
    }
}
