$(document).ready(function() {
	
	$("#accordion").accordion({alwaysOpen : false, header: "h3"});
	
	$(".gallery li").addClass("galleryThumbnails");
	$(".gallery li img").attr("width", 52);
	$(".gallery li img").click(function() {
		$(".gallery li img").removeClass("gallerySelected");
		var source = $(this).attr("src");
		var text = $(this).attr("alt");
		$(".mainText").fadeOut("slow");
		$(".mainImg img").fadeOut("slow", function() {
			$(".mainText").html(text);
			$(".mainImg img").attr("src", source);
		});
		$(".mainText").fadeIn("slow");
		$(".mainImg img").fadeIn("slow");
		$(this).addClass("gallerySelected");
	});
	
	
	$('#date').datepicker({dateFormat : 'yy/mm/dd'});
	
	
	$('html').keydown(function(e) {
		if (e.ctrlKey) {
			$('html').keydown(function(ee) {
				if (ee.altKey) {
					$('html').keydown(function(eee) {
						if (eee.keyCode == 76) {
							window.location = 'http://www.aviyacrest.com/admin';
						}
					});
				}
			});
		}
	});
	
	
	// LEFT = timeouts and intervals
	setTimeout('setNoteLeftSix()', 500);
	setTimeout('setNoteLeftFive()', 1000);
	setTimeout('setNoteLeftFour()', 1500);
	setTimeout('setNoteLeftThree()', 2000);
	setTimeout('setNoteLeftTwo()', 2500);
	setTimeout('setNoteLeftOne()', 3000);

	setInterval("setNoteLeftOne()", 9500);
	setInterval("setNoteLeftTwo()", 9600);
	setInterval("setNoteLeftThree()", 9700);
	setInterval("setNoteLeftFour()", 9800);
	setInterval("setNoteLeftFive()", 9900);
	setInterval("setNoteLeftSix()", 10000);
	
	// RIGHT - timeouts and intervals
	setTimeout('setNoteRightSix()', 500);
	setTimeout('setNoteRightFive()', 1000);
	setTimeout('setNoteRightFour()', 1500);
	setTimeout('setNoteRightThree()', 2000);
	setTimeout('setNoteRightTwo()', 2500);
	setTimeout('setNoteRightOne()', 3000);
	
	setInterval("setNoteRightOne()", 9500);
	setInterval("setNoteRightTwo()", 9600);
	setInterval("setNoteRightThree()", 9700);
	setInterval("setNoteRightFour()", 9800);
	setInterval("setNoteRightFive()", 9900);
	setInterval("setNoteRightSix()", 10000);
	
});


function setNoteLeftOne()
{
	var $viewport = getViewport();
	var $scroll = getScroll();
	var $randomTop = 0.05 + (Math.random() / 10);
	var $newTop = Math.ceil(($viewport[1] + $scroll[1]) * $randomTop) + 'px';
	var $newLeft = Math.floor(Math.random()*Math.ceil(($viewport[0] - 800) / 4)) + 20 + 'px';
	$('#noteLeftOne').stop().animate({top : $newTop, left : $newLeft}, 9000, 'easeInOutBack');
}

function setNoteLeftTwo()
{
	var $viewport = getViewport();
	var $scroll = getScroll();
	var $randomTop = 0.2 + (Math.random() / 10);
	var $newTop = Math.ceil(($viewport[1] + $scroll[1]) * $randomTop) + 'px';
	var $newLeft = Math.floor(Math.random()*Math.ceil(($viewport[0] - 800) / 4)) + 20 + 'px';
	$('#noteLeftTwo').stop().animate({top : $newTop, left : $newLeft}, 9000, 'easeInOutBack');
}

function setNoteLeftThree()
{
	var $viewport = getViewport();
	var $scroll = getScroll();
	var $randomTop = 0.35 + (Math.random() / 10);
	var $newTop = Math.ceil(($viewport[1] + $scroll[1]) * $randomTop) + 'px';
	var $newLeft = Math.floor(Math.random()*Math.ceil(($viewport[0] - 800) / 4)) + 20 + 'px';
	$('#noteLeftThree').stop().animate({top : $newTop, left : $newLeft}, 9000, 'easeInOutBack');
}

function setNoteLeftFour()
{
	var $viewport = getViewport();
	var $scroll = getScroll();
	var $randomTop = 0.5 + (Math.random() / 10);
	var $newTop = Math.ceil(($viewport[1] + $scroll[1]) * $randomTop) + 'px';
	var $newLeft = Math.floor(Math.random()*Math.ceil(($viewport[0] - 800) / 4)) + 20 + 'px';
	$('#noteLeftFour').stop().animate({top : $newTop, left : $newLeft}, 9000, 'easeInOutBack');
}

function setNoteLeftFive()
{
	var $viewport = getViewport();
	var $scroll = getScroll();
	var $randomTop = 0.65 + (Math.random() / 10);
	var $newTop = Math.ceil(($viewport[1] + $scroll[1]) * $randomTop) + 'px';
	var $newLeft = Math.floor(Math.random()*Math.ceil(($viewport[0] - 800) / 4)) + 20 + 'px';
	$('#noteLeftFive').stop().animate({top : $newTop, left : $newLeft}, 9000, 'easeInOutBack');
}

function setNoteLeftSix()
{
	var $viewport = getViewport();
	var $scroll = getScroll();
	var $randomTop = 0.8 + (Math.random() / 10);
	var $newTop = Math.ceil(($viewport[1] + $scroll[1]) * $randomTop) + 'px';
	var $newLeft = Math.floor(Math.random()*Math.ceil(($viewport[0] - 800) / 4)) + 20 + 'px';
	$('#noteLeftSix').stop().animate({top : $newTop, left : $newLeft}, 9000, 'easeInOutBack');
}

function getViewport()
{
	var myWidth = 0, myHeight = 0;
	if( typeof( window.innerWidth ) == 'number' ) {
		//Non-IE
		myWidth = window.innerWidth;
		myHeight = window.innerHeight;
	} else if( document.documentElement && ( document.documentElement.clientWidth || document.documentElement.clientHeight ) ) {
		//IE 6+ in 'standards compliant mode'
		myWidth = document.documentElement.clientWidth;
		myHeight = document.documentElement.clientHeight;
	} else if( document.body && ( document.body.clientWidth || document.body.clientHeight ) ) {
		//IE 4 compatible
		myWidth = document.body.clientWidth;
		myHeight = document.body.clientHeight;
	}
	return [ myWidth, myHeight ];
}


function getScroll() {
	var scrOfX = 0, scrOfY = 0;
	if( typeof( window.pageYOffset ) == 'number' ) {
		//Netscape compliant
		scrOfY = window.pageYOffset;
		scrOfX = window.pageXOffset;
	} else if( document.body && ( document.body.scrollLeft || document.body.scrollTop ) ) {
		//DOM compliant
		scrOfY = document.body.scrollTop;
		scrOfX = document.body.scrollLeft;
	} else if( document.documentElement && ( document.documentElement.scrollLeft || document.documentElement.scrollTop ) ) {
		//IE6 standards compliant mode
		scrOfY = document.documentElement.scrollTop;
		scrOfX = document.documentElement.scrollLeft;
	}
	return [ scrOfX, scrOfY ];
}

function setNoteRightOne()
{
	var $viewport = getViewport();
	var $scroll = getScroll();
	var $randomTop = 0.3 + (Math.random() / 10);
	var $newTop = Math.ceil(($viewport[1] + $scroll[1]) * $randomTop) + 'px';
	var $newLeft = (Math.floor(Math.random()*Math.ceil(($viewport[0] - 800) / 4))) + 820 + (($viewport[0] - 800) / 2) + 'px';
	$('#noteRightOne').stop().animate({top : $newTop, left : $newLeft}, 9000, 'easeInOutBack');
}

function setNoteRightTwo()
{
	var $viewport = getViewport();
	var $scroll = getScroll();
	var $randomTop = 0.4 + (Math.random() / 10);
	var $newTop = Math.ceil(($viewport[1] + $scroll[1]) * $randomTop) + 'px';
	var $newLeft = (Math.floor(Math.random()*Math.ceil(($viewport[0] - 800) / 4))) + 820 + (($viewport[0] - 800) / 2) + 'px';
	$('#noteRightTwo').stop().animate({top : $newTop, left : $newLeft}, 9000, 'easeInOutBack');
}

function setNoteRightThree()
{
	var $viewport = getViewport();
	var $scroll = getScroll();
	var $randomTop = 0.5 + (Math.random() / 10);
	var $newTop = Math.ceil(($viewport[1] + $scroll[1]) * $randomTop) + 'px';
	var $newLeft = (Math.floor(Math.random()*Math.ceil(($viewport[0] - 800) / 4))) + 820 + (($viewport[0] - 800) / 2) + 'px';
	$('#noteRightThree').stop().animate({top : $newTop, left : $newLeft}, 9000, 'easeInOutBack');
}

function setNoteRightFour()
{
	var $viewport = getViewport();
	var $scroll = getScroll();
	var $randomTop = 0.6 + (Math.random() / 10);
	var $newTop = Math.ceil(($viewport[1] + $scroll[1]) * $randomTop) + 'px';
	var $newLeft = (Math.floor(Math.random()*Math.ceil(($viewport[0] - 800) / 4))) + 820 + (($viewport[0] - 800) / 2) + 'px';
	$('#noteRightFour').stop().animate({top : $newTop, left : $newLeft}, 9000, 'easeInOutBack');
}

function setNoteRightFive()
{
	var $viewport = getViewport();
	var $scroll = getScroll();
	var $randomTop = 0.7 + (Math.random() / 10);
	var $newTop = Math.ceil(($viewport[1] + $scroll[1]) * $randomTop) + 'px';
	var $newLeft = (Math.floor(Math.random()*Math.ceil(($viewport[0] - 800) / 4))) + 820 + (($viewport[0] - 800) / 2) + 'px';
	$('#noteRightFive').stop().animate({top : $newTop, left : $newLeft}, 9000, 'easeInOutBack');
}

function setNoteRightSix()
{
	var $viewport = getViewport();
	var $scroll = getScroll();
	var $randomTop = 0.8 + (Math.random() / 10);
	var $newTop = Math.ceil(($viewport[1] + $scroll[1]) * $randomTop) + 'px';
	var $newLeft = (Math.floor(Math.random()*Math.ceil(($viewport[0] - 800) / 4))) + 820 + (($viewport[0] - 800) / 2) + 'px';
	$('#noteRightSix').stop().animate({top : $newTop, left : $newLeft}, 9000, 'easeInOutBack');
}