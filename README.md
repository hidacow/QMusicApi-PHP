# QMusicAPI PHP Version
QMusicAPI PHP Version

Author: hidacow

Inspired by [metowolf/TencentMusicApi](https://github.com/metowolf/TencentMusicApi)

## Introduction
A simple API Framework unlocking exclusive power of Tencent Music
> **Note:** This project is intended for studying PHP and API Framework

### Function
 - [x] 搜索单曲
 - [x] 歌手解析
 - [x] 歌曲详细信息
 - [x] 专辑解析
 - [x] 歌单解析
 - [x] 歌词解析
 - [x] MV 解析
 - [x] 排行榜解析
 - [x] 数字专辑配置解析
 - [ ] 歌曲地址获取（非公开）

## Requirement
PHP 5.4+ and Curl, OpenSSL extension installed.

## Demo Site
`http://wxw73i2814.hk11.horainwebs.top/`

## Donate
You can open the QQ/Wechat/Alipay app and scan the QR code in [This Webpage](https://ivip.tech/jz.html)

## API Docs
### 获取音乐文件链接

接口：`?method=GetSURL`

参数：

`key`: 必填，访问秘钥，防止接口滥用，算法可自行修改，当前为静态秘钥 `QM`

`fn`: 必填，请求音乐文件名（由音质标识头+Fileid+后缀名组成），示例：`fn=F000001glaI72k8BQX.flac`

`redir`: 可选，若值为`1`则自动跳转，默认`0`

> **支持正版音乐，不提供本段代码**

示例：[?method=GetSURL&key=QM&fn=F000001glaI72k8BQX.flac](http://wxw73i2814.hk11.horainwebs.top/?method=GetSURL&key=QM&fn=F000001glaI72k8BQX.flac)


### 搜索

接口：`?method=Search`

参数：

`keyword`: 必填，搜索关键词

`page`: 可选，页码，默认`1`

`num`: 可选，一页返回项目数量，默认`30`


示例：`?method=Search&key=周杰伦`

### 获取歌词

接口：`?method=GetLyric`

参数：

`songmid`: 必填，歌曲的songmid

`trans`: 可选，若值为`1`则返回包含翻译的歌词，默认`1`

`kana`: 可选，若值为`0`则返回自动去除部分日文歌词中的lrc`[kana:]`头，默认`0`

`raw`: 可选，若值为`1`则返回QQ音乐API原版JSON格式，默认`0`

示例：[?method=GetLyric&songmid=001glaI72k8BQX&raw=1](http://wxw73i2814.hk11.horainwebs.top/?method=GetLyric&songmid=001glaI72k8BQX&raw=1)



### 获取歌单

接口: `?method=GetSonglistById`

参数：

`id`: 必填，歌单的disstid

`num`: 可选，返回项目的最大数量，默认`1000`

`mode`: 可选，默认`1`

            若值不为`2`则自动获得歌单创建者uin（适用于隐私歌单解析）

            若值为`2`则不获取（适用于公开歌单，提高数据获取速度，减轻服务器压力）

示例：[?method=GetSonglistById&id=7001048679](http://wxw73i2814.hk11.horainwebs.top/?method=GetSonglistById&id=7001048679)


### 获取歌单创建者的uid

接口: `?method=GetSonglistCreatorUid`

参数：

无

### 获取歌手歌曲列表

接口: `?method=GetSinger`

参数：

`singermid`: 与`singerid`二者选一必填，歌手的mid（14位字符串）

`singerid`: 与`singermid`二者选一必填，歌手的id（纯数字）

`order`: 可选，默认`1`

            若值不为`2`则获取歌手最热门的单曲

            若值为`2`则获取歌手最新发布的单曲

`begin`: 可选，从第n+1首歌曲开始返回，默认`0`

`num`: 可选，返回项目的最大数量，默认`40`

 > 若`singermid`与`singerid`参数同时传入则`singerid`参数失效

示例：[?method=GetSinger&singermid=0025NhlN2yWrP4](http://wxw73i2814.hk11.horainwebs.top/?method=GetSinger&singermid=0025NhlN2yWrP4)

### 获取专辑歌曲列表

接口: `?method=GetAlbum`

参数：

`albummid`: 与`albumid`二者选一必填，专辑的mid（14位字符串）

`albumid`: 与`albummid`二者选一必填，专辑的id（纯数字）

 > 若`albummid`与`albumid`参数同时传入则`albumid`参数失效

该接口将一次性返回所有专辑内的歌曲

示例：[?method=GetAlbum&albummid=0009C3rp3Kfwg0](http://wxw73i2814.hk11.horainwebs.top/?method=GetAlbum&albummid=0009C3rp3Kfwg0)

### 获取MV/视频播放地址

接口: `?method=GetMVURL`

参数：

`vid`: 必填，视频的vid

`quality`: 可选，视频品质，只能为`0`（未知）,`1`（标清）,`2`（高清）,`3`（超清/用户上传）,`4`（蓝光），默认`3`

`raw`: 可选，若值为`1`则`quality`和`redir`参数失效，返回QQ音乐API原版JSON格式（包含所有视频品质链接），默认`0`

`redir`: 可选，若值为`1`则自动跳转，默认`0`

示例：[?method=GetMVURL&vid=q0034sb9eru&quality=4](http://wxw73i2814.hk11.horainwebs.top/?method=GetMVURL&vid=q0034sb9eru&quality=4)

### 获取单曲信息

接口: `?method=GetSongDetail`

参数：

`songmid`: 与`songid`二者选一必填，歌曲的mid（14位字符串）

`songid`: 与`songmid`二者选一必填，歌曲的id（纯数字）

 > 若`songmid`与`songid`参数同时传入则`songid`参数失效

示例：[?method=GetSongDetail&songmid=001glaI72k8BQX](http://wxw73i2814.hk11.horainwebs.top/?method=GetSongDetail&songmid=001glaI72k8BQX)

### 获取数字专辑信息/配置

接口: `?method=GetDigitalAlbumConfig`

参数：

`albummid`: 与`albumid`二者选一必填，专辑的mid（14位字符串）

`albumid`: 与`albummid`二者选一必填，专辑的id（纯数字）

`raw`: 可选，若值为`1`则返回原接口所有信息

 > 若`albummid`与`albumid`参数同时传入则`albumid`参数失效

若传入非数字专辑的mid或id则会返回`Err getting config. Is it a digital album on sale?`

示例：[?method=GetDigitalAlbumConfig&albummid=0009C3rp3Kfwg0](http://wxw73i2814.hk11.horainwebs.top/?method=GetDigitalAlbumConfig&albummid=0009C3rp3Kfwg0)

### 获取排行榜索引

接口: `?method=GetToplistIndex`

参数：

无

### 获取排行榜歌曲列表

接口: `?method=GetToplist`

参数：

`updatekey`: 必填，用来获取xxx期的排行榜数据

`topid`: 必填，排行榜的id

`type`: 必选，用于配合topid获取排行榜数据（`global`:全球榜,`top`:QQ音乐巅峰榜）

`begin`: 可选，从第n+1首歌曲开始返回，默认`0`

`num`: 可选，返回项目的最大数量，默认`100`

示例：[?method=GetToplist&topid=129&updatekey=2020_1&type=global](http://wxw73i2814.hk11.horainwebs.top/?method=GetToplist&topid=129&updatekey=2020_1&type=global)
