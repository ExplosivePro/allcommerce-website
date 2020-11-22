<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\IconsSetRequest;
use Backpack\CRUD\app\Http\Controllers\CrudController;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;
use Backpack\ReviseOperation\ReviseOperation;
use Silber\Bouncer\BouncerFacade as Bouncer;

/**
 * Class IconsSetCrudController
 * @package App\Http\Controllers\Admin
 * @property-read \Backpack\CRUD\app\Library\CrudPanel\CrudPanel $crud
 */
class IconsSetCrudController extends CrudController
{
    use \Backpack\CRUD\app\Http\Controllers\Operations\ListOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\CreateOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\UpdateOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\DeleteOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\ShowOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\InlineCreateOperation;
    use ReviseOperation;

    /**
     * Configure the CrudPanel object. Apply settings to all operations.
     *
     * @return void
     */
    public function setup()
    {
        CRUD::setModel(\App\Models\Utility\IconsSet::class);
        CRUD::setRoute(config('backpack.base.route_prefix') . '/iconsset');
        CRUD::setEntityNameStrings('Icon', 'Icons');
    }

    /**
     * Define what happens when the List operation is loaded.
     *
     * @see  https://backpackforlaravel.com/docs/crud-operation-list-entries
     * @return void
     */
    protected function setupListOperation()
    {
        CRUD::column('')->type('closure')->function(function ($entry) {
            return '<i class="'.$entry->icon.'" title="'.$entry->name.'"></i>';
        });
        CRUD::column('icon')->type('text');
        CRUD::column('name')->type('text');
        CRUD::column('source')->type('text');

        if(!Bouncer::is(backpack_user())->an('admin'))
        {
            $this->crud->hasAccessOrFail('nope');
        }
    }

    /**
     * Define what happens when the Create operation is loaded.
     *
     * @see https://backpackforlaravel.com/docs/crud-operation-create
     * @return void
     */
    protected function setupCreateOperation()
    {
        CRUD::setValidation(IconsSetRequest::class);

        CRUD::field('icon')->type('view')->view('crud::fields.text-icons');
        CRUD::field('name')->type('text');
        CRUD::field('source')->type('select2_from_array')
            ->options([
                'font-awesome' => 'Font Awesome',
                'line-awesome' => 'Line Awesome'
            ]);

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
        if(!Bouncer::is(backpack_user())->an('admin'))
        {
            $this->crud->hasAccessOrFail('nope');
        }
        else
        {
            $this->setupCreateOperation();
        }
    }

    protected function setupInlineCreateOperation()
    {
        CRUD::field('icon')->type('text');
        CRUD::field('name')->type('text');
        CRUD::field('source')->type('select2_from_array')
            ->options([
                'font-awesome' => 'Font Awesome',
                'line-awesome' => 'Line Awesome'
            ]);
    }
}
