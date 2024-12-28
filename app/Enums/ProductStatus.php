<?php

namespace App\Enums;

enum ProductStatus: string
{
    case Pending = 'pending';
    case Active = 'active';
    case Inactive = 'inactive';
    case Rejected = 'rejected';
}

/*
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $fillable = ['name','body','status'];

    protected function casts(): array
    {
        return [
            'status' => ProductStatus::class,
        ];
    }
}
*/
/*
use App\Http\Controllers\Controller;

class ProductController extends Controller
{
    public function index()
    {
        $data = [
            'name' => 'Silver',
            'body' => 'This is best silver',
            'status' => ProductStatus::Active,
        ];
    }
}
*/
