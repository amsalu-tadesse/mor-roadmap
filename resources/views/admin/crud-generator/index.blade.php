<x-layout>
    @if (session('success'))
    <div class="alert alert-success">
        {{ session('success') }}
    </div>
    @endif
    <!-- Content Header (Page header) -->
    <x-breadcrump title="Crude Generator List" parent="Crude Generator" child="List" />
    <!-- /.content-header -->

    <!-- /.content-Main -->


    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">Copy PHP Code Example</div>

                    <div class="card-body">
                        <h1>PHP Code to Copy</h1>
                        <pre id="phpCodeToCopy">
                        <!-- < <code id="phpCode">CODE</code></pre> -->
                        <button onclick="copyToClipboard()">Copy</button>
                        <button onclick="saveToController()">Save</button>
                    </div>
                </div>
            </div>
        </div>
    </div>


    <div class="card">

        <form class="form-horizontal" method="POST" id="updateForm" action="{{ route('admin.crud-generator') }}">

            {{ csrf_field() }}
            <div class="card-body">
                <div class="form-group row">
                    <label class="col-sm-2 col-form-label">Model</label>
                    <div class="col-sm-10">
                        <input type="input" class="form-control" id="model" name="model">
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-sm-2 col-form-label">Database Plural</label>
                    <div class="col-sm-10">
                        <input type="input" class="form-control" id="database_plural" name="database_plural">
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-sm-2 col-form-label">Database Singular</label>
                    <div class="col-sm-10">
                        <input type="input" class="form-control" id="database_singular" name="database_singular">
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-sm-2 col-form-label">Title Singular</label>
                    <div class="col-sm-10">
                        <input type="input" class="form-control" id="singular_title" name="singular_title">
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-sm-2 col-form-label">Title Plural</label>
                    <div class="col-sm-10">
                        <input type="input" class="form-control" id="plular_title" name="plular_title">
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-sm-2 col-form-label">Base permission</label>
                    <div class="col-sm-10">
                        <input type="input" class="form-control" id="database_singular_with_minus" name="database_singular_with_minus">
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-sm-2 col-form-label">Route</label>
                    <div class="col-sm-10">
                        <input type="input" class="form-control" id="database_plular_with_minus" name="database_plular_with_minus">
                    </div>
                </div>

                <!-- hr -->
                <div class="form-group row">
                    <div class="col-sm-12 text-center">
                        <hr class="line">
                        <span class="label label-default" style="font-weight: 400;">Attributes</span>
                        <hr class="line">
                    </div>
                </div>
                <!-- end hr -->

                <div id="formContainer">
                    <div class="form-group row" id="template">
                        <div class="col-sm-2">
                            <input type="text" class="form-control" name="label[]" placeholder="enter label name">
                        </div>
                        <div class="col-sm-2">
                            <input type="text" class="form-control" name="attribute[]" placeholder="enter attribute name">
                        </div>

                        <div class="col-sm-2">
                            <select class="form-control" name="dataType[]">
                                <option value="">Data Type</option>
                                <option value="string">string</option>
                                <option value="boolean">boolean</option>
                                <option value="text">text</option>
                                <option value="integer">integer</option>
                                <option value="longText">longText</option>
                                <option value="datetime">datetime</option>
                                <option value="foreignId">foreignId</option>
                            </select>
                        </div>
                        <div class="col-sm-2 foreignIdInput" style="display: none">
                            <input type="text" class="form-control" name="referred_table[]" placeholder="enter referred table">
                        </div>
                        <div class="col-sm-2">
                            <select class="form-control" name="required[]">
                                <option value="required">required</option>
                                <option value="">nullable</option>
                            </select>
                        </div>
                        <div class="col-sm-1">
                            <select class="form-control" name="inputType[]">
                                <option value="">Input Type</option>
                                <option value="textarea">textarea</option>
                                <option value="email">email</option>
                                <option value="text">text</option>
                                <option value="checkbox">checkbox</option>
                                <option value="select">select</option>
                                <option value="file">file</option>
                                <option value="date">date</option>
                            </select>
                        </div>
                        <button class="removeFormGroup btn btn-warning">
                            <i class="fas fa-times"></i>
                        </button>
                    </div>
                </div>

                <button id="addFormGroup" class="btn btn-success">Add Another</button>


            </div>



            <div class="card-footer">
                <button type="submit" class="btn btn-info float-right">Generate</button>
            </div>

        </form>
        <!-- /.card-body -->
    </div>
    <!-- /.card -->
    @push('scripts')
    <script>
        function copyToClipboard() {
            const phpCodeToCopy = document.getElementById("phpCodeToCopy");
            const textArea = document.createElement("textarea");

            textArea.value = phpCodeToCopy.textContent;
            document.body.appendChild(textArea);
            textArea.select();

            document.execCommand("copy");

            document.body.removeChild(textArea);

            alert("PHP code copied to clipboard:\n" + phpCodeToCopy.textContent);
        }


        // function saveToController() {

        //     var fileName = $("#model").val();
        //     var phpCode = document.getElementById("phpCode").textContent;
        //     $.ajax({
        //      
        //         type: "POST",
        //         dataType: 'text/plain',
        //         contentType: 'application/x-www-form-urlencoded',
        //         data: {
        //             'fileName': fileName,
        //             'phpCode': phpCode
        //         },
        //         success: function(response) {
        //             console.log('success');

        //             if (response.success == 1) {


        //             } else {
        //                 alert("Invalid ID.");
        //             }
        //         }
        //     });
        // }

        $(document).ready(function() {
            var formGroupCount = 2;

            $("#addFormGroup").click(function(event) {
                event.preventDefault();

                var $template = $("#template").clone();
                $template.find(".label").text("attr " + formGroupCount);

                $template.find("input, select").val("");

                $template.find('.removeFormGroup').click(function() {
                    $(this).closest('.form-group').remove();
                });

                $('#formContainer').append($template);
                formGroupCount++;
            });

            $(document).on('click', '.removeFormGroup', function() {
                $(this).closest('.form-group').remove();
            });

            $(document).on('change', 'select[name="dataType[]"]', function() {
                var $formGroup = $(this).closest('.form-group');
                var $referredTableInput = $formGroup.find('.foreignIdInput');

                if (this.value === 'foreignId') {
                    $referredTableInput.show();
                } else {
                    $referredTableInput.hide();
                }
            });
        });
    </script>
    @endpush
</x-layout>

<style>
    .line {
        border: 0;
        border-top: 2px solid #000000;
        width: 45%;
        display: inline-block;
    }

    .label {
        padding: 0 10px;
        background-color: white;
        position: relative;
        top: -8px;
    }
</style>