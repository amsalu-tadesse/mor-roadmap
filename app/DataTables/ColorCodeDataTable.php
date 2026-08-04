<?php

namespace App\DataTables;

use App\Constants\Constants;
use App\Models\ColorCode;
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

class ColorCodeDataTable extends DataTable
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
            ->editColumn('color', function ($row) {
                return '
                <span style="
                    display:inline-block;
                    width:30px;
                    height:30px;
                    background-color:' . $row->color . ';
                    border:1px solid #ccc;
                    border-radius:4px;
                "></span>
                <span class="ms-2">' . $row->color . '</span>
            ';
            })
            ->addColumn('action', function ($color_code) {
                return view('components.action-buttons', [
                    'row_id' => $color_code->id,
                    'show' => true,
                    'permission_delete' => 'color-code: delete',
                    'permission_edit' => 'color-code: edit',
                    'permission_view' => 'color-code: view',
                ]);
            })
            ->rawColumns(['no', 'color', 'action']);
    }

    /**
     * Get query source of dataTable.
     *
     * @param \App\Models\ColorCode $model
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function query(ColorCode $model): QueryBuilder
    {
        // return $model->newQuery();
        return $model::select([
            'id',
            'created_at' ,
'label',
'min',
'max',
'color',


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
            ->setTableId('color-codes-table')
            ->columns($this->getColumns())
            ->orderBy(3)
            ->minifiedAjax()
            ->selectStyleSingle()
            ->dom("'<'row'<'col-sm-12 col-md-2'l><'col-sm-12 col-md-6'B>
                           <'col-sm-12 col-md-4'f>><'row'<'col-sm-12'tr>>
                           <'row'<'col-sm-12 col-md-5'i><'col-sm-12 col-md-7'p>>'")
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
                'lengthMenu' => '_MENU_ records per page', // Customize the attribute
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
Column::make('label'),
Column::make('min'),
Column::make('max'),
// Column::make('color'),
Column::make('color')->title('Color'),

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
        return "color_codes". date('YmdHis');
    }
}
