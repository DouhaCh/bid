$(document).ready(function(){
	$('.ajax_loader').click(function(){
		var target = $('' + $(this).attr('target'));
		target.html("Loading ...");
		$.ajax({
			url : $(this).attr('href'),
			success : function(data){
				target.html(data);
			}
		});
		
		return false;
	});
	
	$('.button_link').click(function(){
		$()
	});
});

// script to generate slider
/*$(document).ready(function(){
	var images = ['/img/banners/banner1.jpeg', 'img/banners/banner2.jpeg', 'img/banners/banner3.jpeg'];
	var width = '940px';
	var height = '150px';
	
	$('#s3slider').append('<ul id="s3sliderContent"></ul>');
	for(var i=0; i<images.length; i++){
		$('#s3slider #s3sliderContent').append('<li class="s3sliderImage"><img width="'+width+'" height="'+height+'" src="'+images[i]+'"/><span>Your text comes here</span></li>');
	}
	
	// create css
	var s3sliderCss = {
		width: width,
		height: height,
		position: 'relative',
		overflow: 'hidden',
		'margin-bottom' : '5px',
		'border-radius' : '10px 10px 10px 10px',
		'-moz-border-radius': '10px 10px 10px 10px',
		'-webkit-border-radius': '10px 10px 10px 10px',
	};
	$('#s3slider').css(s3sliderCss);
	
	var s3sliderContentCss = {
		width: width,
		position: 'absolute',
		top: '0',
		'margin-left' : '0'                 
	};
	$('#s3slider .s3sliderContent').css(s3sliderContentCss);
	
	var s3sliderImageCss = {
		float: 'left',
		position: 'relative',
		display: 'none',                   
	};
	$('#s3slider .s3sliderImage').css(s3sliderImageCss);

	var s3sliderSpanCss = {
		'position' : 'absolute',
		left: '0',
		font: '10px/15px Arial, Helvetica, sans-serif',
		padding: '10px 13px',
		width: '374px',
		'background-color': '#000',
		filter: 'alpha(opacity=70)',
		'-moz-opacity' : '0.7',
		'-khtml-opacity' : '0.7',
		opacity: '0.7',
		color: '#FFF',
		display: 'none',
		top: '0'             
	};
	$('#s3slider .s3sliderImage span').css(s3sliderSpanCss);

	$('#s3slider').s3Slider({ 
		timeOut: 4000 
	});
});*/

$(document).ready(function(){
	$('ul.sf-menu').superfish({
		delay : 1000, // one second delay on mouseout 
		animation : {
			opacity : 'show',
			height : 'show'
		}, // fade-in and slide-down animation 
		speed : 'fast', // faster animation speed 
		autoArrows : false, // disable generation of arrow mark-up 
		dropShadows : true
	});
});

(function(){
	Array.prototype.removeDuplicate = function removeDuplicate(){
		var newArray = [];
		for(var i=0, len=this.length; i<len; i++){
			if(!newArray.containValue(this[i])){
				newArray.push(this[i]);
			}
		}
		
		return newArray;
	};
	
	Array.prototype.containValue = function containValue(val){
		for(var i=0, len=this.length; i<len; i++){
			if(this[i] === val) return true;
		}
		return false;
	}
})();
