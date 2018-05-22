<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\User;
use App\Http\Requests\StoreUsers;
use App\Http\Requests\UpdateUsersRequest;
use App\UserInfo;
use Session;

class UsersController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param \Illuminate\Http\Request $request request
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $nameUser = $request->input('name_user_search');
        $users = User::search($nameUser)->with('userInfo')->paginate(config('define.number_pages'));
        return view('admin.user.index', compact('users'));
    }
    /**
     * Show the form for creating a new data.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.user.create');
    }
    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request request
     *
     * @return \Illuminate\Http\Response
     */
    public function store(StoreUsers $request)
    {
        User::create([
            'name'=>$request->name,
            'email'=>$request->email,
            'password'=>bcrypt($request->password),
        ]);
        return redirect()->route('user.index');
    }
    
    /**
     * Show the form for editing the specified resource.
     *
     * @param User $user User object
     *
     * @return \Illuminate\Http\Response
    */
    public function edit(User $user)
    {
        $user->load('userInfo');
        return view('admin.user.edit')->with('user', $user);
    }

    /**
    * Update the specified resource in storage.
    *
    * @param \Illuminate\Http\Request $request request
    * @param User                     $user    object
    *
    * @return \Illuminate\Http\Response
    */
    public function update(UpdateUsersRequest $request, User $user)
    {
        $user->update($request->only(['name']));
        if ($request->hasFile('avatar')) {
            $image = $request->file('avatar');
            $nameNew = time().'_'.md5(rand(0, 99999)).'.'.$image->getClientOriginalExtension();
            $image->move(public_path(config('define.images_path_users')), $nameNew);
            UserInfo::where('user_id', $user->id)->update([
                'address' => $request->address,
                'phone' => $request->phone,
                'avatar' => $nameNew
            ]);
        } else {
            UserInfo::where('user_id', $user->id)->update([
                'address' => $request->address,
                'phone' => $request->phone,
            ]);
        }
        Session::flash('message', trans('message.user.update'));
        return redirect()->route('user.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param User $user User object
     *
     * @return \Illuminate\Http\Response
    */
    public function destroy(User $user)
    {
        if ($user->id == User::ROOT_ADMIN) {
            flash(trans('user.admin.message.cancel'))->error();
            return redirect()->route('user.index');
        }
        $user->delete();
        flash(trans('user.admin.message.success'))->success();
        return redirect()->route('user.index');
    }
}
