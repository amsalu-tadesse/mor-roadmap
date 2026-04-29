<x-layout>
    <!-- Content Header (Page header) -->
    <x-breadcrump title="Activity Logs List" parent="Activity Logs" child="List" />
    <!-- /.content-header -->

    <!-- /.content-Main -->
    <div class="container-fluid">


        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <form method="GET" action="{{route('admin.audit.index')}}">
                            <div class="col-md-12 row">
                                <!-- select with search fields -->
                                <div class="col-md-4">
                                    <div class="form-group" data-select2-id="5">
                                        <div class="select2-blue" data-select2-id="4">
                                            <select name="actor" class="role_select2 select2-hidden-accessible" id="actor" multiple="" data-placeholder="Actor" data-dropdown-css-class="select2-blue" style="width: 100%;" data-select2-id="1" tabindex="-1" aria-hidden="true">
                                                @foreach($actors as $actor)
                                                <option value="{{$actor->id}}" @if ($filter_actor==$actor->id)
                                                    selected
                                                    @endif
                                                    >{{$actor->first_name}} {{$actor->middle_name}} {{$actor->last_name}}</option>
                                                @endforeach

                                            </select><span class="select2 select2-container select2-container--default select2-container--focus select2-container--open select2-container--above" dir="ltr" data-select2-id="2" style="width: 100%;"><span class="dropdown-wrapper" aria-hidden="true"></span></span>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-4">
                                    <div class="form-group" data-select2-id="3">
                                        <div class="select2-blue" data-select2-id="3">
                                            <select name="subject" class="role_select2 select2-hidden-accessible" id="subject" multiple="" data-placeholder="Subject" data-dropdown-css-class="select2-blue" style="width: 100%;" data-select2-id="2" tabindex="-1" aria-hidden="true">

                                                @foreach($subjects as $subject)
                                                <option value="{{$subject}}" @if ($filter_subject==$subject) selected @endif>{{$subject}}</option>
                                                @endforeach


                                            </select><span class="select2 select2-container select2-container--default select2-container--focus select2-container--open select2-container--above" dir="ltr" data-select2-id="3" style="width: 100%;"><span class="dropdown-wrapper" aria-hidden="true"></span></span>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-4 row ">
                                    <div class="col-md-4">
                                        <button type="submit" id="search-button" class="btn btn-success form-control">Search</button>
                                    </div>
                                    <div class="col-md-4">
                                        <a href="{{route('admin.audit.index')}}" class="btn btn-warning form-control">Reset</a>
                                    </div>
                                    <div class="col-md-4">
                                        <a class="btn btn-primary form-control" id="activity_log"><span class="fa fa-eye">&nbsp</span>Show</a>
                                    </div>
                                    <!-- <div class="col-md-2">
                                        <a class="btn btn-info form-control"><span class="fa fa-download"></span></a>
                                    </div> -->
                                </div>
                            </div>
                            <!-- /end select with search fields -->

                            <!-- start Advanced div -->
                            <div class="col-md-12 row " id="advanced" style="display: none;">
                                <!-- by action filtering -->
                                <div class="col-md-4">
                                    <div class="form-group" data-select2-id="7">
                                        <div class="select2-blue" data-select2-id="7">
                                            <select name="action" class="role_select2 select2-hidden-accessible" id="action" multiple="" data-placeholder="Action" data-dropdown-css-class="select2-blue" style="width: 100%;" data-select2-id="7" tabindex="-1" aria-hidden="true">
                                                @foreach($actions as $action)
                                                <option value="{{$action}}" @if ( $filter_action==$action) selected @endif>{{$action}}</option>
                                                @endforeach
                                            </select><span class="select2 select2-container select2-container--default select2-container--focus select2-container--open select2-container--above" dir="ltr" data-select2-id="7" style="width: 100%;"><span class="dropdown-wrapper" aria-hidden="true"></span></span>
                                        </div>
                                    </div>
                                </div>
                                <!-- Date range picker -->
                                <div class="form-group">
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text">
                                                <i class="far fa-calendar-alt"></i>
                                            </span>
                                        </div>
                                        <!-- <input type="text" class="form-control float-right" name="dateRange" id="reservation"> -->
                                        <input type="text" id="date_range" name="dateRange" placeholder="Select date range" class="form-control" value="{{$filter_dateRange}}">

                                    </div>
                                </div>
                            </div>
                            <!-- /end Andvanced -->

                            <input type="checkbox" id="myCheckbox" onchange="toggleDiv()"> Advanced
                        </form>
                    </div>
                    <!-- /end card header -->

                    <div class="card-body p-0">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th style="width: 10px">#</th>
                                    <th>Subject Type</th>
                                    <th>Subject</th>
                                    <th>Actor</th>
                                    <th>Activity</th>
                                    <th>Date</th>
                                </tr>
                            </thead>

                            <tbody id="userList">
                                @foreach ($audits as $audit)
                                <tr id="">
                                    <td>{{$loop->index + 1 }}</td>
                                    <td>{{$audit['subject_type']}}</td>
                                    <td>{{$audit['subject_name']}}</td>
                                    <td>{{$audit['actor_name']}}</td>
                                    <td>{{$audit['activity']}}</td>
                                    <td>{{$audit['created_at']}}</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <x-partials.audit_modal :audits="$audits" />
    @push('scripts')
    <script>
        $("input[data-bootstrap-switch]").each(function() {
            $(this).bootstrapSwitch('state', $(this).prop('checked'));
        });

        //Initialize Select2 Elements
        $(document).ready(function() {
            $('#actor').select2();
            $('#actor').on('select2:select', function(e) {
                var currentValue = e.params.data.id;
                $(this).val(currentValue).trigger('change');
            });
            $('#subject').select2();
            $('#subject').on('select2:select', function(e) {
                var currentValue = e.params.data.id;
                $(this).val(currentValue).trigger('change');
            });
            $('#action').select2();
            $('#action').on('select2:select', function(e) {
                var currentValue = e.params.data.id;
                $(this).val(currentValue).trigger('change');
            });
        });

        //Date range picker
        $(document).ready(function() {
            $('#date_range').daterangepicker({
                timePicker: true,
                timePicker24Hour: true,
                timePickerIncrement: 1,
                autoUpdateInput: false, // Prevent automatic input value update
                locale: {
                    format: 'YYYY-MM-DD HH:mm:ss',
                }
            });
            // Clear input value on cancel
            $('#date_range').on('apply.daterangepicker', function(ev, picker) {
                $(this).val(picker.startDate.format('YYYY-MM-DD HH:mm:ss') + ' - ' + picker.endDate.format('YYYY-MM-DD HH:mm:ss'));
            });
            // Clear input value on cancel
            $('#date_range').on('cancel.daterangepicker', function(ev, picker) {
                $(this).val('');
            });
        });


        // Function to show/hide the div based on checkbox state
        function toggleDiv() {
            var checkbox = document.getElementById('myCheckbox');
            var div = document.getElementById('advanced');

            if (checkbox.checked) {
                div.style.display = 'inline-flex'; // Show the div
            } else {
                div.style.display = 'none'; // Hide the div
            }

            // Store the checkbox state in localStorage
            localStorage.setItem('checkboxState', checkbox.checked);
        }

        // Run on page load
        window.addEventListener('load', function() {
            var checkbox = document.getElementById('myCheckbox');
            var div = document.getElementById('advanced');

            // Retrieve the checkbox state from localStorage
            var checkboxState = localStorage.getItem('checkboxState');

            if (checkboxState === 'true') {
                checkbox.checked = true;
                div.style.display = 'inline-flex'; // Show the div
            } else {
                checkbox.checked = false;
                div.style.display = 'none'; // Hide the div
            }
        });

        // show modal
        $(document).ready(function() {
            $(document).on('click', '#activity_log', function() {
                $('#activity_log_show_modal').modal('show');
            });
        });

        // print modal content
        document.getElementById('print-modal-content').addEventListener('click', function() {
            const printWindow = window.open('', '', 'width=800,height=600');

            const modalContent = document.querySelector('.modal-content');
            const modalContentClone = modalContent.cloneNode(true);

            const excludedElements = modalContentClone.querySelectorAll('.exclude-print');
            excludedElements.forEach(element => {
                element.style.display = 'none';
            });

            printWindow.document.body.appendChild(modalContentClone);

            const tablesInPrintWindow = printWindow.document.querySelectorAll('table');
            tablesInPrintWindow.forEach(table => {
                table.style.borderCollapse = 'collapse'; // To collapse the borders
                const cells = table.querySelectorAll('td, th');
                cells.forEach(cell => {
                    cell.style.border = '1px solid black'; // You can customize the border style
                });
            });

            const tableInClone = printWindow.document.querySelector('table');
            if (tableInClone) {
                tableInClone.classList.add('table-bordered');
            }

            printWindow.document.body.style.margin = '0';
            printWindow.document.title = 'Activity Log';

            printWindow.print();

            printWindow.close();
        });
    </script>
    @endpush
</x-layout>
