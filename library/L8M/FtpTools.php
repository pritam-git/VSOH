<?php

/**
 * L8M
 *
 *
 * @filesource /library/L8M/FtpTools.php
 * @author     Debopam Parua <debopam.parua@bcssarl.com>
 * @version    $Id: FtpTools.php 27 2019-05-27 11:20:00Z dp $
 */

/**
 *
 *
 * L8M_FtpTools
 *
 *
 */
class L8M_FtpTools
{

	/**
	 *
	 *
	 * Class Variables
	 *
	 *
	 */
    protected $_server;
    protected $_user;
    protected $_password;
    protected $_port;
    protected $_ftpConnection = FALSE;

	/**
	 * Factory method to crate a version updater instance
	 *
     * @return VersionUpdater_Instance
	 */
	public static function factory($server, $user, $password, $port = 21)
	{
		return new L8M_FtpTools($server, $user, $password, $port);
	}

    /**
     * __construct
     *
     * @param string $server
     * @param string $user
     * @param string $password
     * @param string $port (default = 21)
     *
     * @return void
     *
     */
    public function __construct($server, $user, $password, $port = 21) {
        $this->_ftpConnection = $this->connectFtp($server, $user, $password, $port);
        if($this->_ftpConnection) {
            $this->_server = $server;
            $this->_user = $user;
            $this->_password = $password;
            $this->_port = $port;
        }
    }

	/**
	 *
	 *
	 * Class Methods
	 *
	 *
	 */

    /**
     * Connect to FTP server
     *
     * @param string $server
     * @param string $user
     * @param string $password
     * @param int $port
     *
     * @return object ftp connection instance
     *
     */
    private function connectFtp($server, $user, $password, $port)
    {
        $cid = FALSE;

        try {
            $cid = ftp_connect($server, $port);
            if($cid !== FALSE) {
                // Login into FTP server
                if(ftp_login($cid, $user, $password)) {
                    // Set the network timeout to 10 seconds
                    ftp_set_option($cid, FTP_TIMEOUT_SEC, 3000);
                    ftp_pasv($cid, TRUE);
                } else {
                    $cid = FALSE;
                    throw new L8M_Exception('FtpTools::connectFtp : cannot connect to Ftp as ' . $user);
                }
            }
        } catch(Exception $e) {
            throw new L8M_Exception("FtpTools::connectFtp : " . $e->getMessage());
        }

        return $cid;
    }

    private function ftpListingIterator($path = '') {
        $items = $this->getFtpDirectoryContents($path);

        foreach($items as $item) {
            if($item !== '.' && $item !== '..') {
                $list[] = $item;

                if($this->checkIfFtpDirectoryExists($item)) {

                    $listToAppend = $this->ftpListingIterator($item);

                    foreach($listToAppend as $appendItem) {
                        $list[] = $appendItem;
                    }
                }
            }
        }

        return $list;
    }

    /**
     * Recursively delete files and folder
     *
     * @param string $path
     *
     * @return bool $deletedAll
     */
    private function ftpDeleteIterator($path)
    {
        $deletedAll = FALSE;
        $toDeleteCounter = 0;
        $deletedCounter = 0;

        $list = ftp_nlist($this->_ftpConnection, $path);

        foreach ($list as $element) {
            if($element !== '.' && $element !== '..') {
                $toDeleteCounter++;
                if($this->checkIfFtpDirectoryExists($path . DIRECTORY_SEPARATOR . $element)) {
                    // Go inside directory and delete contents
                    $this->ftpDeleteIterator($path . DIRECTORY_SEPARATOR . $element);
                    // Delete the empty directory
                    if(ftp_rmdir($this->_ftpConnection, $path . DIRECTORY_SEPARATOR . $element)) {
                        $deletedCounter++;
                    }
                } else {
                    // Delete file
                    if(ftp_delete($this->_ftpConnection, $path . DIRECTORY_SEPARATOR . $element)) {
                        $deletedCounter++;
                    }
                }
            }
        }

        if($deletedCounter === $toDeleteCounter) {
            $deletedAll = TRUE;
        }

        return $deletedAll;
    }

    /**
     * Recursively copy files and folders
     *
     * @param string $path
     * @param string $remotePath
     *
     * @return bool $uploadedAll
     *
     */
    private function ftpUploadIterator($path, $remotePath)
    {
        $uploadedAll = FALSE;
        $toUploadCounter = 0;
        $uploadedCounter = 0;

        // Create remote directory if not exists
        if(!$this->checkIfFtpDirectoryExists($remotePath, $this->_ftpConnection)) {
            ftp_mkdir($this->_ftpConnection, $remotePath);
        }

        $directory = dir($path);
        while($file = $directory->read()) {
            // To prevent an infinite loop
            if ($file != "." && $file != "..") {
                $toUploadCounter++;
                if (is_dir($path . DIRECTORY_SEPARATOR . $file)) {
                    // Upload directory
                    // Recursive part
                    if($this->ftpUploadIterator($path . DIRECTORY_SEPARATOR . $file, $remotePath . DIRECTORY_SEPARATOR . $file)) {
                        $uploadedCounter++;
                    }
                } else {
                    // Upload file
                    if(ftp_put($this->_ftpConnection, $remotePath . DIRECTORY_SEPARATOR . $file, $path . DIRECTORY_SEPARATOR . $file, FTP_BINARY)) {
                        $uploadedCounter++;
                    }
                }
            }
        }
        $directory->close();

        if($toUploadCounter === $uploadedCounter) {
            $uploadedAll = TRUE;
        }

        return $uploadedAll;
    }

    /**
     * Recursively download remote files
     *
     * @param string $remotePath
     * @param string $path
     *
     * @return bool $downloadAll
     *
     */
    private function ftpDownloadIterator($remotePath, $path)
    {
        $downloadAll = FALSE;

        if($this->checkIfFtpDirectoryExists($remotePath)) {
            $files = ftp_nlist($this->_ftpConnection, $remotePath);
            if($files !== FALSE) {
                $toDownloadCounter = 0;
                $downloadedCounter = 0;

                // do this for each file in the remote directory
                foreach ($files as $file) {
                    // To prevent an infinite loop
                    if ($file != "." && $file != "..") {
                        $toDownloadCounter++;
                        // do the following if it is a directory
                        if ($this->checkIfFtpDirectoryExists($remotePath . DIRECTORY_SEPARATOR . $file)) {
                            // Create directory on local filesystem
                            $oldmask = umask(0);
                            mkdir($path . DIRECTORY_SEPARATOR . basename($file));
                            umask($oldmask);

                            // Recursive part
                            if($this->ftpDownloadIterator($remotePath . DIRECTORY_SEPARATOR . $file, $path . DIRECTORY_SEPARATOR . basename($file))) {
                                $downloadedCounter++;
                            }
                        } else {
                            // Download files
                            $oldmask = umask(0);
                            if(ftp_get($this->_ftpConnection, $path . DIRECTORY_SEPARATOR . basename($file), $remotePath . DIRECTORY_SEPARATOR . basename($file), FTP_BINARY, 0)) {
                                $downloadedCounter++;
                            }
                            umask($oldmask);
                        }
                    }
                }

                // Check all files and folders have been downloaded
                if($toDownloadCounter === $downloadedCounter)
                {
                    $downloadAll = true;
                }
            }
        }

        return $downloadAll;
    }

    /**
     * List files on mentioned path on FTP server
     *
     * @param string $path
     *
     * @return array $files Files listed in directory or false
     *
     */
    public function getFtpDirectoryContents($path)
    {
        $files = FALSE;

        if($this->_ftpConnection !== FALSE) {
            try {
                $files = ftp_nlist($this->_ftpConnection, $path);
            } catch(Exception $e) {
                throw new L8M_Exception("FtpTools::getFtpDirectoryContents : " . $e->getMessage());
            }
        }

        return $files;
    }

    public function getFtpAllContents() {
        $files = FALSE;

        if($this->_ftpConnection !== FALSE) {
            try {
                $files = $this->ftpListingIterator();
            } catch(Exception $e) {
                throw new L8M_Exception("FtpTools::getFtpAllContents : " . $e->getMessage());
            }
        }

        return $files;
    }

    /**
     * Test if a directory exist
     *
     * @param string $dir
     *
     * @return bool $dirExists
     *
     */
    public function checkIfFtpDirectoryExists($dir)
    {
        $dirExists = FALSE;

        if($this->_ftpConnection !== FALSE) {
            try {
                // Get the current working directory
                $origin = ftp_pwd($this->_ftpConnection);
                // Attempt to change directory, suppress errors
                if(@ftp_chdir($this->_ftpConnection, $dir)) {
                    // If the directory exists, set back to origin
                    ftp_chdir($this->_ftpConnection, $origin);
                    $dirExists = TRUE;
                }
            } catch(Exception $e) {
                throw new L8M_Exception("FtpTools::checkIfFtpDirectoryExists : " . $e->getMessage());
            }
        }

        return $dirExists;
    }

    /**
     * Check if a file exists on FTP Server
     *
     * @param string $file
     *
     * @return bool $fileExists
     *
     */
    public function checkIfFtpFileExists($file)
    {
        $fileExists = FALSE;

        if($this->_ftpConnection !== FALSE) {
            $fileSize = @ftp_size($this->_ftpConnection, $file);
            if($fileSize !== -1) {
                $fileExists = TRUE;
            }
        }

        return $fileExists;
    }

    /**
     * Delete a file on remote FTP server
     *
     * @param string $file
     *
     * @return bool $fileDeleted
     *
     */
    public function deleteFileFromFtp($file)
    {
        $fileDeleted = FALSE;

        if($this->_ftpConnection !== FALSE) {
            $fileSize = @ftp_size($this->_ftpConnection, $file);
            if($fileSize !== -1) {
                try {
                    // Delete
                    if(ftp_delete($this->_ftpConnection, $file)) {
                        $fileDeleted = TRUE;
                    }
                } catch(Exception $e) {
                    throw new L8M_Exception("FtpTools::deleteFileFromFtp : " . $e->getMessage());
                }
            }
        }

        return $fileDeleted;
    }

    /**
     * Recursively delete files and folder in given directory
     *
     * If path ends with a slash delete folder content
     * otherwise delete folder itself
     *
     * @param string $path
     *
     * @return bool $deleted
     *
     */
    public function deleteDirectoryFromFtp($path)
    {
        $deleted = FALSE;

        if($this->_ftpConnection !== FALSE) {
            try {
                // Delete directory content
                if($this->ftpDeleteIterator($path, $this->_ftpConnection)) {
                    // If path does not end with /
                    if(substr($path, -1) !== '/') {
                        // Delete the directory itself
                        if(ftp_rmdir($this->_ftpConnection, $path)) {
                            $deleted = TRUE;
                        }
                    } else {
                        $deleted = TRUE;
                    }
                }
            } catch(Exception $e) {
                throw new L8M_Exception("FtpTools::deleteDirectoryFromFtp : " . $e->getMessage());
            }
        }

        return $deleted;
    }

    /**
     * Upload a file on FTP server
     *
     * @param string $filePath
     * @param string $remotePath (default NULL : same path as filePath)
     * @param string $mode (FTP_ASCII | FTP_BINARY)
     * @param string $timeout (default 1000 : 10 seconds)
     *
     * @return bool $fileUploaded
     *
     */
    public function uploadFileToFtp($filePath, $remotePath = NULL, $mode = FTP_BINARY, $timeout = 1000)
    {
        $fileUploaded = FALSE;

        if($remotePath === NULL) {
            $remotePath = $filePath;
            $filePath = BASE_PATH . '/' . $filePath;
        }

        if($this->_ftpConnection !== FALSE) {
            try {
                if(ftp_put($this->_ftpConnection, $remotePath, $filePath, $mode)) {
                    $fileUploaded = TRUE;
                }
            } catch(Exception $e) {
                throw new L8M_Exception("FtpTools::uploadFileToFtp : " . $e->getMessage());
            }
        }

        return $fileUploaded;
    }

    /**
     * Upload a directory from local to remote FTP server
     *
     * If path ends with a slash upload folder content
     * otherwise upload folder itself
     *
     * @param string $path
     * @param string $remotePath
     *
     * @return bool $uploaded
     *
     */
    public function uploadDirectoryToFtp($path, $remotePath = NULL)
    {
        $uploaded = FALSE;

        if($remotePath === NULL) {
            $remotePath = $path;
            $path = BASE_PATH . '/' . $path;
        }

        // Remove trailing slash
        $remotePath = rtrim($remotePath, DIRECTORY_SEPARATOR);

        if($this->_ftpConnection !== FALSE) {
            try {
                // If path does not end with /
                if(substr($path, -1) !== '/') {
                    // Create first level directory on remote filesystem
                    ftp_mkdir($this->_ftpConnection, $remotePath);
                }

                if($this->checkIfFtpDirectoryExists($remotePath)) {
                    $uploaded = $this->ftpUploadIterator($path, $remotePath);
                }
            } catch(Exception $e) {
                throw new L8M_Exception("FtpTools::uploadDirectoryToFtp : " . $e->getMessage());
            }
        }

        return $uploaded;
    }

    /**
     * Download a file from remote FTP server
     *
     * @param string $remotePath
     * @param string $filePath (default NULL : same path as remotePath)
     *
     * @return bool $fileDownloaded
     *
     */
    public function downloadFileFromFtp($remotePath, $filePath = NULL)
    {
        $fileDownloaded = FALSE;

        if($filePath === NULL) {
            $filePath = BASE_PATH . '/' . $remotePath;
        }

        if($this->_ftpConnection !== FALSE) {
            try {
                // Download File
                $oldmask = umask(0);
                if (ftp_get($this->_ftpConnection, $filePath, $remotePath, FTP_BINARY)) {
                    $fileDownloaded = TRUE;
                }
                umask($oldmask);
            } catch(Exception $e) {
                throw new L8M_Exception("FtpTools::downloadFileFromFtp : " . $e->getMessage());
            }
        }

        return $fileDownloaded;
    }

    /**
     * Download a directory from remote FTP server
     *
     * If remotePath ends with a slash download folder content
     * otherwise download folder itself
     *
     * @param string $remotePath
     * @param string $path (default NULL : same path as remotePath)
     *
     * @return bool $downloaded
     *
     */
    public function downloadDirectoryFromFtp($remotePath, $path = NULL)
    {
        $downloaded = FALSE;

        if($path === NULL) {
            $path = BASE_PATH . '/' . $remotePath;
        }

        if($this->_ftpConnection !== FALSE) {
            // If remotePath does not end with /
            if(substr($remotePath, -1) !== '/') {
                // Create fisrt level directory on local filesystem
                $path = rtrim($path, DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR . basename($remotePath);
                $oldmask = umask(0);
                mkdir($path);
                umask($oldmask);
            }

            // Remove trailing slash
            $path = rtrim($path, DIRECTORY_SEPARATOR);

            try {
                $downloaded = $this->ftpDownloadIterator($remotePath, $path);
            } catch(Exception $e) {
                throw new L8M_Exception("FtpTools::downloadDirectoryFromFtp : " . $e->getMessage());
            }
        }

        return $downloaded;
    }

    /**
     * Create file on the Ftp at specified path
     *
     * @return bool $created
     *
     */
    public function createFtpFile($path, $content) {
        $created = FALSE;

        if($this->_ftpConnection !== FALSE) {

            try {
                $fp = fopen('php://temp', 'w');
                fwrite($fp, $content);
                rewind($fp);

                $created = ftp_fput($this->_ftpConnection, $path, $fp, FTP_ASCII);
            } catch(Exception $e) {
                throw new L8M_Exception("FtpTools::createFtpFile : " . $e->getMessage());
            }
        }

        return $created;
    }

    /**
     * Create directory on the Ftp at specified path if it does not exist
     *
     * @return bool $created
     *
     */
    public function createFtpDirectory($path) {
        $created = FALSE;

        if($this->_ftpConnection !== FALSE) {
            try {
                if(!$this->checkIfFtpDirectoryExists($path, $this->_ftpConnection)) {
                    $created = (bool)ftp_mkdir($this->_ftpConnection, $path);
                } else {
                    $created = TRUE;
                }
            } catch(Exception $e) {
                throw new L8M_Exception("FtpTools::createFtpDirectory : " . $e->getMessage());
            }
        }

        return $created;
    }

    /**
     * Get default login FTP directory aka pwd
     *
     * @return string $dir Print Working Directory or false
     *
     */
    public function getFtpPwd()
    {
        $dir = FALSE;

        if($this->_ftpConnection !== FALSE)
        {
            try {
                $dir = ftp_pwd($this->_ftpConnection);
            } catch(Exception $e) {
                throw new L8M_Exception("FtpTools::getFtpPwd : " . $e->getMessage());
            }
        }

        return $dir;
    }

    /**
     * Re-connect to FTP server
     *
     * @return void
     *
     */
    public function reconnectToFtp() {
        if($this->_ftpConnection !== FALSE) {
            if(!is_array($this->getFtpDirectoryContents('.'))) {
                $this->_ftpConnection = self::connectFtp($this->_server, $this->_user, $this->_password, $this->_port);
            }
        }
    }

    /**
     * Close connection to FTP server
     *
     * @return void
     *
     */
    public function closeFtpConnection() {
        if($this->_ftpConnection !== FALSE) {
            ftp_close($this->_ftpConnection);

            $this->_ftpConnection = FALSE;
        }
    }

}