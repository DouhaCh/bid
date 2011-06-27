$(document).ready(function(){

    // Variable to hold auction data
    var auctions = [];
    var auctionObjects = new Array();

    // Collecting auction data, the layer id and auction id
    $('.auction-item').each(function(){
        var auctionId    = $(this).attr('id');
        var auctionTitle = $(this).attr('title');

        if($('#' + auctionId + ' .countdown').length){
        	
            // collect the id for post data
            auctions.push(auctionTitle);

            // collect the object
            auctionObjects[auctionId]                           = $('#' + auctionId);
            auctionObjects[auctionId]['flash-elements']         = $('#' + auctionId + ' .countdown, #' + auctionId + ' .bid-price, #' + auctionId + ' .bid-bidder, #' + auctionId+ ' .bid-savings-price, #' + auctionId + ' .bid-savings-percentage, #' + auctionId + ' .closes-on');
            auctionObjects[auctionId]['countdown']              = $('#' + auctionId + ' .countdown');
            auctionObjects[auctionId]['closes-on']              = $('#' + auctionId + ' .closes-on');
            auctionObjects[auctionId]['bid-bidder']             = $('#' + auctionId + ' .bid-bidder');
            auctionObjects[auctionId]['bid-button']             = $('#' + auctionId + ' .bid-button');
            auctionObjects[auctionId]['bid-button-a']           = $('#' + auctionId + ' .bid-button a');
            auctionObjects[auctionId]['bid-button-p']           = $('#' + auctionId + ' .bid-button p');
            auctionObjects[auctionId]['bid-price']              = $('#' + auctionId + ' .bid-price');
            auctionObjects[auctionId]['bid-price2']             = $('#' + auctionId + ' .bid-price2');
            auctionObjects[auctionId]['buy-it-now']             = $('#' + auctionId + ' .price_bin');
            auctionObjects[auctionId]['bid-price-fixed']        = $('#' + auctionId + ' .bid-price-fixed');
            auctionObjects[auctionId]['bid-loading']            = $('#' + auctionId + ' .bid-loading');
            auctionObjects[auctionId]['bid-message']            = $('#' + auctionId + ' .bid-message');
            auctionObjects[auctionId]['bid-flash']              = $('#' + auctionId + ' .bid-flash');
            auctionObjects[auctionId]['bid-savings-price']      = $('#' + auctionId + ' .bid-savings-price');
            auctionObjects[auctionId]['bid-savings-percentage'] = $('#' + auctionId + ' .bid-savings-percentage');
            auctionObjects[auctionId]['bid-bookbidbutler']      = $('#' + auctionId + ' .bid-bookbidbutler');
            auctionObjects[auctionId]['bid-increment']      = $('#' + auctionId + ' .bid-increment');
            auctionObjects[auctionId]['price-increment']      = $('#' + auctionId + ' .price-increment');

            auctionObjects[auctionId]['bid-histories']          = $('#bidHistoryTable' + auctionTitle);
            auctionObjects[auctionId]['bid-histories-p']        = $('#bidHistoryTable' + auctionTitle + ' p');
            auctionObjects[auctionId]['bid-histories-tbody']    = $('#bidHistoryTable' + auctionTitle + ' tbody');
        }
    });

    // additional object
    var bidOfficialTime        = $('.bid-official-time');
    var bidBalance             = $('.bid-balance');
    var getAuctionsUrl;
    var time = 0;
    var lastGetAuctionsTime = 0;

    if($('.bid-histories').length){
        getAuctionsUrl = '/live/auctions.php?histories=yes&ms=';
    }else{
        getAuctionsUrl = '/live/auctions.php?ms=';
    }

    // Do the loop when auction available only
    if(auctions){
        setInterval(function(){
            $.ajax({
                url: getAuctionsUrl,
                dataType: 'json',
                type: 'POST',
                data: "auctions=" + JSON.stringify(auctions),
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
            			
            			auctionObject['bid-price'].html(auction.price);
            			auctionObject['bid-bidder'].html(auction.username);
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
  
            			if(t>10){
            				auctionObject['countdown'].html('<div class="clock">'+hour+'</div> <div class="clock">'+minute+'</div><div class="clock last">'+second+'</div>');
            			}else if(t>=0){
            				auctionObject['countdown'].html('<div class="clock rush">'+hour+'</div> <div class="clock rush">'+minute+'</div><div class="clock rush last">'+second+'</div>');                      	
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
    $('.bid-button-link').click(function(){
        var auctionElement = 'auction_' + $(this).attr('title');

        auctionObjects[auctionElement]['bid-button'].hide(1);
        auctionObjects[auctionElement]['bid-loading'].show(1);

        $.ajax({
            url: "/live/bid.php?auction_id=" + $(this).attr('title'),
            success: function(data){
            	console.log(data);
            }
        });

        return false;
    });

    // Function to check limit and change the icon whenever it's changed
    // Run only when bid icon available
    if($('.bid-limit-icon').length){
        setInterval(function(){
            var count = $('.bid-limit-icon').length
            if(count > 0){
                $.ajax({
                    url: '/limits/getlimitsstatus/?ms=' + new Date().getTime(),
                    dataType: 'json',
                    success: function(data){
                        if(data){
                            $('.bid-limit-icon').each(function(i){
                                if(data[i].image){
                                    $(this).attr('src', '/img/'+data[i].image);
                                }
                            });
                        }
                    }
                });
            }
        }, 30000);
    }

    if($('.productImageThumb').length){
        $('.productImageThumb').click(function(){
            $('.productImageMax').fadeOut('fast').attr('src', $(this).attr('href')).fadeIn('fast');
            return false;
        });
    }

    if($('#CategoryId').length){
        $('#CategoryId').change(function(){
            document.location = '/categories/view/' + $('#CategoryId option:selected').attr('value');
        });
    }

    if($('#myselectbox').length){
        $('#myselectbox').change(function(){
            document.location = '/categories/view/' + $('#myselectbox option:selected').attr('value');
        });
    }
});
