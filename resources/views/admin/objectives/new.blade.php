<x-layout>
    <x-breadcrump title='Add New Objective' parent='Objectives' child='Add New Objective' index="objectives" />

    <div class="card card-info">
        <div class="card-header">
            <h3 class="card-title">Add Objective Form</h3>
        </div>

        <form action="{{ route('admin.objectives.store') }}" method="post">
            @csrf
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="name">Objective Name<span class="required-field">*</span></label>
                            <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" id="name" placeholder="Enter Objective Name" value="{{ old('name') }}">
                            @error('name')
                                <span class="invalid-feedback d-block">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="theme_id">Theme<span class="required-field">*</span></label>
                            <select name="theme_id" class="form-control @error('theme_id') is-invalid @enderror" id="theme_id">
                                <option value="">Select Theme</option>
                                @foreach($themes as $theme)
                                    <option value="{{ $theme->id }}" {{ old('theme_id') == $theme->id ? 'selected' : '' }}>{{ $theme->name }}</option>
                                @endforeach
                            </select>
                            @error('theme_id')
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
