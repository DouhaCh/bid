$(document).ready(function(){

    // Variable to hold auction data
    var auctions = [];
    var auctionObjects = {};

    // Collecting auction data, the layer id and auction id
    $('.auction-item').each(function(){
        var auctionId    = $(this).attr('id');

        if($('#' + auctionId + ' .-auction-time').length){
            // collect the id for post data
            auctions.push($(this).attr('auction_id'));

            // collect the object
            auctionObjects[auctionId]                           = $('#' + auctionId);
            auctionObjects[auctionId]['bidder']					= $('#' + auctionId + " .-auction-bidder");
            auctionObjects[auctionId]['price']					= $('#' + auctionId + " .-auction-price");
            auctionObjects[auctionId]['time']					= $('#' + auctionId + " .-auction-time");
        }
    });

    // additional object
    var bidBalance = $('.bid-balance');
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
    	console.log(auctions);
    	(function getAuctionsLoop(){
        	$.ajax({
                url: getAuctionsUrl,
                dataType: 'json',
                type: 'POST',
                data: "auctions=" + JSON.stringify(auctions),
                success: function(data){
            		if(lastGetAuctionsTime > data.ms) return;
            		if(time < 10000) return;
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
            				if(time > 0){
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
                    			
                    			auctionObject['time'].html(
                    				"<span>" + hour + "</span> <span>" + minute + "</span> <span>" + second + "</span>"
                    			);
            				}
            			}else{
            				auctionObject.removeClass("bid-panel-active").addClass("bid-panel");
            			}
            		}
                }
            });
        	
        	setTimeout(function(){
        		getAuctionsLoop();
        	}, 1000);
        })();
        
        (function getRemoteTimeLoop(){
        	var gettime = '/live/time.php?' + new Date().getTime();
        	$.ajax({
        		url: gettime,
        		timeout : 1000,
        		success: function(data){
        			time = data;
        		}
        	});
        	
        	setTimeout(function(){
        		// increase time prevent gettime error
            	time = parseInt(time, 10) + 1;
        		getRemoteTimeLoop();
        	}, 1000);
        })();
    }

    // Function for bidding
    $('.-auction-bid').click(function(){
        $.ajax({
            url: "/live/bid.php?auction_id=" + $(this).attr('auction_id'),
            success: function(data){
            	var parts = data.split("::");
            	$.jGrowl(parts[0]);
            	if(parts[1] !== undefined){
            		bidBalance.html(parts[1]);
            	}
            }
        });

        return false;
    });
});
