        <link href="http://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400" rel="stylesheet">
		<script src="hcd/astral/js/jquery-1.9.1.min.js"></script>
		<script src="hcd/astral/css/5grid/init.js?use=mobile,desktop"></script><link rel="stylesheet" href="hcd/astral/css/5grid/core.css"><link rel="stylesheet" href="hcd/astral/css/style.css">
		<!--[if lte IE 8]><link rel="stylesheet" href="hcd/astral/css/ie8.css" /><![endif]-->
	</head>
	<body style="font-size: 15pt;background:<?php
	if(!$_GET['icon']){echo 'url(hcd/img/B304B.png)';}else{echo 'none';}
	?>;" >

		<!-- Wrapper-->
			<div id="wrapper" style="padding-top: 61.5px; opacity: 1;" class="">
				
				<!-- Nav -->
				
					<nav id="nav">
					<?php
					function sfaas($cc){
					if($_GET['icon']==$cc){
					return 'active';
					}
					}
					include_once('api_list.php');
					$icons=array('album','book','comment','file','folder','info','mail','notify','right','share','tilemenu','user','dribbble','facebook','google','linkedin','tumblr','twitter');
					for($i=0;$i<count($api_list);$i++){
					$ssr=$api_list[$i];
					while(true){
					$iicc=rand(0,count($icons)-1);
					$iccons=$icons[$iicc];
					if($iccons){break 1;}
					}
					unset($icons[array_search($iccons,$icons)]);
					echo '<a href="javascript:void();" onclick="window.open(\'index.php?corn='.$ssr['corn'].'\',\''.($_GET['icon']?'_parent':'_self').'\');" class="icon icon-'.$iccons.' '.sfaas($ssr['corn']).'"><span>'.$ssr['name'].'</span></a>';
					}
					?>
				<?php if($_GET['icon']){echo '<!--';}?>	
		<div align="center" style="background:rgba(20, 16, 16, 0.67);height:500px"><img style="margin-top:6%" height="80%" src="<?php 
		$picc[count($picc)]='http://www.pp3.cn/uploads/20121001j/bz/210.jpg';
		$picc[count($picc)]='http://img.mylove1314.net/mmdata/mm_net/2012_04_03/1333459498361.jpg';
		$picc[count($picc)]='http://img.bzdao.com/2290/3316550.jpg';
		$picc[count($picc)]='http://s1.dwstatic.com/group1/M00/15/F4/eec535b32481a2202693820cc23fecd2.jpg';
		$picc[count($picc)]='http://img1.anyouhui.com/0x0/201302/468/anyouhui46751350e31e4e3f47e1280x960.jpg';
		echo $picc[rand(0,count($picc)-1)];?>"/></div>	
		<?php if($_GET['icon']){echo '-->';}?>	
					</nav>

					
		
			</div>

	
</body></html>

