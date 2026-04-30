<x-layout>
    <x-breadcrump title='Add New Directorate' parent='Directorates' child='Add New Directorate' index="directorates" />

    <div class="card card-info">
        <div class="card-header">
            <h3 class="card-title">Add Directorate Form</h3>
        </div>

        <form action="{{ route('admin.directorates.store') }}" method="post">
            @csrf
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="name">Directorate Name<span class="required-field">*</span></label>
                            <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" id="name" placeholder="Enter Directorate Name" value="{{ old('name') }}">
                            @error('name')
                                <span class="invalid-feedback d-block">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label for="user_id">Director<span class="required-field">*</span></label>
                            <select name="user_id" class="form-control select2 @error('user_id') is-invalid @enderror" id="user_id" style="width: 100%;">
                                <option value="">Select Director</option>
                                @foreach ($users as $user)
                                    <option value="{{ $user->id }}" {{ old('user_id') == $user->id ? 'selected' : '' }}>{{ $user->name }}</option>
                                @endforeach
                            </select>
                            @error('user_id')
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
