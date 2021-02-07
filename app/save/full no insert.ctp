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
        $this->form_option = [];
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
            
            $thead .= '<th>Action</th>';
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
                                    if ($type[$field]['type'] == 'boolean') {
                                        $td .= '<td>'.$type[$field][$row[$field]].' </td>';
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
                if ($this->table_option['remove'] == true) {$btn_remove = '<a type="button"  onclick="checkDelete('.$row['id_'.$this->table].', \''.$this->table.'\')" class="btn btn-danger btn-xs bg-maroon"  data-toggle="modal" data-target="#modal-sm"> Del </a>';}
                if ($this->table_option['update'] == true) {$btn_update = '<a type="button" href="'.base_url().'/'.$this->request->uri->getPath()."?opc=update&id=".$row['id_'.$this->table].'&table='.$this->table.'" class="btn btn-warning btn-xs bg-warning"> Edit </a>';}
                $td .= '<td> '.$btn_remove.' '.$btn_update.' </td>';
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
                        if (isset($this->form_option['type_field'])) {
                            foreach ($this->form_option['type_field'] as $key => $type){
                                foreach ($type as $key_type => $params){ 
                                    if ($key_type == $row['COLUMN_NAME']) {
                                        $flag_file = false;
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
                                                                    $write = str_replace("writable","public",WRITEPATH);
                                                                    $file_as->move($write.'uploads',$newName);
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
                                                            $write = str_replace("writable","public",WRITEPATH);
                                                            $file->move($write.'uploads',$newName);
                                                            $data[$row['COLUMN_NAME']] = substr($newName, 0, -1);
                                                            $flag_file = false;
                                                        }
                                                    } 
                                                }
                                            }
                                        }
                                    }
                                }
                            }
                        }
                        if ($flag_file) {
                            $data[$row['COLUMN_NAME']] = $this->request->getPost($row['COLUMN_NAME']);
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
                        }else {
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
                                                                    $write = str_replace("writable","public",WRITEPATH);
                                                                    $file_as->move($write.'uploads',$newName);
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
                                                            $write = str_replace("writable","public",WRITEPATH);
                                                            $file->move($write.'uploads',$newName);
                                                            $data[$row['COLUMN_NAME']] = substr($newName, 0, -1);
                                                            $flag_file = false;
                                                        }
                                                    } 
                                                }
                                            }
                                        }
                                    }
                                }
                            }
                        }
                        if ($flag_file) {
                            $data[$row['COLUMN_NAME']] = $this->request->getPost($row['COLUMN_NAME']);
                        }
                    }
                }else {
                    $id = $this->request->getPost($row['COLUMN_NAME']);
                }
            }
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
                $select2 = '<div class="form-group">
                                <label>'.ucfirst($field['table']).' '.$field['field_show'].'</label>
                                <select class="form-control select2bs4" name="'.$field['field_join'].'" style="width: 100%;">';
                $id_relation = 0;
                if ($id != 0) {
                    $query_data = $this->libmin->getTablet($table, $id);
                    foreach($query_data->getResultArray() as $row_data){
                        foreach($query_relation->getResultArray() as $relation){
                            $id_relation = $field['field_relation'];
                            if ($relation[$field['field_join']] == $row_data['id_'.$field['table']]) {
                                $select2 .= ' <option selected value="'.$relation[$field['field_relation']].'">'.$relation[$field['field_show']].'</option>';
                            } else {
                                $select2 .= ' <option value="'.$relation[$field['field_relation']].'">'.$relation[$field['field_show']].'</option>';
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
                    } else if( $type == 'text' ){
                        $form .= "<div class='form-check'>
                                    <label for='$name'>".str_replace('_',' ',ucfirst($name))."</label>
                                    <textarea name='$name' id='$id' class='textarea form-control' rows='5' placeholder='Enter ...' >".$row_data[$name]."</textarea>
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
                                                $multiple = '';
                                                $multiple_array = '';
                                                if ($params['multiple'] === true) { 
                                                    $multiple = 'multiple'; $multiple_array = '[]'; 
                                                }
                                                $form .= "<label for='$name'>".str_replace('_',' ',ucfirst($name))."</label>";
                                                $form .= '<div class="input-group">
                                                            <div class="custom-file">
                                                                <input type="file" class="custom-file-input" id="'.$id.'" name="'.$name.$multiple_array.'" '.$multiple.'>
                                                                <label class="custom-file-label" for="exampleInputFile">Search file</label>
                                                            </div>
                                                            <div class="input-group-append">
                                                            <a class="btn btn-default" id=""><i class="fas fa-download"></i> <span class="c-white">'.$row_data[$name].'</span></a>
                                                            </div>
                                                        </div>';
                                                $flag = false;
                                            }
                                        }
                                    }
                                }
                            }
                        }

                        if ($flag) {
                            $form .= "<label for='$name'>".str_replace('_',' ',ucfirst($name))."</label>";
                            $form .= "<input type='$type' class='form-control' id='$id' name='$name' placeholder='$placeholder' value='".$row_data[$name]."' >";
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
                } else if( $type == 'text' ){
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
                                            $multiple = '';
                                            $multiple_array = '';
                                            if ($params['multiple'] === true) { 
                                                $multiple = 'multiple'; $multiple_array = '[]'; 
                                            }
                                            $form .= "<label for='$name'>".str_replace('_',' ',ucfirst($name))."</label>";
                                            $form .= '<div class="input-group">
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
                                    }
                                }
                            }
                        }
                    }
                    
                    if ($flag) {
                        $form .= "<label for='$name'>".str_replace('_',' ',ucfirst($name))."</label>";
                        $form .= "<input type='$type' class='form-control' id='$id' name='$name' placeholder='$placeholder'>";
                    }
                }
                $form .= "</div>";
            }
        }

        if ( $this->request->getGet('opc') == 'update' ) {
            $form .= "<button type='submit' class='btn btn-danger'>Update</button>";
        } else if ( $this->request->getGet('opc') == 'insert' ) {
            $form .= "<button type='submit' class='btn btn-primary'>Add</button>";
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