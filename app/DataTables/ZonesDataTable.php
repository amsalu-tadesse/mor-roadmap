<?php

namespace App\DataTables;

use App\Constants\Constants;
use App\Models\Zone;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Html\Editor\Editor;
use Yajra\DataTables\Html\Editor\Fields;
use Yajra\DataTables\Services\DataTable;

use function Termwind\render;

class ZonesDataTable extends DataTable
{
    /**
     * Build DataTable class.
     *
     * @param QueryBuilder $query Results from query() method.
     * @return \Yajra\DataTables\EloquentDataTable
     */
    public function dataTable(QueryBuilder $query): EloquentDataTable
    {
        $index_column = 0;
        return (new EloquentDataTable($query))
            ->addColumn('no', function () use (&$index_column) {
                return ++$index_column;
            })
            //Region
            ->addColumn('region', function ($zone) {
                return $zone?->region;
            })->orderColumn('region', function ($query, $order) {
                $query->orderBy('region', $order);
            })->filterColumn('region', function ($zone, $keyword) {
                $sql = "regions.name like ?";
                $zone->whereRaw($sql, ["%{$keyword}%"]);
            })

            ->filter(function ($query) {
                if (request()->has('region_filter') and request()->input('region_filter')) {
                    $query->where('regions.name', '=', request('region_filter'));
                }
            }, true)

            ->addColumn('action', function ($zone) {
                return view('components.action-buttons', [
                    'row_id' => $zone->id,
                    'permission_delete'=>'zone: delete',
                     'permission_edit'=>'zone: edit',
                     'permission_view'=>'zone: view',
                ]);
            })
            ->rawColumns(['no', 'action']);
    }

    /**
     * Get query source of dataTable.
     *
     * @param \App\Models\Zone $model
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function query(Zone $model): QueryBuilder
    {
        // return $model::with(['region'])
        //     ->select(['id', 'name']);
        return $model::leftjoin('regions', 'region_id', '=', 'regions.id')
            ->select(['zones.id', 'zones.name as name', 'zones.created_at',  'regions.name AS region']);
    }

    /**
     * Optional method if you want to use html builder.
     *
     * @return \Yajra\DataTables\Html\Builder
     */
    public function html(): HtmlBuilder
    {
        return $this->builder()
            ->setTableId('zones-table')
            ->columns($this->getColumns())
            ->orderBy(4)
            // ->minifiedAjax()
            ->ajax([
                'url' => route('admin.zones.index'), // Update the route name to match your route definition
                'data' => 'function(d) {
                    d.region_filter = $("#region_filter").val();
                }',
            ])
            ->selectStyleSingle()
            ->dom(
                "<'row'<'col-sm-12 col-md-2'l><'col-sm-12 col-md-6'B>
                           <'col-sm-12 col-md-4'f>><'row'<'col-sm-12'tr>>
                           <'row'<'col-sm-12 col-md-5'i><'col-sm-12 col-md-7'p>>",
            )
            ->responsive(true)
            ->processing(true)
            ->autoWidth(false)
            ->buttons(
                [
                    [
                        'extend' => 'csvHtml5',
                        'text' => 'CSV',
                        'exportOptions' => [
                            'columns' => ':visible',
                        ],
                    ],
                    [
                        'extend' => 'excelHtml5',
                        'text' => 'Excel',
                        'exportOptions' => [
                            'columns' => ':visible',
                        ],
                    ],
                    [
                        'extend' => 'pdfHtml5',
                        'text' => 'PDF',
                        'exportOptions' => [
                            'columns' => ':visible',
                        ],
                    ],

                    [
                        'extend' => 'print',
                        'text' => 'Print',
                        'exportOptions' => [
                            'columns' => ':visible',
                        ],
                    ],
                    'colvis',
                ]
            )
            ->lengthMenu(Constants::PAGE_NUMBER()) // Customize the options here
            ->language([
                'lengthMenu' => '_MENU_ records per page', // Customize the label
            ]);
    }

    /**
     * Get the dataTable columns definition.
     *
     * @return array
     */
    public function getColumns(): array
    {
        return [
            Column::make('no')
                ->title('No')
                ->addClass('text-center')
                ->orderable(false),
            Column::make('name')->title('Name'),
            Column::make('region', 'region')
                ->title('Region')
                ->addClass('text-center'),
            Column::computed('action')
                ->exportable(false)
                ->printable(false)
                ->addClass('text-center')
                ->orderable(false),
             Column::make('created_at')->visible(false)

        ];
    }

    /**
     * Get filename for export.
     *
     * @return string
     */
    protected function filename(): string
    {
        return 'Zones' . date('YmdHis');
    }
}
