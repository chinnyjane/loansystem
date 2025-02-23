<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

function pdf_create($html, $filename='', $stream=TRUE, $size="folio", $orientation="portrait") 
{
    require_once("dompdf/dompdf_config.inc.php");

        $dompdf = new DOMPDF();
		$dompdf->set_paper($size, $orientation);
		$dompdf->set_base_path(base_url()."assets/css/bootstrap.min.css");
        $dompdf->load_html($html);
        $dompdf->render();
		
        if ($stream) {
            $dompdf->stream($filename.".pdf", array( 'Attachment'=>0 ));
        } else {
            return $dompdf->output();
        }
}
?>