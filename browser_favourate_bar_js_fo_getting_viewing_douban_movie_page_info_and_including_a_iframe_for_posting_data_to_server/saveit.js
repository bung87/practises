/**
 * User: peng
 * Date: 13-3-21
 * Time: 上午11:44
 */
javascript: (function () {
    var w = window,
        d = document,
        info = [];
        if(w.getit) return false;
    // if (!w.tentacle) {
    //     w.tentacle = 1;
    //     s = d.createElement('script');
    //     s.src = 'http://www.jiathis.com/code/j.js';
    //     d.getElementsByTagName('head')[0].appendChild(s);
    //     s = null
    // } else {
    //     $CKE.center()
    // }
    var data = {};
    var mname = $('span[property="v:itemreviewed"]').text();
    var runtime = $('span[property="v:runtime"]').attr('content');
    var year = $('.year').text().match(/\d+/);
    if($('span.all.hidden').get(0)){var summary = $('span.all.hidden').get(0).firstChild.nodeValue;}else{
        var summary=$('span[property="v:summary"]').text()
    }
    
    var initialreleasedate=$('span[property="v:initialReleaseDate"]').attr('content');
    if (year) year = year[0];
          data.mname = mname;
    data.year = year;
    
    data.initialreleasedate=initialreleasedate;
    info = $('#info').text().split('\n');
    var infoarray = [];
    for (var i = 0; i < info.length; i++) {
        if (info[i]) {
            var record = info[i].split(':');
            var recordName = record[0].replace(/(^\s*)|(\s*$)/g, "");
            var recordValue = record[1];
            switch (recordName) {
                case "导演":
                    data.director = recordValue;
                    break;
                case "编剧":
                    data.writer = recordValue;
                    break;
                case "主演":
                    data.starring = recordValue;
                    break;
                case "类型":
                    data.genre = recordValue;
                    break;
           
                case "制片国家/地区":
                    data.area = recordValue;
                    break;
                case "语言":
                    data.language = recordValue;
                    break;
                // case "首播日期":
                //     data.initialreleasedate = recordValue;
                //     break;
                // case "集数"
                //     data.writer=recordValue;
                //   break;
                // case "单集片长"
                //     data.writer=recordValue;
                //   break;
                // case "片长":
                // data.runtime=;
                case "又名":
                    data.alias = recordValue;
                    break;
                case "IMDb链接":
                    data.imdblink = $('a[href^="http://www.imdb.com/title/"]').attr('href');
                    break;
                default:

                break;

            }
            infoarray.push(info[i]);
        }

    }

    var tags = [];
    $('.tags-body a').each(function () {
        tags.push($(this).get(0).firstChild.nodeValue);
    });
    tagsstr = tags.join('/');
    score = $('strong[property="v:average"]').text();
    doubanid = document.location.href.match(/\d+/g);
    if (doubanid) doubanid = doubanid[0];

    
    data.runtime = runtime;
    data.tags = tagsstr;
    data.score = score;
    data.doubanid = doubanid;
    // var $main = $('<div style="display:block;width:500px;position:fixed;left:20%;top:0;background: rgba(0,0,0,0.8);border-radius: 5px;font-size: 14px;color: #fff;padding: 10px;"></div>');
    // var $ele;
    // for (i in data) {
    //     $ele = $('<p><label>' + i + '<input type="text" name="' + i + '" value="' + data[i] + '"></label></p>');
    // $main.append($ele);
    // }
    var url="http://www.xxx.com/showDataFromDouban.php?";
    pra=[];
    for (i in data){
        pra.push(i+'='+encodeURIComponent(data[i]));
    }
    url+=pra.join('&');
    var $main=$('<div style="display:block;width:500px;position:fixed;left:20%;top:0;z-index:9999;background: #fff;border-radius: 5px;font-size: 14px;color: #fff;padding: 10px;"><iframe src="'+url+'" width="500" height="400"></iframe></div>');
    $(document.body).append($main);
    w.getit=true;
})();