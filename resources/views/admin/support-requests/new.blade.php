<x-layout>
    <x-breadcrump title='Add New Support Request' parent='Support Requests' child='Add New' index="support-requests" />

    <div class="card card-info">
        <div class="card-header">
            <h3 class="card-title">Add Support Request Form</h3>
        </div>

        <form action="{{ route('admin.support-requests.store') }}" method="post">
            @csrf
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="initiative_id">Initiative <span
                                    class="text-muted text-sm">(Implementation/Shelf)</span></label>
                            <select name="initiative_id"
                                class="form-control select2 @error('initiative_id') is-invalid @enderror"
                                id="initiative_id">
                                <option value="">Select Initiative</option>
                                @foreach($initiatives as $initiative)
                                    <option value="{{ $initiative->id }}" {{ old('initiative_id') == $initiative->id ? 'selected' : '' }}>
                                        {{ $initiative->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('initiative_id')
                                <span class="invalid-feedback d-block">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="partner_id">Partner<span class="required-field">*</span></label>
                            <select name="partner_id"
                                class="form-control select2 @error('partner_id') is-invalid @enderror" id="partner_id">
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
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="request_status_id">Request Status<span class="required-field">*</span></label>
                            <select name="request_status_id"
                                class="form-control select2 @error('request_status_id') is-invalid @enderror"
                                id="request_status_id">
                                <option value="">Select Status</option>
                                @foreach($requestStatuses as $status)
                                    <option value="{{ $status->id }}" {{ old('request_status_id') == $status->id ? 'selected' : '' }}>
                                        {{ $status->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('request_status_id')
                                <span class="invalid-feedback d-block">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="priority">Priority<span class="required-field">*</span></label>
                            <select name="priority" class="form-control select2 @error('priority') is-invalid @enderror"
                                id="priority">
                                <option value="">Select Priority</option>
                                @foreach($priorities as $key => $label)
                                    <option value="{{ $key }}" {{ old('priority') == $key ? 'selected' : '' }}>
                                        {{ $label }}
                                    </option>
                                @endforeach
                            </select>
                            @error('priority')
                                <span class="invalid-feedback d-block">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-12">
                        <div class="form-group">
                            <label for="activities">Activities / Description<span
                                    class="required-field">*</span></label>
                            <textarea name="activities" class="form-control @error('activities') is-invalid @enderror"
                                id="activities" rows="5"
                                placeholder="Detailed description of activities...">{{ old('activities') }}</textarea>
                            @error('activities')
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
@push('scripts')
    <script>
        $(document).ready(function () {
            $('.select2').select2({
                theme: 'bootstrap4',
                width: '100%'
            });
        });
    </script>
@endpush

<style>
    .required-field {
        color: red;
        margin-left: 4px;
    }
</style>