<?php

namespace App\DataTables;

use App\Constants\Constants;
use App\Models\Initiative;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Services\DataTable;

class DraftInitiativesDataTable extends DataTable
{
    public function dataTable(QueryBuilder $query): EloquentDataTable
    {
        $index_column = 0;
        return (new EloquentDataTable($query))
            ->addColumn('no', function () use (&$index_column) {
                return ++$index_column;
            })
            ->addColumn('objective_name', fn($row) => $row->objective->name ?? 'N/A')
            ->addColumn('directorate_name', fn($row) => $row->directorate->name ?? 'N/A')
            ->addColumn('implementation_status_name', fn($row) => $row->implementationStatus->name ?? 'N/A')
            ->addColumn('action', function ($row) {
                return view('components.action-buttons', [
                    'row_id' => $row->id,
                    'show' => true,
                    'permission_delete' => 'draft-initiative: delete',
                    'permission_edit' => 'draft-initiative: edit',
                    'permission_view' => 'draft-initiative: view',
                ]);
            })
            ->rawColumns(['no', 'action']);
    }

    public function query(Initiative $model): QueryBuilder
    {
        $query = $model->newQuery()->with(['objective', 'directorate', 'implementationStatus'])
            ->whereHas('implementationStatus', function ($query) {
                $query->where('name', 'Draft');
            });

        if ($this->request()->has('directorate_id') && $this->request()->get('directorate_id') != '') {
            $query->where('directorate_id', $this->request()->get('directorate_id'));
        }

        if ($this->request()->has('objective_id') && $this->request()->get('objective_id') != '') {
            $query->where('objective_id', $this->request()->get('objective_id'));
        }

        return $query;
    }

    public function html(): HtmlBuilder
    {
        return $this->builder()
            ->setTableId('draft-initiatives-table')
            ->columns($this->getColumns())
            ->minifiedAjax('', 'data.directorate_id = $("#filter_directorate").val(); data.objective_id = $("#filter_objective").val();')
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
            Column::make('objective_name')->title('Objective')->orderable(false),
            Column::make('directorate_name')->title('Directorate')->orderable(false),
            Column::computed('action')->exportable(false)->printable(true)->addClass('text-center')->orderable(false),
        ];
    }

    protected function filename(): string
    {
        return 'DraftInitiatives_' . date('YmdHis');
    }
}
