<?php

namespace App\Actions\Users;

use Illuminate\Support\Facades\Storage;
use Lorisleiva\Actions\Action;
use Silber\Bouncer\BouncerFacade as Bouncer;

class UploadProfileImage extends Action
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
        return [
            'uuid' => 'bail|required',
            'key' => 'bail|required',
            'bucket' => 'bail|required',
            'name' => 'bail|required',
            'content_type' => 'bail|required',
        ];
    }

    /**
     * Execute the action and return a result.
     *
     * @return mixed
     */
    public function handle()
    {
        $results = ['success' => false, 'message' => 'Invalid Request'];
        $code = 500;

        $data = $this->attributes;

        try
        {
            $img_file = Storage::disk('s3')->get($data['key']);
            $user_is_admin = Bouncer::is(backpack_user())->an('admin');

            if($user_is_admin)
            {
                $path = 'assets/admins/';
                // check for assets/admins/uuid directory or create
                if(in_array(backpack_user()->id, Storage::disk('s3')->directories($path)))
                {
                    Storage::disk('s3')->makeDirectory($path.backpack_user()->id); //creates directory
                }

                $path .= backpack_user()->id.'/';

                // check for assets/admins/uuid/profile-images or create
                if(in_array('profile-images', Storage::disk('s3')->directories($path)))
                {
                    Storage::disk('s3')->makeDirectory('clients/'.backpack_user()->client_id.'/profile-images'); //creates directory
                }

                $path .= 'profile-images/'.$data['name'];

                // Put the file there.
                Storage::disk('s3')->put($path, $img_file, 'public');

                $url = Storage::disk('s3')->url($path);

                Storage::disk('s3')->delete($data['key']);

                // Return the success url
                $results = ['success' => true, 'url' => $url];
                $code = 200;
            }
            else
            {
                // check for clients/uuid directory or create
                if(in_array(backpack_user()->client_id, Storage::disk('s3')->directories('clients')))
                {
                    Storage::disk('s3')->makeDirectory('clients/'.backpack_user()->client_id); //creates directory
                }

                // check for clients/uuid/profile-images or create
                if(in_array('profile-images', Storage::disk('s3')->directories('clients/'.backpack_user()->client_id)))
                {
                    Storage::disk('s3')->makeDirectory('clients/'.backpack_user()->client_id.'/profile-images'); //creates directory
                }

                // check for clients/uuid/profile-images/user_id or create
                if(in_array(backpack_user()->id, Storage::disk('s3')->directories('clients/'.backpack_user()->client_id.'/profile-images')))
                {
                    Storage::disk('s3')->makeDirectory('clients/'.backpack_user()->client_id.'/profile-images/'.backpack_user()->id); //creates directory
                }

                // Put the file there.
                $path = 'clients/'.backpack_user()->client_id.'/profile-images/'.backpack_user()->id.'/'.$data['name'];
                Storage::disk('s3')->put($path, $img_file, 'public');

                $url = Storage::disk('s3')->url($path);

                Storage::disk('s3')->delete($data['key']);

                // Return the success url
                $results = ['success' => true, 'url' => $url];
                $code = 200;
            }
        }
        catch(\Exception $e)
        {
            $code = 500;
            $results['message'] = 'Could Read Image File.';
        }

        return response($results, $code);
    }
}
