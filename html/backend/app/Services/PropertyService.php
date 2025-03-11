<?php

namespace App\Services;

use App\Models\Property;
use Illuminate\Database\Eloquent\Builder;

class PropertyService
{
    public function get(array $input)
    {
        $title = $input['title'] ?? '';
        $order_by = $input['order_by'] ?? '';
        $order = $input['order'] ?? '';

        $data = Property::query()
            ->when($title, function (Builder $query) use ($title) { // where
                $query->where('title', 'like', "%$title%")
                    ->orWhereHas('location', function (Builder $q) use ($title) {
                        $q->where('country', 'like', "%$title%")
                            ->orWhere('province', 'like', "%$title%")
                            ->orWhere('street', 'like', "%$title%");
                    });
            })
            ->when($order_by && $order, function (Builder $query) use ($order_by, $order) {
                $query->orderBy($order_by, $order);
            })
            ->with('location', 'photo')
            ->paginate($input['per_page'] ?? 25);

        return $data;
    }
}
