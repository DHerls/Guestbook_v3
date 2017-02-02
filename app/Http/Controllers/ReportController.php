<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;

use App\GuestRecord;

class ReportController extends Controller
{
    public function reportView() {
        return view('reports.index');
    }

    public function guestReceipt(GuestRecord $record) {
        $pdf = \PDF::loadView('reports.guestReceipt', compact('record'));
        return $pdf->download('guest_receipt_'.$record->member->last_names().'.pdf');
    }

    public function guestReport(Request $request) {

        $startDate = $this->startTime($request->start_year,$request->start_month,$request->start_date);
        $endDate = $this->endTime($request->end_year,$request->end_month,$request->end_date);

        Excel::create('guest_report_'.date('Y_m_d_h:i:s'), function($excel) use($startDate,$endDate){

            $excel->sheet('Summary By Week', function($sheet) use($startDate,$endDate){
                $records = DB::table('guest_guest_record as ggr')
                    ->selectRaw("STR_TO_DATE(CONCAT(YEARWEEK(MIN(ggr.created_at)), ' Sunday'), '%X%V %W') as 'Start Date',
  STR_TO_DATE(CONCAT(YEARWEEK(MIN(ggr.created_at)), ' Saturday'), '%X%V %W') as 'End Date',
  SUM(CASE WHEN g.type = 'adult' THEN 1 ELSE 0 END) Adults,
  SUM(CASE WHEN g.type = 'child' THEN 1 ELSE 0 END) Children,
  COUNT(*) 'Total Guests',
  SUM(CASE WHEN g.type = 'adult' AND ggr.free_pass = 1 THEN 1 ELSE 0 END) 'Adult Passes',
  SUM(CASE WHEN g.type = 'child' AND ggr.free_pass = 1 THEN 1 ELSE 0 END) 'Child Passes',
  SUM(CASE WHEN ggr.free_pass = 1 THEN 1 ELSE 0 END) 'Total Passes'")
                    ->join('guests as g','g.id','=','ggr.guest_id')
                    ->whereBetween('ggr.created_at', [$startDate,$endDate])
                    ->groupBy(DB::raw("YEARWEEK(ggr.created_at)"))
                    ->get()->toArray();

                foreach ($records as &$record) {
                    $record = (array)$record;
                    $record['Adults'] = intval($record['Adults']);
                    $record['Adult Passes'] = intval($record['Adult Passes']);
                    $record['Children'] = intval($record['Children']);
                    $record['Child Passes'] = intval($record['Child Passes']);
                    $record['Total Guests'] = intval($record['Total Guests']);
                    $record['Total Passes'] = intval($record['Total Passes']);
                }

                $sheet->fromArray($records, null , 'A1', true);

                $highestRow = $sheet->getHighestRow();

                $sheet->appendRow(["","Grand Totals",
                    "=SUM(C2:C{$highestRow})",
                    "=SUM(D2:D{$highestRow})",
                    "=SUM(E2:E{$highestRow})",
                    "=SUM(F2:F{$highestRow})",
                    "=SUM(G2:G{$highestRow})",
                    "=SUM(H2:H{$highestRow})"]);

                $highestRow++;

                $sheet->freezeFirstRow();

                $sheet->cells('A1:H1', function($cells) {
                    $cells->setFontWeight('bold');
                    $cells->setAlignment('center');
                    $cells->setBorder(array(
                        'bottom'   => array(
                            'style' => 'solid'
                        ),
                    ));
                });

                $sheet->cells("A{$highestRow}:H{$highestRow}", function($cells) {
                    $cells->setFontWeight('bold');
                    $cells->setBorder(array(
                        'top'   => array(
                            'style' => 'solid'
                        ),
                    ));
                });
            });

            $excel->sheet('Summary By Day', function($sheet) use($startDate, $endDate){
                $records = DB::select("SELECT day_of_week as 'Day of Week',
                  COUNT(*) as 'Number',
                  SUM(a) as 'Total Adults',
                  AVG(a) as 'Average Adults',
                  SUM(c) as 'Total Children',
                  AVG(c) as 'Average Children',
                  SUM(tg) as 'Total Guests',
                  AVG(tg) as 'Average Guests',
                  SUM(ap) as 'Total Adult Passes',
                  AVG(ap) as 'Average Adult Passes',
                  SUM(cp) as 'Total Child Passes',
                  AVG(cp) as 'Average Child Passes',
                  SUM(tp) as 'Total Passes',
                  AVG(tp) as 'Average Passes'
                FROM
                  (SELECT DAYNAME(ggr.created_at) day_of_week,
                           DAYOFWEEK(ggr.created_at) day_num,
                           TO_DAYS(ggr.created_at) date,
                           SUM(CASE WHEN g.type = 'adult' THEN 1 ELSE 0 END) a,
                           SUM(CASE WHEN g.type = 'child' THEN 1 ELSE 0 END) c,
                           COUNT(*) tg,
                           SUM(CASE WHEN g.type = 'adult' AND ggr.free_pass = 1 THEN 1 ELSE 0 END) ap,
                           SUM(CASE WHEN g.type = 'child' AND ggr.free_pass = 1 THEN 1 ELSE 0 END) cp,
                           SUM(CASE WHEN ggr.free_pass = 1 THEN 1 ELSE 0 END) tp
                    FROM guest_guest_record as ggr
                      INNER JOIN guests as g on g.id = ggr.guest_id
                      WHERE ggr.created_at >= '{$startDate}'
                    AND ggr.created_at <= '{$endDate}'
                    GROUP BY date
                  ) as temp
                GROUP BY day_of_week
                ORDER BY day_num
                ");

                $records = (array)$records;

                foreach ($records as &$record) {
                    $record = (array)$record;
                    $record['Number'] = intval($record['Number']);
                    $record['Total Adults'] = intval($record['Total Adults']);
                    $record['Average Adults'] = floatval($record['Average Adults']);
                    $record['Total Children'] = intval($record['Total Children']);
                    $record['Average Children'] = floatval($record['Average Children']);
                    $record['Total Guests'] = intval($record['Total Guests']);
                    $record['Average Guests'] = floatval($record['Average Guests']);
                    $record['Total Adult Passes'] = intval($record['Total Adult Passes']);
                    $record['Average Adult Passes'] = floatval($record['Average Adult Passes']);
                    $record['Total Child Passes'] = intval($record['Total Child Passes']);
                    $record['Average Child Passes'] = floatval($record['Average Child Passes']);
                    $record['Total Passes'] = intval($record['Total Passes']);
                    $record['Average Passes'] = floatval($record['Average Passes']);
                }

                $sheet->fromArray($records, null , 'A1', true);

                $sheet->freezeFirstRow();

                $sheet->cells('A1:N1', function($cells) {
                    $cells->setFontWeight('bold');
                    $cells->setAlignment('center');
                    $cells->setBorder(array(
                        'bottom'   => array(
                            'style' => 'solid'
                        ),
                    ));
                });
            });

            $excel->sheet('Grouped', function($sheet) use($startDate,$endDate) {
                $records = DB::table('guest_records as gr')
                    ->selectRaw("a.last_names as 'Member Last Name', SUM(CASE WHEN g.type = 'adult' THEN 1 ELSE 0 END) Adults,
                    SUM(CASE WHEN g.type = 'adult' AND ggr.free_pass = 1 THEN 1 ELSE 0 END) 'Adult Passes',
  SUM(CASE WHEN g.type = 'child' THEN 1 ELSE 0 END) Children, 
  SUM(CASE WHEN g.type = 'child' AND ggr.free_pass = 1 THEN 1 ELSE 0 END) 'Child Passes',
  gr.price as Price, 
  (CASE WHEN gr.payment_method = 'account' THEN 'Applied to Account' ELSE 'Paid Cash' END) as 'Payment Method', 
  u.name as 'Employee', DATE(MAX(gr.created_at)) as Date")
                    ->leftJoin('guest_guest_record as ggr','ggr.guest_record_id','=','gr.id')
                    ->join('users as u','u.id','=','gr.user_id')
                    ->join('guests as g','g.id','=','ggr.guest_id')
                    ->join(DB::raw("(SELECT member_id, GROUP_CONCAT(DISTINCT last_name SEPARATOR '/') as last_names
   FROM adults GROUP BY adults.member_id) a"),'a.member_id','=','gr.member_id')
                    ->whereBetween('gr.created_at', [$startDate,$endDate])
                    ->groupBy("gr.id")
                    ->orderBy("gr.created_at",'asc')
                    ->get()->toArray();

                foreach ($records as &$record) {
                    $record = (array)$record;
                    $record['Adults'] = intval($record['Adults']);
                    $record['Adult Passes'] = intval($record['Adult Passes']);
                    $record['Children'] = intval($record['Children']);
                    $record['Child Passes'] = intval($record['Child Passes']);
                    $record['Price'] = intval($record['Child Passes']);
                }

                $sheet->fromArray($records, null , 'A1', true);

                $sheet->freezeFirstRow();

                $sheet->cells('A1:I1', function($cells) {
                    $cells->setFontWeight('bold');
                    $cells->setAlignment('center');
                    $cells->setBorder(array(
                        'bottom'   => array(
                            'style' => 'solid'
                        ),
                    ));
                });
            });

            $excel->sheet('Individual', function($sheet) use($startDate,$endDate){
                $records = DB::table('guest_records as gr')
                    ->selectRaw("a.last_names as 'Member Last Names', g.first_name as 'Guest First Name', 
  g.last_name as 'Guest Last Name', g.city as 'Guest City', g.type as 'Guest Age', 
  (CASE WHEN ggr.free_pass <> 1 THEN 'Yes' ELSE 'No' END) as 'Free Pass', 
  u.name as 'Employee',     DATE(gr.created_at) as Date")
                    ->leftJoin('guest_guest_record as ggr','ggr.guest_record_id','=','gr.id')
                    ->join('users as u','u.id','=','gr.user_id')
                    ->join('guests as g','g.id','=','ggr.guest_id')
                    ->join(DB::raw("(SELECT member_id, GROUP_CONCAT(DISTINCT last_name SEPARATOR '/') as last_names
   FROM adults GROUP BY adults.member_id) a"),'a.member_id','=','gr.member_id')
                    ->whereBetween('gr.created_at', [$startDate,$endDate])
                    ->orderBy("gr.created_at",'asc')
                    ->get()->toArray();

                foreach ($records as &$record) {
                    $record = (array)$record;
                }

                $sheet->fromArray($records);

                $sheet->freezeFirstRow();

                $sheet->cells('A1:H1', function($cells) {
                    $cells->setFontWeight('bold');
                    $cells->setAlignment('center');
                    $cells->setBorder(array(
                        'bottom'   => array(
                            'style' => 'solid'
                        ),
                    ));
                });
            });

            $excel->sheet('Number of Visits', function($sheet) use($startDate,$endDate){
                $records = DB::table('guest_guest_record as ggr')
                    ->selectRaw("g.first_name as 'First Name',g.last_name as 'Last Name',g.city as 'City', COUNT(ggr.guest_id) as Visits")
                    ->join("guests as g",'g.id','=','ggr.guest_id')
                    ->whereBetween('ggr.created_at', [$startDate,$endDate])
                    ->groupBy('g.id')
                    ->orderBy("g.last_name",'asc')
                    ->get()
                    ->toArray();

                foreach ($records as &$record) {
                    $record = (array)$record;
                    $record['Visits'] = intval($record['Visits']);
                }

                $sheet->fromArray($records);

                $sheet->freezeFirstRow();

                $sheet->cells('A1:D1', function($cells) {
                    $cells->setFontWeight('bold');
                    $cells->setAlignment('center');
                    $cells->setBorder(array(
                        'bottom'   => array(
                            'style' => 'solid'
                        ),
                    ));
                });
            });

            $excel->setActiveSheetIndex(0);
        })->download('xlsx');
    }

    public function memberReport(Request $request) {
        $startDate = $this->startTime($request->start_year,$request->start_month,$request->start_date);
        $endDate = $this->endTime($request->end_year,$request->end_month,$request->end_date);

        Excel::create('guest_report_'.date('Y_m_d_h:i:s'), function($excel) use($startDate,$endDate) {
            $excel->sheet('Summary', function($sheet) {

            });

            $excel->sheet('Count', function($sheet) use($startDate, $endDate){
                $records = DB::table('members as m')
                    ->selectRaw("a.last_names as 'Member', COUNT(*) as 'Num Visits'")
                    ->leftJoin('member_records as mr','mr.member_id','=','m.id')
                    ->join(DB::raw("(SELECT member_id, GROUP_CONCAT(DISTINCT last_name SEPARATOR '/') as last_names
   FROM adults GROUP BY adults.member_id) a"),'a.member_id','=','m.id')
                    ->whereBetween('mr.created_at', [$startDate,$endDate])
                    ->orderBy("a.last_names",'asc')
                    ->groupBy("m.id")
                    ->get()->toArray();

                foreach ($records as &$record) {
                    $record = (array)$record;
                }

                $sheet->fromArray($records);

                $sheet->freezeFirstRow();

                $sheet->cells('A1:B1', function($cells) {
                    $cells->setFontWeight('bold');
                    $cells->setAlignment('center');
                    $cells->setBorder(array(
                        'bottom'   => array(
                            'style' => 'solid'
                        ),
                    ));
                });
            });

            $excel->sheet('Individual', function($sheet) use($startDate, $endDate){
                $records = DB::table('members as m')
                    ->selectRaw("a.last_names as 'Member', mr.num_members as 'Num Members', u.name as 'Employee', DATE(mr.created_at) as 'Date'")
                    ->leftJoin('member_records as mr','mr.member_id','=','m.id')
                    ->join('users as u','u.id','=','mr.user_id')
                    ->join(DB::raw("(SELECT member_id, GROUP_CONCAT(DISTINCT last_name SEPARATOR '/') as last_names
   FROM adults GROUP BY adults.member_id) a"),'a.member_id','=','m.id')
                    ->whereBetween('mr.created_at', [$startDate,$endDate])
                    ->orderBy("mr.created_at",'asc')
                    ->get()->toArray();

                foreach ($records as &$record) {
                    $record = (array)$record;
                }

                $sheet->fromArray($records);

                $sheet->freezeFirstRow();

                $sheet->cells('A1:D1', function($cells) {
                    $cells->setFontWeight('bold');
                    $cells->setAlignment('center');
                    $cells->setBorder(array(
                        'bottom'   => array(
                            'style' => 'solid'
                        ),
                    ));
                });
            });
        })->download('xlsx');
    }

    //Format array with dates to MySQL Date format
    private function startTime($year, $month, $date) {
        return $year . '-' . $month . '-' . $date . ' 00:00:01';
    }

    //Format array with dates to MySQL Date format
    private function endTime($year, $month, $date) {
        return $year . '-' . $month . '-' . $date . ' 23:59:59';
    }
}
