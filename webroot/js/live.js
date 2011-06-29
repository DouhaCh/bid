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
            			var t = auction.end_time - time;
            			
            			if(t>0){
            				hour=parseInt(t/3600);
            				minute=parseInt((t-3600*hour)/60);
            				second=t%60;
            				if(hour<10) hour='0'+hour;
            				if(minute<10) minute='0'+minute;
            				if(second<10) second='0'+second;
            			}else{
            				hour='00';
            				minute='00';
            				second='00';
            			}
  
            			/*if(t>10){
            				auctionObject['countdown'].html('<div class="clock">'+hour+'</div> <div class="clock">'+minute+'</div><div class="clock last">'+second+'</div>');
            			}else if(t>=0){
            				auctionObject['countdown'].html('<div class="clock rush">'+hour+'</div> <div class="clock rush">'+minute+'</div><div class="clock rush last">'+second+'</div>');                      	
            			}*/
            			auctionObject['time'].html(hour + " : " + minute + " : " + second);
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
            	console.log(data);
            }
        });

        return false;
    });
});
