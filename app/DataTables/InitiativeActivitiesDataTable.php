<?php

namespace App\DataTables;

use App\Constants\Constants;
use App\Models\Activity;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Services\DataTable;

class InitiativeActivitiesDataTable extends DataTable
{
    protected string $tableId = 'initiative-activities-table';

    protected bool $showActions = true;

    public function setTableId(string $tableId): static
    {
        $this->tableId = $tableId;

        return $this;
    }

    public function setShowActions(bool $showActions): static
    {
        $this->showActions = $showActions;

        return $this;
    }

    public function dataTable(QueryBuilder $query): EloquentDataTable
    {
        $index_column = 0;

        $table = (new EloquentDataTable($query))
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
            ->addColumn('activities_description', function ($row) {
                return '<div>' . e($row->activities) . '</div>';
            })
            ->addColumn('start_date_formatted', fn($row) => $row->start_date ? $row->start_date->format('Y-m-d') : 'N/A')
            ->addColumn('end_date_formatted', fn($row) => $row->end_date ? $row->end_date->format('Y-m-d') : 'N/A')
            ->addColumn('budget_col', fn($row) => $row->budget ?? 'N/A')
            ->addColumn('completion_col', fn($row) => $row->completion !== null && $row->completion !== '' ? $row->completion . '%' : 'N/A')
            ->addColumn('activity_status_name', fn($row) => $row->activityStatus->name ?? 'N/A')
            ->addColumn('request_status_name', fn($row) => $row->requestStatus->name ?? 'N/A')
            ->addColumn('request_type_col', fn($row) => $row->request_type ?? 'N/A')
            ->addColumn('interested_partners_col', function ($row) {
                if ($row->interestedPartners->isNotEmpty()) {
                    return $row->interestedPartners
                        ->map(fn($p) => '<span class="badge badge-info mr-1">' . e($p->name) . '</span>')
                        ->join('');
                }
                return 'N/A';
            })
            ->addColumn('directorates_col', function ($row) {
                if ($row->directorates->isNotEmpty()) {
                    return $row->directorates
                        ->map(fn($d) => '<span class="badge badge-secondary mr-1">' . e($d->name) . '</span>')
                        ->join('');
                }
                return 'N/A';
            });

        $rawColumns = ['no', 'priority_badge', 'activities_description', 'interested_partners_col', 'directorates_col'];

        if ($this->showActions) {
            $table->addColumn('action', function ($row) {
                return view('components.initiative-activity-action-buttons', [
                    'row_id' => $row->id,
                    'initiative_id' => $row->initiative_id,
                ]);
            });
            $rawColumns[] = 'action';
        }

        return $table->rawColumns($rawColumns);
    }

    public function query(Activity $model): QueryBuilder
    {
        $query = $model->newQuery()
            ->with(['partner', 'requestStatus', 'activityStatus', 'interestedPartners', 'directorates']);

        $initiativeId = $this->request()->get('initiative_id');

        if ($initiativeId) {
            $query->where('initiative_id', $initiativeId);
        } else {
            $query->whereRaw('1 = 0');
        }

        return $query;
    }

    public function html(): HtmlBuilder
    {
        $initiativeIdSelector = $this->tableId === 'initiative-activities-show-table'
            ? '#show_initiative_id'
            : '#initiative_id';

        return $this->builder()
            ->setTableId($this->tableId)
            ->columns($this->getColumns())
            ->minifiedAjax(
                route('admin.activities.initiative-datatable'),
                "data.initiative_id = $('{$initiativeIdSelector}').val();"
            )
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
        $columns = [
            Column::make('id')->visible(false),
            Column::make('no')->title('No')->addClass('text-center')->orderable(false),
            Column::make('activities_description')->title('Description')->orderable(false),
            Column::make('partner_name')->title('Partner')->orderable(false)->visible(false),
            Column::make('interested_partners_col')->title('Interested Partners')->orderable(false)->visible(false),
            Column::make('directorates_col')->title('Directorates')->orderable(false)->visible(false),
            Column::make('start_date_formatted')->title('Start Date')->orderable(false),
            Column::make('end_date_formatted')->title('End Date')->orderable(false),
            Column::make('budget_col')->title('Budget')->orderable(false)->visible(false),
            Column::make('completion_col')->title('Completion')->orderable(false),
            Column::make('activity_status_name')->title('Activity Status')->orderable(false),
            Column::make('request_type_col')->title('Request Type')->orderable(false),
            Column::make('request_status_name')->title('Request Status')->orderable(false)->visible(false),
            Column::make('priority_badge')->title('Priority')->addClass('text-center')->orderable(false),
        ];

        if ($this->showActions) {
            $columns[] = Column::computed('action')
                ->exportable(false)
                ->printable(false)
                ->addClass('text-center')
                ->orderable(false);
        }

        return $columns;
    }

    protected function filename(): string
    {
        return 'InitiativeActivities_' . date('YmdHis');
    }
}
