<?php
/*
 * Description of email
 * @author tyten
 */

include 'phpMailer/class.phpmailer.php';

class email{
    //put your code here
  private   $_mailer,$_content,
            $_footer='<p style="line-height:22px;font-size:16px;margin-top:25px;margin-bottom:0px;font-family:helvetica,sans-serif">Thank you</p>
            <p style="line-height:22px;font-size:16px;margin-top:4px;margin-bottom:0px;font-family:\'Source Sans Pro\',\'Helvetica Neue\',Helvetica,Arial,sans-serif"><a style=" text-decoration: none; color: #ff962f; font-weight: 100;" href="http://mypola.lk/" target="_blank" ><b style="color: #ff962f; font-weight: 900;">My</b>pola.lk</a></p>
            <table style="width:100%;max-width:600px;margin-top:0px" border="0" cellspacing="0" cellpadding="0">
            <tbody>
            <tr>
            <td style="margin-top:0px;padding-top:2px;line-height:22px;font-size:11px;font-family:helvetica,sans-serif;color:#7a7a7a;width:100%;max-width:600px;text-align:left"></td>
            </tr>
            </tbody>
            </table>
         
            </div>
            </body>',
          
            $_header =  '<div style="width:100%;max-width:600px;margin:20px;font-size:14px;margin-top:20px;font-family:helvetica,sans-serif">
            <div class="adM"></div>
            <table style="border-bottom-style:solid;border-bottom-width:1px;border-bottom-color:#eeeeee;width:100%;max-width:600px" cellspacing="0" cellpadding="0">
            <tbody>
            <tr>
            <td style="padding-top:0px;padding-bottom:13px"><a href="www.mypola.lk" target="_blank"><img src="http://w3globe.com/mypola/beta/public/images/mypola-logo.png" border="0"></a></td>
            <td align="right" width="99" valign="top" style="padding-top:19px">
            <table width="99%" border="0" align="right" cellpadding="0" cellspacing="0" style=" color:#a0a0a0; font-family:Verdana,sans-serif;font-size:12px;letter-spacing:0px;text-align:right" >
            <tbody>
            <tr>
             <td width="33" align="right" valign="middle"></td>
            <td width="33" align="right" valign="middle"><a href="" target="_blank"></a></td>
            <td width="33" align="right" valign="middle"></td>
            </tr>
            </tbody>
            </table>              
            </td>
            </tr>
            </tbody>
            </table>',$_name='mypola.lk',$_start;
  
  
      function __construct() {
        $this->_mailer = new PHPMailer();
    }
    
    public function addHeaders(){
        
       //   $this->_mailer->IsSMTP();
         // $this->_mailer->SMTPDebug = 1;	
          //if use smtp email account add those details
      /*    $this->_mailer->SMTPSecure = 'ssl';
            $this->_mailer->Host = "mail.mypola.lk";
         $this->_mailer->Port = 25; */
      //   $this->_mailer->Username = "no-reply@mypola.lk";
       //  $this->_mailer->Password = "rG@=lyGC6+~M";  
      
        
        
        $this->_mailer->ReturnPath="no-reply@".$this->_name;
        $this->_mailer->Sender = "no-reply@".$this->_name;	
        $this->_mailer->SetFrom("no-reply@".$this->_name, "no-reply@".$this->_name);
        $this->_mailer->AddReplyTo("no-reply@".$this->_name, "no-reply@".$this->_name);
    }
    

            
    
    public function addResiprnt($address,$name=NULL){
        if($name==NULL){ $name=$address;}
        if($this->_mailer->addAddress($address, $name));
        
        
    }
    
    public function addContent($details=array(),$type='p'){
        switch ($type) {
            case 'h':
                //add paragraph
                foreach ($details as $value) {
                    $this->_content .= '<p style="line-height:22px;font-size:16px;margin-top:25px;margin-top:5px;font-weight: 600;font-family:helvetica,sans-serif">'.$value.'</p>'; 
                }
                break;
            case 'p':
                //add paragraph
                foreach ($details as $value) {
                    $this->_content .= '<p style="line-height:22px;font-size:16px;margin-top:25px;margin-bottom:0px;font-family:helvetica,sans-serif">'.$value.'</p>'; 
                }
                break;
            case 'li':
                //list
                $this->_content .='<ul>';
                foreach ($details as $value) {
                    $this->_content .= '<li>'.$value.'</li>'; 
                }
                $this->_content .='</ul>';
                break;
            case 'link':
                //link
                foreach ($details as $key=>$value) {
                   $this->_content .= '<a href="'.$key.'" target="_new">'.$value.'</a>';  
                }
            break;
            case 'table':
                //table with two colum
                $this->_content .= '<table style="border-bottom-style:solid;border-bottom-width:1px;border-bottom-color:#eeeeee;width:100%;max-width:600px" cellspacing="0" cellpadding="0">
                                    <tbody>';
                foreach ($details as $key=>$value) {
                   $this->_content .= '<tr>
                <td width="50%" align="left" valign="middle">'.$key.'</td>
                <td width="50%" align="left" valign="middle">'.$value.'</td>
              </tr>';  
                }
                $this->_content .= '</tbody></table>';
                break;
            case 'talbemulti':
                //table with  multi colum
                $this->_content .= '<table style="border-bottom-style:solid;border-bottom-width:1px;border-bottom-color:#eeeeee;width:100%;max-width:600px" cellspacing="0" cellpadding="0">
                                    <tbody>';
                foreach ($details as  $value) {
                   $this->_content .= '<tr>'; 
                    foreach ($value as $v) {
                   $this->_content .= '
                <td  align="left" valign="middle">'.$v.'</td>';                
                }
               $this->_content .= '</tr>';
                }
                $this->_content .= '</tbody></table>';
                break;
            default:
               foreach ($details as $value) {
                    $this->_content .= '<p style="line-height:22px;font-size:16px;margin-top:25px;margin-bottom:0px;font-family:helvetica,sans-serif">'.$value.'</p>'; 
                } 
                break;
        }
    }
    
    public function addSubject($subject,$name){
        $this->_mailer->Subject = $subject;
        $this->_start = '<p style="line-height:22px;font-size:16px;margin-top:25px;margin-bottom:0px;font-family:helvetica,sans-serif">Hello '.$name.'</p>'
                . '<p style="line-height:22px;font-size:16px;margin-top:25px;margin-bottom:0px;font-family:helvetica,sans-serif"><u>'.$subject.'</u></p>';
    }
    
    public function atachment($path, $name){
        $this->_mailer->addAttachment($path, $name);
    }

    

    public function sendMail(){
        $this->_mailer->msgHTML($this->_header.$this->_start.$this->_content.$this->_footer);
        try {
            $this->_mailer->send();
            return TRUE;
        } catch (Exception $exc) {
            throw new Exception($exc->getMessage());
        }
        }
        
        public function getContent(){
            return $this->_content;
        }
    
    
    
}
