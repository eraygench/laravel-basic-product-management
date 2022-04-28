<?php

namespace App\Models;

use Backpack\CRUD\app\Models\Traits\CrudTrait;
use Backpack\CRUD\app\Models\Traits\SpatieTranslatable\HasTranslations;
use Cocur\Slugify\Slugify;
use Cviebrock\EloquentSluggable\Sluggable;
use Cviebrock\EloquentSluggable\SluggableScopeHelpers;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class Category extends Model
{
    //use HasTranslations;
    use CrudTrait;
    use Sluggable, SluggableScopeHelpers;
    use HasFactory, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'parent_id',
        'company_id',
        'name',
        'description',
        'slug',
        'image',
        'active',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'parent_id' => 'integer',
        'company_id' => 'integer',
        'active' => 'boolean',
    ];

    //protected $translatable = ['name', 'description'];

    public function sluggable()
    {
        return [
            'slug' => [
                'source' => 'name'
            ],
        ];
    }

    /**
     * @param \Cocur\Slugify\Slugify $engine
     * @param string $attribute
     * @return \Cocur\Slugify\Slugify
     */
    public function customizeSlugEngine(Slugify $engine, string $attribute): Slugify
    {
        $engine->addRules([
            'ı' => 'i', 'ğ' => 'g', 'ü' => 'u', 'ş' => 's', 'ö' => 'o', 'ç' => 'c',
            'I' => 'i', 'Ğ' => 'g', 'Ü' => 'u', 'Ş' => 's', 'Ö' => 'o', 'Ç' => 'c'
        ]);

        return $engine;
    }

    public function scopeWithUniqueSlugConstraints(
        Builder $query,
        Model $model,
        string $attribute,
        array $config,
        string $slug
    ): Builder
    {
        return $query->where('company_id', backpack_user()->company_id);
    }

    public static function boot()
    {
        parent::boot();
        static::deleted(function($obj) {
            Storage::disk('public')->delete($obj->image);
            DB::table('products')->where('category_id', $obj->id)->update(['category_id' => null]);
        });
    }
    /**
     * Set the user's first name.
     *
     * @param  string  $value
     * @return void
     */
    public function setImageAttribute($value)
    {
        $attribute_name = "image";
        $disk = "public"; //config('backpack.base.root_disk_name');
        $destination_path = "uploads/" . backpack_user()->company_id;

        $this->uploadFileToDisk($value, $attribute_name, $disk, $destination_path);

        // return $this->attributes[{$attribute_name}]; // uncomment if this is a translatable field
    }

    public function getImage()
    {
        return asset($this->image != null && file_exists(public_path($this->image)) ? $this->image : 'uploads/blank.jpg');
    }

    public function products()
    {
        return $this->hasMany(Product::class);
    }

    public function childrens()
    {
        return $this->hasMany(Category::class, 'parent_id', 'id')
            ->when(
                (!backpack_auth()->check() || (backpack_user()->company_id != $this->company_id)),
                function ($query) {
                    $query->where('active', 1)
                        ->whereRaw('(select count(*) from products as p where p.active=1 and p.category_id=categories.id) > 0');
                }
            );
    }

    public function parent()
    {
        return $this->belongsTo(Category::class);
    }

    public function company()
    {
        return $this->belongsTo(Company::class);
    }
}
