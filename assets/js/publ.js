function setSnapShotTimer(remainCount){
    var hours = Math.round(remainCount / 3600);
    var min = Math.round((remainCount % 3600) / 60);
    var sec = remainCount % 60;
    if(hours < 10){
    	hours = '0'+hours;
    }
    if(min < 10){
    	min = '0'+min;
    }
    if(sec < 10){
    	sec = '0'+sec;
    }
    $(".snapShotTimer.Hour").html(hours);
    $(".snapShotTimer.Min").html(min);
    $(".snapShotTimer.Sec").html(sec);
}

$(document).ready(function(){
	var remainTime = $("#remainTime").val();
	setSnapShotTimer(remainTime);
	setInterval(function(){
		remainTime--;
		if(remainTime < 0){
			remainTime = 86400;
		}
		setSnapShotTimer(remainTime);
	}, 1000);
})