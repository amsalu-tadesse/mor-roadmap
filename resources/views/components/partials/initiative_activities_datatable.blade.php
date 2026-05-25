@props(['dataTable'])

<div class="table-responsive initiative-activities-datatable">
    {!! $dataTable->html()->table(['class' => 'table table-sm table-bordered table-striped w-100']) !!}
</div>
