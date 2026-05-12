<x-layout>
    <x-breadcrump title='Draft New Initiative' parent='Draft Initiatives' child='Draft New' index="draft-initiatives" />

    <div class="card card-info">
        <div class="card-header">
            <h3 class="card-title">Draft Initiative Form</h3>
        </div>

        <form action="{{ route('admin.draft-initiatives.store') }}" method="post">
            @csrf
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="name">Initiative Name<span class="required-field">*</span></label>
                            <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" id="name" placeholder="Enter Initiative Name" value="{{ old('name') }}">
                            @error('name')
                                <span class="invalid-feedback d-block">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="directorate_id">Directorate<span class="required-field">*</span></label>
                            <select name="directorate_id" class="form-control select2 @error('directorate_id') is-invalid @enderror" id="directorate_id">
                                <option value="">Select Directorate</option>
                                @foreach($directorates as $directorate)
                                    <option value="{{ $directorate->id }}" {{ old('directorate_id') == $directorate->id ? 'selected' : '' }}>
                                        {{ $directorate->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('directorate_id')
                                <span class="invalid-feedback d-block">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="theme_id">Theme<span class="required-field">*</span></label>
                            <select name="theme_id" class="form-control select2 @error('theme_id') is-invalid @enderror" id="theme_id">
                                <option value="">Select Theme</option>
                                @foreach($themes as $theme)
                                    <option value="{{ $theme->id }}" {{ old('theme_id') == $theme->id ? 'selected' : '' }}>
                                        {{ $theme->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('theme_id')
                                <span class="invalid-feedback d-block">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="objective_id">Objective<span class="required-field">*</span></label>
                            <select name="objective_id" class="form-control select2 @error('objective_id') is-invalid @enderror" id="objective_id">
                                <option value="">Select Objective</option>
                                @if(old('theme_id'))
                                    @foreach($objectives->where('theme_id', old('theme_id')) as $objective)
                                        <option value="{{ $objective->id }}" {{ old('objective_id') == $objective->id ? 'selected' : '' }}>
                                            {{ $objective->name }}
                                        </option>
                                    @endforeach
                                @endif
                            </select>
                            @error('objective_id')
                                <span class="invalid-feedback d-block">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="implementation_status_id">Implementation Status</label>
                            <select name="implementation_status_id" class="form-control select2 @error('implementation_status_id') is-invalid @enderror" id="implementation_status_id">
                                <option value="">Select Implementation Status</option>
                                @foreach($implementationStatuses as $status)
                                    <option value="{{ $status->id }}" {{ old('implementation_status_id') == $status->id ? 'selected' : '' }}>
                                        {{ $status->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('implementation_status_id')
                                <span class="invalid-feedback d-block">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="note">Note</label>
                            <textarea name="note" class="form-control @error('note') is-invalid @enderror" id="note" rows="1" placeholder="Enter Note">{{ old('note') }}</textarea>
                            @error('note')
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

    @push('scripts')
        <script>
            $(document).ready(function () {
                $('.select2').select2({
                    theme: 'bootstrap4',
                    width: '100%'
                });

                $('#theme_id').on('change', function () {
                    var themeId = $(this).val();
                    if (themeId) {
                        $.ajax({
                            url: "{{ route('admin.get-objectives-by-theme') }}",
                            type: "GET",
                            data: { theme_id: themeId },
                            dataType: "json",
                            success: function (data) {
                                $('#objective_id').empty();
                                $('#objective_id').append('<option value="">Select Objective</option>');
                                $.each(data, function (key, value) {
                                    $('#objective_id').append('<option value="' + value.id + '">' + value.name + '</option>');
                                });
                            }
                        });
                    } else {
                        $('#objective_id').empty();
                        $('#objective_id').append('<option value="">Select Objective</option>');
                    }
                });
            });
        </script>
    @endpush
</x-layout>

<style>
    .required-field { color: red; margin-left: 4px; }
</style>
