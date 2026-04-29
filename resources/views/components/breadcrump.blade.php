@props(['title', 'parent', 'child','index'])

<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h5 class="m-0">{{ ucwords($title) ?? 'Dashboard' }}

                    @can('help: create***')
                    <a
                    href="{{ route('admin.helps.create') }}?route={{ request()->route()->getName() }}"><i
                        class="fa fa-plus-circle" aria-hidden="true" style="color:green"></i></a>
                    @endcan
                    <i class="fa fa-question-circle" aria-hidden="true" style="color:green" class="btn btn-none "
                        data-toggle="modal" data-target="#exampleModalLong"></i>

                </h5>
            </div><!-- /.col -->
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="#">Home</a></li>
                    @if(isset($index))
                    <li class="breadcrumb-item active">
                        <a href="{{ route('admin.' . $index . '.index') }}">{{ ucwords($parent) }}</a>
                    </li>
                    @else
                    <li class="breadcrumb-item active">
                       {{ ucwords($parent) }}
                    </li>
                    @endif

                    <li class="breadcrumb-item active">{{ ucwords($child) }}</li>
                </ol>
            </div><!-- /.col -->
        </div><!-- /.row -->
    </div><!-- /.container-fluid -->
</div>
<div class="modal fade " id="exampleModalLong" tabindex="-1" role="dialog" aria-labelledby="exampleModalLongTitle"
    aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">

        @php
            $model = App\Models\Help::where('route', Route::current()?->getName())?->first();

        @endphp
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLongTitle">{{ $model?->title }}</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                {!! $model?->body !!}
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                {{-- <button type="button" class="btn btn-primary">Save changes</button> --}}
            </div>
        </div>
    </div>
</div>
