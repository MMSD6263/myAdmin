<?

namespace App\Repositories\commonclass;

class FSC
{
    /**
     * 功能: 得到指定文件的内容
     * 参数: $file 目标文件
     *  test passed
     * @param $file
     * @return bool|string
     */
    public function getFileSource($file)
    {

        if ($fp = fopen($file, 'r')) {
            $filesource = fread($fp, filesize($file));
            fclose($fp);
            return $filesource;
        } else
            return false;
    }

    /*
     * 功能: 创建新文件，并写入内容，如果指定文件名已存在，那将直接覆盖
     * 参数: $file -- 新文件名
     * $source  文件内容
     * test passed
     */
    public function writeFile($file, $source)
    {

        if ($fp = fopen($file, 'w')) {
            $filesource = fwrite($fp, $source);
            fclose($fp);
            return $filesource;
        } else
            return false;
    }

    /*
      *  功能: 移动文件
      *  参数: $file -- 待移动的文件名
      *  $destfile -- 目标文件名
      *  $overwrite 如果目标文件存在，是否覆盖.默认是覆盖.
      *  $bak 是否保留原文件 默认是不保留即删除原文件
      *  test passed
     */

    function moveFile($file, $destfile, $overwrite = 1, $bak = 0)
    {

        if (file_exists($destfile)) {
            if ($overwrite)
                unlink($destfile);
            else
                return false;
        }

        if ($cf = copy($file, $destfile)) {

            if (!$bak)
                return (unlink($file));
        }
        return ($cf);
    }

    /**
     * 功能: 这是下一涵数move的附助函数，功能就是移动目录
     * @param $dir
     * @param $destdir
     * @param int $overwrite
     * @param int $bak
     * @return bool
     */
    function moveDir($dir, $destdir, $overwrite = 1, $bak = 0)
    {
        @set_time_limit(600);
        if (!file_exists($destdir))
            FSC::notFateAnyMkDir($destdir);
        if (file_exists($dir) && (is_dir($dir))) {
            if (substr($dir, -1) != '/') $dir .= '/';
            if (file_exists($destdir) && (is_dir($destdir))) {
                if (substr($destdir, -1) != '/') $destdir .= '/';
                $h = opendir($dir);
                while ($file = readdir($h)) {
                    if ($file == '.' || $file == '..') {
                        continue;
                        $file = "";
                    }
                    if (is_dir($dir . $file)) {
                        if (!file_exists($destdir . $file))
                            FSC::notFateMkDir($destdir . $file);
                        else
                            chmod($destdir . $file, 0777);
                        FSC::moveDir($dir . $file, $destdir . $file, $overwrite, $bak);
                        FSC::delForder($dir . $file);
                    } else {
                        if (file_exists($destdir . $file)) {
                            if ($overwrite) unlink($destdir . $file);
                            else {
                                continue;
                                $file = "";
                            }
                        }
                        if (copy($dir . $file, $destdir . $file))
                            if (!$bak)
                                if (file_exists($dir . $file) && is_file($dir . $file))
                                    @unlink($dir . $file);
                    }
                }
            } else
                return false;
        } else
            return false;
    }

    /**
     *  函数名: move
     *   功能: 移动文件或目录
     *   参数: $file -- 源文件/目录
     *   $path -- 目标路径
     *   $overwrite -- 如是目标路径中已存在该文件时，是否覆盖移动
     *   --  默认值是1, 即覆盖
     *   $bak  -- 是否保留备份(原文件/目录)
     * @param $file
     * @param $path
     * @param int $overwrite
     * @param int $bak
     * @return bool
     */

    function move($file, $path, $overwrite = 1, $bak = 0)
    {
        if (file_exists($file)) {
            if (is_dir($file)) {
                if (substr($file, -1) == '/') $dirname = basename(substr($file, 0, strlen($file) - 1));
                else $dirname = basename($file);
                if (substr($path, -1) != '/') $path .= '/';
                if ($file != '.' || $file != '..' || $file != '../' || $file != './') $path .= $dirname;
                FSC::moveDir($file, $path, $overwrite, $bak);
                if (!$bak) FSC::delForder($file);
            } else {
                if (file_exists($path)) {
                    if (is_dir($path)) chmod($path, 0777);
                    else {
                        if ($overwrite)
                            @unlink($path);
                        else
                            return false;
                    }
                } else
                    FSC::notFateAnyMkDir($path);
                if (substr($path, -1) != '/') $path .= '/';
                FSC::movefile($file, $path . basename($file), $overwrite, $bak);
            }
        } else
            return false;
    }

    /**
     *  函数名: move
     *   功能: 移动文件或目录
     *   参数: $file -- 源文件/目录
     *   $path -- 目标路径
     *   $overwrite -- 如是目标路径中已存在该文件时，是否覆盖移动
     *   --  默认值是1, 即覆盖
     *   $bak  -- 是否保留备份(原文件/目录)
     * @param $file
     * @return bool
     */

    function delForder($file)
    {
        chmod($file, 0777);
        if (is_dir($file)) {
            $handle = opendir($file);
            while ($filename = readdir($handle)) {
                if ($filename != "." && $filename != "..") {
                    FSC::delForder($file . "/" . $filename);
                }
            }
            closedir($handle);
            return (rmdir($file));
        } else {
            unlink($file);
        }
    }

    /**
     * 函数名: notFateMkDir
     * 功能: 创建新目录,这是来自php.net的一段代码.弥补mkdir的不足.
     * 参数: $dir -- 目录名
     * @param $dir
     * @param int $mode
     * @return bool
     */
    function notFateMkDir($dir, $mode = 0777)
    {
        $u = umask(0);
        $r = mkdir($dir, $mode);
        umask($u);
        return $r;
    }

    /**
     * 功能: 创建新目录,与上面的notFateMkDir有点不同，因为它多了一个any,即可以创建多级目录
     * 如:notFateAnyMkDir("abc/abc/abc/abc/abc")
     * 参数: $dirs -- 目录名
     * @param $dirs
     * @param int $mode
     * @return bool
     */
    function notFateAnyMkDir($dirs, $mode = 0777)
    {
        if (!strrpos($dirs, '/')) {
            return (FSC::notFateMkDir($dirs, $mode));
        } else {
            $forder = explode('/', $dirs);
            $f      = '';
            for ($n = 0; $n < count($forder); $n++) {
                if ($forder[$n] == '') continue;
                $f .= ((($n == 0) && ($forder[$n] <> '')) ? ('') : ('/')) . $forder[$n];
                if (file_exists($f)) {
                    chmod($f, 0777);
                    continue;
                } else {
                    if (FSC::notFateMkDir($f, $mode)) continue;
                    else
                        return false;
                }
            }
            return true;
        }
    }

    /**
     * 遍历数据当前所有文件
     * 并且返回
     * @param $dir
     * @param array $filter
     * @param $flag
     * @return array|bool
     */
    public function scanDir($dir, $filter = ['log', 'sql'])
    {
        if (!is_dir($dir)) return false;
        $files = array_diff(scandir($dir), array('.', '..'));
        if (is_array($files)) {
            foreach ($files as $key => $value) {
                if (is_dir($dir . '/' . $value)) {
                    $files[$value] = $this->scanDir($dir . '/' . $value, $filter);
                    unset($files[$key]);
                    continue;
                }

                $pathinfo  = pathinfo($dir . '/' . $value);
                $extension = array_key_exists('extension', $pathinfo) ? $pathinfo['extension'] : '';
                if (!in_array($extension, $filter)) {
                    unset($files[$key]);
                }
            }
        }
        unset($key, $value);
        return $files;
    }

    /**
     * 获取文件的大小和修改日期
     * @param $filePath
     * @return string
     */

    public function getFileSize($filePath)
    {
        $bytes = filesize($filePath);
        if ($bytes > pow(2, 40)) {
            $size = round($bytes / pow(1024, 4), 2);
            $unit = 'TB';
        } elseif ($bytes > pow(2, 30)) {
            $size = round($bytes / pow(1024, 3), 2);
            $unit = 'GB';
        } elseif ($bytes > pow(2, 20)) {
            $size = round($bytes / pow(1024, 2), 2);
            $unit = 'MB';
        } elseif ($bytes > pow(2, 10)) {
            $size = round($bytes / pow(1024, 1), 2);
            $unit = 'KB';
        } else {
            $size = $bytes;
            $unit = 'Byte';
        }

        $fileCTime = date('Y-m-d H:i:s', filectime($filePath));
        $fileMTime = date('Y-m-d H:i:s', filemtime($filePath));
        $fileATime = date('Y-m-d H:i:s', fileatime($filePath));
        $size      = $size . ' ' . $unit;

        return [
            'CTime' => $fileCTime,
            'MTime' => $fileMTime,
            'ATime' => $fileATime,
            'size'  => $size,
        ];
    }

    /**
     * 删除单文件
     */
    public function delFile($path)
    {
        $result = @unlink ($path);
        if ($result){
            return true;
        } else {
           return  false;
        }
    }
}