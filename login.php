<?php
	session_start();
	
	error_reporting(0);

	define("IN_PHP",11);
	include_once 'class/mysql.class.php';
	$dbObj = new db_mysql("localhost","root","","byzp");

	

	if(!empty($_POST))
	{
		//用户名
		$usr  = isset($_POST['usr'])?$_POST['usr']:'';
		//密码
		$pwds = isset($_POST['pwds'])?$_POST['pwds']:'';
		//验证码
		$code = isset($_POST['authcode'])?$_POST['authcode']:'';
		//记住账号
		$reg = isset($_POST['reg'])?$_POST['reg']:'';

		if($usr =='' || $pwds == '' || $code=='')
		{
			echo "<script>";
			echo "alert('账号,密码,及验证码都不能为空!');";
			echo "location.href='login.php';";
			echo "</script>";
		}

		//获取生成的验证码
		$codeArr = explode('|',$_SESSION['code']);
		
		//判断验证码是否超时
		if(time() - $codeArr[1] > 60)
		{
			echo "<script>";
			echo "alert('验证码超时');";
			echo "location.href='login.php'";
			echo "</script>";
		}

		echo "<pre>";
		//print_r($_SESSION);
		echo "</pre>";

		if(strtoupper($code) != strtoupper($codeArr[0]))
		{
			echo "<script>";
			echo "alert('验证码不正确');";
			echo "location.href='login.php';";
			echo "</script>";
		}

		//账号
		$sql = " select * from admin where name='".$usr."'";
		$uArr = $dbObj->getone($sql);

		/*echo "<pre>";
		print_r($uArr);
		echo "</pre>";
		exit();*/

		if(empty($uArr))
		{
			echo "<script>";
			echo "alert('账号不正确');";
			echo "location.href='login.php'";
			echo "</script>";
		}
		else
		{
			//if(md5($pwds) == $uArr['pass'])
			if ($pwds==$uArr['pass'])
			{
				//处理记住账号
				if($reg == 1)
				{
					setcookie('REGUSR',$usr,time()+60*60*24*7,'/');
				}
				//将登陆成功的用户写入session
				$_SESSION['USR']=$usr;
				echo "<script>";
				echo "alert('登陆成功');";
				echo "location.href='index.php'";
				echo "</script>";
			}
			else
			{
				echo "<script>";
				echo "alert('密码错误');";
				echo "location.href='login.php'";
				echo "</script>";
			}
		}
	}
?>
<!doctype html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>后台登录</title>
	<meta name="renderer" content="webkit|ie-comp|ie-stand">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <meta name="viewport" content="width=device-width,user-scalable=yes, minimum-scale=0.4, initial-scale=0.8,target-densitydpi=low-dpi" />
    <meta http-equiv="Cache-Control" content="no-siteapp" />

    <link rel="shortcut icon" href="/favicon.ico" type="image/x-icon" />
    <link rel="stylesheet" href="./css/font.css">
	<link rel="stylesheet" href="./css/xadmin.css">
    <script type="text/javascript" src="https://cdn.bootcss.com/jquery/3.2.1/jquery.min.js"></script>
    <script src="./lib/layui/layui.js" charset="utf-8"></script>
    <script type="text/javascript" src="./js/xadmin.js"></script>

</head>
<body class="login-bg">
    
    <div class="login layui-anim layui-anim-up">
        <div class="message">后台管理登录</div>
        <div id="darkbannerwrap"></div>
        
        <form action="login.php" method="post" class="layui-form" >
            <input name="usr" placeholder="用户名"  type="text" lay-verify="required" class="layui-input" >
            <hr class="hr15">
            <input name="pwds" lay-verify="required" placeholder="密码"  type="password" class="layui-input">
            <hr class="hr15">
			<input type='text' placeholder="验证码" name='authcode' id='authcode'>
			<div id='usrmag2'></div>
			<img src='code.php' id='auths'>
			<a href='javascript:;' onclick='change();'>换一张</a><br>
			<hr class="hr15">
			
            <input value="登录" lay-submit lay-filter="login" style="width:100%;" type="submit">
			<br><br>
			<a href='#'>忘记密码</a>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
			<a href='login2.php'>没有账号,先注册</a>
            <hr class="hr20" >
        </form>
    </div>

	<script src='jquery-1.7.js'></script>
	<script>
	<!--
		function change()
		{
			$("#auths").attr("src","code.php?"+Math.random());
		}
	-->
	</script>
    <!--script>
        $(function  () {
            layui.use('form', function(){
              var form = layui.form;
              // layer.msg('玩命卖萌中', function(){
              //   //关闭后的操作
              //   });
              //监听提交
              form.on('submit(login)', function(data){
                // alert(888)
                layer.msg(JSON.stringify(data.field),function(){
                    location.href='login.php'
                });
                return false;
              });
            });
        })

        
    </script-->
    
    <!-- 底部结束 -->
    <script>
    //百度统计可去掉
    var _hmt = _hmt || [];
    (function() {
      var hm = document.createElement("script");
      hm.src = "https://hm.baidu.com/hm.js?b393d153aeb26b46e9431fabaf0f6190";
      var s = document.getElementsByTagName("script")[0]; 
      s.parentNode.insertBefore(hm, s);
    })();
    </script>
</body>
</html>