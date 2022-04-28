<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\CategoryRequest;
use Backpack\CRUD\app\Http\Controllers\CrudController;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;

/**
 * Class CategoryCrudController
 * @package App\Http\Controllers\Admin
 * @property-read \Backpack\CRUD\app\Library\CrudPanel\CrudPanel $crud
 */
class CategoryCrudController extends CrudController
{
    use \Backpack\CRUD\app\Http\Controllers\Operations\ListOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\CreateOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\UpdateOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\DeleteOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\ShowOperation;

    /**
     * Configure the CrudPanel object. Apply settings to all operations.
     *
     * @return void
     */
    public function setup()
    {
        CRUD::setModel(\App\Models\Category::class);
        CRUD::setRoute(config('backpack.base.route_prefix') . '/category');
        CRUD::setEntityNameStrings('Kategori', 'Kategoriler');
    }

    /**
     * Define what happens when the List operation is loaded.
     *
     * @see  https://backpackforlaravel.com/docs/crud-operation-list-entries
     * @return void
     */
    protected function setupListOperation()
    {
        $this->crud->addColumns([
            [
                'name' => 'name',
                'label' => 'Adı',
                'type' => 'text'
            ],
            [
                'name' => 'active',
                'label' => 'Aktif',
                'type' => 'boolean',
                'wrapper' => [
                    'element' => 'span',
                    'class'   => function ($crud, $column, $entry, $related_key) {
                        return $column['value'] ? 'badge badge-success' : 'badge badge-default';
                    },
                ]
            ]
        ]);

        $this->crud->addClause('where', 'company_id', '=', backpack_user()->company_id);

        /**
         * Columns can be defined using the fluent syntax or array syntax:
         * - CRUD::column('price')->type('number');
         * - CRUD::addColumn(['name' => 'price', 'type' => 'number']);
         */
    }

    protected function setupShowOperation()
    {
        $this->crud->addColumns([
            [
                'label'     => 'Üst Kategori', // Table column heading
                'type'      => 'select',
                'name'      => 'select', // the column that contains the ID of that connected entity;
                'entity'    => 'parent', // the method that defines the relationship in your Model
                'attribute' => 'name', // foreign key attribute that is shown to user
                'model'     => "\App\Models\Category", // foreign key model
                'wrapper'   => [
                    'href' => function ($crud, $column, $entry, $related_key) {
                        return backpack_url('category/'.$related_key.'/show');
                    },
                ]
            ],
            [
                'name' => 'image',
                'label' => 'Görsel',
                'type' => 'image'
            ],
            [
                'name' => 'name',
                'type' => 'text',
                'label' => 'Adı'
            ],
            [
                'name' => 'description',
                'type' => 'text',
                'label' => 'Açıklama'
            ],
            [
                'name' => 'active',
                'label' => 'Aktif',
                'type' => 'boolean',
                'wrapper' => [
                    'element' => 'span',
                    'class'   => function ($crud, $column, $entry, $related_key) {
                        return $column['value'] ? 'badge badge-success' : 'badge badge-default';
                    },
                ]
            ],
            [
                'name' => 'products',
                'type' => 'relationship_count',
                'suffix' => '',
                'label' => 'Toplam Ürün Sayısı'
            ],
            [
                'name' => 'created_at',
                'type' => 'datetime',
                'label' => 'Oluşturulma Tarihi'
            ],
            [
                'name' => 'updated_at',
                'type' => 'datetime',
                'label' => 'Güncellenme Tarihi'
            ]
        ]);

        $this->crud->setTitle('Kategori Önizlemesi', 'show');
        $this->crud->setHeading('Kategori Önizlemesi', 'show');
        $this->crud->setSubheading('');
    }

    /**
     * Define what happens when the Create operation is loaded.
     *
     * @see https://backpackforlaravel.com/docs/crud-operation-create
     * @return void
     */
    protected function setupCreateOperation()
    {
        CRUD::setValidation(CategoryRequest::class);

        $this->crud->addFields([
            [
                'name' => 'company_id',
                'type' => 'hidden',
                'value' => backpack_user()->company_id
            ],
            [
                'name' => 'name',
                'label' => 'Adı',
                'type' => 'text'
            ],
            /*[
                'name' => 'slug',
                'label' => 'Seo',
                'type' => 'text'
            ],*/
            [
                'name' => 'description',
                'label' => 'Açıklama',
                'type' => 'textarea'
            ],
            [
                'name'      => 'image',
                'label'     => 'Görsel',
                'type'      => 'upload',
                'upload'    => true,
                'disk'      => 'public', // if you store files in the /public folder, please omit this; if you store them in /storage or S3, please specify it;
                // optional:
                //'temporary' => 10 // if using a service, such as S3, that requires you to make temporary URLs this will make a URL that is valid for the number of minutes specified
            ],
            [
                'name' => 'active',
                'type' => 'toggle',
                'label' => 'Aktif',
                'default' => true
            ]
        ]);

        if(backpack_user()->company->product_style === "1") {
            $this->crud->addField(
            [
                'type' => 'select', //https://github.com/Laravel-Backpack/CRUD/issues/502
                'name' => 'parent_id',
                'label' => 'Üst Kategori',
                'entity' => 'parent',
                'attribute' => 'name',
                'model' => '\App\Models\Category',
                'hint' => 'Bir kategorinin alt kategorisi olarak tanımlayabilirsiniz',
                'options' => (function ($query) {
                    return $query
                        ->orderBy('name', 'ASC')
                        ->where('active', 1)
                        ->where('company_id', backpack_user()->company_id)
                        ->get();
                })
            ])->afterField('name');
        }

        $this->crud->setTitle('Kategori Ekle', 'create');
        $this->crud->setHeading('Kategori Ekle', 'create');
        $this->crud->setSubheading('');


        /**
         * Fields can be defined using the fluent syntax or array syntax:
         * - CRUD::field('price')->type('number');
         * - CRUD::addField(['name' => 'price', 'type' => 'number']));
         */
    }

    /**
     * Define what happens when the Update operation is loaded.
     *
     * @see https://backpackforlaravel.com/docs/crud-operation-update
     * @return void
     */
    protected function setupUpdateOperation()
    {
        $this->setupCreateOperation();

        $this->crud->setTitle('Kategori Düzenle');
        $this->crud->setHeading('Kategori Düzenle');
        $this->crud->setSubheading('');
    }
}
