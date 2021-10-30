<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use Intervention\Image\Facades\Image;

class UserController extends Controller
{

    public function __construct()
    {
        $this->middleware(['permission:users-read'])->only('index');
        $this->middleware(['permission:users-create'])->only('create');
        $this->middleware(['permission:users-update'])->only('edit');
        $this->middleware(['permission:users-delete'])->only('destroy');
    }


    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        //$users=User::all();
        //$users = User::whereRoleIs('admin')->get();
        $users = User::whereRoleIs('admin')->where(function($q) use ($request){
            
            return $q->when($request->search,function($query)use($request){
                
                return $query->where('first_name','like','%'.$request->search.'%')->orWhere('last_name','like','%'.$request->search.'%');
            });

        })->latest()->paginate(5);;
        return view('dashboard.users.index')->with('users',$users);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('dashboard.users.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //dd($request->all());

        $request->validate([
            'first_name' => 'required',
            'last_name' => 'required',
            'email' => 'required|unique:users',
            'image' => 'image',
            'password' => 'required|confirmed',
            'permissions' => 'required|min:1'
        ]);

        $user_data=$request->except(['image','password','permissions','password_confirmation']);
        $user_data['password']=bcrypt($request->password);
        if ($request->image) {
            
            Image::make($request->image)->resize(null, 200, function ($constraint) {
                $constraint->aspectRatio();
            })->save(public_path('uploads/usersImages/'.$request->image->hashName()));
            $user_data['image']=$request->image->hashName();
            
        };
        
        $user=User::create($user_data);
        $user->attachRole('admin');
        $user->syncPermissions($request->permissions);
        
        session()->flash('success',__('site.added_successfully'));
        return redirect()->route('dashboard.users.index');

    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function show(User $user)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function edit(User $user)
    {
        return view('dashboard.users.edit')->with('user',$user);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, User $user)
    {
        $request->validate([
            'first_name' => 'required',
            'last_name' => 'required',
            'email' => ['required',Rule::unique('users')->ignore($user->id)],
            'image' => 'image',
            'permissions' => 'required|min:1'
        ]);

        $user_data=$request->except(['permissions','image']);

        if ($request->image) {

            if ($request->image !='default.png') {
                Storage::disk('public_uploads')->delete('/usersImages/'.$user->image);
            }
            
            Image::make($request->image)->resize(null, 200, function ($constraint) {
                $constraint->aspectRatio();
            })->save(public_path('uploads/usersImages/'.$request->image->hashName()));
            $user_data['image']=$request->image->hashName();
            
        };

        $user->update($user_data);
        $user->syncPermissions($request->permissions);

        session()->flash('success',__('site.updated_successfully'));
        return redirect()->route('dashboard.users.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function destroy(User $user)
    {
        if ($user->image !='default.png') {
            Storage::disk('public_uploads')->delete('/usersImages/'.$user->image);
        }
        $user->delete();
        session()->flash('success',__('site.deleted_successfully'));
        return redirect()->route('dashboard.users.index');
    }
}
