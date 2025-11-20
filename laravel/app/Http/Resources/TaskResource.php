<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Carbon;

class TaskResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id'           => $this->id,
            'title'        => $this->title,
            'description'  => $this->description ?? '',
            'due_date'     => $this->due_date
                ? Carbon::parse($this->due_date)->format('Y-m-d H:i:s')
                : null,
            'assigned_user' => $this->assignedUser ? [
                'id' => $this->assignedUser->id,
                'name' => $this->assignedUser->name,
                'email' => $this->assignedUser->email,
            ] : null,
            'created_by' => $this->created_by,
            'created_by_user' => $this->createdByUser ? [
                'id' => $this->createdByUser->id,
                'name' => $this->createdByUser->name,
                'email' => $this->createdByUser->email,
            ] : null,
            'is_completed' => (bool) $this->is_completed,
            'created_at'   => $this->created_at?->toISOString(),
            'updated_at'   => $this->updated_at?->toISOString(),
        ];
    }

}
