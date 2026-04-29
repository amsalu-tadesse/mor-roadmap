<?php

namespace App\Exports;

use App\Models\Suspect;
use App\Models\Crime;
use App\Models\ReportSetting;
use App\Models\ItemDetail;
use App\Models\AnimalCondition;
use App\Models\IsTrafficing;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Illuminate\Support\Facades\DB;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;


class ReportExport implements FromCollection, WithHeadings, WithStyles, ShouldAutoSize
{
    public function collection()

    {
        $report_setting = ReportSetting::where("status", 1)->get();
        $column_names = $report_setting->pluck('code')->reject(function ($code) {
            return $code == "trafficing_crimes.id";
        })->toArray();

        $columnKeyToReplace = array_search("verdict_types.name", $column_names);
        $itemsColumnKey = array_search("item_details.label", $column_names);

        if ($columnKeyToReplace !== false) {
            $column_names[$columnKeyToReplace] = \DB::raw("CONCAT(verdict_types.name, ', ', crimes.verdict) as CourtDecision");
        }

        if($itemsColumnKey !==false){
            $column_names[$itemsColumnKey] =  \DB::raw("GROUP_CONCAT(CONCAT(
                item_categories.name, ' (', item_types.name, ') : ',
                item_details.count, ' ',
                count_units.name, ' ',
                item_details.label, ' , ',
                item_details.measurement, ' ',
                measurement_units.name, ' (', item_details.estimate_actual, ') , '
            )) AS item_details");
        }


        $report_setting1 = ReportSetting::where(["status"=>1, "crime_type"=>0])->get();
        $column_names1 = $report_setting1->pluck('code')->reject(function ($code) {
            return $code == "trafficing_crimes.id";
        })->toArray();

        $columnKeyToReplace1 = array_search("verdict_types.name", $column_names1);

        if ($columnKeyToReplace1 !== false) {
            $column_names1[$columnKeyToReplace1] = \DB::raw("CONCAT(verdict_types.name, ', ', crimes.verdict) as CourtDecision2");
        }


        $all_col=ReportSetting::all();
        $animal_col=AnimalCondition::all();
        $is_trafficing = IsTrafficing::all();
        $firstTrafficing = $is_trafficing->first();

        $data = Crime::leftJoin('suspect_crimes', 'crimes.id', '=', 'suspect_crimes.crime_id')
        ->leftJoin('suspects', 'suspect_crimes.suspect_id', '=', 'suspects.id')
        ->leftJoin('zones', 'crimes.zone_id', '=', 'zones.id')
        ->join('trafficing_crimes', 'crimes.id', '=', 'trafficing_crimes.crime_id')
        ->leftJoin('item_details', 'trafficing_crimes.id', '=', 'item_details.trafficing_crime_id')
        ->leftJoin('count_units', 'item_details.count_unit_id', '=', 'count_units.id')
        ->leftJoin('measurement_units', 'item_details.measurement_unit_id', '=', 'measurement_units.id')
        ->leftJoin('item_types', 'item_details.item_type_id', '=', 'item_types.id')
        ->leftJoin('item_categories', 'item_details.item_category_id', '=', 'item_categories.id')
        ->leftJoin("countries as nationaitty","suspects.nationality" ,"=","nationaitty.id")
        ->join('routes', 'trafficing_crimes.route_id', '=', 'routes.id')
        ->leftJoin("countries as country_origin","routes.country_origin","country_origin.id")
        ->leftJoin("countries as country_transit","routes.country_transit","country_transit.id")
        ->leftJoin("countries as country_destination","routes.country_destination","country_destination.id")
        ->leftJoin('regions as region_origin', 'routes.region_origin', '=', 'region_origin.id')
        ->leftJoin('regions as region_transit', 'routes.region_transit', '=', 'region_transit.id')
        ->leftJoin('regions as region_destination', 'routes.region_destination', '=', 'region_destination.id')
        ->leftJoin('zones as zone_origin', 'routes.zone_origin', '=', 'zone_origin.id')
        ->leftJoin('zones as zone_destination', 'routes.zone_destination', '=', 'zone_destination.id')
        ->leftJoin('verdict_types', 'crimes.verdict_type_id', '=', 'verdict_types.id')
        ->join('seizuring_bodies', 'crimes.seizuring_body_id', '=', 'seizuring_bodies.id')
        ->leftJoin('trafficing_statuses', 'trafficing_crimes.trafficing_status_id', '=', 'trafficing_statuses.id')
        ->leftJoin('transport_methods', 'trafficing_crimes.transport_method_id', '=', 'transport_methods.id')
        ->leftJoin('crime_types', 'crimes.crime_type_id', '=', 'crime_types.id')
        ->leftJoin('species_seizured', 'crimes.id', '=', 'species_seizured.crime_id')
        ->leftJoin('animals', function($join) {
            $join->on('species_seizured.animal_id', '=', 'animals.id');
        });

    foreach ($animal_col as $column) {
        // Assuming $column has keys 'code' and 'status'
        if ($column['name'] == 'Elephant and Rhino' && $column['status'] == 1) {
            // Use the 'whereIn' method to match multiple values
            $data->whereIn('animals.id', [1, 2]);
        } elseif ($column['name'] == 'Elephant Only' && $column['status'] == 1) {
            // Use the 'where' method for a single value
            $data->where('animals.id', '=', 1);
        }
    }
    $data = $data
    ->groupBy(
        'suspects.full_name',
        'nationaitty.nationality',
        'suspects.passport',
        'suspects.age',
        'suspects.gender',
        'crime_types.name',
        'crimes.crime_commited_time',
        'crimes.crime_description',
        'crimes.exhibit_details',
        'verdict_types.name',
        'seizuring_bodies.name',
        'crimes.crime_commited_place',
        'zones.name',
        'animals.name',
        'country_origin.name',
        'region_origin.name',
        'zone_origin.name',
        'country_transit.name',
        'region_transit.name',
        'country_destination.name',
        'region_destination.name',
        'zone_destination.name',
        'trafficing_statuses.name',
        'crimes.verdict',
        'trafficing_crimes.id'
    )
    ->select(
       $column_names
    )
    ->get();








if(!($firstTrafficing->name == "only trafficing" && $firstTrafficing->status == 1)){
    $data2= Crime::leftJoin('suspect_crimes', 'crimes.id', '=', 'suspect_crimes.crime_id')
    ->leftJoin('suspects', 'suspect_crimes.suspect_id', '=', 'suspects.id')
    ->leftJoin("countries as nationaitty","suspects.nationality" ,"=","nationaitty.id")
    ->join('zones', 'crimes.zone_id', '=', 'zones.id')
    ->join('verdict_types', 'crimes.verdict_type_id', '=', 'verdict_types.id')
    ->join('seizuring_bodies', 'crimes.seizuring_body_id', '=', 'seizuring_bodies.id')
    ->join('crime_types', 'crimes.crime_type_id', '=', 'crime_types.id')
    ->leftJoin('species_seizured', 'crimes.id', '=', 'species_seizured.crime_id')
    ->leftJoin('animals', function($join) {
    $join->on('species_seizured.animal_id', '=', 'animals.id');
    });
    foreach ($animal_col as $column) {
        // Assuming $column has keys 'code' and 'status'
        if ($column['name'] == 'Elephant and Rihno' && $column['status'] == 1) {
            // Use the 'whereIn' method to match multiple values
            $data2->whereIn('animals.id', [1, 2]);
        } elseif ($column['name'] == 'Elephant Only' && $column['status'] == 1) {
            // Use the 'where' method for a single value
            $data2->where('animals.id', '=', 1);
        }
    }

$data2=$data2->where("crimes.crime_category_id","!=",4)

->select(
    $column_names1
)
->get();


foreach($data2 as $dt){
$data[]=$dt;
}
}

// dd($data, $data2, $data3);


          /*  $xx=ReportSetting::where("name","ID")->where("status",1)->get();

            if($xx){
            $item_detail=ItemDetail::all();
                foreach($data as $k=>$dat){

                    $trafficing_crime_id=$dat->id;

                    $found = false;


                    foreach($item_detail as $item){


                        if($item->trafficing_crime_id == $trafficing_crime_id)
                        {


                            $string = '';

                            if($item->measurement) //add measurement
                            {

                                $string .= $item->measurement.' '.$item->measurementUnit->name. ', ';
                            }

                            if($item->count) //add count
                            {

                                $string .= $item->count.' '.$item->countUnit->name. ', ';

                            }

                            $string .= $item->item_type. ', '.$item->description;
                            $data[$k]->{$item->label}=$string;

                            // $data[$k]->{$item->count}=$item->count;
                            // $data[$k]->{$item->measurement}=$item->measurement;
                            // $data[$k]->{$item->description}=$item->description;
                            // $data[$k]->{$item->measurement_unit}=$item->measurement_unit;
                            // $data[$k]->{$item->item_type}=$item->item_type;
                          //  break;
                        }
                        else {
                            $data[$k]->{$item->label}=''; //skip cell.
                        }

                        unset($dat->id);

                }


            }
        }*/


            // foreach($item_detail as $item){
            //  //  $temp[]=['one','two','three'];
            //  if($item)
            //  $data0 = isset($arr2[$activetrainee->userId])?$arr2[$activetrainee->userId]:'';
            //    $headings[]=$item->label;

            // }
      //  dd($data,$item_detail );

return $data;

    }

    public function headings(): array
    {

        $report_setting = ReportSetting::where("status", 1)->get();


        $headings = [];

       foreach ($report_setting as $name) {
   if($name->name=='ID') continue;
        $headings[] = $name->name;
     }

     $status=ReportSetting::where(["name"=>"ID","status"=>1])->get();
        //  foreach($xx as $x){
        //     $status=$x->status;
        //  }


//   if($status){
//     $item_detail=ItemDetail::all();
//      foreach($item_detail as $item){
//       //  $temp[]=['one','two','three'];

//         $headings[]=$item->label;
//         // $headings[]=$item->count;
//         // $headings[]=$item->measurement;
//         // $headings[]=$item->description;
//         // $headings[]=$item->measurement_unit;
//         // $headings[]=$item->item_type;

//      }
//     }

    return $headings;
    }


    public function styles(Worksheet $sheet)
    {
        return [
            1 => [
                'font' => ['bold' => true],
                'fill' => ['fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID, 'startColor' => ['argb' => 'FFFF00']],
            ],
        ];
    }
}



