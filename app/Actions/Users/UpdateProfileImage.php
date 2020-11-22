<?php

namespace App\Actions\Users;

use App\Aggregates\Users\UserProfileAggregate;
use Lorisleiva\Actions\Action;

class UpdateProfileImage extends Action
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
     *
     * @return mixed
     */
    public function handle()
    {
        $data = $this->attributes;

        if(empty($data['profile_image']) || is_null($data['profile_image']))
        {
            \Alert::warning('An Image Is Required!')->flash();
            return redirect()->back()->withInput();
        }
        else
        {
            UserProfileAggregate::retrieve(backpack_user()->id)
                ->updateProfileImage($data['profile_image'])
                ->persist();

            session()->put('profile_image', $data['profile_image']);

            \Alert::success('Image saved! Looking sharp! Your pic will replace the gravatar at the top bar! The process may take a moment.')->flash();
            return redirect()->back();
        }
    }
}
