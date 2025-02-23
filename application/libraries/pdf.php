<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
 
class pdf {
    
    function pdf()
    {
        $CI = & get_instance();
        log_message('Debug', 'mPDF class is loaded.');
    }
 
    function load($format='A4', $orientation='P',$mgl=10, $mgr=10, $mgt=8)
    {
        include_once APPPATH.'third_party/MPDF/mpdf.php';
         
        if ($param == NULL)
        {
            $param = '"utf-8", "Folio"';	
			//echo $param;			
        }else{
			//echo $param;
		}
       
   return new mPDF($mode='',$format,$default_font_size=0,$default_font='',$mgl,$mgr,$mgt,$mgb=16,$mgh=9,$mgf=9, $orientation);
	  
    }
}