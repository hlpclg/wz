bi.ready(function(){
	var link = window.location.href;
	var time = new Date;
	var start = time.getTime();
	
	$('body').delegate('.page', 'out', function(e){
		var $outPage = $(e.target);

		var now = new Date;
		var end = now.getTime();
		var rt = end - start;
		var pageID = $outPage.attr('id').replace('page-', '');
		var prevPageIndex = $outPage.index() + 1;

        bi.push(4002, 'cuid' ,bi.util.getCUID());
        bi.push(4002, 'pvid' , bi.util.getPVID());
        bi.push(4002, 'visit_time', start);
        bi.push(4002, 'page_hold_time' ,rt);
        bi.push(4002, 'pnum', prevPageIndex);
        bi.push(4002, 'page_id', pageID);
        bi.post();
		start = end;
   });

	var cuid = bi.util.getCUID();
	var sf = '';

	var activity_id = link.match(/\/([0-9]+)/)[1];
	var sfstr = link.match(/\?sf=([^&]+)[&]?/);
	if(sfstr){
		sf = sfstr[1];
		var newlink = link.replace(/(\?)sf=[^&]+[&]?/,'$1');
	}else{
		var newlink = link;
	}
    var	linkArr = newlink.split('?');

    var shareLink = linkArr[0]+'?sf='+cuid+(linkArr[1] ? '&'+linkArr[1] : '');
    var biLink = linkArr[0]+'?cuid='+cuid+(linkArr[1] ? '&'+linkArr[1] : '');

	bi.weixin.share.all({
		link:shareLink
	});

	bi.weixin.ready(function(){
		bi.weixin.onShare(function (e) {
			bi.push(4003, 'link', biLink);
			bi.push(4003, 'app_id', bi.util.getAppID());
			if(sf){
				bi.push(4003, 'sf', sf);
			}
			bi.push(4003, 'type', 'shareTimeline');
			bi.push(4003, 'stime', bi.util.getSTime());
			bi.push(4003, 'cuid', bi.util.getCUID());
			bi.push(4003, 'channel', bi.util.getChannelId());
			bi.push(4003, 'enter', bi.util.getEnter());		
			bi.post();
		}, 'shareTimeline');

		bi.weixin.onShare(function (e) {
			bi.push(4003, 'link', biLink);
			bi.push(4003, 'app_id', bi.util.getAppID());
			if(sf){
				bi.push(4003, 'sf', sf);
			}
			bi.push(4003, 'type', 'sendAppMessage');
			bi.push(4003, 'stime', bi.util.getSTime());
			bi.push(4003, 'cuid', bi.util.getCUID());
			bi.push(4003, 'channel', bi.util.getChannelId());
			bi.push(4003, 'enter', bi.util.getEnter());	
			bi.post();
		}, 'sendAppMessage');
		
/*		bi.weixin.onShare(function (e) {
			//alert(3);
			bi.push(4003, 'link', biLink);
			bi.push(4003, 'app_id', bi.util.getAppID());
			if(sf){
				bi.push(4003, 'sf', sf);
			}
			bi.push(4003, 'type', 'sendAppMessage');
			bi.push(4003, 'stime', bi.util.getSTime());
			bi.push(4003, 'cuid', cuid);
			bi.push(4003, 'channel', util.getChannelId());
			bi.post();
		}, 'weibo');*/					
	});
});