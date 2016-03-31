window.addEventListener('touchmove', function () {
    event.preventDefault();
})

window.onload = init;


function loadStartConfig(){
	
}
function init(){
    var windowWidth;
    var windowHeight;
    var gameCanvas,context,buffer,bufferCtx;
    var loadIntervel,personIntervel,treeIntervel,fingerIntervel,startIntervel,secondIntervel;
    var meter = 0;
	var runmeter = 0;
    var time = 100;
    var flag = false;
	var fontarrnum = 0
	var fontarr = gameConfig.fontarr;

    var loadImg = new Image();
	var personImg = new Image();
	var personsImg = new Image();
	var treeImage = new Image
	var sky = new Image();
	var green = new Image();
	var road = new Image();
	var sun = new Image();
	var cloud1 = new Image();
	var cloud2 = new Image();
	var cloud3 = new Image();
	var fullbg = new Image();
	var ImgReady=11;
	var ImageAry=['personImg','personsImg','treeImage','green','road','sun','cloud1','cloud2','cloud3','fullbg'];
    windowWidth = document.documentElement.clientWidth;
    windowHeight = document.documentElement.clientHeight;


    GetObj('gameCanvas').width = windowWidth;
    GetObj('gameCanvas').height = windowHeight;

    gameCanvas = GetObj('gameCanvas');
    context = gameCanvas.getContext("2d");

    buffer = document.createElement("canvas");
    buffer.width = GetObj('gameCanvas').width;
    buffer.height = GetObj('gameCanvas').height;
    bufferCtx = buffer.getContext("2d");

    context.clearRect(0,0,GetObj('gameCanvas').width,GetObj('gameCanvas').height);
    bufferCtx.clearRect(0,0,buffer.width,buffer.height);

    var second = parseInt($('#second span').text());
    loadImg.addEventListener('load', eventLoaded, false);
    loadImg.src = gameConfig.img_loadImg ? gameConfig.img_loadImg:"./../addons/j_run/template/mobile/img/loading.png";
    personImg.src = gameConfig.img_personImg ? gameConfig.img_personImg: "./../addons/j_run/template/mobile/img/per_other.png";
    personsImg.src = gameConfig.img_personsImg ? gameConfig.img_personsImg: "./../addons/j_run/template/mobile/img/persons.png";
    treeImage.src = gameConfig.img_treeImage ? gameConfig.img_treeImage: "./../addons/j_run/template/mobile/img/tree.png";
	green.src = gameConfig.img_green ? gameConfig.img_green: "./../addons/j_run/template/mobile/img/green.png";
    road.src = gameConfig.img_road ?  gameConfig.img_road: "./../addons/j_run/template/mobile/img/black.png";
    sun.src = gameConfig.img_sun ?  gameConfig.img_sun: "./../addons/j_run/template/mobile/img/sun.png";
    cloud1.src = gameConfig.img_cloud1 ? gameConfig.img_cloud1:  "./../addons/j_run/template/mobile/img/cloud1.png";
    cloud2.src = gameConfig.img_cloud2 ?  gameConfig.img_cloud2: "./../addons/j_run/template/mobile/img/cloud2.png";
    cloud3.src = gameConfig.img_cloud3 ? gameConfig.img_cloud3:  "./../addons/j_run/template/mobile/img/cloud3.png";
	fullbg.src = gameConfig.img_fullbg ? gameConfig.img_fullbg:  "./../addons/j_run/template/mobile/img/bank.png";
	/*加载测试*/
	personImg.addEventListener('load', loadReady('personImg'), false);
	personsImg.addEventListener('load', loadReady('personsImg'), false);
	treeImage.addEventListener('load', loadReady('treeImage'), false);
	green.addEventListener('load', loadReady('green'), false);
	road.addEventListener('load', loadReady('road'), false);
	sun.addEventListener('load', loadReady('sun'), false);
	cloud1.addEventListener('load', loadReady('cloud1'), false);
	cloud2.addEventListener('load', loadReady('cloud2'), false);
	cloud3.addEventListener('load', loadReady('cloud3'), false);
	fullbg.addEventListener('load', loadReady('fullbg'), false); 
	fullbg.addEventListener('load', loadReady('fullbg'), false); 
	//
	if(gameConfig.is_showfullbg){
		treeImage.src=gameConfig.img_treeImage_road;
		treeImage.addEventListener('load', loadReady, false);
	}
	
	function loadReady(imagename){
		if(contains(ImageAry,imagename)>-1){
			ImageAry.splice(contains(ImageAry,imagename),1);
		}
		ImgReady--;
	}
    clearInterval(fingerIntervel);
    function eventLoaded() {
        drawLoad(0,0);
        var imagex = 66;
        loadIntervel = setInterval(function(){
            if(imagex > 660)imagex=66;
			if(ImgReady<1){
				setTimeout(function(){
                    clearInterval(loadIntervel);
                    context.clearRect(0,0,windowWidth,windowHeight);
                    context.fillStyle = "#c5f3ff";
                    context.fillRect(0, 0, gameCanvas.width, gameCanvas.height);
                    ready();
                },10);
			}
            drawLoad(imagex,'');
            imagex+=66;
        },50)
    }

    function personLoaded(){
        drawPerson();
    }

    var personx = 125;
	var persony = 275;
	var personx_increate=125;
    var treex = 290;
	var d = 0;
    function start(time){
        startIntervel = setInterval(function(){
            if(personx > 875){
                personx = 875;
                d++;
            }else if(personx == 0){
                personx = 0;
                d++;
            }
            if(treex > 3744){
                treex = 0;
            }
            draw(personx,treex);
            ((d+1)%2 ==0) ? personx-=personx_increate : personx+=personx_increate;
            treex+=288;
            meter+=gameConfig.speed;
            $('#score span').text(parseInt(meter)+'m');
        },time)
    }
    function drawLoad(imgX,others) {
        context.beginPath();
        context.fillStyle = "white";
        context.fillRect(0, 0, gameCanvas.width, gameCanvas.height);
        context.drawImage(loadImg,imgX,0,66,120,windowWidth/2-33,windowHeight/2-60,66,120);
        context.font="14px Arial";
        context.fillStyle="orange";
		var str="loading...";
		if(others)str+=others;
        context.fillText(str,windowWidth/2-33,windowHeight/2+74);
        context.stroke();
    }
	//背景
	function drawFullbg(){
        drawBuffer(fullbg,0,0,windowWidth,windowHeight,0,0,windowWidth,windowHeight);
        context.drawImage(buffer,0,0);
    }
	
    function drawPerson(){
        drawBuffer(personImg,0,0,366,520,windowWidth/2-90,windowHeight/2-60,180,260);
        context.drawImage(buffer,0,0);
    }
    function drawPersonTwo(){
        drawBuffer(personImg,366,0,366,520,windowWidth/2-90,windowHeight/2-120,180,260);
        context.drawImage(buffer,0,0);
    }

    function draw(personx,treex){
        context.clearRect(0,0,GetObj('gameCanvas').width,GetObj('gameCanvas').height);
        bufferCtx.clearRect(0,0,buffer.width,buffer.height);
        context.fillStyle = "#c5f3ff";
        context.fillRect(0, 0, gameCanvas.width, gameCanvas.height);
		drawsun();
        drawcloud();
        drawgreen();
		if(gameConfig.is_showfullbg)drawBuffer(fullbg,0,0,640,1200,0,0,windowWidth,windowHeight);
        drawroad();
        drawBuffer(treeImage,treex,0,288,315,windowWidth/2-145,windowHeight/2-157.5,288,315);
        drawBuffer(personsImg,personx,0,125,275,windowWidth/2-65,windowHeight/2-120,125,persony);
        context.drawImage(buffer,0,0);
    }
	function drawgreen(){
        drawBuffer(green,0,0,640,464,0,windowHeight/2-157.5+50,windowWidth,464);
        context.drawImage(buffer,0,0);
    }
    function drawroad(){
        drawBuffer(road,0,0,640,625,0,windowHeight/2-157.5+50,windowWidth,625);
        context.drawImage(buffer,0,0);
    }
    function drawsun(){
        drawBuffer(sun,0,0,231,93,windowWidth/2-(231/2)/2,windowHeight/2-135,231/2,93/2);
        context.drawImage(buffer,0,0);
    }
    function drawcloud(){
        drawBuffer(cloud1,0,0,254,120,windowWidth/2-(254/2)/2-254/2,windowHeight/2-200,254/2,120/2);
        drawBuffer(cloud2,0,0,163,59,windowWidth/2-(163/2)/2,windowHeight/2-200,163/2,59/2);
        drawBuffer(cloud3,0,0,228,107,windowWidth/2+(107/2)/2,windowHeight/2-200,228/2,107/2);
        context.drawImage(buffer,0,0);
    }
	
    function drawBuffer(img,sx,sy,swidth,sheight,moveWt,moveHt,w,h){
        bufferCtx.drawImage(img,sx,sy,swidth,sheight,moveWt,moveHt,w,h);
    }


    function ready(){
		$('#music')[0].play();
        $('#ready,#finger,.g-pace').show();
		drawsun();
        drawcloud();
        drawgreen();
		if(gameConfig.is_showfullbg)drawBuffer(fullbg,0,0,640,1200,0,0,windowWidth,windowHeight);
        drawroad();
        drawPerson();
        drawBuffer(treeImage,0,0,288,315,windowWidth/2-145,windowHeight/2-157.5,288,315);
        context.drawImage(buffer,0,0);
        var fingerNum = 0;
        fingerIntervel = setInterval(function(){
            $('#finger span').removeClass('on');
            $('#finger span').eq(fingerNum).addClass('on');
            if(fingerNum > 1){
                fingerNum = 0;
            }else{
                fingerNum++;
            }
        },100)

        $('#finger #start').bind('touchstart',function(){
            clearInterval(fingerIntervel);
            $('#finger span').removeClass('on');
            $('#ready').hide();
            $('#finger').remove();
            $('#second,#score').show();

            secondIntervel = setInterval(function(){
                $('#second span').text(second+'s');
                if(second <= 0){
                    clearInterval(startIntervel);
                    flag = false;
					clearInterval(secondIntervel);
					gameover();
                }else{
                    second--;
					$('#g-voiceover').text(fontarr[fontarrnum]);
					fontarrnum++;
                }
            },1000)
            start(time);
            $(this).remove();
            flag = true;
        })

        $('.g-pace a').bind('touchstart',function(){
            $('.g-pace a').removeClass('sel');
            $(this).addClass('sel');
            if(flag == true){
				if(gameConfig.modol)meter=meter+gameConfig.speedStep;
                clearInterval(startIntervel);
                if(time <= 30){
                    time = 30;
                }else{
                    time -= 5;
                }
                start(time);
                //console.log(time);
            }
        })

    }

    function gameover(){
        context.clearRect(0,0,GetObj('gameCanvas').width,GetObj('gameCanvas').height);
        bufferCtx.clearRect(0,0,buffer.width,buffer.height);
        context.fillStyle = "#c5f3ff";
        context.fillRect(0, 0, gameCanvas.width, gameCanvas.height);
		drawsun();
        drawcloud();
        drawgreen();
		if(gameConfig.is_showfullbg)drawBuffer(fullbg,0,0,640,1200,0,0,windowWidth,windowHeight);
        drawroad();
        drawPersonTwo();
        drawBuffer(treeImage,0,0,288,315,windowWidth/2-145,windowHeight/2-157.5,288,315);
        context.drawImage(buffer,0,0);
		//console.log(1111111);
		if(meter){
			var url =$("#runremurl").val();
			$.getJSON(url,{'meter':meter},function(data){
				if(data.success){
					if(gameConfig.isself){
						setTimeout(showInfo(),1500);
					}else{
						dp_submitScore(meter);
						setTimeout(showSelfInfo(),1500);
					}
				}else{
					alert(data.msg);
					window.location.href=reurl;	
				}
			});
		}
    }
}

function GetObj(id) {
    var tagArr = ['body','div','ul','ol','li','i','b','p','span','label'];
    for(var i = 0;i<tagArr.length;i++){
        if(id != tagArr[i]){
            var obj = document.getElementById(id);
            return obj;
        }else{
            var obj = document.getElementsByTagName(id)[0];
            return obj;
        }
    }
}

function contains(arr, obj) {  
    var i = arr.length;  
    while (i--) {  
        if (arr[i] === obj) {  
            return i;  
        }  
    }
    return -1;  
}