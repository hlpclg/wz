
$(function(){
  //雪花飘
  var d="<div class='snow'>♥<div>"
  setInterval(function(){
    var f=$(document).width();
    var e=Math.random()*f-100;
    var o=0.3+Math.random();
    var fon=10+Math.random()*30;
    var l = e - 100 + 200 * Math.random();
    var k=2000 + 5000 * Math.random();
    $(d).clone().appendTo(".snowbg").css({
      left:e+"px",
      opacity:o,
      "font-size":fon,
    }).animate({
      top:"400px",
      left:l+"px",
      opacity:0.1,
    },k,"linear",function(){$(this).remove()})
  },200)
  //end-雪花飘
  
  //音乐按钮
  $(".musicicon_area").bind("click", function(){
    $(".musicicon").toggleClass("musicbg").toggleClass("nomusicbg").toggleClass("musicicon_animate");
    if(document.getElementById('car_audio').paused){
      document.getElementById('car_audio').play();
    }else{
      document.getElementById('car_audio').pause();
    }
  });
  //end-音乐按钮
})

