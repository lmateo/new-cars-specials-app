<?php

  /**
   * File Class
   *
   * package Wojo Framework
   * @author wojoscripts.com
   * @copyright 2014
   * @version $Id: file.class.php, v1.00 2014-04-20 18:20:24 gewa Exp $
   */

  if (!defined("_WOJO"))
      die('Direct access to this location is not allowed.');

  class File
  {
	  
      /**
       * File::getExtension()
       * 
       * @param mixed $path
       * @return
       */
      public static function getExtension($path)
      {
          return pathinfo($path, PATHINFO_EXTENSION);
      }

      /**
       * File::deleteRecrusive()
       * 
	   * Usage File::deleteRecrusive("test/dir");
       * @param string $dir
	   * @param string $removeParent - remove parent directory
       * @return
       */
      public static function deleteRecrusive($dir = '', $removeParent = false)
      {
		  if(is_dir($dir)) {
			  $di = new RecursiveDirectoryIterator($dir, FilesystemIterator::SKIP_DOTS);
			  $ri = new RecursiveIteratorIterator($di, RecursiveIteratorIterator::CHILD_FIRST);
			  foreach ($ri as $file) {
				  $file->isDir() ?  rmdir($file) : unlink($file);
			  }
			  $removeParent ? self::deleteDirectory($dir) : null;
			  return true;
		  } else {
			  return true;
		  }
      }
	  
      /**
       * File::deleteDirectory()
       * 
       * @param string $dir
       * @return
       */
      public static function deleteDirectory($dir = '')
      {
          self::emptyDirectory($dir);
          return rmdir($dir);
      }

      /**
       * File::makeDirectory()
       * 
	   * /my/path/to/dir
       * @param string $dir
       * @return
       */
      public static function makeDirectory($dir = '')
      {
          if (!file_exists($dir)) {
               if (false === mkdir($dir, 0755, true)) {
				  self::_errorHanler('directory-error', 'Directory not writable {dir}.', array('{dir}' => $dir));
			   }
          }
      }

      /**
       * File::renameDirectory()
       * 
	   * /my/path/to/dir
       * @param string $old
	   * @param string $new
       * @return
       */
      public static function renameDirectory($old = '', $new = '')
      {
          if (file_exists($old)) {
               if (false === rename($old, $new)) {
				  self::_errorHanler('directory-error', 'Can\'t rename {dir}.', array('{dir}' => $new));
			   }
          }
      }
	  
      /**
       * File::emptyDirectory()
       * 
       * @param string $dir
       * @return
       */
      public static function emptyDirectory($dir = '')
      {
          foreach (glob($dir . '/*') as $file) {
              if (is_dir($file)) {
                  self::emptyDirectory($file);
              } else {
                  unlink($file);
              }
          }
          return true;
      }

      /**
       * File::copyDirectory()
       * 
	   * Copies content of source directory into destination directory
	   * Warning: if the destination file already exists, it will be overwritten
       * @param string $src
       * @param string $dest
       * @param bool $fullPath
       * @return
       */
      public static function copyDirectory($src = '', $dest = '', $fullPath = true)
      {
          $result = false;
          $dirPath = (($fullPath) ? BASEPATH : '') . $src;

          if (is_dir($dirPath)) {
              $dir = opendir($dirPath);
              if (!$dir)
                  return $result;
              if (!file_exists(trim($dest, '/') . '/'))
                  mkdir((($fullPath) ? BASEPATH : '') . $dest, 0755, true);
              while (false !== ($file = readdir($dir))) {
                  if (($file != '.') && ($file != '..')) {
                      $fromDir = trim($src, '/') . '/' . $file;
                      $toDir = trim($dest, '/') . '/' . $file;
                      if (is_dir($fromDir)) {
                          $result = self::copyDirectory($fromDir, $toDir, $fullPath);
                      } else {
                          $result = copy($fromDir, $toDir);
                      }
                  }
              }
              closedir($dir);
          }

          return $result;
      }

      /**
       * File::copyRemove()
       * 
	   * Copies content of source directory into destination directory
	   * Removes sourece direcotry afterwards
       * @param string $src
       * @param string $dest
	   * @param array $exclusions
	   *    array(
	   *       '/readme.txt/',
	   *   	   '/orig.css/',
	   *       '/.*\.bak.*\/'
	   *  );
       * @return
       */
	  public static function copyRemove($src, $dest, $exclusions = array())
	  {
		  
		  if (!is_dir($src))
			  self::_errorHanler('directory-error', 'Invalid source directory selected {src}.', array('{dir}' => $src));
		  if (!is_dir($dest)) {
			  if (!mkdir($dest, 0, true)) {
				  self::_errorHanler('directory-error', 'The destination does not exist, and I can not create it {dest}.', array('{dir}' => $dest));
			  }
		  }
		  if (!is_array($exclusions))
			  self::_errorHanler('directory-error', 'The exclustion parameter is not an array, it MUST be an array.', array('{dir}' => $exclusions));
		  $emptiedDirs = array();
		  foreach (new RecursiveIteratorIterator(new RecursiveDirectoryIterator($src, FilesystemIterator::SKIP_DOTS), RecursiveIteratorIterator::CHILD_FIRST) as $f) {
			  foreach ($exclusions as $pattern) {
				  if (preg_match($pattern, $f->getRealPath())) {
					  if ($f->isFile()) {
						  if (!unlink($f->getRealPath()))
							  self::_errorHanler('directory-error', 'Failed to delete file.', array('{dir}' => $f->getRealPath()));
					  } elseif ($f->isDir()) {
						  array_push($emptiedDirs, $f->getRealPath());
					  }
					  continue 2;
				  }
			  }
			  $relativePath = str_replace($src, '', $f->getRealPath());
			  $destination  = $dest . $relativePath;
			  if ($f->isFile()) {
				  $path_parts = pathinfo($destination);
				  if (!is_dir($path_parts['dirname'])) {
					  if (!mkdir($path_parts['dirname'], 0, true))
						  self::_errorHanler('directory-error', 'Failed to create the destination directory.', array('{dir}' => $path_parts['dirname']));
				  }
				  if (!rename($f->getRealPath(), $destination))
					  self::_errorHanler('directory-error', 'Failed to rename file {$f->getRealPath()}', array('{dir}' => $destination));
			  } elseif ($f->isDir()) {
				  if (!is_dir($destination)) {
					  if (!mkdir($destination, 0, true))
						  self::_errorHanler('directory-error', 'Failed to create the destination directory', array('{dir}' => $destination));
				  }
				  array_push($emptiedDirs, $f->getRealPath());
			  } else {
				  self::_errorHanler('directory-error', 'Foundound {$f->getRealPath()} yet it appears to be neither a directory nor a file', array('{dir}' => $f->isDot()));
			  }
		  }
		  foreach ($emptiedDirs as $emptyDir) {
			  if (realpath($emptyDir) == realpath($src)) {
				  continue;
			  }
			  if (!is_readable($emptyDir))
				  self::_errorHanler('directory-error', 'The source directory is not Readable', array('{dir}' => $emptyDir));
			  if (!rmdir($emptyDir)) {
				  if ((count(scandir($emptyDir)) == 2)) {
					  self::_errorHanler('directory-error', 'Failed to delete the source directory', array('{dir}' => $emptyDir));
				  }
			  }
		  }
		  if (!rmdir($src))
			  self::_errorHanler('directory-error', 'Failed to delete the base source directory', array('{dir}' => $src));
		  return true;
	  }
	  
      /**
       * File::isDirectoryEmpty()
       * 
       * @param string $dir
       * @return
       */
      public static function isDirectoryEmpty($dir = '')
      {
          if ($dir == '' || !is_readable($dir))
              return false;
          $hd = opendir($dir);
          while (false !== ($entry = readdir($hd))) {
              if ($entry !== '.' && $entry !== '..') {
                  return false;
              }
          }
          closedir($hd);
          return true;
      }

      /**
       * File::getDirectoryFilesNumber()
       * 
       * @param string $dir
       * @return
       */
      public static function getDirectoryFilesNumber($dir = '')
      {
          return count(glob($dir . '*'));
      }

      /**
       * File::removeDirectoryOldestFile()
       * 
       * @param string $dir
       * @return
       */
      public static function removeDirectoryOldestFile($dir = '')
      {
          $oldestFileTime = date('Y-m-d H:i:s');
          $oldestFileName = '';
          if ($hdir = opendir($dir)) {
              while (false !== ($obj = readdir($hdir))) {
                  if ($obj == '.' || $obj == '..' || $obj == '.htaccess')
                      continue;
                  $fileTime = date('Y-m-d H:i:s', filectime($dir . $obj));
                  if ($fileTime < $oldestFileTime) {
                      $oldestFileTime = $fileTime;
                      $oldestFileName = $obj;
                  }
              }
          }
          if (!empty($oldestFileName)) {
              self::deleteFile($dir . $oldestFileName);
          }
      }

      /**
       * File::findSubDirectories()
       * 
       * @param string $dir
       * @param bool $fullPath
       * @return
       */
      public static function findSubDirectories($dir = '.', $fullPath = false)
      {
          $subDirectories = array();
          $folder = dir($dir);
          while ($entry = $folder->read()) {
              if ($entry != '.' && $entry != '..' && is_dir($dir . $entry)) {
                  $subDirectories[] = ($fullPath ? $dir : '') . $entry;
              }
          }
          $folder->close();
          return $subDirectories;
      }



      /**
       * File::scanRecursively()
       * 
       * @param string $dir
       * @param bool $filter
       * @return
       */
	  public static function scanRecursively($directory, $filter = false)
	  {
		  // if the path has a slash at the end we remove it here
		  if (substr($directory, -1) == '/') {
			  $directory = substr($directory, 0, -1);
		  }
	
		  if (!file_exists($directory) || !is_dir($directory)) {
			  self::_errorHanler('directory-error', 'Invalid directory selected {dir}.', array('{dir}' => $directory));
			  return false;
	
		  } elseif (is_readable($directory)) {
			  $directory_tree = array();
				  $directory_list = opendir($directory);
	
			  while (false !== ($file = readdir($directory_list))) {
				  if ($file != '.' && $file != '..') {
					  $path = $directory . '/' . $file;
	
					  // if the path is readable
					  if (is_readable($path)) {
						  $subdirectories = explode('/', $path);
	
						  if (is_dir($path)) {
							  $directory_tree[] = array(
								  'path' => $path,
								  'url' => str_replace(BASEPATH, "", $path) . "/",
								  'name' => str_replace("_", " ", end($subdirectories)),
								  'kind' => 'directory',
								  'total' => 'directory',
								  'content' => self::scanRecursively($path, $filter));
	
							  // if the new path is a file
						  } elseif (is_file($path)) {
							  $ext = explode(".", end($subdirectories));
							  $extension = $ext[count($ext) - 1];
	
							  if ($filter === false || $filter == $extension) {
								  $directory_tree[] = array(
									  'path' => $path,
									  'url' => str_replace(BASEPATH, "", $path),
									  'name' => end($subdirectories),
									  'extension' => $extension,
									  'ftime' => date('d-m-Y', filemtime($path)),
									  'size' => filesize($path),
									  'kind' => 'file');
							  }
						  }
					  }
				  }
			  }
			  closedir($directory_list);
			  return $directory_tree;
	
		  } else {
			  self::_errorHanler('directory-error', 'Directory not readable {dir}.', array('{dir}' => $directory));
			  return false;
		  }
	  }

      /**
       * File::loadFile()
       * 
       * @param string $file
       * @return
       */
      public static function loadFile($file = '')
      {
          $content = file_get_contents($file);
          self::_errorHanler('file-loading-error', 'An error occurred while loading file {file}.', array('{file}' => $file));
          return $content;
      }
	  
      /**
       * File::writeToFile()
       * 
       * @param string $file
       * @param string $content
       * @return
       */
      public static function writeToFile($file = '', $content = '')
      {
		  file_put_contents($file, urldecode($content));
          self::_errorHanler('file-writing-error', 'An error occurred while writing to file {file}.', array('{file}' => $file));
          return true;
      }

      /**
       * File::copyFile()
       * 
	   * @param string $src (absolute path BASEPATH . $sourcePath)
	   * @param string $dest (absolute path BASEPATH . $targetPath)
       * @param string $src
       * @param string $dest
       * @return
       */
      public static function copyFile($src = '', $dest = '')
      {
          $result = copy($src, $dest);
          self::_errorHanler('file-coping-error', 'An error occurred while copying the file {source} to {destination}.', array('{source}' => $src, '{destination}' => $dest));
          return $result;
      }

      /**
       * File::findFiles()
       * 
	   * Returns the files found under the given directory and subdirectories
	   * Usage:
	   * findFiles(
	   *    $dir,
	   *    array(
	   *       'fileTypes'=>array('php', 'zip'),
	   *   	 'exclude'=>array('html', 'htaccess', 'path/to/'),
	   *   	 'level'=>-1,
	   *       'returnType'=>'fileOnly'
	   *  ))
	   * fileTypes: array, list of file name suffix (without dot). 
	   * exclude: array, list of directory and file exclusions. Each exclusion can be either a name or a path.
	   * level: integer, recursion depth, (-1 - unlimited depth, 0 - current directory only, N - recursion depth)
	   * returnType : 'fileOnly' or 'fullPath'
       * @param mixed $dir
       * @param mixed $options
       * @return
       */
      public static function findFiles($dir, $options = array())
      {
          $fileTypes = isset($options['fileTypes']) ? $options['fileTypes'] : array();
          $exclude = isset($options['exclude']) ? $options['exclude'] : array();
          $level = isset($options['level']) ? $options['level'] : -1;
          $returnType = isset($options['returnType']) ? $options['returnType'] : 'fileOnly';
          $filesList = self::_findFilesRecursive($dir, '', $fileTypes, $exclude, $level, $returnType);
          sort($filesList);
          return $filesList;
      }

      /**
       * File::deleteFile()
       * 
       * @param string $file
       * @return
       */
      public static function deleteFile($file = '')
      {
		  $result = false;
		  if (is_file($file)) {
			  $result = unlink($file);
		  }
          self::_errorHanler('file-deleting-error', 'An error occurred while deleting the file {file}.', array('{file}' => $file));
          return $result;
      }

	  /**
	   * File::getThemes()
	   * 
	   * @param mixed $dir
	   * @param mixed $selected
	   * @return
	   */
	  public static function getThemes($dir, $selected)
	  {
		  $directories = glob($dir . '/*', GLOB_ONLYDIR);
		  if ($directories) {
			  foreach ($directories as $row)
				  $dir = basename($row);
			  $selected = ($selected == $dir) ? " selected=\"selected\"" : "";
			  print "<option value=\"{$dir}\"{$selected}>{$dir}</option>\n";
		  }
	  }

	  /**
	   * File::getMailerTemplates()
	   * 
	   * @return
	   */
	  public static function getMailerTemplates()
	  {
		  $path = BASEPATH . "/mailer/" . App::get('Core')->lang . "/";
          $files = glob($path . "*.{tpl.php}", GLOB_BRACE); 
		  
          return $files;
	  }
	  
      /**
       * File::getFileSize()
       * 
       * @param mixed $file
       * @param string $units
	   * @param bool $print
       * @return
       */
      public static function getFileSize($file, $units = 'kb', $print = false)
      {
          if (!$file || !is_file($file))
              return 0;
          $showunit = $print ? $units : null;
          $filesSize = filesize($file);
          switch (strtolower($units)) {
              case 'g':
              case 'gb':
                  $result = number_format($filesSize / (1024 * 1024 * 1024), 2, '.', ',') . $showunit;;
                  break;
              case 'm':
              case 'mb':
                  $result = number_format($filesSize / (1024 * 1024), 2, '.', ',') . $showunit;;
                  break;
              case 'k':
              case 'kb':
                  $result = number_format($filesSize / 1024, 2, '.', ',') . $showunit;
                  break;
              case 'b':
              default:
                  $result = number_format($filesSize, 2, '.', ',') . $showunit;;
                  break;
          }
          return $result;
      }

	  /**
	   * File::getSize()
	   * 
	   * @param mixed $size
	   * @param integer $precision
	   * @param bool $long_name
	   * @param bool $real_size
	   * @return
	   */
	  public static function getSize($size, $precision = 2, $long_name = false, $real_size = true)
	  {
		  $base = $real_size ? 1024 : 1000;
		  $pos = 0;
		  while ($size > $base) {
			  $size /= $base;
			  $pos++;
		  }
		  $prefix = self::_getSizePrefix($pos);
		  @$size_name = ($long_name) ? $prefix . "bytes" : $prefix[0] . "B";
		  return round($size, $precision) . ' ' . ucfirst($size_name);
	  }
	  
	  /**
	   * File::_getSizePrefix()
	   * 
	   * @param mixed $pos
	   * @return
	   */
	  private static function _getSizePrefix($pos)
	  {
		  switch ($pos) {
			  case 00:
				  return "";
			  case 01:
				  return "kilo";
			  case 02:
				  return "mega";
			  case 03:
				  return "giga";
			  default:
				  return "?-";
		  }
	  }
  
      /**
       * File::createShortenName()
       * 
       * @param mixed $file
       * @param integer $lengthFirst
       * @param integer $lengthLast
       * @return
       */
      public static function createShortenName($file, $lengthFirst = 10, $lengthLast = 10)
      {
          return preg_replace("/(?<=.{{$lengthFirst}})(.+)(?=.{{$lengthLast}})/", "...", $file);
      }
	  
      /**
       * File::_findFilesRecursive()
       * 
       * @param mixed $dir
       * @param mixed $base
       * @param mixed $fileTypes
       * @param mixed $exclude
       * @param mixed $level
       * @param string $returnType
       * @return
       */
      protected static function _findFilesRecursive($dir, $base, $fileTypes, $exclude, $level, $returnType = 'fileOnly')
      {
          $list = array();
          if ($hdir = opendir($dir)) {
              while (($file = readdir($hdir)) !== false) {
                  if ($file === '.' || $file === '..')
                      continue;
                  $path = $dir . '/' . $file;
                  $isFile = is_file($path);
                  if (self::_validatePath($base, $file, $isFile, $fileTypes, $exclude)) {
                      if ($isFile) {
                          $list[] = ($returnType == 'fileOnly') ? $file : $path;
                      } else
                          if ($level) {
                              $list = array_merge($list, self::_findFilesRecursive($path, $base . '/' . $file, $fileTypes, $exclude, $level - 1, $returnType));
                          }
                  }
              }
          }
          closedir($hdir);
          return $list;
      }

      /**
       * File::_validatePath()
       * 
       * @param mixed $base
       * @param mixed $file
       * @param mixed $isFile
       * @param mixed $fileTypes
       * @param mixed $exclude
       * @return
       */
      protected static function _validatePath($base, $file, $isFile, $fileTypes, $exclude)
      {
          foreach ($exclude as $e) {
              if ($file === $e || strpos($base . '/' . $file, $e) === 0)
                  return false;
          }
          if (!$isFile || empty($fileTypes))
              return true;
          if (($type = pathinfo($file, PATHINFO_EXTENSION)) !== '') {
              return in_array($type, $fileTypes);
          } else {
              return false;
          }
      }

      /**
       * File::_errorHanler()
       * 
       * @param string $msgType
       * @param string $msg
       * @return
       */
      private static function _errorHanler($msgType = '', $msg = '')
      {
          if (version_compare(PHP_VERSION, '5.3.0', '>=')) {
              $err = error_get_last();
              if (isset($err['message']) && $err['message'] != '') {
                  $lastError = $err['message'] . ' | file: ' . $err['file'] . ' | line: ' . $err['line'];
                  $errorMsg = ($lastError) ? $lastError : $msg;
                  Debug::addMessage('errors', $msgType, $errorMsg, 'session');
                  @trigger_error('');
              }
          }
      }

  }