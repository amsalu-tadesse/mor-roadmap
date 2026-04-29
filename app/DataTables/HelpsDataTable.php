<?php

namespace App\DataTables;

use App\Constants\Constants;
use App\Models\Help;
use App\Models\Kpi;
use App\Models\User;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Html\Editor\Editor;
use Yajra\DataTables\Html\Editor\Fields;
use Yajra\DataTables\Services\DataTable;

use function Termwind\render;

class HelpsDataTable extends DataTable
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
            // ->addColumn('active',function ($help) {
            //         if ($help->active == 1) {
            //             return '<span id="nikebtn" class="fa fa-check-circle" style="color: green;"></span>';
            //         } else {
            //             return '<span  id="xbtn" class="fa fa-times-circle" style="color: red;"></span>';
            //         }
            //     }
            // )
            ->addColumn('action', function ($help) {
                return view('components.action-buttons', [
                    'row_id' => $help->id,
                     'show' => true,
                     'permission_delete'=>'help: delete',
                     'permission_edit'=>'help: edit',
                     'permission_view'=>'help: view',
                    ]);
            })
            ->rawColumns(['no',  'action']);
    }

    /**
     * Get query source of dataTable.
     *
     * @param \App\Models\Help $model
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function query(Help $model): QueryBuilder
    {
        // return $model->newQuery();
        return $model::select([
            'id',
            'title',
            'url',
            'route',
            // 'active',
            'created_at'
        ]);
    }

    /**
     * Optional method if you want to use html builder.
     *
     * @return \Yajra\DataTables\Html\Builder
     */
    public function html(): HtmlBuilder
    {
        return $this->builder()
            ->setTableId('helps-table')
            ->columns($this->getColumns())
            ->minifiedAjax()
            ->orderBy(5)
            ->selectStyleSingle()
            ->dom("<'row'<'col-sm-12 col-md-2'l><'col-sm-12 col-md-6'B>
                           <'col-sm-12 col-md-4'f>><'row'<'col-sm-12'tr>>
                           <'row'<'col-sm-12 col-md-5'i><'col-sm-12 col-md-7'p>>")
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
            Column::computed('no')->title('No')
                ->exportable(false)
                ->addClass('text-center')
                ->orderable(false),
            Column::make('title'),
            Column::make('url'),
            Column::make('route'),
            // Column::computed('active')
            //     ->exportable(false)
            //     ->printable(true)
            //     ->addClass('text-center')
            //     ->orderable(false),
            Column::computed('action')
                ->exportable(false)
                ->printable(true)
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
        return 'helps_' . date('YmdHis');
    }
}
