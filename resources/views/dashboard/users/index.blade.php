@extends('layouts.dashboard.app')

@section('content')
    <div class="content-wrapper">
    
    <section class="content-header">

        <h1>@lang('site.users')</h1>

        <ol class="breadcrumb">
            <li class="active"><i class="fa fa-dashboard"></i> @lang('site.dashboard')</li>
        </ol>
    </section>

    <section class="content">

        <div class="box box-primary">
            <div class="box-header with-border">
                <h3 class="box-title">@lang('site.users') <small>{{$users->total()}}</small></h3>
                
                <form action="{{ route('dashboard.users.index') }}" method="get">

                        <div class="row">

                            <div class="col-md-4">
                                <input type="text" name="search" class="form-control" placeholder="@lang('site.search')" value="{{ request()->search }}">
                            </div>

                            <div class="col-md-4">
                                <button type="submit" class="btn btn-primary"><i class="fa fa-search"></i> @lang('site.search')</button>
                                
                                @if (auth()->user()->hasPermission('users-create'))
                                    <a href="{{ route('dashboard.users.create') }}" class="btn btn-primary"><i class="fa fa-plus"></i> @lang('site.add')</a>
                                @else
                                    <a href="#" class="btn btn-primary disabled"><i class="fa fa-plus"></i> @lang('site.add')</a>
                                @endif

                                

                            </div>

                        </div>
                </form><!-- end of form -->
            </div>
            <div class="box-body">

                @if ($users->count()>0)
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>@lang('site.first_name')</th>
                                <th>@lang('site.last_name')</th>
                                <th>@lang('site.email')</th>
                                <th>@lang('site.image')</th>
                                <th>@lang('site.action')</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($users as $index=>$user)
                                <tr>
                                    <td>{{$index+1}}</td>
                                    <td>{{$user->first_name}}</td>
                                    <td>{{$user->last_name}}</td>
                                    <td>{{$user->last_name}}</td>
                                    <td><img src="{{$user->image_path}}" style="width: 50px;"  alt=""></td>
                                    <td>
                                        
                                        @if (auth()->user()->hasPermission('users-update'))
                                            <a href="{{ route('dashboard.users.edit', $user->id)}}" class="btn btn-info btn-sm"><i class="fa fa-edit"></i> @lang('site.edit')</a>
                                        @else
                                            <button  class="btn btn-info disabled"><i class="fa fa-trash"></i> @lang('site.edit')</button>
                                        @endif

                                        @if (auth()->user()->hasPermission('users-delete'))

                                            <form action="{{route('dashboard.users.destroy', $user->id)}}" method="post" style="display: inline-block">
                                                
                                                @csrf
                                                @method('delete')
                                                <button type="submit" class="btn btn-danger delete btn-sm"><i class="fa fa-trash"></i> @lang('site.delete')</button>
                                            </form><!-- end of form -->
        
                                        @else
                                            <button  class="btn btn-danger disabled"><i class="fa fa-trash"></i> @lang('site.delete')</button>
                                        @endif
                                        
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                    {{-- pagination --}}
                    {{ $users->onEachSide(2)->links('pagination::bootstrap-4') }}
                @else
                    <h2>@lang('site.no_data_found')</h2>
                @endif
                

            </div>
        </div>

    </section><!-- end of content -->

</div><!-- end of content wrapper -->
@endsection