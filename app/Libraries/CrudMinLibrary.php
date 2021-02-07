<?php namespace App\Libraries;
use \App\Models\SystemModel;

class CrudMinLibrary
{
    protected $request;
    protected $libmin;
    protected $table = 'user';
    protected $opc_insert = false;
    protected $modal = false;
    protected $table_option = [];
    protected $form_option = [];
    
    public function __construct() {
        $this->request = service('request');
        $this->libmin = new SystemModel();
        $this->table_option = [];
        $this->table_option['modal'] = [];
        $this->form_option = [];
        $this->modal = false;
    }

    function getListTable($table = '', $table_option = 0, $form_option = 0){
        $this->table = $table;
        $this->table_option = $table_option;
        $this->form_option = $form_option;
        $this->checkInsert();
        $this->checkUpdate();
        if ( $this->checkRemove() ) {
            header('Location: '.(base_url().'/admin/'.$this->table));
            exit();
        };

        if ($this->checkFormInsert()) {
            return $this->getFromTable($table, $form_option);
        } else if ($this->checkFormUpdate()) {
            $id = $this->request->getGet('id');
            return $this->getFromTable($table, $form_option, $id);
        } else {
            
            $this->opc_insert = $this->table_option['insert'] == true && $this->table_option['insert'];

            $this->modal = $table_option['modal'] == true && $table_option['modal'];
            $thead = '<thead> <tr>';
            $query = $this->libmin->getSchema($table, $table_option['relation']);
            if ($table_option['show_field']) {
                $count = 0;
                foreach($query->getResultArray() as $row){
                    
                    foreach ($table_option['show_field'] as $row_to) {
                        if ($row['COLUMN_NAME'] == $row_to) {
                            //echo '<hr>'.$row['COLUMN_NAME'].' = '.$row_to;
                            $thead .= '<th>'.ucfirst($row['COLUMN_NAME']).'</th>';
                        }
                    }
                    
                    $count++;
                }
            }

            if (!($this->table_option['remove'] === false && $this->table_option['update'] === false)) {
                $thead .= '<th>Action</th>';
            }

            $thead .= '</tr> </thead>';
            $query = $this->libmin->getTablet($table, NULL, $table_option['relation']);
            $table = '<table id="example1" class="table table-bordered table-striped">'.$thead.'<tbody>';
            foreach ($query->getResultArray() as $row) {
                $td = '';
                foreach ($table_option['show_field'] as $temp => $field) {
                    $falg_table = true;
                    if ($table_option['type_field']){
                        foreach ($table_option['type_field'] as $key => $type){
                            if ($table_option['show_field'][$temp] == $key) {
                                foreach ($type as $key_type => $params){
                                    $falg_table = false;
                                    if( isset($type[$field]) ){
                                        if ($type[$field]['type'] == 'boolean') {
                                            $td .= '<td>'.$type[$field][$row[$field]].' </td>';
                                        }else{
                                            $td .= '<td>'.$row[$field].' </td>';
                                        }
                                    }else{
                                        $td .= '<td>'.$row[$field].' </td>';
                                    }

                                }
                            } else if ($falg_table) {
                                $td .= '<td>'.$row[$field].' </td>';
                            }
                        }
                    } else {
                        $td .= '<td>'.$row[$field].' </td>';
                    }
                }

                if (!($this->table_option['remove'] === false && $this->table_option['update'] === false)) {
                    $btn_remove = '';
                    $btn_update = '';
                    if ($this->table_option['remove'] == true) {$btn_remove = '<a type="button"  onclick="checkDelete('.$row['id_'.$this->table].', \''.$this->table.'\')" class="btn btn-danger btn-xs bg-maroon" data-toggle="modal" data-target="#modal-sm"> Del </a>';}
                    if ($this->table_option['update'] == true) {$btn_update = '<a type="button" href="'.base_url().'/'.$this->request->uri->getPath()."?opc=update&id=".$row['id_'.$this->table].'&table='.$this->table.'" class="btn btn-warning btn-xs bg-warning"> Edit </a>';}
                    $td .= '<td> '.$btn_remove.' '.$btn_update.' </td>';
                }

                $table .= '<tr>'.$td.'</tr>';
            }
            $table .= '</tbody><tfoot>'.$thead.'</tfoot></table>';
            $table = $this->cardBody($table);
            $table = $this->formRole($table);
            $table = $this->cardColor($table, $table_option['color'], 'List Users');
            return $table.$this->scriptTable();
        }
    }

    function checkRemove(){
        if ($this->table_option['remove'] == true) {
            if ($this->request->getGet('lib_min') == 'remove'){ 
                $this->libmin->removeTable($this->request->getGet('table'), $this->request->getGet('id'));
                return true;
            }
        }
    }

    function checkInsert($data = []){
        if ( $this->request->getGet('lib_min') == 'insert' ) {
            $query = $this->libmin->getSchema($this->table);
            $data = array();
            foreach($query->getResultArray() as $row){
                if($row['COLUMN_KEY'] != 'PRI'){
                    if ($row['DATA_TYPE'] == 'tinyint') {
                        if ($this->request->getPost($row['COLUMN_NAME']) == 'on') {
                            $data[$row['COLUMN_NAME']] = 1;
                        }else {
                            $data[$row['COLUMN_NAME']] = 0;
                        }
                    } else {
                        $flag_file = true;
                        if (isset($this->form_option['type_field'])) {
                            foreach ($this->form_option['type_field'] as $key => $type){
                                foreach ($type as $key_type => $params){ 
                                    if ($key_type == $row['COLUMN_NAME']) {
                                        $flag_file = true;
                                        $save_file = false;
                                        $array_name_file = '';
                                        if ( $params['type'] == 'file') {
                                            if (isset($params['multiple']) && $params['multiple'] == true ) {
                                                $files = $this->request->getFiles($row['COLUMN_NAME']);
                                                foreach($files[$row['COLUMN_NAME']] as $file_as) {
                                                    if ($file_as->isValid() && ! $file_as->hasMoved()) {
                                                        $newName = $file_as->getRandomName();
                                                        $file_ext = $file_as->getClientExtension();
                                                        foreach ($params as $check_ext => $ext_data){
                                                            $ext = explode(",",$ext_data);
                                                            foreach ($ext as $type_ext) {
                                                                if ($file_ext == $type_ext) {
                                                                    $array_name_file .= $newName.',';
                                                                    $write = str_replace("writable/","",WRITEPATH);
                                                                    $file_as->move($write.'public/uploads',$newName);
                                                                    $flag_file = false;
                                                                    $save_file = true;
                                                                }
                                                            } 
                                                        }
                                                    }
                                                }
                                                if ($save_file === true) {
                                                    $data[$row['COLUMN_NAME']] = substr($array_name_file, 0, -1);
                                                }
                                            } else {
                                                $file = $this->request->getFile($row['COLUMN_NAME']);
                                                $newName = $file->getRandomName();
                                                $file_ext = $file->getClientExtension();
                                                $file_name = $file->getName();

                                                foreach ($params as $check_ext => $ext_data){
                                                    $ext = explode(",",$ext_data);
                                                    foreach ($ext as $type_ext) {
                                                        if ($file_ext == $type_ext) {
                                                            $write = str_replace("writable/","",WRITEPATH);
                                                            $file->move($write.'public/uploads',$file_name.'-'.$newName);
                                                            $data[$row['COLUMN_NAME']] = $file_name.'-'.$newName;
                                                            $flag_file = false;
                                                        }
                                                    } 
                                                }
                                            }
                                        } else {
                                            $data[$row['COLUMN_NAME']] = $this->request->getPost($row['COLUMN_NAME']);
                                            $flag_file = false;
                                        }
                                    }
                                }
                            }
                        }
                        if ($flag_file) {
                            if (is_array($this->request->getPost($row['COLUMN_NAME']))) {
                                $arrays_string = '';
                                foreach ($this->form_option['relation'] as $key => $type){
                                    foreach ($type as $key_type => $params){ 
                                        if ($key_type === 'field_relation'){
                                            if ($row['COLUMN_NAME'] === $params) {
                                                foreach ($type as $key_type_val => $params_val){ 
                                                    if ($key_type_val === 'multiple'){
                                                        foreach($this->request->getPost($params) as $selected_val) {
                                                            $arrays_string .= $selected_val.",";
                                                        }
                                                    }
                                                }
                                            }
                                        }
                                    }
                                }
                                $data[$row['COLUMN_NAME']] = $arrays_string;
                            } else {
                                $data[$row['COLUMN_NAME']] = $this->request->getPost($row['COLUMN_NAME']);
                            }
                        }
                    }
                }else {
                    $id = $this->request->getPost($row['COLUMN_NAME']);
                }
            }
            $this->libmin->setTablet($this->table, $data);
        }
    }

    function checkUpdate(){
        $id = NULL;
        if ( $this->request->getGet('lib_min') == 'update' ) {
            $query = $this->libmin->getSchema($this->table);
            $data = array();
            foreach($query->getResultArray() as $row){
                if($row['COLUMN_KEY'] != 'PRI'){
                    if ($row['DATA_TYPE'] == 'tinyint') {
                        if ($this->request->getPost($row['COLUMN_NAME']) == 'on') {
                            $data[$row['COLUMN_NAME']] = 1;
                        } else {
                            $data[$row['COLUMN_NAME']] = 0;
                        }
                    } else {
                        $flag_file = true;
                        if (isset($this->form_option['type_field'])) {
                            foreach ($this->form_option['type_field'] as $key => $type){
                                foreach ($type as $key_type => $params){
                                    if ($key_type == $row['COLUMN_NAME']) {
                                        $flag_file = false;
                                        $save_file = false;
                                        $array_name_file = '';

                                        if ( $params['type'] == 'file') {
                                            if (isset($params['multiple']) && $params['multiple'] === true ) {
                                                $files = $this->request->getFiles($row['COLUMN_NAME']);
                                                foreach($files[$row['COLUMN_NAME']] as $file_as) {
                                                    if ($file_as->isValid() && ! $file_as->hasMoved()) {
                                                        $newName = $file_as->getRandomName();
                                                        $file_ext = $file_as->getClientExtension();
                                                        $file_name = $file_as->getName();
                                                        foreach ($params as $check_ext => $ext_data){
                                                            $ext = explode(",",$ext_data);
                                                            foreach ($ext as $type_ext) {
                                                                if ($file_ext == $type_ext) {
                                                                    $array_name_file .= $file_name.'-'.$newName.',';
                                                                    $write = str_replace("writable/","",WRITEPATH);
                                                                    $file_as->move($write.'public/uploads',$file_name.'-'.$newName);
                                                                    $flag_file = false;
                                                                    $save_file = true;
                                                                }
                                                            }
                                                        }
                                                    }
                                                }
                                                if ($save_file === true) {
                                                    $data[$row['COLUMN_NAME']] = substr($array_name_file, 0, -1);
                                                }
                                            }else {
                                                $file = $this->request->getFile($row['COLUMN_NAME']);
                                                $newName = $file->getRandomName();
                                                $file_ext = $file->getClientExtension();
                                                $file_name = $file->getName();

                                                foreach ($params as $check_ext => $ext_data){
                                                    $ext = explode(",",$ext_data);
                                                    foreach ($ext as $type_ext) {
                                                        if ($file_ext == $type_ext) {
                                                            $write = str_replace("writable/","",WRITEPATH);
                                                            $file->move($write.'public/uploads',$file_name.'-'.$newName);
                                                            $data[$row['COLUMN_NAME']] = $file_name.'-'.$newName;
                                                            $flag_file = false;
                                                        }
                                                    }
                                                }
                                            }
                                        } else {
                                            $files = $this->request->getFiles($row['COLUMN_NAME']);
                                            $data[$row['COLUMN_NAME']] = $this->request->getPost($row['COLUMN_NAME']);
                                            $flag_file = false;
                                        }
                                    }
                                }
                            }
                        }
                        if ($flag_file) {
                            if (is_array($this->request->getPost($row['COLUMN_NAME']))) {
                                $arrays_string = '';
                                foreach ($this->form_option['relation'] as $key => $type){
                                    foreach ($type as $key_type => $params){ 
                                        if ($key_type === 'field_relation'){
                                            if ($row['COLUMN_NAME'] === $params) {
                                                foreach ($type as $key_type_val => $params_val){ 
                                                    if ($key_type_val === 'multiple'){
                                                        foreach($this->request->getPost($params) as $selected_val) {
                                                            $arrays_string .= $selected_val.",";
                                                        }
                                                    }
                                                }
                                            }
                                        }
                                    }
                                }
                                $data[$row['COLUMN_NAME']] = $arrays_string;
                            } else {
                                $data[$row['COLUMN_NAME']] = $this->request->getPost($row['COLUMN_NAME']);
                            }
                        }
                    }
                }else {
                    $id = $this->request->getPost($row['COLUMN_NAME']);
                }
            }
            // exit();
            $this->libmin->updateTable($this->table, $data, $id);
        }
    }

    function checkFormInsert(){
        if ( $this->request->getGet('opc') == 'insert' ) {
            return true;
        }
    }

    function checkFormUpdate(){
        if ( $this->request->getGet('opc') == 'update' ) {
            return true;
        }
    }

    function getFromTable($table = '', $form_option = false, $id = 0){

        $this->checkInsert($table);
        
        $query_data = NULL;
        
        $select2 = '';
        $array_relation = array();
        if (isset($form_option['relation'])) {
            foreach ($form_option['relation'] as $count => $field) {
                $query_relation = $this->libmin->getTablet($field['table']);
                $multiple = isset($field['multiple']) ? 'multiple="multiple"' : '';
                $array_mul = isset($field['multiple']) ? '[]' : '';
                $select2 = '<div class="form-group">
                                <label>'.ucfirst($field['table']).' '.$field['field_show'].'</label>
                                <select class="form-control select2bs4" '.$multiple.' name="'.$field['field_join'].$array_mul.'" style="width: 100%;">';
                $id_relation = 0;
                if ($id != 0) {
                    $query_data = $this->libmin->getTablet($table, $id);
                    foreach($query_data->getResultArray() as $row_data){
                        foreach($query_relation->getResultArray() as $relation){
                            $id_relation = $field['field_relation'];
                            if ($relation[$field['field_join']] == $row_data[$field['field_relation']]) {                                
                                $select2 .= ' <option selected value="'.$relation[$field['field_relation']].'">'.$relation[$field['field_show']].'</option>';
                            } else {
                                
                                if (strpos($row_data[$field['field_relation']], ',')) {
                                    $is_strpos = true;
                                    $item = explode(",", $row_data[$field['field_relation']]);
                                    foreach ($item as $item_separator) {
                                        if ($item_separator === $relation[$field['field_relation']]) {  
                                            $select2 .= ' <option selected value="'.$relation[$field['field_relation']].'">'.$relation[$field['field_show']].'</option>';
                                            $is_strpos = false;
                                        }
                                    }
                                    if ($is_strpos === true) {
                                        $select2 .= ' <option value="'.$relation[$field['field_relation']].'">'.$relation[$field['field_show']].'</option>';
                                    }
                                } else {
                                    $select2 .= ' <option value="'.$relation[$field['field_relation']].'">'.$relation[$field['field_show']].'</option>';
                                }
                            }
                            
                        }
                    }
                } else {
                    foreach($query_relation->getResultArray() as $relation){
                        $id_relation = $field['field_relation'];
                        $select2 .= ' <option value="'.$relation[$field['field_relation']].'">'.$relation[$field['field_show']].'</option>';
                    }
                }
                $select2 .= '</select></div>';
                array_push($array_relation, array($id_relation => $select2));
            }
        }

        $form = '';
        $query = $this->libmin->getSchema($table);

        $field_show = null;
        $field_flag = true;

        if ($id != 0) {
            $query_data = $this->libmin->getTablet($table, $id);
            foreach($query_data->getResultArray() as $row_data){
                foreach($query->getResultArray() as $row){
                    $type   = $row['DATA_TYPE'];
                    $id     = $row['COLUMN_NAME'];
                    $name   = $row['COLUMN_NAME'];
                    $placeholder = $row['COLUMN_NAME'];
        
                    $form .= "<div class='form-group'>";
                    if($row['COLUMN_KEY'] == 'PRI'){
                        $form .= "<label hidden for='$name'>".str_replace('_',' ',ucfirst($name))."</label>";
                        if ( $this->request->getGet('opc') == 'update' ) {
                            $form .= "<input hidden type='$type' class='form-control' id='$id' name='$name' placeholder='$placeholder' value='".$row_data[$name]."'>";
                        } else if ( $this->request->getGet('opc') == 'insert ' ) {
                            $form .= "<input hidden type='$type' class='form-control' id='$id' name='$name' placeholder='$placeholder' value='NULL'>";
                        }
                    } else if( $type == 'tinyint' ){
                            $checked = '';
                        if ($row_data[$name] == 1) {
                            $checked = 'checked';
                        }
                        
                        $form .= "<div class='form-check'>
                                    <input type='checkbox' class='form-check-input' name='$name' id='$id' $checked>
                                    <label class='form-check-label' for='exampleCheck1'>".str_replace('_',' ',ucfirst($name))."</label>
                                </div>";
                    } else if( $type == 'longblob' ){
                        $form .= "<div class='form-check'>
                                    <label for='$name'>".str_replace('_',' ',ucfirst($name))."</label>
                                    <textarea name='$name' id='$id' class='textarea form-control' rows='5' placeholder='Enter ...' >".$row_data[$name]."</textarea>
                                </div>";
                        if (isset($form_option['type_field'])) {
                            foreach ($form_option['type_field'] as $key => $type){
                                foreach ($type as $key_type => $params){
                                    foreach ($params as $key_params => $params_data){
                                        if ( $params_data == 'WYSIHTML5') {
                                            // @todo adicionar script
                                            $form .= "<script> $(function () { $('.textarea').summernote() }) </script>";
                                        }
                                    }
                                }
                            }
                        }
                    } else {
                        $flag = true;
                        if (isset($form_option['relation'])) {
                            foreach ($form_option['relation'] as $count => $field) {
                                if ($name == $field['field_join']) {
                                    $flag = false;
                                    foreach ($array_relation as $coun_select => $form_option_field) {
                                        //echo implode($form_option_field);
                                        $form .= $form_option_field[$field['field_relation']];
                                    }
                                }
                            }   
                        }
                        
                        if (isset($form_option['type_field'])) {
                            foreach ($form_option['type_field'] as $key => $type){
                                foreach ($type as $key_type => $params){ 
                                    if ($key_type == $name) {
                                        foreach ($params as $key_params => $params_data){
                                            
                                            if ( $params[$key_params] === 'file') {
                                                $file_enable = '';    
                                                $ext = explode(",",$params['ext']);
                                                foreach ($ext as $type_ext) {
                                                    switch ($type_ext) {
                                                        case "pdf":
                                                            $file_enable .= "<span class='badge badge-danger'>PDF</span> ";
                                                            break;
                                                        case "jpg":
                                                            $file_enable .= "<span class='badge badge-success'>JPG</span> ";
                                                            break;
                                                        case "jpge":
                                                            $file_enable .= "<span class='badge badge-warning'>JPGE</span> ";
                                                            break;
                                                        case "png":
                                                            $file_enable .= "<span class='badge badge-primary'>PNG</span> ";
                                                            break;
                                                    }
                                                }

                                                $img_gallery = '';
                                                $multiple = '';
                                                $multiple_array = '';
                                                if (isset($params['multiple']) && $params['multiple'] === true) {
                                                    if ($params['multiple'] === true) {
                                                        $multiple = 'multiple'; $multiple_array = '[]';
                                                    }
                                                    $img_foreach = explode(",",$row_data[$name]);
                                                    foreach ($img_foreach as $img_as) {
                                                        $img_gallery .= '<div class="col-lg-2 col-md-2 col-sm-2 col-2 row"><img class="gallery-update" src="'.base_url().'/uploads/'.$img_as.'"/> 
                                                        <a target="_blank" href="'.base_url().'/uploads/'.$img_as.'"  class="btn btn-block btn-outline-primary btn-sm tooltip_admin"><i class="fas fa-download"></i> Download
                                                            <span class="tooltiptext">'.$row_data[$name].'</span>
                                                        </a> </div>';
                                                    }
                                                }else {
                                                    if (isset($params['multiple']) === true) {
                                                        if ($params['multiple'] === true) {
                                                            $multiple = 'multiple'; $multiple_array = '[]';
                                                        }
                                                    }
                                                    $img_foreach = explode(",",$row_data[$name]);
                                                    $img_gallery = '';
                                                    foreach ($img_foreach as $img_as) {
                                                        if ((preg_match("/.*\.(js|jpg|jpeg|png|gif)/", $img_as)) === 1) {
                                                            $img_gallery .= '<div class="col-lg-2 col-md-2 col-sm-2 col-2 row"><img class="gallery-update" src="'.base_url().'/uploads/'.$img_as.'"/> 
                                                            <a target="_blank" href="'.base_url().'/uploads/'.$img_as.'" class="btn btn-block btn-outline-primary btn-sm tooltip_admin"><i class="fas fa-download"></i> View
                                                                <span class="tooltiptext">'.$row_data[$name].'</span>
                                                            </a> </div>';
                                                        } else if ((preg_match("/.*\.(pdf)/", $img_as)) === 1) {
                                                            $img_gallery .= '<div class="col-lg-2 col-md-2 col-sm-2 col-2 row"><img class="gallery-update" src="'.base_url().'/assets/img/admin/pdf.png"/>  
                                                            <a target="_blank" href="'.base_url().'/uploads/'.$row_data[$name].'" class="btn btn-block btn-outline-primary btn-sm tooltip_admin"><i class="fas fa-download"></i> View
                                                                <span class="tooltiptext">'.$row_data[$name].'</span>
                                                            </a> </div>';
                                                        }

                                                    }
                                                }
                                                $form .= "<label for='$name'> ".str_replace('_',' ',ucfirst($name)).' '.$file_enable."</label>";
                                                
                                                $name_file = '';
                                                if ($img_gallery === '') {
                                                    $name_file = $row_data[$name];
                                                } else {
                                                    
                                                }
                                                $form .= '<div class="input-group" style="border: solid 1px red; border-radius: 4px">
                                                            <div class="custom-file">
                                                                <input type="file" class="custom-file-input" id="'.$id.'" name="'.$name.$multiple_array.'" '.$multiple.'>
                                                                <label class="custom-file-label" for="exampleInputFile">Search file</label>
                                                            </div>
                                                            <div class="input-group-append">
                                                            <a class="btn btn-default" id=""><i class="fas fa-download"></i> <span class="c-white"></span>'.$name_file.'</a>
                                                            </div>
                                                        </div>


                                                        <div class="bg-default col-lg-12 col-md-12 col-sm-12 col-12 row">
                                                            '.$img_gallery.'
                                                        </div>';
                                                $flag = false;
                                            }
                                            if ( $params[$key_params] === 'WYSIHTML5') {
                                                $form .= "<div class='form-check'>
                                                            <label for='$name'>".str_replace('_',' ',ucfirst($name))."</label>
                                                            <p> Contenido como: Imagenes no disponibles para este componente! </p>
                                                            <textarea name='$name' id='$id' class='textarea form-control $id' rows='10' placeholder='Enter ...'>".$row_data[$name]."</textarea>
                                                        </div>";
                                                $form .= "<script> $(function () { $('.textarea').summernote() }) </script>";
                                                $flag = false;
                                            }
                                        }
                                    }
                                }
                            }
                        }

                        if ($flag) {
                            $form .= "<label for='$name'>".str_replace('_',' ',ucfirst($name))."</label>";
                            $form .= "<input type='".json_encode($type)."' class='form-control' id='$id' name='$name' placeholder='$placeholder' value='".$row_data[$name]."' >";
                            //$form .= "<input class='form-control' id='$id' name='$name' placeholder='$placeholder' value='".$row_data[$name]."' >";
                        }
                    }
                    $form .= "</div>";
                }

            }
        } else {
            foreach($query->getResultArray() as $row){
                $type   = $row['DATA_TYPE'];
                $id     = $row['COLUMN_NAME'];
                $name   = $row['COLUMN_NAME'];
                $placeholder = $row['COLUMN_NAME'];
    
                $form .= "<div class='form-group'>";
                if($row['COLUMN_KEY'] == 'PRI'){
                    $form .= "<label hidden for='$name'>".str_replace('_',' ',ucfirst($name))."</label>";
                    $form .= "<input hidden type='$type' class='form-control' id='$id' name='$name' placeholder='$placeholder' value='NULL'>";
                } else if( $type == 'tinyint' ){
                    $form .= "<div class='form-check'>
                                <input type='checkbox' class='form-check-input' name='$name' id='$id'>
                                <label class='form-check-label' for='exampleCheck1'>".str_replace('_',' ',ucfirst($name))."</label>
                            </div>";
                } else if( $type == 'longblob' ){
                    $form .= "<div class='form-check'>
                                <label for='$name'>".str_replace('_',' ',ucfirst($name))."</label>
                                <textarea name='$name' id='$id' class='textarea form-control $id' rows='5' placeholder='Enter ...'></textarea>
                            </div>";
                    if (isset($form_option['type_field'])) {
                        foreach ($form_option['type_field'] as $key => $type){
                            foreach ($type as $key_type => $params){
                                foreach ($params as $key_params => $params_data){
                                    if ( $params_data == 'WYSIHTML5') {
                                        $form .= "<script> $(function () { $('.textarea').summernote() }) </script>";
                                    }
                                }
                            }
                        }
                    }
                } else {
                    $flag = true;
                    if (isset($form_option['relation'])) {
                        foreach ($form_option['relation'] as $count => $field) {
                            if ($name == $field['field_join']) {
                                $flag = false;
                                foreach ($array_relation as $coun_select => $form_option_field) {
                                    $form .= $form_option_field[$field['field_relation']];
                                }
                            }
                        }   
                    } 
                    
                    if (isset($form_option['type_field'])) {
                        foreach ($form_option['type_field'] as $key => $type){
                            foreach ($type as $key_type => $params){ 
                                if ($key_type == $name) {
                                    foreach ($params as $key_params => $params_data){
                                        if ( $params[$key_params] === 'file') {
                                            $file_enable = '';    
                                            $ext = explode(",",$params['ext']);
                                            foreach ($ext as $type_ext) {
                                                switch ($type_ext) {
                                                    case "pdf":
                                                        $file_enable .= "<span class='badge badge-danger'>PDF</span> ";
                                                        break;
                                                    case "jpg":
                                                        $file_enable .= "<span class='badge badge-success'>JPG</span> ";
                                                        break;
                                                    case "jpge":
                                                        $file_enable .= "<span class='badge badge-warning'>JPGE</span> ";
                                                        break;
                                                    case "png":
                                                        $file_enable .= "<span class='badge badge-primary'>PNG</span> ";
                                                        break;
                                                }
                                            }

                                            $multiple = '';
                                            $multiple_array = '';
                                            if (isset($params['multiple']) === true) {
                                                if ($params['multiple'] === true) {
                                                    $multiple = 'multiple'; $multiple_array = '[]';
                                                }
                                            }
                                            
                                            $form .= "<label for='$name'>".str_replace('_',' ',ucfirst($name)).' '.$file_enable."</label>";
                                            $form .= '<div class="input-group" style="border: solid 1px blue; border-radius: 4px">
                                                        <div class="custom-file">
                                                            <input type="file" class="custom-file-input" id="'.$id.'" name="'.$name.$multiple_array.'" '.$multiple.'>
                                                            <label class="custom-file-label" for="exampleInputFile">Search file</label>
                                                        </div>
                                                        <div class="input-group-append">
                                                        <a class="btn btn-default" id=""><i class="fas fa-download"></i> <span class="c-white"> </span></a>
                                                        </div>
                                                    </div>';
                                            $flag = false;
                                        } 
                                        if ( $params[$key_params] === 'WYSIHTML5') {
                                            $form .= "<div class='form-check'>
                                                        <label for='$name'>".str_replace('_',' ',ucfirst($name))."</label>
                                                        <p> Contenido como: Imagenes no disponibles para este componente! </p>
                                                        <textarea name='$name' id='$id' class='textarea form-control $id' rows='5' placeholder='Enter ...'></textarea>
                                                    </div>";
                                            $form .= "<script> $(function () { $('.textarea').summernote() }) </script>";
                                            $flag = false;
                                        }
                                    }
                                }
                            }
                        }
                    }
                    
                    if ($flag) {
                        $form .= "<label for='$name'>".str_replace('_',' ',ucfirst($name))."</label>";
                        $form .= "<input type='".json_encode($type)."' class='form-control' id='$id' name='$name' placeholder='$placeholder'>";
                    }
                }
                $form .= "</div>";
            }
        }

        if ( $this->request->getGet('opc') == 'update' ) {
            $form .= "<button type='submit' class='btn btn-danger' data-toggle='modal' data-target='#modal-overlay'>Update</button>";
        } else if ( $this->request->getGet('opc') == 'insert' ) {
            $form .= "<button type='submit' class='btn btn-primary' data-toggle='modal' data-target='#modal-overlay'>Add</button>";
        }

        $form = $this->cardBody($form);
        $form = $this->formRole($form);
        $form = $this->cardColor($form, $form_option['color'], $form_option['title']);
        
        return $form;
    }

    function cardColor($form = '', $color = 'primary', $title = 'no title') {
        
        return "<div class='card-$color'>
                    <div class='card-header'> 
                        <h3 class='card-title'>$title</h3> 

                        <div class='card-tools'>
                            <button type='button' class='btn btn-tool' data-card-widget='collapse'>
                                <i class='fas fa-minus'></i>
                            </button>
                            <button type='button' class='btn btn-tool' data-card-widget='remove'>
                                <i class='fas fa-times'></i>
                            </button>
                        </div>
                    </div>
                    $form
                </div>";
    }

    function cardBody($form = '') {
        $btn_insert = "";
        $modal = $this->modal == true && "data-toggle='modal' data-target='#modal-primary'";
        if ($this->opc_insert) { $btn_insert = "<a type='button' style='color: white;' href='".base_url()."/".$this->request->uri->getPath()."?opc=insert' class='btn btn-primary' $modal > Add </a>"; }
        return "<div class='card-body'>$btn_insert $form</div> ";
    }

    function formRole($form = '') {
        if ( $this->request->getGet('opc') == 'update' ) {
            return "<form action='".base_url()."/".$this->request->uri->getPath()."?lib_min=update' method='post' enctype='multipart/form-data' role='form'> $form </form>";
        } else if ( $this->request->getGet('opc') == 'insert' ) {
            return "<form action='".base_url()."/".$this->request->uri->getPath()."?lib_min=insert' method='post' enctype='multipart/form-data' role='form'> $form </form>";
        } else {
            return $form;
        }

    }

    function scriptTable($form = '') {
        return "
              ";
    }
    
} 