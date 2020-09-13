<?php

namespace AnchorCMS\Http\Controllers\Admin;

use AnchorCMS\Abilities;
use AnchorCMS\Clients;
use AnchorCMS\Permissions;
use AnchorCMS\Roles;
use Backpack\CRUD\CrudPanel;
use Prologue\Alerts\Facades\Alert;
use Silber\Bouncer\BouncerFacade as Bouncer;
use Backpack\CRUD\app\Http\Controllers\CrudController;
use AnchorCMS\Http\Requests\RolesRequest as StoreRequest;
use AnchorCMS\Http\Requests\RolesRequest as UpdateRequest;

/**
 * Class RolesCrudController
 * @package App\Http\Controllers\Admin
 * @property-read CrudPanel $crud
 */
class RolesCrudController extends CrudController
{

    public function setup()
    {
        $this->data['page'] = 'crud-roles';
        /*
        |--------------------------------------------------------------------------
        | CrudPanel Basic Information
        |--------------------------------------------------------------------------
        */
        $this->crud->setModel('AnchorCMS\Roles');
        $this->crud->setRoute(config('backpack.base.route_prefix') . '/crud-roles');
        $this->crud->setEntityNameStrings('roles', 'roles');

        if(backpack_user()->isHostUser())
        {
            if(session()->has('active_client'))
            {
                $this->crud->addClause('where', 'client_id', '=', session()->get('active_client'));
            }
        }
        else
        {
            if(backpack_user()->can('create-roles'))
            {
                $this->crud->addClause('where', 'client_id', '=', backpack_user()->client_id);
            }
            else
            {
                $this->crud->hasAccessOrFail('');
            }
        }
        /*
        |--------------------------------------------------------------------------
        | CrudPanel Configuration
        |--------------------------------------------------------------------------
        */
        $name = [
            'name' => 'name', // the db column name (attribute name)
            'label' => "Role Name", // the human-readable label for it
            'type' => 'text' // the kind of column to show
        ];
        $title = [
            'name' => 'title', // the db column name (attribute name)
            'label' => "Role Title", // the human-readable label for it
            'type' => 'text' // the kind of column to show
        ];

        $client = [
            'name' => 'client.name',
            'label' => 'Client',
            'type' => 'text'
        ];

        $add_role_client_select = [
            'name' => 'client_id',
            'label' => 'Assign a Client',
            'type' => 'select2_from_array',
            'options' => Clients::getAllClientsDropList()
        ];

        $route = \Route::current()->uri();
        $mode = 'edit';
        if(strpos($route,'create') !== false)
        {
            $mode = 'create';
        }

        $abilities_box = [
            'name' => 'assignable_abilities',
            'label' => 'Assign Abilities',
            'type' => 'custom_html',
            'value' => "
                <label>Assign Abilities</label>
                <role-ability-assign
                    mode='{$mode}'
                ></role-ability-assign>
            ",
        ];

        if($mode == 'edit')
        {
            $add_role_client_select['attributes'] = [];
            $add_role_client_select['attributes']['disabled'] = 'disabled';
        }

        $column_defs = [$name, $title, $client];
        $edit_create_defs = [$name, $title ];
        $this->crud->addColumns($column_defs);
        $this->crud->addFields($edit_create_defs, 'both');

        $create_defs = [$add_role_client_select, $abilities_box];
        $this->crud->addFields($create_defs, 'create');
        $this->crud->addFields($create_defs, 'edit');
        // add asterisk for fields that are required in RolesRequest

        $this->crud->setRequiredFields(StoreRequest::class, 'create');
        $this->crud->setRequiredFields(UpdateRequest::class, 'edit');
    }

    public function store(StoreRequest $request, Roles $role_model)
    {
        // your additional operations before save here
        //$redirect_location = parent::storeCrud();
        $new_role = Bouncer::role()->firstOrCreate([
            'name' => $request->all()['name'],
            'title' => $request->all()['title']
        ]);

        if($new_role)
        {
            $new_role->client_id = $request->all()['client_id'];
            $new_role->save();

            $requested_abilities = explode(',', $request->all()['abilities']);

            if(count($requested_abilities) == 1 && empty($requested_abilities[0]))
            {
                $requested_abilities[0] = $request->all()['abilities'];
            }

            foreach ($requested_abilities as $idx => $ab)
            {
                $requested_abilities[$ab] = $ab;
                unset($requested_abilities[$idx]);
            }

            $role = $request->all()['name'];
            $abilities = $role_model->getAssignedAbilities($role);
            if(count($abilities) > 0)
            {
                // retract any abilities not in $requested_abilities
                foreach ($abilities as $ability)
                {
                    if(!array_key_exists($ability['name'], $requested_abilities))
                    {
                        Bouncer::disallow($role)->to($ability['name']);
                    }
                }

                foreach($requested_abilities as $req_ability)
                {
                    Bouncer::allow($role)->to($req_ability);
                }
            }

            Alert::success(trans('backpack::crud.insert_success'))->flash();
        }
        else
        {
            Alert::error(trans('backpack::crud.insert_fail'))->flash();
        }
        // your additional operations after save here
        // use $this->data['entry'] or $this->crud->entry
        // show a success message

        return redirect('/crud-roles');
    }

    public function update(UpdateRequest $request, Roles $role_model)
    {
        // your additional operations before save here
        //$redirect_location = parent::updateCrud($request);
        if(Bouncer::is(backpack_user())->a('god', 'admin'))
        {
            $requested_abilities = explode(',', $request->all()['abilities']);

            if(count($requested_abilities) == 1 && empty($requested_abilities[0]))
            {
                $requested_abilities[0] = $request->all()['abilities'];
            }

            foreach ($requested_abilities as $idx => $ab)
            {
                $requested_abilities[$ab] = $ab;
                unset($requested_abilities[$idx]);
            }

            $role_id = $request->all()['id'];
            $role = $role_model->find($role_id);
            $abilities = $role_model->getAssignedAbilities($role->name, $role->client_id);

            if(count($abilities) > 0)
            {
                // retract any abilities not in $requested_abilities
                foreach ($abilities as $ability)
                {
                    if(!array_key_exists($ability['id'], $requested_abilities))
                    {
                        $permission = Permissions::whereAbilityId($ability['id'])
                            ->whereEntityId($role->id)
                            ->first();

                        if(!is_null($permission))
                        {
                            $permission->delete();
                        }
                        //Bouncer::disallow($role->name)->to($ability);
                    }
                }
            }

            foreach($requested_abilities as $req_ability)
            {
                if(!is_null($req_ability))
                {
                    $ab_record = Abilities::find($req_ability);
                    //$ab_record = Abilities::whereName($req_ability)->whereClientId(backpack_user()->client_id)->first();

                    if(!is_null($ab_record))
                    {
                        if(!is_null($ab_record->entity_id))
                        {
                            $model = new $ab_record->entity_type;
                            $model = $model->find($ab_record->entity_id);

                            if(!is_null($model))
                            {
                                $permission = Permissions::whereAbilityId($ab_record->id)
                                    ->whereEntityId($role->id)
                                    ->first();

                                if(is_null($permission))
                                {
                                    Bouncer::allow($role)->to($ab_record, $model);

                                    $permission = Permissions::whereAbilityId($ab_record->id)
                                        ->whereEntityId($role->id)
                                        ->first();

                                    if(!is_null($permission))
                                    {
                                        $permission->entity_type = 'roles';
                                        $permission->save();
                                    }
                                }
                            }
                        }
                        else
                        {
                            $permission = Permissions::whereAbilityId($ab_record->id)
                                ->whereEntityId($role->id)
                                ->first();

                            if(is_null($permission))
                            {
                                Bouncer::allow($role)->to($ab_record);

                                $permission = Permissions::whereAbilityId($ab_record->id)
                                    ->whereEntityId($role->id)
                                    ->first();

                                if(!is_null($permission))
                                {
                                    $permission->entity_type = 'roles';
                                    $permission->save();
                                }
                            }
                        }
                    }
                }
            }

            Alert::success(trans('backpack::crud.insert_success'))->flash();
        }
        else
        {
            Alert::error('Access Denied. You do not have permission to update roles.')->flash();
        }

        // your additional operations after save here
        // use $this->data['entry'] or $this->crud->entry
        return redirect('/crud-roles');
    }
}
