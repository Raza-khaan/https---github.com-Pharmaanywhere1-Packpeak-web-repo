<?php
   
namespace App\Exports;
   
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\Exportable;

use App\Models\Admin\Admin;
// use App\Financial_layouts;

class Returnexport implements FromCollection, WithHeadings
{

    use Exportable;
   
    // protected $year;
    
    public function __construct(array $ids,array $website_Ids)
    {
    //    print_r($ids); die; 
        
    }

   

     public function collection()
    {
        return Admin::all(); 
    } 

   /*  public function collection()
    {
        echo $this->id;  die; 
        $current_date=date('Y-m-d');
        // return Bookings::get_financial_report_data();
        //return  Financial_layouts::get_financial_report_data($current_date); 
    }  */
    
    public function headings(): array
    {
        return [
            'Date',
            'Doc',
            'Description',
            'Booking Type',
            'Debit A/C',
            'Credit A/C',
            'Amount (INR)',
            'CC1',
            'CC2',
            'CC3'
            // 'Booking Status'
        ];
    }
}