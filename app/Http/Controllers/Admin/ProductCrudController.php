<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\ProductRequest;
use Backpack\CRUD\app\Http\Controllers\CrudController;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;

/**
 * Class ProductCrudController
 * @package App\Http\Controllers\Admin
 * @property-read \Backpack\CRUD\app\Library\CrudPanel\CrudPanel $crud
 */
class ProductCrudController extends CrudController
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
        CRUD::setModel(\App\Models\Product::class);
        CRUD::setRoute(config('backpack.base.route_prefix') . '/product');
        CRUD::setEntityNameStrings('Ürün', 'Ürünler');
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
                'name'  => 'name',
                'label' => 'Adı',
                'type'  => 'text'
            ],
            [
                'label'     => 'Kategori', // Table column heading
                'type'      => 'select',
                'name'      => 'select', // the column that contains the ID of that connected entity;
                'entity'    => 'category', // the method that defines the relationship in your Model
                'attribute' => 'name', // foreign key attribute that is shown to user
                'model'     => "\App\Models\Category", // foreign key model
                'wrapper'   => [
                    'href' => function ($crud, $column, $entry, $related_key) {
                        return backpack_url('category/'.$related_key.'/show');
                    },
                ]
            ],
            [
                'name'  => 'price',
                'label' => 'Fiyat',
                'type'  => 'number',
                'prefix'        => '₺',
                'decimals'      => 2,
                'dec_point'     => ',',
                'thousands_sep' => '.'
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

        //$this->crud->setOperationSetting('exportButtons', true);

        //$this->crud->addButtonFromView('top', 'import', 'import', 'end');

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
                'name' => 'image',
                'label' => 'Görsel',
                'type' => 'image'
            ],
            [
                'name'  => 'name',
                'label' => 'Adı',
                'type'  => 'text',
            ],
            [   // 1-n relationship
                'label'     => 'Kategori', // Table column heading
                'type'      => 'select',
                'name'      => 'select', // the column that contains the ID of that connected entity;
                'entity'    => 'category', // the method that defines the relationship in your Model
                'attribute' => 'name', // foreign key attribute that is shown to user
                'model'     => "\App\Models\Category", // foreign key model
                'wrapper'   => [
                    'href' => function ($crud, $column, $entry, $related_key) {
                        return backpack_url('category/'.$related_key.'/show');
                    },
                ],
            ],
            [
                'name'  => 'price',
                'label' => 'Fiyat',
                'type'  => 'number',
                'prefix'        => '₺',
                'decimals'      => 2,
                'dec_point'     => ',',
                'thousands_sep' => '.',
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

        $this->crud->setTitle('Ürün Önizlemesi', 'show');
        $this->crud->setHeading('Ürün Önizlemesi', 'show');
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
        CRUD::setValidation(ProductRequest::class);

        $this->crud->addFields([
            [
                'name' => 'company_id',
                'type' => 'hidden',
                'value' => backpack_user()->company_id
            ],
            [
                'label' => 'Kategori',
                'type' => 'select', //https://github.com/Laravel-Backpack/CRUD/issues/502
                'name' => 'category_id',
                'entity' => 'category',
                'attribute' => 'name',
                'model' => '\App\Models\Category',
                'options' => (function ($query) {
                    return $query
                        ->orderBy('name', 'ASC')
                        ->where('active', 1)
                        ->where('company_id', backpack_user()->company_id)
                        ->get();
                })
            ],
            [
                'name' => 'name',
                'label' => 'Adı',
                'type' => 'text',
            ],
            [
                'name' => 'description',
                'label' => 'Açıklama',
                'type' => 'textarea',
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
                'name' => 'price',
                'label' => 'Fiyat',
                'type' => 'number',
                'attributes' => ["step" => "any"]
            ],
            [
                'name' => 'active',
                'type' => 'toggle',
                'label' => 'Aktif',
                'default' => true
            ]
        ]);

        $this->crud->setTitle('Ürün Ekle', 'create');
        $this->crud->setHeading('Ürün Ekle', 'create');
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

        $this->crud->setTitle('Ürün Düzenle');
        $this->crud->setHeading('Ürün Düzenle');
        $this->crud->setSubheading('');
    }

    public function import()
    {
        //echo "test";
        // whatever you decide to do
    }
}
