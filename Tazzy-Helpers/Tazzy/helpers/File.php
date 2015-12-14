<?php
    ini_set('upload_max_filesize', '10M');
    class File{
        private $file;
        private $filename;
        private $file_tmp;
        private $file_size;
        private $file_error;
        private $max_size;
        private $allowed = array('csv','txt');
        public function __construct($fileObj){
            $this->file = $fileObj;
            $this->filename = array_key_exists('name', $this->file) ? $this->file['name'] : null;
            $this->file_tmp = array_key_exists('tmp_name', $this->file) ? $this->file['tmp_name'] : null;
            $this->file_size = array_key_exists('size', $this->file) ? $this->file['size'] : null;
            $this->file_error = array_key_exists('error', $this->file) ? $this->file['error'] : null;
            $this->max_size = 5000;
        }
        public function fileObj(){
            return $this->file;
        }
        public function ext(){
             $file_ext= explode('.',$this->filename);
             $file_ext = strtolower(end($file_ext));
             return $file_ext;
        }
        public function fileName(){
            return $this->filename;
        }
        public function tmpName(){
            return $this->file_tmp;
        }
        public function fileSize(){
            return $this->file_size;
        }
        public function error(){
             if($this->file_error === 0){
                 return FALSE;
             }else{
                 return $this->file_error;
             }
             
        }
        public function isAllowed(){
            
            return in_array($this->ext(),$this->allowed);
        }
        public function setAllowed($allowed){
            $this->allowed = $allowed;
        }
        public function setMaxSize($size){
            $this->max_size = $size;
        }
         public function inSizeLimit($size){
            return $this->fileSize() <= $size;
        }
         public function fileLoc(){
            return $this->file['tmp_name'];
        }
        public function move($path){
            move_uploaded_file($this->fileLoc(),$path);
        }
    }
?>