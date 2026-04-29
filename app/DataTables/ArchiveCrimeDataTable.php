<?php

namespace App\DataTables;

use App\Constants\Constants;
use App\Models\Crime;
use App\Models\CrimeCategory;
use App\Models\CrimeType;
use App\Models\ElephantSpecies;
use App\Models\SeizuringBody;
use App\Models\Species;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Services\DataTable;


class ArchiveCrimeDataTable  extends DataTable
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
            ->addColumn('suspectsName', function ($crime) {
                $suspects = explode(',', $crime->suspectsName);
                $badgeHtml = '';
                foreach ($suspects as $suspect) {
                    $badgeHtml .= "<span class='badge badge-success'>" . $suspect . "</span> ";
                }
                return rtrim($badgeHtml);
            })

            ->addColumn('action', function ($crime) {
                return view(
                    'components.action-button-for-archive',
                    [
                        'row_id' => $crime->id,
                        'crime' => true,
                        'permission_delete' => 'crime: delete',
                        'permission_edit' => 'crime: edit',
                        'permission_view' => 'crime: view',
                        'deleted' => true,
                    ]
                );
            })

            ->rawColumns(['no', 'suspectsName', 'action']);
    }

    /**
     * Get query source of dataTable.
     *
     * @param \App\Models\Crime $model
     * @return \Illuminate\Database\Eloquent\Builder
     */

    public function query(Crime $model): QueryBuilder
    {
        return $model::leftJoin('crime_types', 'crime_type_id', '=', 'crime_types.id')
            ->leftJoin('crime_categories', 'crimes.crime_category_id', '=', 'crime_categories.id')
            ->leftJoin('suspect_crimes', 'crimes.id', '=', 'suspect_crimes.crime_id')
            ->leftJoin('suspects', 'suspect_crimes.suspect_id', '=', 'suspects.id')->
            onlyTrashed()->select([
                'crimes.id',
                'crimes.crime_commited_time',
                'crimes.created_at',
                'crimes.file_number',
                'crime_types.name as crimeTypeName',
                'crime_categories.name as crimeCategoryName',
                DB::raw('GROUP_CONCAT(suspects.full_name) as suspectsName'),
            ])
            ->groupBy([
                'crimes.id',
                'crimes.crime_commited_time',
                'crimes.created_at',
                'crimes.file_number',
                'crime_types.name',
                'crime_categories.name',
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
            ->setTableId('crimes-table')
            ->columns($this->getColumns())
            ->orderBy(7)
            ->minifiedAjax()
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
            Column::make('file_number')->title('File No'),
            Column::make('suspectsName')->title('Suspects'),
            Column::make('crimeTypeName')->title('Crime Type'),
            Column::make('crimeCategoryName')->title('Crime Category'),
            Column::make('crime_commited_time')->title('Commited Date'),
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
        return 'Crimes' . date('YmdHis');
    }
}
