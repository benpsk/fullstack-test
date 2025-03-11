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
    $province = $input['province'] ?? '';

    // TODO: should be more simpler
    $data = Property::query()
        ->when($province, function (Builder $query, string $province) {
            $query->whereRelation('location', 'province', $province); // where
        })
        ->when($title, function (Builder $query) use ($title) {
            $query->where(function (Builder $q) use ($title) { // and to the $province
                $q->where('title', 'like', "%$title%")
                    ->orWhereHas('location', function (Builder $q) use ($title) {
                        $q->where('country', 'like', "%$title%")
                            ->orWhere('province', 'like', "%$title%")
                            ->orWhere('street', 'like', "%$title%");
                    });
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
