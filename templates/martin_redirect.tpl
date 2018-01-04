<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=<{$xoops_charset}>">
    <title><{$message}> - <{$xoops_sitename}></title>
    <link rel="stylesheet" type="text/css" media="all" href="<{$xoops_themecss}>">
    <style type="text/css">
        /*跳转页面*/
        .block {
            width: 980px;
            overflow: hidden;
            text-align: left;
        }

        .showinfo {
            width: 800px;
            height: 300px;
            margin: 80px auto;
            overflow: hidden;
            background: url(/images/showinfo.gif) left bottom repeat-y;
        }

        .showinfo .showtop {
            width: 800px;
            height: 9px;
            overflow: hidden;
            background: url(/images/showinfo_top.gif) top center no-repeat;
        }

        .showinfo h2 {
            width: 750px;
            height: 30px;
            margin: 0 auto;
            margin-top: 20px;
            font-size: 15px;
            line-height: 30px;
            border-bottom: #CCCCCC 1px solid;
            margin-bottom: 15px;
        }

        .showinfo h2 img {
            float: left;
            margin: 5px 5px 0 0;
        }

        .showinfo p {
            color: #000000;
            margin-top: 10px;
        }

        .showinfo p a {
            color: #FF7800;
        }

        .showinfo p a:hover {
            color: #FF7800;
        }

        /*结束跳转页面*/
    </style>
</head>
<body>
<!--<div style="text-align:center; background-color: #EBEBEB; border-top: 1px solid #FFFFFF; border-left: 1px solid #FFFFFF; border-right: 1px solid #AAAAAA; border-bottom: 1px solid #AAAAAA; font-weight : bold;">
  <h4><{$message}></h4>
  <p><{$lang_ifnotreload}></p>
</div>-->
<div class="block">
    <div class="showinfo">
        <div class="showtop"></div>
        <h2><img src="/images/showicon.gif" width="18" height="18"><{$message}></h2>
        <p align="left">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<{$nextMsg}></p>
        <p align="center"><b><font color="#FF7B04"> 下一步：</font></b> <a href="<{$url}>"><{$NavMsg}></a></p>
        <{if $xoops_logdump != ''}>
            <div><{$xoops_logdump}></div><{/if}>
    </div>
</div>
</body>
</html>
