<?php

namespace App\DataTables;

use App\Constants\Constants;
use App\Models\RequestStatus;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Services\DataTable;

class RequestStatusesDataTable extends DataTable
{
    public function dataTable(QueryBuilder $query): EloquentDataTable
    {
        $index_column = 0;
        return (new EloquentDataTable($query))
            ->addColumn('no', function () use (&$index_column) {
                return ++$index_column;
            })
            ->addColumn('action', function ($status) {
                return view('components.action-buttons', [
                    'row_id' => $status->id,
                    'show' => true,
                    'permission_delete' => 'request-status: delete',
                    'permission_edit' => 'request-status: edit',
                    'permission_view' => 'request-status: view',
                ]);
            })
            ->rawColumns(['no', 'action']);
    }

    public function query(RequestStatus $model): QueryBuilder
    {
        return $model->newQuery();
    }

    public function html(): HtmlBuilder
    {
        return $this->builder()
            ->setTableId('request-statuses-table')
            ->columns($this->getColumns())
            ->minifiedAjax()
            ->orderBy(1)
            ->selectStyleSingle()
            ->dom(
                "<'row'<'col-sm-12 col-md-2'l><'col-sm-12 col-md-6'B>
                           <'col-sm-12 col-md-4'f>><'row'<'col-sm-12'tr>>
                           <'row'<'col-sm-12 col-md-5'i><'col-sm-12 col-md-7'p>>",
            )
            ->responsive(true)
            ->processing(true)
            ->autoWidth(false)
            ->buttons([
                ['extend' => 'csvHtml5', 'text' => 'CSV', 'exportOptions' => ['columns' => ':visible']],
                ['extend' => 'excelHtml5', 'text' => 'Excel', 'exportOptions' => ['columns' => ':visible']],
                ['extend' => 'pdfHtml5', 'text' => 'PDF', 'exportOptions' => ['columns' => ':visible']],
                ['extend' => 'print', 'text' => 'Print', 'exportOptions' => ['columns' => ':visible']],
                'colvis',
            ])
            ->lengthMenu(Constants::PAGE_NUMBER())
            ->language(['lengthMenu' => '_MENU_ records per page']);
    }

    protected function getColumns(): array
    {
        return [
            Column::make('no')->title('No')->addClass('text-center')->orderable(false),
            Column::make('name')->title('Status Name'),
            Column::computed('action')->exportable(false)->printable(true)->addClass('text-center')->orderable(false),
        ];
    }

    protected function filename(): string
    {
        return 'RequestStatuses_' . date('YmdHis');
    }
}
