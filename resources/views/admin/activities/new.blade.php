<x-layout>
    <x-breadcrump title='Add New Activity Request' parent='Activity Requests' child='Add New' index="activities" />

    <div class="card card-info">
        <div class="card-header">
            <h3 class="card-title">Add Activity Request Form</h3>
        </div>

        <form action="{{ route('admin.activities.store') }}" method="post">
            @csrf
            <div class="card-body">
                <div class="row">
                    <div class="col-md-12">
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
                </div>

                <div class="row">
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
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="interested_partners">Interested Partners</label>
                            <select name="interested_partners[]"
                                class="form-control select2 @error('interested_partners') is-invalid @enderror" id="interested_partners" multiple="multiple" data-placeholder="Select Interested Partners">
                                @foreach($partners as $partner)
                                    <option value="{{ $partner->id }}" {{ (is_array(old('interested_partners')) && in_array($partner->id, old('interested_partners'))) ? 'selected' : '' }}>
                                        {{ $partner->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('interested_partners')
                                <span class="invalid-feedback d-block">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-12">
                        <div class="form-group">
                            <label for="directorates">Directorates</label>
                            <select name="directorates[]"
                                class="form-control select2 @error('directorates') is-invalid @enderror"
                                id="directorates" multiple="multiple" data-placeholder="Select Directorates">
                                @foreach($directorates as $directorate)
                                    <option value="{{ $directorate->id }}"
                                        {{ (is_array(old('directorates')) && in_array($directorate->id, old('directorates'))) ? 'selected' : '' }}>
                                        {{ $directorate->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('directorates')
                                <span class="invalid-feedback d-block">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-12">
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
                            <input type="text" name="budget" class="form-control @error('budget') is-invalid @enderror" id="budget" placeholder="Enter budget" value="{{ old('budget') }}">
                            @error('budget')
                                <span class="invalid-feedback d-block">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="completion">Completion (%)</label>
                            <input type="number" step="0.01" min="0" max="100" name="completion" class="form-control @error('completion') is-invalid @enderror" id="completion" placeholder="Enter completion percentage" value="{{ old('completion') }}">
                            @error('completion')
                                <span class="invalid-feedback d-block">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="activity_status_id">Activity Status</label>
                            <select name="activity_status_id" class="form-control select2 @error('activity_status_id') is-invalid @enderror" id="activity_status_id">
                                <option value="">Select Activity Status</option>
                                @foreach($activityStatuses as $status)
                                    <option value="{{ $status->id }}" {{ old('activity_status_id') == $status->id ? 'selected' : '' }}>
                                        {{ $status->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('activity_status_id')
                                <span class="invalid-feedback d-block">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="request_type">Request Type</label>
                            <select name="request_type" class="form-control select2 @error('request_type') is-invalid @enderror" id="request_type">
                                <option value="">Select Request Type</option>
                                <option value="New" {{ old('request_type') == 'New' ? 'selected' : '' }}>New</option>
                                <option value="Current" {{ old('request_type') == 'Current' ? 'selected' : '' }}>Current</option>
                            </select>
                            @error('request_type')
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
                    <div class="row">
                    <div class="col-md-12">
                        <div class="form-group">
                            <label for="expenditure">Expenditure Details</label>
                            <textarea name="expenditure" class="form-control @error('expenditure') is-invalid @enderror"
                                id="expenditure" rows="3"
                                placeholder="Details of expenditure...">{{ old('expenditure') }}</textarea>
                            @error('expenditure')
                                <span class="invalid-feedback d-block">{{ $message }}</span>
                            @enderror
                        </div>
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

            function loadDirectorates(initiativeId, selectedVals) {
                if (initiativeId) {
                    $.ajax({
                        url: "{{ route('admin.get-directorates-by-initiative') }}",
                        type: "GET",
                        data: { initiative_id: initiativeId },
                        dataType: "json",
                        success: function(data) {
                            var select = $('#directorates');
                            select.empty();
                            $.each(data, function(key, value) {
                                select.append('<option value="' + value.id + '">' + value.name + '</option>');
                            });
                            if (selectedVals) {
                                select.val(selectedVals).trigger('change.select2');
                            } else {
                                select.trigger('change.select2');
                            }
                        }
                    });
                } else {
                    $('#directorates').empty().trigger('change.select2');
                }
            }

            $(document).on('change', '#initiative_id', function() {
                loadDirectorates($(this).val(), null);
            });

            // If there's an already selected initiative on page load (e.g. old input)
            var initialInitiativeId = $('#initiative_id').val();
            if (initialInitiativeId) {
                var oldDirectorates = @json(old('directorates') ?? []);
                loadDirectorates(initialInitiativeId, oldDirectorates);
            }
        });
    </script>
@endpush

<style>
    .required-field {
        color: red;
        margin-left: 4px;
    }
</style>
