$(document).ready(function(){

    // Variable to hold auction data
    var auctions = [];
    var auctionObjects = new Array();

    // Collecting auction data, the layer id and auction id
    $('.auction-item').each(function(){
        var auctionId    = $(this).attr('id');
        var auctionTitle = $(this).attr('title');

        if($('#' + auctionId + ' .auction-time').length){
        	
            // collect the id for post data
            auctions.push(auctionTitle);

            // collect the object
            auctionObjects[auctionId]                           = $('#' + auctionId);
            auctionObjects[auctionId]['bidder']					= $('#' + auctionId + " .auction-bidder");
            auctionObjects[auctionId]['price']					= $('#' + auctionId + " .auction-price");
            auctionObjects[auctionId]['time']					= $('#' + auctionId + " .auction-time");
            auctionObjects[auctionId]['bid-1']					= $('#' + auctionId + ".auction-type-1 .auction-bid-link");
            auctionObjects[auctionId]['bid-container-1']		= $('#' + auctionId + ".auction-type-1 .auction-bid-container");
        }
    });

    // additional object
    var bidBalance             = $('.bid-balance');
    var getAuctionsUrl;
    var time = 0;
    var lastGetAuctionsTime = 0;

    if($('.bid-histories').length){
        getAuctionsUrl = '/live/auctions.php?histories=yes';
    }else{
        getAuctionsUrl = '/live/auctions.php';
    }

    // Do the loop when auction available only
    if(auctions){
        setInterval(function(){
            $.ajax({
                url: getAuctionsUrl,
                dataType: 'json',
                type: 'POST',
                data: "auctions=" + JSON.stringify(auctions.removeDuplicate()),
                success: function(data){
            		if(lastGetAuctionsTime > data.ms) return;
            		lastGetAuctionsTime = data.ms;
            		var auctions = data.auctions;
            		var auction;
            		var id;
            		var auctionObject;
            		for(var i=0; i<auctions.length; i++){
            			auction = auctions[i];
            			id = 'auction_' + auction.id;
            			auctionObject = auctionObjects[id];
            			
            			auctionObject['price'].html(auction.price);
            			auctionObject['bidder'].html(auction.username);
            			//auctionObject['bid-bidder-avatar'].attr('src', auction.avatar);
            			if(auction.closed == "0"){
            				var t = auction.end_time - time - 1;
                			
                			if(t>0){
                				hour=Math.floor(t/3600);
                				minute=Math.floor((t-3600*hour)/60);
                				second=t%60;
                				if(hour<10) hour='0'+hour;
                				if(minute<10) minute='0'+minute;
                				if(second<10) second='0'+second;
                			}else{
                				hour='00';
                				minute='00';
                				second='00';
                			}
                			
                			auctionObject['time'].html(hour + " : " + minute + " : " + second);auctionObject
            			}else{
            				if(auctionObject['bid-container-1'].html()){
            					auctionObject['bid-container-1'].html("<a href=\"#\" class=\"auction-bid-ended\">Xem</a>");
            					auctionObject['time'].html("00 : 00 : 00");
            				}
            			}
            		}
                }
            });
        }, 1000);
    }
    
    setInterval(function(){
    	// increase time prevent gettime error
    	time = parseInt(time, 10) + 1;
    	var gettime = '/live/time.php?' + new Date().getTime();
    	$.ajax({
    		url: gettime,
    		success: function(data){
    			time = data;
    		}
    	});
	}, 1000);

    // Function for bidding
    $('.auction-bid-link').click(function(){
        $.ajax({
            url: "/live/bid.php?auction_id=" + $(this).attr('href'),
            success: function(data){
            	$.jGrowl(data);
            }
        });

        return false;
    });
});
