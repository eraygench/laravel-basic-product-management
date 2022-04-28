<?php

namespace App\Models;

use Backpack\CRUD\app\Models\Traits\CrudTrait;
use Backpack\CRUD\app\Models\Traits\HasUploadFields;
//use Backpack\CRUD\app\Models\Traits\SpatieTranslatable\HasTranslations;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Storage;

class Product extends Model
{
    use HasUploadFields;
    use CrudTrait;
    //use HasTranslations;
    use HasFactory, SoftDeletes;

    //protected $table = 'products';
    //protected $primaryKey = 'id';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'category_id',
        'company_id',
        'name',
        'description',
        'image',
        'price',
        'active',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'category_id' => 'integer',
        'company_id' => 'integer',
        'active' => 'boolean',
        'price' => 'float'
    ];

    //protected $translatable = ['name', 'description'];


    public static function boot()
    {
        parent::boot();
        static::deleted(function($obj) {
            Storage::disk('public')->delete($obj->image);
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
        $destination_path = "uploads/" . backpack_user()->company_id . ($this->category_id != null ? "/".$this->category_id : "");

        /*Validator::make(request()->all(), [
            'image' => 'image',
        ])->validate();*/

        //dd($value, $attribute_name, $disk, $destination_path, request()->hasFile("image"), $this->image);

        $this->uploadFileToDisk($value, $attribute_name, $disk, $destination_path);

        /*if ($value == null) {
            // delete the image from disk
            Storage::disk($disk)->delete($this->{$attribute_name});

            // set null in the database column
            $this->attributes[$attribute_name] = null;
        }*/

        // if a base64 was sent, store it in the db
        /*if (Str::startsWith($value, 'data:image'))
        {
            // 0. Make the image
            $image = Image::make($value)->encode('jpg', 90);

            // 1. Generate a filename.
            $filename = md5($value.time()).'.jpg';

            // 2. Store the image on disk.
            Storage::disk($disk)->put($destination_path.'/'.$filename, $image->stream());

            // 3. Delete the previous image, if there was one.
            Storage::disk($disk)->delete($this->{$attribute_name});

            // 4. Save the public path to the database
            // but first, remove "public/" from the path, since we're pointing to it
            // from the root folder; that way, what gets saved in the db
            // is the public URL (everything that comes after the domain name)
            $public_destination_path = Str::replaceFirst('public/', '', $destination_path);
            $this->attributes[$attribute_name] = $public_destination_path.'/'.$filename;
        }
        else*/

        // return $this->attributes[{$attribute_name}]; // uncomment if this is a translatable field
    }

    public function getImage()
    {
        return asset($this->image != null && file_exists(public_path($this->image)) ? $this->image : 'uploads/blank.jpg');
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function company()
    {
        return $this->belongsTo(Company::class);
    }
}
