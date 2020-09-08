<?php
/*
Backend.php By @hidacow
Inspired by metowolf/TencentMusicApi
*/

 /*!
 * Tencent(QQ) Music Api
 * https://i-meto.com
 * Version 20161203
 * Copyright 2016, METO
 * Released under the MIT license
 */


class QMusicAPI{
    // General
    
    protected $_COOKIE='ts_uid=7211541136; yq_index=0; yq_playschange=0; yq_playdata=; player_exist=1; yqq_stat=0; yplayer_open=1; qqmusic_fromtag=30; pgv_pvid=2051442720; pgv_info=ssid=s306829075; ts_last=y.qq.com/portal/player.html; ts_uid=4084761720';
    protected $_REFERER='https://y.qq.com';
    // CURL
    function curlget($url){
        $headerArray =array("Content-type:application/json;","Accept:application/json","Cookie:ts_uid=7211541136; yq_index=0; yq_playschange=0; yq_playdata=; player_exist=1; yqq_stat=0; yplayer_open=1; qqmusic_fromtag=30; pgv_pvid=2051442720; pgv_info=ssid=s306829075; ts_last=y.qq.com/portal/player.html; ts_uid=4084761720","Referer: y.qq.com");
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE); 
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE); 
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch,CURLOPT_HTTPHEADER,$headerArray);
        $output = curl_exec($ch);
        curl_close($ch);
        //$output = json_decode($output,true);
        return $output;
    }

    function curlpost($url,$data){
        $data  = json_encode($data);    
        $headerArray =array("Content-type:application/json;charset='utf-8'","Accept:application/json");
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, FALSE);
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST,FALSE);
        curl_setopt($curl, CURLOPT_POST, 1);
        curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
        curl_setopt($curl,CURLOPT_HTTPHEADER,$headerArray);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        $output = curl_exec($curl);
        curl_close($curl);
        //return json_decode($output，true);
        return $output;
    }
    
    
    
    public function checkkey($accesskey){
        #accesskey validation module
        if ($accesskey=='QM'){
            return '1';
        } else {
            return '0';
        }
    }

    public function geturl($filename){
        return 'Write your code or support genuine music';
    }
    public function search($searchword,$page,$searchnum){
        $url='https://c.y.qq.com/soso/fcgi-bin/client_search_cp?';
        $data=array(
            'p'=>$page,
            'n'=>$searchnum,
            'w'=>$searchword,
        );
        return substr($this->curlget($url.http_build_query($data)),9,-1);  //去掉默认callback()
    }
    public function lyric($songmid){
        $url='https://c.y.qq.com/lyric/fcgi-bin/fcg_query_lyric_new.fcg?nobase64=1&songmid='.$songmid.'&g_tk=5381';
        return substr($this->curlget($url),18,-1); //去掉MusicJsonCallback()

    }

    public function songlist($playlist_id,$musicnum,$uid){
        $url='https://c.y.qq.com/qzone/fcg-bin/fcg_ucc_getcdinfo_byids_cp.fcg?type=1&json=1&utf8=1&onlysong=0&song_begin=0&song_num='.$musicnum.'&disstid='.$playlist_id.'&loginUin='.$uid.'&hostUin=0&format=json&inCharset=utf8&outCharset=utf-8&notice=0&platform=yqq.json&needNewCode=0';
        
        return $this->curlget($url);    //去除空格
    }

    public function GetSonglistCreatorUid($playlist_id){
        $url='https://c.y.qq.com/3gmusic/fcgi-bin/3g_dir_order_uinlist?loginUin=0&hostUin=0&format=json&inCharset=utf8&outCharset=utf-8&notice=0&platform=yqq.json&needNewCode=0&cid=322&nocompress=1&disstid='.$playlist_id;
        
        return $this->curlget($url);
    }

    public function singerbymid($singermid,$order,$begin,$musicnum){
        $url='https://c.y.qq.com/v8/fcg-bin/fcg_v8_singer_track_cp.fcg?g_tk=5381&jsonpCallback=&loginUin=0&hostUin=0&format=jsonp&inCharset=utf8&outCharset=utf-8&notice=0&platform=yqq&needNewCode=0&singermid='.$singermid.'&order='.$order.'&begin='.$begin.'&num='.$musicnum.'&songstatus=1';
        return substr($this->curlget($url),1);    //去除空格
    }

    public function singerbyid($singerid,$order,$begin,$musicnum){
        $url='https://c.y.qq.com/v8/fcg-bin/fcg_v8_singer_track_cp.fcg?g_tk=5381&jsonpCallback=&loginUin=0&hostUin=0&format=jsonp&inCharset=utf8&outCharset=utf-8&notice=0&platform=yqq&needNewCode=0&singerid='.$singerid.'&order='.$order.'&begin='.$begin.'&num='.$musicnum.'&songstatus=1';
        return substr($this->curlget($url),1);    //去除空格
    }

    public function albumbymid($album_mid){
        $url='https://c.y.qq.com/v8/fcg-bin/fcg_v8_album_info_cp.fcg?albummid='.$album_mid.'&g_tk=1150451887&jsonpCallback=&loginUin=66600000&hostUin=0&format=jsonp&inCharset=utf8&outCharset=utf-8&notice=0&platform=yqq&needNewCode=0';
        return substr($this->curlget($url),1);    //去除空格
    }

    public function albumbyid($album_id){
        $url='https://c.y.qq.com/v8/fcg-bin/fcg_v8_album_info_cp.fcg?albumid='.$album_id.'&g_tk=1150451887&jsonpCallback=&loginUin=66600000&hostUin=0&format=jsonp&inCharset=utf8&outCharset=utf-8&notice=0&platform=yqq&needNewCode=0';
        return substr($this->curlget($url),1);    //去除空格
    }

    public function mv($vid){
        $url='https://u.y.qq.com/cgi-bin/musicu.fcg?data={%22getMvUrl%22:{%22module%22:%22gosrf.Stream.MvUrlProxy%22,%22method%22:%22GetMvUrls%22,%22param%22:{%22vids%22:[%22'.$vid.'%22],%22request_typet%22:10001}}}&g_tk=181465879&callback=&loginUin=66600000&hostUin=0&format=jsonp&inCharset=utf8&outCharset=GB2312&notice=0&platform=yqq&needNewCode=0';
        return $this->curlget($url);
    }

    public function detailbymid($song_mid){
        $url='http://c.y.qq.com/v8/fcg-bin/fcg_play_single_song.fcg?songmid='.$song_mid.'&tpl=yqq_song_detail&format=json&g_tk=1885845528';
        return $this->curlget($url);
    }

    public function detailbyid($song_id){
        $url='http://c.y.qq.com/v8/fcg-bin/fcg_play_single_song.fcg?songid='.$song_id.'&tpl=yqq_song_detail&format=json&g_tk=1885845528';
        return $this->curlget($url);
    }

    public function digitalalbumconfigbymid($albummid){
        $url='https://u.y.qq.com/cgi-bin/musicu.fcg?data=%7B%22loadConfig%22%3A%7B%22module%22%3A%22mall.MusicMallSvr%22%2C%22method%22%3A%22AlbumLoadConfig%22%2C%22param%22%3A%7B%22albummid%22%3A%22'.$albummid.'%22%2C%22albumid%22%3A0%2C%22state%22%3A%22reg%22%7D%7D%7D';
        return $this->curlget($url);
    }

    public function digitalalbumconfigbyid($albumid){
        $url='https://u.y.qq.com/cgi-bin/musicu.fcg?data=%7B%22loadConfig%22%3A%7B%22module%22%3A%22mall.MusicMallSvr%22%2C%22method%22%3A%22AlbumLoadConfig%22%2C%22param%22%3A%7B%22albummid%22%3A%22%22%2C%22albumid%22%3A'.$albumid.'%2C%22state%22%3A%22reg%22%7D%7D%7D';
        return $this->curlget($url);
    }

    public function toplistindex(){
        $url='https://c.y.qq.com/v8/fcg-bin/fcg_v8_toplist_opt.fcg?page=index&format=html&tpl=macv4&v8debug=1';
        return substr($this->curlget($url),14,-1);    //去除jsoncallback()
    }

    public function toplist($updatekey,$topid,$type,$begin,$num){
        $url='https://c.y.qq.com/v8/fcg-bin/fcg_v8_toplist_cp.fcg?tpl=3&page=detail&date='.$updatekey.'&topid='.$topid.'&type='.$type.'&song_begin='.$begin.'&song_num='.$num.'&g_tk=181465879&jsonpCallback=&loginUin=0&hostUin=0&format=jsonp&inCharset=utf8&outCharset=utf-8&notice=0&platform=yqq&needNewCode=0';
        return $this->curlget($url);
    }
}