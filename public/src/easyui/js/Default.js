$(function () {
    InitLeftMenu();
    $('body').layout();
})

function InitLeftMenu() {
    $('.easyui-accordion li a').click(function () {
        var tabTitle = $(this).text();
        var url = $(this).attr("href");
		if(cookie('PHPSESSID')){
			addTab(tabTitle, url);
		}else{
			window.top.location.reload(true);
		}
        $('.easyui-accordion li').removeClass("selected");
        $(this).parent().addClass("selected");
    }).hover(function () {
        $(this).parent().addClass("hover");
    }, function () {
        $(this).parent().removeClass("hover");
    });
}

function addTab(subtitle, url) {
    

    if (!$('#tabs').tabs('exists', subtitle)) {
			$(".tabs").html("");	
			$('#tabs').tabs('add', {
				title: subtitle,
				content: createFrame(url),
				closable: true,
				width: $('#mainPanle').width() - 10,
				height: $('#mainPanle').height() - 26
			});
    } else {
        $('#tabs').tabs('select', subtitle);
    }

	
}

function cookie(name){   

   var cookieArray=document.cookie.split("; "); //得到分割的cookie名值对   

   var cookie=new Object();   

   for (var i=0;i<cookieArray.length;i++){   

      var arr=cookieArray[i].split("=");       //将名和值分开   

      if(arr[0]==name)return unescape(arr[1]); //如果是指定的cookie，则返回它的值   

   }

   return "";

}


function createFrame(url) {
    var s = '<iframe name="mainFrame"  frameborder="0"  src="' + url + '" style="width:100%;height:99.5%;"></iframe>';
    return s;
}