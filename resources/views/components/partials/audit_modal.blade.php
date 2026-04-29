@props(['audits'])
<!-- Modal -->
<div class="modal fade" id="activity_log_show_modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalScrollableTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-scrollable modal-xl" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title exclude-print" id="exampleModalScrollableTitle">Activity Log</h5>
                <button type="button" class="close exclude-print" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <table class="table table-striped table-bordered text-center">
                    <thead>
                        <tr>
                            <th style="width: 10px">#</th>
                            <th>Actor</th>
                            <th>Activity</th>
                            <th colspan="3">Data</th>
                            <th>Date</th>
                        </tr>
                        <tr>
                            <th colspan="3"></th>
                            <th style="width: 33.333%;">Attribute</th>
                            <th style="width: 33.333%;">From</th>
                            <th style="width: 33.333%;">To</th>
                        </tr>
                    </thead>
                    @php

                    @endphp
                    <tbody id="audittablee">
                        @foreach ($audits as $audit)
                        <tr id="">
                            <td>{{ $loop->index + 1 }}</td>
                            <td>{{ $audit['actor_name'] }}</td>
                            <td>{{ $audit['activity'] }}</td>
                            <td colspan="3" class="table-cell">

                                @foreach ($audit['properties'] as $key => $property)
                                <table style="width: 100%;">

                                    @php $columnaName = ''; @endphp


                                    <!-- updated or created-->
                                    @if ($key == 'attributes')
                                    @php $columnaName = 1 @endphp

                                    @foreach ($property as $k => $v)
                                    @php
                                    if (is_array($v)) {
                                    echo "coming Soon";
                                    continue;
                                    }

                                    @endphp



                                    <tr>
                                        <td style="width: 33.333%;" class="table-cell">{{ $k }}</td>

                                        <!-- updated-->
                                        @isset($audit['properties']['old'])
                                        <td style="width: 33.333%;" class="table-cell">
                                            @if(isset($audit['properties']['old'][$k]) && is_string($audit['properties']['old'][$k]))
                                            {{ htmlspecialchars($audit['properties']['old'][$k], ENT_QUOTES, 'UTF-8') }}
                                            @else
                                            <!-- Handle other types or empty cases if needed -->
                                            @endif
                                        </td>
                                        @else
                                        <td style="width: 33.333%;" class="table-cell"></td>
                                        @endisset




                                        <td style="width: 33.333%;" class="table-cell">{{ $v }}</td>
                                    </tr>
                                    @endforeach
                                    @endif



                                    <!-- updated or deleted-->
                                    @php $columnaName0 = ''; @endphp
                                    @if ($key == 'old')
                                    @php $columnaName0 = 1 @endphp

                                    @foreach ($property as $k0 => $v0)
                                    @php
                                    if (is_array($v0)) {
                                    echo "coming Soon";
                                    continue;
                                    }
                                    @endphp



                                    <tr>
                                        @php
                                        $column = App\Http\Controllers\Utility::changeIdToName($k0, $v0);
                                        @endphp
                                        <td style="width: 33.333%;" class="table-cell">{{ $column[0] }}</td> <!-- attributes -->
                                        <td style="width: 33.333%;" class="table-cell">{{ $column[1]  }}</td> <!-- from values -->

                                        <!-- updated-->
                                        @isset($audit['properties']['attributes'])
                                        <td style="width: 33.333%;" class="table-cell">
                                            {{ $audit['properties']['attributes'][$k0] }}
                                        </td> <!-- to values -->
                                        @else
                                        <td style="width: 33.333%;" class="table-cell"></td>
                                        @endisset

                                    </tr>
                                    @endforeach
                                    @endif


                                </table>
                                @endforeach
                            </td>
                            <td>{{ $audit['created_at'] }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="modal-footer justify-content-between">
                <button type="button" class="btn btn-danger exclude-print" data-dismiss="modal">Close</button>
                <!-- <button type="button" class="btn btn-success" id="print-pdf-button" data-dismiss="modal">Download</button> -->

                <button id="print-modal-content" class="btn btn-success exclude-print">Print</button>
            </div>
        </div>
    </div>
</div>


<style>
    /* Apply styles to the cells with long text */
    .table-cell {
        max-width: 200px;
        /* Adjust the max width as needed */
        overflow: hidden;
        white-space: normal;
        /* text-overflow: ellipsis; */
    }
</style>