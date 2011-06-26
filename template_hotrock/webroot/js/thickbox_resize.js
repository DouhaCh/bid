function thickboxResize() {  
  
    var boundHeight = 530; // minimum height  
    var boundWidth = 400; // minimum width  
  
    var viewportWidth = (self.innerWidth || (document.documentElement.clientWidth || (document.body.clientWidth || 0)))  
    var viewportHeight =(self.innerHeight || (document.documentElement.clientHeight || (document.body.clientHeight || 0)))  
  
    $('a.thickbox').each(function(){  
        var text = $(this).attr("href");  
  
        if ( viewportHeight < boundHeight  || viewportHeight < boundWidth)  
        {  
            // adjust the height  
            text = text.replace(/height=[0-9]*/,'height=' + Math.round(viewportHeight * .8));  
            // adjust the width  
            text = text.replace(/width=[0-9]*/,'width=' + Math.round(viewportWidth * .8));  
        }  
        else   
        {  
            // constrain the height by defined bounds  
            text = text.replace(/height=[0-9]*/,'height=' + boundHeight);  
            // constrain the width by defined bounds  
            text = text.replace(/width=[0-9]*/,'width=' + boundWidth);  
        }  
  
        $(this).attr("href", text);  
    });  
}  
  
$(window).bind('load', thickboxResize );  
$(window).bind('resize', thickboxResize );  