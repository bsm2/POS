@extends('layouts.dashboard.app')

@section('content')
    <div class="content-wrapper">
    
    <section class="content-header">

        <h1>@lang('site.edit')</h1>

        <ol class="breadcrumb">
            <li class="active"><i class="fa fa-dashboard"></i> @lang('site.dashboard')</li>
        </ol>
    </section>

    <section class="content">

        
            <div class="box box-primary">
                <div class="box-header">
                    <h3 class="box-title">@lang('site.edit')</h3>
                </div>
                <div class="box-body">
                    @include('partials._errors')
                    <form action="{{route('dashboard.categories.update',$category->id)}}" method="post" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')

                        @foreach (config('translatable.locales') as $locale)
                            <div class="form-group">
                                <label>@lang('site.'.$locale.'.name')</label>
                                <input type="text" name="{{$locale}}[name]" class="form-control" value="{{ $category->translate($locale)->name }}">
                            </div>
                        @endforeach

                        <div class="form-group">
                            <button type="submit" class="btn btn-primary"><i class="fa fa-plus"></i> @lang('site.edit')</button>
                        </div>

                    </form>
                </div>
            </div>

        

    </section><!-- end of content -->

</div><!-- end of content wrapper -->
@endsection