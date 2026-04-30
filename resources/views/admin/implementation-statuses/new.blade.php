<x-layout>
    <x-breadcrump title='Add New Implementation Status' parent='Implementation Statuses' child='Add New' index="implementation-statuses" />

    <div class="card card-info">
        <div class="card-header">
            <h3 class="card-title">Add Status Form</h3>
        </div>

        <form action="{{ route('admin.implementation-statuses.store') }}" method="post">
            @csrf
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="name">Status Name<span class="required-field">*</span></label>
                            <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" id="name" placeholder="Enter Status Name" value="{{ old('name') }}">
                            @error('name')
                                <span class="invalid-feedback d-block">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                </div>
            </div>

            <div class="card-footer text-right">
                <button type="submit" class="btn btn-info float-right mx-3">Submit</button>
                <a href="javascript:history.back()" class="btn btn-secondary float-right mx-3">Back</a>
            </div>
        </form>
    </div>
</x-layout>

<style>
    .required-field {
        color: red;
        margin-left: 4px;
    }
</style>
