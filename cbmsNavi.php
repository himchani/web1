<?php include_once 'languages/'.$_SESSION['langFile']; ?>

<script type="text/javascript">

function alarmOn()
{
	var broswerInfo = navigator.userAgent;
	if(broswerInfo.indexOf("BmsNetwork_Android")>-1)
	{
		window.HybridApp.callAndroid_setPush("<?php echo $_SESSION["accountSerial"]; ?>","<?php echo $laArr['if_on']; ?>");//디바이스 ID 등록
	}
	else
	{
		alert("only support on android");
	}	
}

function alarmOff()
{
	var broswerInfo = navigator.userAgent;
	if(broswerInfo.indexOf("BmsNetwork_Android")>-1)
	{
		window.HybridApp.callAndroid_removePush("<?php echo $_SESSION["accountSerial"]; ?>","<?php echo $laArr['if_off']; ?>");//디바이스 ID 해제
	}
	else{
		alert("only support on android");
	}
}

function setLanguage(code)
{
	var broswerInfo = navigator.userAgent;
	if(broswerInfo.indexOf("BmsNetwork_Android")>-1)
	{
		window.HybridApp.callAndroid_setLanguage(code);//언어 코드 변경
	}
	else{
		alert("only support on android");
	}
}

function focusSideMenu(e)
{
	var nodes = e.parentNode.parentNode.getElementsByTagName("a");
	
	for(var index = 0; index < nodes.length; index++){
		nodes[index].className = "";
	}
	
	e.className = "rectangleBorder";
}

function toggleMenu(e)
{
	// bottom navi (하단 메뉴 토글동작)
	var nodes = e.parentNode.parentNode.getElementsByTagName("div");
	
	for(var index = 0; index < nodes.length; index++){
		if(e.title != nodes[index].title){
			nodes[index].className = "";
			
		}		
	} 
	
	document.getElementById("bottomBtn0").src = "icon/home48.png";
	document.getElementById("bottomBtn1").src = "icon/time48.png";
	document.getElementById("bottomBtn2").src = "icon/calendar48.png";
	document.getElementById("bottomBtn3").src = "icon/timeLine48.png";
	document.getElementById("bottomBtn4").src = "icon/info48.png";
	
	
	if(e.title == "home"){
		document.getElementById("bottomBtn0").src = "icon/homeWhite48.png";
		e.className = "toggled";
		
		document.getElementById("topRefreshBtn").title = "home";
		document.getElementById("preArrow").onclick = "";
		document.getElementById("subTitle").onclick = "";
		document.getElementById("nextArrow").onclick = "";
		
		//subTitle
		document.getElementById("preArrow").childNodes[0].style.display = 'none';
		document.getElementById("nextArrow").childNodes[0].style.display = 'none';
		document.getElementById("subTitle").innerHTML = "<?php echo $laArr['cn_Subtitle1']; ?>";
		document.getElementById("caption").innerHTML = "<?php echo $laArr['ms_AllStatus']; ?>";
		
		lastClickIndex = 0;
		
		var date = new Date();
		var todayDate = pad(date.getFullYear(), 4)+"-"+pad(date.getMonth()+1, 2)+"-"+pad(date.getDate(), 2);
		
		getPage('homeFeedback.php?d='+todayDate);
	}
	else if(e.title == "history"){
		document.getElementById("bottomBtn1").src = "icon/timeWhite48.png";
		e.className = "toggled";
		
		document.getElementById("topRefreshBtn").title = "history";
		document.getElementById("preArrow").onclick = openCalendar;
		document.getElementById("subTitle").onclick = openTodayHistory;
		document.getElementById("nextArrow").onclick = openCalendar;
		
		//subTitle
		document.getElementById("preArrow").childNodes[0].style.display = '';
		document.getElementById("nextArrow").childNodes[0].style.display = '';
		document.getElementById("caption").innerHTML = "<?php echo $laArr['cn_Title2']; ?>";
        document.getElementById("subTitle").innerHTML = historyDate;
		
		lastClickIndex = 1;
		
		getPage('historyFeedback.php?d='+historyDate);//history는 전역 변수
	}
	else if(e.title == "period"){
		document.getElementById("bottomBtn2").src = "icon/calendarWhite48.png";
		e.className = "toggled";
		
		document.getElementById("topRefreshBtn").title = "period";
		document.getElementById("preArrow").onclick = openOption;
		document.getElementById("subTitle").onclick = "";
		document.getElementById("nextArrow").onclick = openOption;
		
		//subTitle
		document.getElementById("preArrow").childNodes[0].style.display = '';
		document.getElementById("nextArrow").childNodes[0].style.display = '';
		document.getElementById("subTitle").innerHTML = periodFromDate + "~" + periodToDate;
		document.getElementById("caption").innerHTML = "<?php echo $laArr['cn_Title3']; ?>";
		
		lastClickIndex = 2;
		
		changePeriod();//periodFromDate , periodToDate 전역 변수 
	}
	else if(e.title == "timeline")
	{
		document.getElementById("bottomBtn3").src = "icon/timeLineWhite48.png";
		e.className = "toggled";
		
		document.getElementById("topRefreshBtn").title = "TimeLine";
		document.getElementById("preArrow").onclick = openOption2;
		document.getElementById("subTitle").onclick = "";
		document.getElementById("nextArrow").onclick = openOption2;
		
		//subTitle
		document.getElementById("preArrow").childNodes[0].style.display = '';
		document.getElementById("nextArrow").childNodes[0].style.display = '';
		document.getElementById("subTitle").innerHTML = periodFromDate2 + "~" + periodToDate2;
		document.getElementById("caption").innerHTML = "<?php echo $laArr['cn_Title5']; ?>";

		lastClickIndex = 3;
		
		changePeriod2();//periodFromDate2 , periodToDate2 전역 변수 
	}
	else if(e.title == "info"){
		document.getElementById("bottomBtn4").src = "icon/infoWhite48.png";
		e.className = "toggled";
		
		document.getElementById("topRefreshBtn").title = "info";
		document.getElementById("preArrow").onclick = "";
		document.getElementById("subTitle").onclick = "";
		document.getElementById("nextArrow").onclick = "";
		
		//subTitle
		document.getElementById("preArrow").childNodes[0].style.display = 'none';
		document.getElementById("nextArrow").childNodes[0].style.display = 'none';
		document.getElementById("subTitle").innerHTML = "<?php echo $laArr['cn_Subtitle4']; ?>";
		document.getElementById("caption").innerHTML = "<?php echo $laArr['cn_Title4']; ?>";

		lastClickIndex = 4;
		
		getPage('infoFeedback.php');
	}
}

var request1;
function getPage(url){
	request1 = createRequest();
	if(request1 == null){
		alert("unable to create request");
	}
	else{
		//var args = escape("1234");
		request1.onreadystatechange = drawPage;
		request1.open("GET",url,true);
		request1.send(null);
		document.getElementById("ContentsBody").className = "backcolorGary";
	}
}

function drawPage(){
	if(request1.readyState == 4){
		if(request1.status == 200){
			document.getElementById("ContentsBody").innerHTML = request1.responseText;
			document.getElementById("ContentsBody").className = "";
			$('html,body').scrollTop(0);
			$('.scrollup').fadeOut();
			
			//관리자 계정 로그인시 검은 막이 home로드 전에 크기가 결정되기 때문에
			//body 크기가 바뀔 때마다 검은 막 크기를 재조정한다.
			//화면의 높이와 너비를 구한다.
		    var maskHeight = $(document).height();  
		    var maskWidth = $(window).width();  
		    
		    //마스크의 높이와 너비를 화면 것으로 만들어 전체 화면을 채운다.
		    $('#mask').css({'width':maskWidth,'height':maskHeight});  
		}
	}
	
}

function getPopPage(url){
	request1 = createRequest();
	if(request1 == null){
		alert("unable to create request");
	}
	else{
		//var args = escape("1234");
		request1.onreadystatechange = drawPopPage;
		request1.open("GET",url,true);
		request1.send(null);
		document.getElementById("ContentsBody").className = "backcolorGary";
	}
}

function drawPopPage(){
	if(request1.readyState == 4){
		if(request1.status == 200){
			document.getElementById("popContents").innerHTML = request1.responseText;
			document.getElementById("ContentsBody").className = "";
			wrapWindowByMask('#popwin1');
		}
	}
	
}

</script>
