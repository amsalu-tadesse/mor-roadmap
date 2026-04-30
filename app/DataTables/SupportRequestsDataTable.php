<?php

namespace App\DataTables;

use App\Constants\Constants;
use App\Models\SupportRequest;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Services\DataTable;

class SupportRequestsDataTable extends DataTable
{
    public function dataTable(QueryBuilder $query): EloquentDataTable
    {
        $index_column = 0;
        return (new EloquentDataTable($query))
            ->addColumn('no', function () use (&$index_column) {
                return ++$index_column;
            })
            ->addColumn('partner_name', fn($row) => $row->partner->name ?? 'N/A')
            ->addColumn('request_status_name', fn($row) => $row->requestStatus->name ?? 'N/A')
            ->addColumn('priority_badge', function ($row) {
                $badges = [
                    'L' => '<span class="badge badge-success">Low</span>',
                    'M' => '<span class="badge badge-warning">Medium</span>',
                    'H' => '<span class="badge badge-danger">High</span>',
                ];
                return $badges[$row->priority] ?? $row->priority;
            })
            ->addColumn('activities_short', fn($row) => \Str::limit($row->activities, 60))
            ->addColumn('action', function ($row) {
                return view('components.action-buttons', [
                    'row_id' => $row->id,
                    'show' => true,
                    'permission_delete' => 'support-request: delete',
                    'permission_edit' => 'support-request: edit',
                    'permission_view' => 'support-request: view',
                ]);
            })
            ->rawColumns(['no', 'priority_badge', 'action']);
    }

    public function query(SupportRequest $model): QueryBuilder
    {
        return $model->newQuery()->with(['partner', 'requestStatus']);
    }

    public function html(): HtmlBuilder
    {
        return $this->builder()
            ->setTableId('support-requests-table')
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
            Column::make('partner_name')->title('Partner')->orderable(false),
            Column::make('activities_short')->title('Activities')->orderable(false),
            Column::make('request_status_name')->title('Status')->orderable(false),
            Column::make('priority_badge')->title('Priority')->addClass('text-center')->orderable(false),
            Column::computed('action')->exportable(false)->printable(true)->addClass('text-center')->orderable(false),
        ];
    }

    protected function filename(): string
    {
        return 'SupportRequests_' . date('YmdHis');
    }
}
