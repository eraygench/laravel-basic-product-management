<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\CompanyRequest;
use App\Models\Company;
use Backpack\CRUD\app\Http\Controllers\CrudController;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;
use SimpleSoftwareIO\QrCode\Facades\QrCode;


/**
 * Class CompanyCrudController
 * @package App\Http\Controllers\Admin
 * @property-read \Backpack\CRUD\app\Library\CrudPanel\CrudPanel $crud
 */
class CompanyCrudController extends CrudController
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
        CRUD::setModel(\App\Models\Company::class);
        CRUD::setRoute(config('backpack.base.route_prefix') . '/company');
        CRUD::setEntityNameStrings('Şirket', 'Şirketler');
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
            /*[
                'name' => 'name',
                'label' => 'Name',
                'type' => 'closure',
                'function' => function($company) {
                    return '<a href="'.url($company->slug).'" target="_blank">'.$company->name.'</a>';
                },
                'escaped' => false
            ],*/
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

        $this->crud->addButtonFromView('line', 'showQrCodePage', 'company_showQrCodePage', 'beginning');



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
                'type' => 'image',
                'label' => 'Görsel'
            ],
            [
                'name' => 'url',
                'label' => 'Adı',
                'type' => 'closure',
                'function' => function($company) {
                    return '<a href="http://'.$company->slug.'.atiasoftmenu.com" target="_blank">'.$company->name.'</a>';
                },
                'escaped' => false
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
                'name' => 'product_style',
                'type' => 'text',
                'label' => 'Menü Tasarımı'
            ],
            [
                'name' => 'theme',
                'label' => 'Kapalı Tema',
                'type' => 'boolean',
                'wrapper' => [
                    'element' => 'span',
                    'class'   => function ($crud, $column, $entry, $related_key) {
                        return $column['value'] ? 'badge badge-success' : 'badge badge-default';
                    },
                ]
            ],
            [
                'name' => 'categories',
                'type' => 'relationship_count',
                'label' => 'Toplam Kategori Sayısı',
                'suffix' => '',
            ],
            [
                'name' => 'products',
                'type' => 'relationship_count',
                'label' => 'Toplam Ürün Sayısı',
                'suffix' => ''
            ],
            [
                'name' => 'users',
                'type' => 'relationship_count',
                'label'=> 'Toplam Kullanıcı Sayısı',
                'suffix' => ''
            ],
            [
                'name' => 'created_at',
                'label' => 'Oluşturulma Tarihi',
                'type' => 'datetime'
            ],
            [
                'name' => 'updated_at',
                'label' => 'Güncellenme Tarihi',
                'type' => 'datetime'
            ],
            /*[
                'name' => 'qr',
                'label' => 'QR (100px)',
                'type' => 'closure',
                'tab' => 'qr',
                'function' => (function($company)
                {
                    return join("<br>", array_map(function ($item) use ($company) {
                        return '<img target="_blank" src="data:image/png;base64,'.base64_encode(QrCode::format('png')->size(100)->errorCorrection('M')->merge(asset($item), .4, true)->generate(route('company', ['slug' => $company->slug]))).'">';
                    }, ['images/qr-logo-1.png', 'images/qr-logo-2.png', 'images/qr-logo-4.png', 'images/qr-logo-3.png', 'images/qr-logo-5.png']));
                }),
                'escaped' => false,
                'priority' => 101,
            ],
            [
                'name' => 'qr2',
                'label' => 'QR (200px)',
                'type' => 'closure',
                'tab' => 'qr',
                'function' => (function($company)
                {
                    return join("<br>", array_map(function ($item) use ($company) {
                        return '<img target="_blank" src="data:image/png;base64,'.base64_encode(QrCode::format('png')->size(200)->errorCorrection('M')->merge(asset($item), .4, true)->generate(route('company', ['slug' => $company->slug]))).'">';
                    }, ['images/qr-logo-1.png', 'images/qr-logo-2.png', 'images/qr-logo-4.png', 'images/qr-logo-3.png', 'images/qr-logo-5.png']));
                }),
                'escaped' => false,
                'priority' => 102,
            ],
            [
                'name' => 'qr3',
                'label' => 'QR (300px)',
                'type' => 'closure',
                'tab' => 'qr',
                'function' => (function($company)
                {
                    return join("<br>", array_map(function ($item) use ($company) {
                        return '<img target="_blank" src="data:image/png;base64,'.base64_encode(QrCode::format('png')->size(300)->errorCorrection('M')->merge(asset($item), .4, true)->generate(route('company', ['slug' => $company->slug]))).'">';
                    }, ['images/qr-logo-1.png', 'images/qr-logo-2.png', 'images/qr-logo-4.png', 'images/qr-logo-3.png', 'images/qr-logo-5.png']));
                }),
                'escaped' => false,
                'priority' => 103,
            ],*/
        ]);

        $this->crud->setTitle('Şirket Önizlemesi', 'show');
        $this->crud->setHeading('Şirket Önizlemesi', 'show');
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
        CRUD::setValidation(CompanyRequest::class);

        $this->crud->addFields([
            [
                'name' => 'name',
                'label' => 'Adı',
                'type' => 'text'
            ],
            [
                'name' => 'slug',
                'label' => 'Seo',
                'type' => 'text'
            ],
            [   // Upload
                'name'      => 'image',
                'label'     => 'Görsel',
                'type'      => 'upload',
                'upload'    => true,
                'disk'      => 'public', // if you store files in the /public folder, please omit this; if you store them in /storage or S3, please specify it;
                // optional:
                //'temporary' => 10 // if using a service, such as S3, that requires you to make temporary URLs this will make a URL that is valid for the number of minutes specified
            ],
            [
                'name' => 'product_style',
                'label' => 'Menü Tasarımı',
                'type' => 'select_from_array',
                'options'     => ['1' => '1', '2' => '2', '3' => '3'],
                'allows_null' => false,
                'default'     => '1'
            ],
            [
                'name' => 'theme',
                'type' => 'toggle',
                'label' => 'Kapalı Tema',
                'default' => true
            ],
            [
                'name' => 'active',
                'type' => 'toggle',
                'label' => 'Aktif',
                'default' => true
            ]
        ]);

        $this->crud->setTitle('Şirket Ekle', 'create');
        $this->crud->setHeading('Şirket Ekle', 'create');
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

        $this->crud->setTitle('Şirket Düzenle');
        $this->crud->setHeading('Şirket Düzenle');
        $this->crud->setSubheading('');
    }

    public function showQrCodePage($company, $size = 100)
    {
        $company = Company::findOrFail($company);
        return view('vendor.backpack.crud.company_qr_code', ['size' => $size, 'company' => $company]);
    }

    /*public function showSettings()
    {
        CRUD::setValidation(CompanyRequest::class);

        $company = Company::findOrFail(backpack_user()->company_id);

        return view('vendor.backpack.crud.edit_settings', ['company' => $company]);
    }*/
}
