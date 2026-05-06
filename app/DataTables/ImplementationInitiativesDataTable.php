<?php

namespace App\DataTables;

use App\Constants\Constants;
use App\Models\Initiative;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Services\DataTable;

class ImplementationInitiativesDataTable extends DataTable
{
    public function dataTable(QueryBuilder $query): EloquentDataTable
    {
        $index_column = 0;
        return (new EloquentDataTable($query))
            ->addColumn('no', function () use (&$index_column) {
                return ++$index_column;
            })
            ->addColumn('start_date', fn($row) => $row->start_date ? $row->start_date->format('Y-m-d') : 'N/A')
            ->addColumn('end_date', fn($row) => $row->end_date ? $row->end_date->format('Y-m-d') : 'N/A')
            ->addColumn('partner_name', fn($row) => $row->partner->name ?? 'N/A')
            ->addColumn('initiative_status_name', fn($row) => $row->initiativeStatus->name ?? 'N/A')
            ->addColumn('completion', fn($row) => $row->completion ? $row->completion . '%' : 'N/A')
            ->addColumn('action', function ($row) {
                return view('components.action-buttons', [
                    'row_id' => $row->id,
                    'show' => true,
                    'route' => 'admin.implementation-initiatives.edit',
                    'route_detail' => 'admin.implementation-initiatives.show',
                    'permission_delete' => 'implementation-initiative: delete',
                    'permission_edit' => 'implementation-initiative: edit',
                    'permission_view' => 'implementation-initiative: view',
                ]);
            })
            ->rawColumns(['no', 'action']);
    }

    public function query(Initiative $model): QueryBuilder
    {
        return $model->newQuery()->with(['partner', 'initiativeStatus'])
            ->whereHas('implementationStatus', function ($query) {
                $query->where('name', 'Implementation');
            });
    }

    public function html(): HtmlBuilder
    {
        return $this->builder()
            ->setTableId('implementation-initiatives-table')
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
            Column::make('name')->title('Initiative Name'),
            Column::make('start_date')->title('Start Date'),
            Column::make('end_date')->title('End Date'),
            Column::make('budget')->title('Budget'),
            Column::make('partner_name')->title('Partner')->orderable(false),
            Column::make('completion')->title('Completion')->addClass('text-center'),
            Column::make('initiative_status_name')->title('Status')->orderable(false),
            Column::computed('action')->exportable(false)->printable(true)->addClass('text-center')->orderable(false),
        ];
    }

    protected function filename(): string
    {
        return 'ImplementationInitiatives_' . date('YmdHis');
    }
}
