<?php

class Formlabel extends AppModel {

    public $useDbConfig = 'ngprs';
    public $useTable = 'ngdrstab_mst_formlabels';
    public $primaryKey = 'labelname';

    public function updatepo() {
    
     $query = $this->Find('all');
        $englbl = NULL;
        $mrlbl = NULL;
        $gujratilbl=NULL;
        foreach ($query as $qry) {
            $qry = $qry['Formlabel'];
            $lbls = explode('_ll', $qry['labelname']);
            
            $ll_activation_flag=$qry['ll_activation_flag'];
            $ll1_activation_flag=$qry['ll1_activation_flag'];
            $ll2_activation_flag=$qry['ll2_activation_flag'];
            
            /*
            if (count($lbls) > 1) {
                $englbl.="msgid \"" . $lbls[0] . "_ll\" \nmsgstr \"" . $qry['label_desc_ll'] . "\" \n\n";
                $englbl.="msgid \"" . $lbls[0] . "\" \nmsgstr \"" . $qry['label_desc_en'] . "\" \n\n";
                $gujratilbl.="msgid \"" . $lbls[0] . "\" \nmsgstr \"" . $qry['label_desc_en'] . "\" \n\n";

                $mrlbl.="msgid \"" . $lbls[0] . "\" \nmsgstr \"" . $qry['label_desc_ll'] . "\" \n\n";
                $mrlbl.="msgid \"" . $lbls[0] . "_ll\" \nmsgstr \"" . $qry['label_desc_en'] . "\" \n\n";
                 $gujratilbl.="msgid \"" . $lbls[0] . "\" \nmsgstr \"" . $qry['label_desc_ll1'] . "\" \n\n";
            } else {
                $englbl.="msgid \"" . $qry['labelname'] . "\" \nmsgstr \"" . $qry['label_desc_en'] . "\" \n\n";
                $mrlbl.="msgid \"" . $qry['labelname'] . "\" \nmsgstr \"" . $qry['label_desc_ll'] . "\" \n\n";
                $gujratilbl.="msgid \"" . $lbls[0] . "\" \nmsgstr \"" . $qry['label_desc_ll1'] . "\" \n\n";
            }*/
            
            $englbl.="msgid \"" . $qry['labelname'] . "\" \nmsgstr \"" . $qry['label_desc_en'] . "\" \n\n";
            if($ll_activation_flag=='Y')
            {
                if($qry['label_desc_ll']=='')
                    $mrlbl.="msgid \"" . $qry['labelname'] . "\" \nmsgstr \"" . $qry['label_desc_en'] . "\" \n\n";
                else
                    $mrlbl.="msgid \"" . $qry['labelname'] . "\" \nmsgstr \"" . $qry['label_desc_ll'] . "\" \n\n";
            }
            if($ll_activation_flag=='N'){
                $mrlbl.="msgid \"" . $qry['labelname'] . "\" \nmsgstr \"" . $qry['label_desc_en'] . "\" \n\n";
            }
            if($ll1_activation_flag=='Y')
            {
                if($qry['label_desc_ll1']=='')
                    $gujratilbl.="msgid \"" . $lbls[0] . "\" \nmsgstr \"" . $qry['label_desc_en'] . "\" \n\n";
                else
                    $gujratilbl.="msgid \"" . $lbls[0] . "\" \nmsgstr \"" . $qry['label_desc_ll1'] . "\" \n\n";
            }
                
        }

        $engfile = fopen(APP . 'Locale' . DS . 'eng' . DS . 'LC_MESSAGES' . DS . "default.po", "w") or die("Unable to open file!");
        $mrtfile = fopen(APP . 'Locale' . DS . 'll' . DS . 'LC_MESSAGES' . DS . "default.po", "w") or die("Unable to open file!");
        $gujratifile = fopen(APP . 'Locale' . DS . 'll1' . DS . 'LC_MESSAGES' . DS . "default.po", "w") or die("Unable to open file!");

        fwrite($engfile, $englbl);
        fclose($engfile);

        fwrite($mrtfile, $mrlbl);
        fclose($mrtfile);
        
         fwrite($gujratifile, $gujratilbl);
        fclose($gujratifile);

}
}
