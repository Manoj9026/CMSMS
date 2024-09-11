<?php 

 require_once (ROOT.DS.'tyt'.DS.'lib'.DS.'TCPDF-master'.DS.'tcpdf.php');
class pdf extends TCPDF {
	
    // Page footer
    public function Footer() {
        
        $this->SetTextColorArray($this->footer_text_color);
        //set style for cell border
        $line_width = (0.85 / $this->k);
        $this->SetLineStyle(array('width' => $line_width, 'cap' => 'butt', 'join' => 'miter', 'dash' => 0, 'color' => $this->footer_line_color));

        // Position at 15 mm from bottom
        $this->SetY(-19);
        // Set font
        $this->SetFont('helvetica', 'I', 8);
        // Page number 

        $this->Cell(0, 10, 'Printed By : '.FOOTER_TEXT, 0, false, 'L', 0, '', 0, false, 'T', 'M');
         $this->Cell(0, 10, 'Page '.$this->getAliasNumPage().'/'.$this->getAliasNbPages(), 0, false, 'R', 0, '', 0, false, 'T', 'M');
         $this->SetY(-14);
         $this->SetFont('helvetica', 'I', 6);
         $this->Cell(0, 8, 'Software Solution by Directorate of Information Technology - SRI LANKA ARMY', 0, false, 'R', 0, '', 0, false, 'T', 'M');
       
    }
}
