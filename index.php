<?php
//Written By hidacow
//HaoApps Music Backend Service

require_once 'backend.php';

# Initialize

$api = new QMusicAPI();


@$method = $_GET['method'];
@$timestamp = $_GET['ts'];
@$israw = $_GET['raw'];


switch (@$method){
	case "":	//获取更新配置
		//echo 'Music Backend Service';
		$frontfilepath = "welcome.html";	//文件路径
		$fronthandle = fopen($frontfilepath, "r");	//文件操作句柄
 		$frontcontents = fread($fronthandle, filesize ($frontfilepath));
		fclose($fronthandle);
		echo $frontcontents;
	break;

	case "GetSURL":		//获取歌曲链接（key,fn,[raw=0],[redir=0]） 
		@$accesskey = $_GET['key'];
		@$filen = $_GET['fn'];
		@$isredirect = $_GET['redir'];
		if ($api->checkkey($accesskey)=='1'){	//校验key
    		if(@$filen==''){
				echo 'wrong param';
				error_log("[GetSURL]".$_SERVER['REMOTE_ADDR']." empty fn");
				exit();
			}
			$result = $api->geturl($filen);
			if (@$result==''){
				#header("HTTP/1.1 503 Service Unvailable");
				echo 'failed to get url';
				error_log("[GetSURL]".$_SERVER['REMOTE_ADDR']." ERR no result fn=".$filen);
				exit();
			}
			if (@$israw=='1'){
				echo $result;
				error_log("[GetSURL]".$_SERVER['REMOTE_ADDR']." success fn=".$filen);
				exit();
			}
			$array = json_decode($result,true);
			@$suffix = $array['VKeyAuthCGIDownload']['data']['midurlinfo'][0]['purl'];
			if (@$suffix==''){
				#header("HTTP/1.1 503 Service Unvailable");
				echo 'failed to get url';
				error_log("[GetSURL]".$_SERVER['REMOTE_ADDR']." ERR no purl fn=".$filen);
				exit();
			}
			$resultURL = 'http://ws.stream.qqmusic.qq.com/'.$suffix;
			error_log("[GetSURL]".$_SERVER['REMOTE_ADDR']." success fn=".$filen);
			if (@$isredirect=='1'){
				header('location:'.$resultURL);
				exit();
			}
			echo $resultURL;
			
		} else {
			header("HTTP/1.1 403 Forbidden");
			echo 'wrong key';
			error_log("[GetSURL]".$_SERVER['REMOTE_ADDR']." wrong key=".@$accesskey);
		}
	break;




	case "Search":	//搜索(keyword,[page=1],[num=30])
		@$searchstring = $_GET['keyword'];
		@$searchpage = $_GET['page'];
		@$searchnum = $_GET['num'];
		if(@$searchstring==''){
			echo 'wrong param';
			error_log("[Search]".$_SERVER['REMOTE_ADDR']." empty keyword");
			exit();
		}
		if(@$searchpage==''){
			$searchpage = '1';
		}
		if(@$searchnum==''){
			$searchnum = '30';
		}
		$result = $api->search($searchstring,$searchpage,$searchnum);
		echo $result;
		error_log("[Search]".$_SERVER['REMOTE_ADDR']." success keyword=".$searchstring);
	break;
		


	case "GetLyric":	//获取歌词(songmid,[trans=1],[kana=0],[raw=0])
		@$songmid = $_GET['songmid'];
		@$withtrans = $_GET['trans'];
		@$withkana = $_GET['kana'];
		if(@$songmid==''){
			echo 'wrong param';
			error_log("[GetLyric]".$_SERVER['REMOTE_ADDR']." empty songmid");
			exit();
		}
		$result = $api->lyric($songmid);
		error_log("[GetLyric]".$_SERVER['REMOTE_ADDR']." success songmid=".$songmid);
		if (@$israw=='1'){
			if (@$withkana=='1'){
				echo $result;
				exit();
			}
			echo preg_replace('/\[kana:([^\[\]]+)\]/', '', $result);
			exit();
		}
		$array = json_decode($result,true);	
		if (@$withtrans=='0'){
			$outputlyric = $array['lyric'];
			if (@$withkana=='1'){
				echo $outputlyric;
				exit();
			}
			echo preg_replace('/\[kana:([^\[\]]+)\]/', '', $outputlyric);
			exit();
		}
		$outputlyric = $array['lyric'].$array['trans'];
		if (@$withkana=='1'){
			echo $outputlyric;
			exit();
		}
		echo preg_replace('/\[kana:([^\[\]]+)\]/', '', $outputlyric);
	break;


	case "GetSonglistById":	//获得歌单(id,[num=1000],[mode=1:自动获得歌单创建者uin（适用于隐私歌单解析）2:不获取（适用于公开歌单，提高数据获取速度，减轻服务器压力）])
		@$disstid = $_GET['id'];
		@$songnum = $_GET['num'];
		@$getmode = $_GET['mode'];
		if(@$disstid==''){
			echo 'wrong param';
			error_log("[GetSonglistById]".$_SERVER['REMOTE_ADDR']." empty disstid");
			exit();
		}
		if(@$songnum==''){
			$songnum = '1000';
		}
		if(@$getmode=='2'){
			$result = $api->songlist($disstid,$songnum,'0');
			echo $result;
			error_log("[GetSonglistById]".$_SERVER['REMOTE_ADDR']." success disstid=".$disstid);
		}
		$result1 = $api->GetSonglistCreatorUid($disstid);	
		$array = json_decode($result1,true);
		$uid = $array['diruin'];
		$result = $api->songlist($disstid,$songnum,$uid);
		echo $result;
		error_log("[GetSonglistById]".$_SERVER['REMOTE_ADDR']." success disstid=".$disstid);
	break;

	case "GetSonglistCreatorUid":	//获得歌单创建者uin和dirid(id)
		@$disstid = $_GET['id'];
		if(@$disstid==''){
			echo 'wrong param';
			error_log("[GetSonglistCreatorUid]".$_SERVER['REMOTE_ADDR']." empty disstid");
			exit();
		}
		$result = $api->GetSonglistCreatorUid($disstid);	
		echo $result;
		error_log("[GetSonglistCreatorUid]".$_SERVER['REMOTE_ADDR']." success disstid=".$disstid);
	break;



	case "GetSinger":	//获取歌手歌曲列表(singermid或singerid(当两个都传优先前者),[order=1:热门2:最新],[begin=0],[num=40])
		@$singermid = $_GET['singermid'];
		@$singerid = $_GET['singerid'];
		@$order = $_GET['order'];
		@$begin = $_GET['begin'];
		@$musicnum = $_GET['num'];
		if(@$order=='2'){
			$order = 'new';
		}else{
			$order = 'listen';
		}
		if(@$begin==''){
			$begin = '0';
		}
		if(@$musicnum==''){
			$musicnum = '40';
		}
		if(@$singermid==''){
			if (@$singerid=='') {
				echo 'wrong param';
				error_log("[GetSinger]".$_SERVER['REMOTE_ADDR']." empty mid&id");
				exit();
			}
			$result = $api->singerbyid($singerid,$order,$begin,$musicnum);
			echo $result;
			error_log("[GetSinger]".$_SERVER['REMOTE_ADDR']." success id=".$singerid);
			exit();
		}
		
		$result = $api->singerbymid($singermid,$order,$begin,$musicnum);
		echo $result;
		error_log("[GetSinger]".$_SERVER['REMOTE_ADDR']." success mid=".$singermid);
	break;


	case "GetAlbum":	//获取专辑歌曲列表(albummid或albumid(当两个都传优先前者))
		@$albummid = $_GET['albummid'];
		@$albumid = $_GET['albumid'];
		if(@$albummid==''){
			if (@$albumid=='') {
				echo 'wrong param';
				error_log("[GetAlbum]".$_SERVER['REMOTE_ADDR']." empty mid&id");
				exit();
			}
			$result = $api->albumbyid($albumid);
			echo $result;
			error_log("[GetAlbum]".$_SERVER['REMOTE_ADDR']." success id=".$albumid);
			exit();
		}
		
		$result = $api->albumbymid($albummid);
		echo $result;
		error_log("[GetAlbum]".$_SERVER['REMOTE_ADDR']." success mid=".$albummid);
	break;



	case "GetMVURL":	//获取MV播放地址(vid,[quality=3],[raw=0],[redir=0])
		@$vid = $_GET['vid'];
		@$quality = $_GET['quality'];
		@$isredirect = $_GET['redir'];
		if(@$vid==''){
			echo 'wrong param';
			error_log("[GetMVURL]".$_SERVER['REMOTE_ADDR']." empty vid");
			exit();
		}
		$result = $api->mv($vid);
		if (@$israw=='1'){
			echo $result;
			error_log("[GetMVURL]".$_SERVER['REMOTE_ADDR']." success vid=".$vid);
			exit();
		}
		$array = json_decode($result,true);
		if(@$quality==''){
			$quality = '3';
		}
		if (@$quality=='1'||@$quality=='2'||@$quality=='3'||@$quality=='4'||@$quality=='0') {
			@$mvurl = $array['getMvUrl']['data'][@$vid]['mp4'][$quality]['freeflow_url'][0];
			if (@$mvurl==''){
				#header("HTTP/1.1 503 Service Unvailable");
				echo 'failed to get url';
				#error_log("[GetMVURL]".$_SERVER['REMOTE_ADDR']." ERR no url fn=".$filen);
				exit();
			}
			error_log("[GetMVURL]".$_SERVER['REMOTE_ADDR']." success vid=".$vid);
			if (@$isredirect=='1'){
				header('location:'.$mvurl);
				exit();
			}
			echo $mvurl;
		}else {
			echo 'wrong quality';
			error_log("[GetMVURL]".$_SERVER['REMOTE_ADDR']." wrong quality");
			exit();
		}	
	break;



	case "GetSongDetail":	//获取单曲信息(songmid或songid(当两个都传优先前者))
		@$songmid = $_GET['songmid'];
		@$songid = $_GET['songid'];
		if(@$songmid==''){
			if (@$songid=='') {
				echo 'wrong param';
				error_log("[GetSongDetail]".$_SERVER['REMOTE_ADDR']." empty mid&id");
				exit();
			}
			$result = $api->detailbyid($songid);
			echo $result;
			error_log("[GetSongDetail]".$_SERVER['REMOTE_ADDR']." success id=".$songid);
			exit();
		}
		
		$result = $api->detailbymid($songmid);
		echo $result;
		error_log("[GetSongDetail]".$_SERVER['REMOTE_ADDR']." success mid=".$songmid);
	break;



	case "GetDigitalAlbumConfig":	//获取数字专辑配置(albummid或albumid(当两个都传优先前者),[raw=0])
		@$albummid = $_GET['albummid'];
		@$albumid = $_GET['albumid'];
		if(@$albummid==''){
			if (@$albumid=='') {
				echo 'wrong param';
				error_log("[GetDigitalAlbumConfig]".$_SERVER['REMOTE_ADDR']." empty mid&id");
				exit();
			}
			$result = $api->digitalalbumconfigbyid($albumid);
		}else{
			$result = $api->digitalalbumconfigbymid($albummid);
		}
		if (@$israw=='1'){
			echo $result;
			error_log("[GetDigitalAlbumConfig]".$_SERVER['REMOTE_ADDR']." success mid=".$albummid.',id='.$albumid);
			exit();
		}
		$array = json_decode($result,true);
		$result = $array['loadConfig']['data']['config'];
		$result = str_replace("\n","",$result);
		$result = str_replace("(function(){  var config = ","",$result);
		$result = str_replace(";  if(window && window.document){  window.GLOBAL_CONFIG = config;  }else{  return (module.exports =exports = config)  }})();","",$result);
		if ($result=='') {
			echo 'Err getting config. Is it a digital album on sale?';
			error_log("[GetDigitalAlbumConfig]".$_SERVER['REMOTE_ADDR']." success mid=".$albummid.',id='.$albumid);
		}else {
			echo $result;
			error_log("[GetDigitalAlbumConfig]".$_SERVER['REMOTE_ADDR']." success mid=".$albummid.',id='.$albumid);
		}
	break;



	case "GetToplistIndex":	//获取排行榜索引(无需参数)
		$result = $api->toplistindex();
		echo $result;
		error_log("[GetToplistIndex]".$_SERVER['REMOTE_ADDR']." success ");
	break;



	case "GetToplist":	//获取排行榜歌曲列表(updatekey,topid,type=global:全球榜1:QQ音乐巅峰榜,[begin=0],[num=100])
		@$topid = $_GET['topid'];
		@$updatekey = $_GET['updatekey'];
		@$type = $_GET['type'];
		@$begin = $_GET['begin'];
		@$musicnum = $_GET['num'];
		if(@$begin==''){
			$begin = '0';
		}
		if(@$musicnum==''){
			$musicnum = '100';
		}
		if(@$topid==''){
			echo 'wrong param';
			error_log("[GetToplist]".$_SERVER['REMOTE_ADDR']." empty topid");
			exit();
		}
		if(@$updatekey==''){
			echo 'wrong param';
			error_log("[GetToplist]".$_SERVER['REMOTE_ADDR']." empty updatekey");
			exit();
		}
		$result = $api->toplist($updatekey,$topid,$type,$begin,$musicnum);
		echo $result;
		error_log("[GetToplist]".$_SERVER['REMOTE_ADDR']." success top=".$topid.',updatekey='.$updatekey);
	break;



	default:	//没有传或者错误的method默认返回
		//header("HTTP/1.1 403 Forbidden");
		echo 'wrong method';
}




