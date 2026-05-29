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
            ->addColumn('theme_name', fn ($row) => $row->objective->theme->name ?? 'N/A')
            ->addColumn('objective_name', fn ($row) => $row->objective->name ?? 'N/A')
            ->addColumn('directorate_name', fn ($row) => $row->directorates->pluck('name')->join(', ') ?: 'N/A')
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
        $query = $model->newQuery()->with(['objective.theme', 'directorates'])
            ->whereHas('implementationStatus', function ($query) {
                $query->where('id', Constants::IMPLEMENTATION_STATUS_IMPLEMENTATION);
            });

        if ($this->request()->has('directorate_id') && $this->request()->get('directorate_id') != '') {
            $query->whereHas('directorates', function ($q) {
                $q->where('directorates.id', $this->request()->get('directorate_id'));
            });
        }
        
        if ($this->request()->has('theme_id') && $this->request()->get('theme_id') != '') {
            $query->whereHas('objective', function($q) {
                $q->where('theme_id', $this->request()->get('theme_id'));
            });
        }

        if ($this->request()->has('objective_id') && $this->request()->get('objective_id') != '') {
            $query->where('objective_id', $this->request()->get('objective_id'));
        }

        if ($this->request()->has('partner_id') && $this->request()->get('partner_id') != '') {
            $partnerId = $this->request()->get('partner_id');
            $query->whereHas('activities', function ($q) use ($partnerId) {
                $q->where('partner_id', $partnerId);
            });
        }



        return $query;
    }

    public function html(): HtmlBuilder
    {
        return $this->builder()
            ->setTableId('implementation-initiatives-table')
            ->columns($this->getColumns())
            ->minifiedAjax('', 'data.directorate_id = $("#filter_directorate").val(); data.theme_id = $("#filter_theme").val(); data.objective_id = $("#filter_objective").val(); data.partner_id = $("#filter_partner").val();')
            ->orderBy(0, 'desc')
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
            Column::make('id')->visible(false),
            Column::make('no')->title('No')->addClass('text-center')->orderable(false),
            Column::make('name')->title('Initiative Name'),
            Column::make('directorate_name')->title('Directorates')->orderable(false),
            Column::make('theme_name')->title('Theme')->orderable(false),
            Column::make('objective_name')->title('Objective')->orderable(false),
            Column::computed('action')->exportable(false)->printable(true)->addClass('text-center')->orderable(false),
        ];
    }

    protected function filename(): string
    {
        return 'ImplementationInitiatives_' . date('YmdHis');
    }
}
