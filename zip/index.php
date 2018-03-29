<?php
/**
 * @check     if login
 * @method:
 * @license   http://www.blags.org/
 * @created   :2010年07月24日 15时58分
 * @copyright 1997-2010 The Martin Group
 * @author    Martin <china.codehome@gmail.com>
 * */
include __DIR__ . '/../../../mainfile.php';
//验证密码
$password = '123465';

global $xoopsUser;
if (!$xoopsUser) {
    redirect_header(XOOPS_URL . '/user.php?xoops_redirect=/' . $_SERVER['REQUEST_URI'], 1, '您还没有登录.');
}
$isAdmin = $xoopsUser->isAdmin();
//var_dump($xoopsUser);
?>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <title>ZIP压缩程序</title>
    <style type="text/css">
        <!--
        body, td {
            font-size: 14px;
            color: #000000;
        }

        a {
            color: #000066;
            text-decoration: none;
        }

        a:hover {
            color: #FF6600;
            text-decoration: underline;
        }

        -->
    </style>
</head>

<body>
<form name="myform" method="post" action="index.php">
    <font color="#FF0000">在线压缩ZIP文件程序</font><br>

    <div style="color:#FF9900;">
        <p>使用方法:选定要压缩的文件或目录（包含子目录），即可开始压缩。</p>
        <p>压缩的结果保留原来的目录结构。</p>
    </div>
    <?php
    if (!$_REQUEST['myaction']):
        ?>
        <table width="100%" border="0" cellspacing="0" cellpadding="0">
            <tr>
                <td width="11%">验证密码:</td>
                <td width="89%"><input name="password" type="password" id="password" size="15"></td>
            </tr>
            <tr>
                <td><input name="myaction" type="hidden" id="myaction" value="dolist"></td>
                <td><input type="submit" name="Submit" value=" 进 入 "></td>
            </tr>
        </table>
    <?php

    elseif ('dolist' === $_REQUEST['myaction']):
    if ($_REQUEST['password'] != $password) {
        die('输入的密码不正确，请重新输入。');
    }
    echo '选择要压缩的文件或目录：<br>';
    $www_path = '../../../../';
    $fdir     = opendir($www_path);
    while ($file = readdir($fdir)) {
        if ('.' === $file || '..' === $file) {
            continue;
        }
        echo "<input name='dfile[]' type='checkbox' value='$www_path$file' " . ($file == basename(__FILE__) ? '' : 'checked') . '> ';
        if (is_file($file)) {
            echo "文件: $file<br>";
        } else {
            echo "目录: $file<br>";
        }
    }
    ?>
    <br>
        压缩文件保存到目录:
    <input name="todir" type="hidden" id="todir" value="" size="15">
        (留空为本目录,必须有写入权限)<br>
        压缩文件名称:
    <input name="zipname" type="text" id="zipname" value="zip.zip" size="15">
        (.zip)<br>
    <br>
    <input name="password" type="hidden" id="password" value="<?= $_POST['password']; ?>">
    <input name="myaction" type="hidden" id="myaction" value="dozip">
    <input type='button' value='反选' onclick='selrev();'>
    <input type="submit" name="Submit" value=" 开始压缩 ">
        <script language='javascript'>
            function selrev() {
                with (document.myform) {
                    for (i = 0; i < elements.length; i++) {
                        thiselm = elements[i];
                        if (thiselm.name.match(/dfile\[]/)) thiselm.checked = !thiselm.checked;
                    }
                }
            }
        </script>
        <?php

    elseif ('dozip' === $_REQUEST['myaction']):

        //  set_time_limit(0);

        /**
         * Class PHPzip
         */
        class PHPzip
        {
            public $file_count  = 0;
            public $datastr_len = 0;
            public $dirstr_len  = 0;
            public $filedata    = ''; //该变量只被类外部程序访问
            public $gzfilename;
            public $fp;
            public $dirstr      = '';

            /*
            返回文件的修改时间格式.
            只为本类内部函数调用.
            */
            /**
             * @param  int $unixtime
             * @return int
             */
            public function unix2DosTime($unixtime = 0)
            {
                $timearray = (0 == $unixtime) ? getdate() : getdate($unixtime);

                if ($timearray['year'] < 1980) {
                    $timearray['year']    = 1980;
                    $timearray['mon']     = 1;
                    $timearray['mday']    = 1;
                    $timearray['hours']   = 0;
                    $timearray['minutes'] = 0;
                    $timearray['seconds'] = 0;
                }

                return (($timearray['year'] - 1980) << 25) | ($timearray['mon'] << 21) | ($timearray['mday'] << 16) | ($timearray['hours'] << 11) | ($timearray['minutes'] << 5) | ($timearray['seconds'] >> 1);
            }

            /*
            初始化文件,建立文件目录,
            并返回文件的写入权限.
            */
            /**
             * @param  string $path
             * @return bool
             */
            public function startfile($path = 'shenbin.zip')
            {
                $this->gzfilename = $path;
                $mypathdir        = [];
                do {
                    $mypathdir[] = $path = dirname($path);
                } while ('.' !== $path);
                @end($mypathdir);
                do {
                    $path = @current($mypathdir);
                    @mkdir($path);
                } while (@prev($mypathdir));

                if ($this->fp = @fopen($this->gzfilename, 'w')) {
                    return true;
                }

                return false;
            }

            /*
            添加一个文件到 zip 压缩包中.
            */
            /**
             * @param $data
             * @param $name
             */
            public function addfile($data, $name)
            {
                $name = str_replace('\\', '/', $name);

                if ('/' === strrchr($name, '/')) {
                    return $this->adddir($name);
                }

                $dtime    = dechex($this->unix2DosTime());
                $hexdtime = '\x' . $dtime[6] . $dtime[7] . '\x' . $dtime[4] . $dtime[5] . '\x' . $dtime[2] . $dtime[3] . '\x' . $dtime[0] . $dtime[1];
                eval('$hexdtime = "' . $hexdtime . '";');

                $unc_len = strlen($data);
                $crc     = crc32($data);
                $zdata   = gzcompress($data);
                $c_len   = strlen($zdata);
                $zdata   = substr(substr($zdata, 0, strlen($zdata) - 4), 2);

                //新添文件内容格式化:
                $datastr = "\x50\x4b\x03\x04";
                $datastr .= "\x14\x00";            // ver needed to extract
                $datastr .= "\x00\x00";            // gen purpose bit flag
                $datastr .= "\x08\x00";            // compression method
                $datastr .= $hexdtime;             // last mod time and date
                $datastr .= pack('V', $crc);             // crc32
                $datastr .= pack('V', $c_len);           // compressed filesize
                $datastr .= pack('V', $unc_len);         // uncompressed filesize
                $datastr .= pack('v', strlen($name));    // length of filename
                $datastr .= pack('v', 0);                // extra field length
                $datastr .= $name;
                $datastr .= $zdata;
                $datastr .= pack('V', $crc);                 // crc32
                $datastr .= pack('V', $c_len);               // compressed filesize
                $datastr .= pack('V', $unc_len);             // uncompressed filesize

                fwrite($this->fp, $datastr);    //写入新的文件内容
                $my_datastr_len = strlen($datastr);
                unset($datastr);

                //新添文件目录信息
                $dirstr = "\x50\x4b\x01\x02";
                $dirstr .= "\x00\x00";                    // version made by
                $dirstr .= "\x14\x00";                    // version needed to extract
                $dirstr .= "\x00\x00";                    // gen purpose bit flag
                $dirstr .= "\x08\x00";                    // compression method
                $dirstr .= $hexdtime;                    // last mod time & date
                $dirstr .= pack('V', $crc);            // crc32
                $dirstr .= pack('V', $c_len);            // compressed filesize
                $dirstr .= pack('V', $unc_len);        // uncompressed filesize
                $dirstr .= pack('v', strlen($name));    // length of filename
                $dirstr .= pack('v', 0);                // extra field length
                $dirstr .= pack('v', 0);                // file comment length
                $dirstr .= pack('v', 0);                // disk number start
                $dirstr .= pack('v', 0);                // internal file attributes
                $dirstr .= pack('V', 32);                // external file attributes - 'archive' bit set
                $dirstr .= pack('V', $this->datastr_len); // relative offset of local header
                $dirstr .= $name;

                $this->dirstr .= $dirstr;    //目录信息

                $this->file_count++;
                $this->dirstr_len  += strlen($dirstr);
                $this->datastr_len += $my_datastr_len;
            }

            /**
             * @param $name
             */
            public function adddir($name)
            {
                $name    = str_replace("\\", '/', $name);
                $datastr = "\x50\x4b\x03\x04\x0a\x00\x00\x00\x00\x00\x00\x00\x00\x00";

                $datastr .= pack('V', 0) . pack('V', 0) . pack('V', 0) . pack('v', strlen($name));
                $datastr .= pack('v', 0) . $name . pack('V', 0) . pack('V', 0) . pack('V', 0);

                fwrite($this->fp, $datastr);    //写入新的文件内容
                $my_datastr_len = strlen($datastr);
                unset($datastr);

                $dirstr = "\x50\x4b\x01\x02\x00\x00\x0a\x00\x00\x00\x00\x00\x00\x00\x00\x00";
                $dirstr .= pack('V', 0) . pack('V', 0) . pack('V', 0) . pack('v', strlen($name));
                $dirstr .= pack('v', 0) . pack('v', 0) . pack('v', 0) . pack('v', 0);
                $dirstr .= pack('V', 16) . pack('V', $this->datastr_len) . $name;

                $this->dirstr .= $dirstr;    //目录信息

                $this->file_count++;
                $this->dirstr_len  += strlen($dirstr);
                $this->datastr_len += $my_datastr_len;
            }

            public function createfile()
            {
                //压缩包结束信息,包括文件总数,目录信息读取指针位置等信息
                $endstr = "\x50\x4b\x05\x06\x00\x00\x00\x00" . pack('v', $this->file_count) . pack('v', $this->file_count) . pack('V', $this->dirstr_len) . pack('V', $this->datastr_len) . "\x00\x00";

                fwrite($this->fp, $this->dirstr . $endstr);
                fclose($this->fp);
            }
        }

        if (!trim($_REQUEST[zipname])) {
            $_REQUEST[zipname] = 'shenbin.zip';
        } else {
            $_REQUEST[zipname] = trim($_REQUEST[zipname]);
        }
        if ('.zip' === !strrchr(strtolower($_REQUEST[zipname]), '.')) {
            $_REQUEST[zipname] .= '.zip';
        }
        $_REQUEST[todir] = str_replace('\\', '/', trim($_REQUEST[todir]));
        if ('/' === !strrchr(strtolower($_REQUEST[todir]), '/')) {
            $_REQUEST[todir] .= '/';
        }
        if ('/' === $_REQUEST[todir]) {
            $_REQUEST[todir] = './';
        }

        /**
         * @param  string $dir
         * @return int
         */
        function listfiles($dir = '.')
        {
            global $faisunZIP;
            $sub_file_num = 0;

            if (is_file((string)$dir)) {
                if (realpath($faisunZIP->gzfilename) != realpath((string)$dir)) {
                    $faisunZIP->addfile(implode('', file((string)$dir)), (string)$dir);

                    return 1;
                }

                return 0;
            }

            $handle = opendir((string)$dir);
            while ($file = readdir($handle)) {
                if ('.' === $file || '..' === $file) {
                    continue;
                }
                if (is_dir("$dir/$file")) {
                    $sub_file_num += listfiles("$dir/$file");
                } else {
                    if (realpath($faisunZIP->gzfilename) != realpath("$dir/$file")) {
                        $faisunZIP->addfile(implode('', file("$dir/$file")), "$dir/$file");
                        $sub_file_num++;
                    }
                }
            }
            closedir($handle);
            if (!$sub_file_num) {
                $faisunZIP->addfile('', "$dir/");
            }

            return $sub_file_num;
        }

        /**
         * @param $num
         * @return string
         */
        function num_bitunit($num)
        {
            $bitunit = [' B', ' KB', ' MB', ' GB'];
            for ($key = 0; $key < count($bitunit); $key++) {
                if ($num >= pow(2, 10 * $key) - 1) { //1023B 会显示为 1KB
                    $num_bitunit_str = (ceil($num / pow(2, 10 * $key) * 100) / 100) . " $bitunit[$key]";
                }
            }

            return $num_bitunit_str;
        }

        if (is_array($_REQUEST[dfile])) {
            $faisunZIP = new PHPzip;
            if ($faisunZIP->startfile("$_REQUEST[todir]$_REQUEST[zipname]")) {
                echo '正在添加压缩文件...<br><br>';
                $filenum = 0;
                foreach ($_REQUEST[dfile] as $file) {
                    if (is_file($file)) {
                        echo "文件: $file<br>";
                    } else {
                        echo "目录: $file<br>";
                    }
                    $filenum += listfiles($file);
                }
                $faisunZIP->createfile();
                echo "<br>压缩完成,共添加 $filenum 个文件.<br><a href='$_REQUEST[todir]$_REQUEST[zipname]'>$_REQUEST[todir]$_REQUEST[zipname] (" . num_bitunit(filesize("$_REQUEST[todir]$_REQUEST[zipname]")) . ')</a>';
            } else {
                echo "$_REQUEST[todir]$_REQUEST[zipname] 不能写入,请检查路径或权限是否正确.<br>";
            }
        } else {
            echo '没有选择的文件或目录.<br>';
        }
        chmod($file, 0777);

    endif;

    ?>
</form>
</body>
</html>
