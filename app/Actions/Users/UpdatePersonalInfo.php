<?php

namespace App\Actions\Users;

use App\Aggregates\Users\UserProfileAggregate;
use App\Models\UserDetails;
use Lorisleiva\Actions\Action;

class UpdatePersonalInfo extends Action
{
    /**
     * Determine if the user is authorized to make this action.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the action.
     *
     * @return array
     */
    public function rules()
    {
        return [];
    }

    /**
     * Execute the action and return a result.
     * @param UserDetails $details
     * @return mixed
     */
    public function handle()
    {
        $data = $this->attributes;

        UserProfileAggregate::retrieve(backpack_user()->id)
            ->storePersonalInfo($data)
            ->persist();

        if($new_user = session()->has('needs_registration') && session()->get('needs_registration'))
        {
            session()->forget('needs_registration');
            \Alert::success('Your update was successful!')->flash();
            return redirect('/access/dashboard');
        }
        else
        {
            \Alert::success('Your update was successful!')->flash();
            return redirect()->back()->withInput();
        }
    }
}
