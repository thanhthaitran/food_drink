<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Http\Response;
use App\Http\Controllers\Api\ApiController;
use App\Http\Requests\Api\UpdateProfileRequest;
use App\User;
use App\UserInfo;
use Illuminate\Support\Facades\Auth;
use App\ShippingAddress;

class ProfileController extends ApiController
{
     /**
     * Get user details
     *
     * @return json user, userInfo
     */
    public function show()
    {
        $user = Auth::user();
        $data['user'] = $user->load('userInfo', 'shippingAddresses');
        return $this->successResponse($data, Response::HTTP_OK);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param App\Http\Requests\UpdateProfileRequest $request request
     *
     * @return \Illuminate\Http\Response
    */
    public function update(UpdateProfileRequest $request)
    {
        $user = Auth::user();
        try {
            $user->update([
                'name'=>$request->name,
                'password' => $request->password ? bcrypt($request->password) : $user->password,
            ]);
            if ($request->hasFile('avatar')) {
                $image = $request->file('avatar');
                $newName = time().'_'.md5(rand(0, 99999)).'.'.$image->getClientOriginalExtension();
                $image->move(public_path(config('define.images_path_users')), $newName);
                UserInfo::where('user_id', $user->id)->update([
                        'user_id' =>$user->id,
                        'address' => $request->address,
                        'phone' => $request->phone,
                        'avatar' => $newName,
                    ]);
            } else {
                UserInfo::where('user_id', $user->id)->update([
                        'user_id' =>$user->id,
                        'address' => $request->address,
                        'phone' => $request->phone,
                    ]);
            }
            foreach ($user->shippingAddresses as $shipping) {
                if ($shipping->is_default == ShippingAddress::ADDRESS_DEFAULT) {
                    $user->shippingAddresses()->update([
                        'is_default' => ShippingAddress::ADDRESS,
                    ]);
                }
            }
            $user->shippingAddresses()->where('id', $request->shipping_id)->update([
                'is_default' => ShippingAddress::ADDRESS_DEFAULT,
            ]);
            $data = $user->load('userInfo', 'shippingAddresses');
            return $this->successResponse($data, Response::HTTP_OK);
        } catch (Exception $e) {
            return $this->errorResponse(__('api.update.error.update'), Response::HTTP_UNPROCESSABLE_ENTITY);
        }
    }
}
