<x-layout>
    <x-breadcrump title='Add New Shelf Initiative' parent='Shelf Initiatives' child='Add New' index="shelf-initiatives" />

    <div class="card card-info">
        <div class="card-header">
            <h3 class="card-title">Add Shelf Initiative Form</h3>
        </div>

        <form action="{{ route('admin.shelf-initiatives.store') }}" method="post">
            @csrf
            <div class="card-body">
                <h5 class="text-info border-bottom pb-2 mb-3">Base Initiative Details</h5>
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
                            <label for="objective_id">Objective<span class="required-field">*</span></label>
                            <select name="objective_id" class="form-control @error('objective_id') is-invalid @enderror" id="objective_id">
                                <option value="">Select Objective</option>
                                @foreach($objectives as $objective)
                                    <option value="{{ $objective->id }}" {{ old('objective_id') == $objective->id ? 'selected' : '' }}>
                                        {{ $objective->name }}
                                    </option>
                                @endforeach
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
                            <label for="directorate_id">Directorate<span class="required-field">*</span></label>
                            <select name="directorate_id" class="form-control @error('directorate_id') is-invalid @enderror" id="directorate_id">
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
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="implementation_status_id">Implementation Status</label>
                            <select name="implementation_status_id" class="form-control @error('implementation_status_id') is-invalid @enderror" id="implementation_status_id">
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
                </div>

                <h5 class="text-info border-bottom pb-2 mb-3 mt-4">Implementation Details</h5>
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="start_date">Start Date</label>
                            <input type="date" name="start_date" class="form-control @error('start_date') is-invalid @enderror" id="start_date" value="{{ old('start_date') }}">
                            @error('start_date')
                                <span class="invalid-feedback d-block">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="end_date">End Date</label>
                            <input type="date" name="end_date" class="form-control @error('end_date') is-invalid @enderror" id="end_date" value="{{ old('end_date') }}">
                            @error('end_date')
                                <span class="invalid-feedback d-block">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="budget">Budget</label>
                            <input type="text" name="budget" class="form-control @error('budget') is-invalid @enderror" id="budget" placeholder="e.g. $10,000" value="{{ old('budget') }}">
                            @error('budget')
                                <span class="invalid-feedback d-block">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="completion">Completion (%)</label>
                            <input type="number" step="0.01" min="0" max="100" name="completion" class="form-control @error('completion') is-invalid @enderror" id="completion" placeholder="0 - 100" value="{{ old('completion') }}">
                            @error('completion')
                                <span class="invalid-feedback d-block">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="partner_id">Partner</label>
                            <select name="partner_id" class="form-control @error('partner_id') is-invalid @enderror" id="partner_id">
                                <option value="">Select Partner</option>
                                @foreach($partners as $partner)
                                    <option value="{{ $partner->id }}" {{ old('partner_id') == $partner->id ? 'selected' : '' }}>
                                        {{ $partner->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('partner_id')
                                <span class="invalid-feedback d-block">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="initiative_status_id">Initiative Status</label>
                            <select name="initiative_status_id" class="form-control @error('initiative_status_id') is-invalid @enderror" id="initiative_status_id">
                                <option value="">Select Status</option>
                                @foreach($initiativeStatuses as $status)
                                    <option value="{{ $status->id }}" {{ old('initiative_status_id') == $status->id ? 'selected' : '' }}>
                                        {{ $status->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('initiative_status_id')
                                <span class="invalid-feedback d-block">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="request">Request</label>
                            <select name="request" class="form-control @error('request') is-invalid @enderror" id="request">
                                <option value="">Select Request Type</option>
                                <option value="New" {{ old('request') == 'New' ? 'selected' : '' }}>New</option>
                                <option value="Current" {{ old('request') == 'Current' ? 'selected' : '' }}>Current</option>
                            </select>
                            @error('request')
                                <span class="invalid-feedback d-block">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <div class="form-group">
                            <label for="expenditure">Expenditure Details</label>
                            <textarea name="expenditure" class="form-control @error('expenditure') is-invalid @enderror" id="expenditure" rows="4" placeholder="Enter Expenditure Details">{{ old('expenditure') }}</textarea>
                            @error('expenditure')
                                <span class="invalid-feedback d-block">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <div class="form-group">
                            <label for="note">Note</label>
                            <textarea name="note" class="form-control @error('note') is-invalid @enderror" id="note" rows="3" placeholder="Enter Base Note">{{ old('note') }}</textarea>
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
</x-layout>

<style>
    .required-field { color: red; margin-left: 4px; }
</style>
