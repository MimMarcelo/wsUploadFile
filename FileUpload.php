<?php

/**
 * File name: FileUpload.php
 * Define a type able to upload a file to server
 */
class FileUpload {

    /** * Private attributes * **/
    private $tmpName;
    private $fileName;
    private $fileSize;
    private $fileExtension;
    private $pathToSaveFile;
    private $error;

    /**
     * Build a FileUpload object
     * 
     * @param type $_file Your $_FILES['name'] to be uploaded
     * @param type $pathToSaveFile Where the uploaded file may be saved,
     * make sure to put a slash at the end of path
     */
    public function __construct($_file, $pathToSaveFile = "") {
        $this->tmpName = $_file["tmp_name"];
        $this->fileName = basename($_file["name"]);
        $this->fileSize = $_file["size"];
        $this->fileExtension = strtolower(pathinfo($this->fileName, PATHINFO_EXTENSION));
        $this->pathToSaveFile = $pathToSaveFile;
        $this->error = array();
    }

    /**
     * Returns an array with all error messages received
     * 
     * @return array()
     */
    public function getError() {
        return $this->error;
    }

    /**
     * Enable to change the file name to be saved
     * 
     * @param string $fileName
     */
    public function setFileName($fileName) {
        $this->fileName = $fileName;
    }

    /**
     * Enable to set where the file may be saved
     * <p>
     * Make sure to put a slash "/" at the end of path
     * </p>
     * 
     * @param string $pathToSaveFile
     */
    public function setPathToSaveFile($pathToSaveFile) {
        $this->pathToSaveFile = $pathToSaveFile;
    }

    /**
     * Enable test some features about the file like:
     * 
     * @param boolean $allowOverride       Enable override file
     * @param float $maxSizeInMb         Max file size enabled (in MB)
     * @param array() $extensionsEnabled   Extensions enabled to upload
     * @return boolean true if all validations were appoved
     */
    public function validate($allowOverride = true, $maxSizeInMb = 0, $extensionsEnabled = null) {
        if (!$allowOverride) {
            if (file_exists($this->pathToSaveFile . $this->fileName)) {
                $this->error[] = "File name '$this->fileName' already exists";
                return false;
            }
        }
        if ($maxSizeInMb > 0) {
            if (!$this->fileSizeLessThan($maxSizeInMb)) {
                $this->error[] = "File size greater than limit of " . $maxSizeInMb . "MB";
                return false;
            }
        }
        if($extensionsEnabled){
            if (!$this->checkExtension($extensionsEnabled)) {
                $this->error[] = "File extension not enabled";
                return false;
            }
        }
        return true;
    }

    /**
     * Move the file to server directory
     * 
     * @return boolean true if success
     */
    public function upload() {
        return move_uploaded_file($this->tmpName, $this->pathToSaveFile . $this->fileName);
    }

    /**
     * Try the file size
     * 
     * @param float $maxSizeInMb Max file size enabled
     * @return boolean true if the file size respects the mas file size
     */
    private function fileSizeLessThan($maxSizeInMb) {
        if ($this->fileSize <= ($maxSizeInMb * 1024 * 1024)) {
            return true;
        }
        return false;
    }

    /**
     * Try the file extension
     * 
     * @param array() $extensionsEnabled list of all extensions enabled to upload
     * @return boolean true if the file extension is in the list
     */
    private function checkExtension($extensionsEnabled) {
        if (is_array($extensionsEnabled)) {
            if (in_array($this->fileExtension, $extensionsEnabled)) {
                return true;
            }
        } else if ($extensionsEnabled != null) {
            $this->error[] = "Enabled file extensions may be an array";
            return false;
        }
        return false;
    }

}
