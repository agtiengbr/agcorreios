<?php
class agcorreiosimportcsvModuleFrontController extends ModuleFrontController
{
    public function __construct()
    {
        set_time_limit(0);
        ignore_user_abort(true);
        
        $filename=$_FILES["fileUpload"]["tmp_name"];    
        $return=AgCorreiosInterval::importCsvIntervals($filename);

        if(is_object($return)){
            echo json_encode([
                'error'=>true,
                'msg'=>$return->getMessage()
            ]);

        }elseif($return){
            echo json_encode([
                'error'=>false
            ]);

        }else{
            echo json_encode([
                'error'=>true,
                'msg'=>'CSV incompleto ou invalido'
            ]);
        }


        die();
    }
}