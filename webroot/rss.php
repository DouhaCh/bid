<?

  class RSS
  {
	public function RSS()
	{
  		DEFINE ('DB_USER', 'root');
 		DEFINE ('DB_PASSWORD', '123');
  		DEFINE ('DB_HOST', 'localhost');
  		DEFINE ('DB_NAME', 'newpenny');

 		// Make the connnection and then select the database.
  		$dbc = @mysql_connect (DB_HOST, DB_USER, DB_PASSWORD) OR die ('Could not connect to MySQL: ' . mysql_error() );
  		mysql_select_db (DB_NAME) OR die ('Could not select the database: ' . mysql_error() );
		
	}

	public function GetFeed()
	{
		return $this->writeHeader().$this->getDetails().$this->writeFooter();
	}

	private function dbConnect()
	{
		mysql_connect (DB_HOST, DB_USER, DB_PASSWORD);
	}

	private function getDetails()
	{
		//$detailsTable = "webref_rss_details";
		//$this->dbConnect($detailsTable);
		$query = "SELECT auctions.id, auctions.product_id, start_time, end_time, meta_description, rrp, title FROM auctions JOIN products ON auctions.product_id=products.id WHERE auctions.active=1 AND auctions.end_time>NOW() order by auctions.id desc";
		$result = mysql_query ($query) or die('Can not query');
		$details='';
		while($row = mysql_fetch_array($result))
		{
			$sql="select image from products join images on products.id=images.product_id where images.product_id=".$row['product_id'];
			$row_image=mysql_fetch_array(mysql_query($sql));
			$image=$row_image['image'];
			$details.='
				<item>
					<title>'. $row['title'].'</title>
					<link>'.'http://www.1bid.vn/auctions/view/'.$row['id'].'</link>
					<description><![CDATA[<img src="/img/product_images/'.$image.'" alt="'.$row['title'].'"> <p> Tên sản phẩm:'.$row['title'].' </p><p> Thời gian bắt đầu:'.$row['start_time'].'</p><p> Thời gian kết thúc:'.$row['end_time'].'</p><p>'.$row['meta_description'].'</p><p>Giá thị trường:'.number_format($row['rrp'],0,',','.').' VND</p>]]></description>
					<pubDate>'. date('D, d M Y G:i:s',strtotime($row['start_time'])) . ' EST</pubDate>
				</item>';
		}
		return $details;
	}

	private function writeHeader()
	{
		$header='<?xml version="1.0" encoding="UTF-8"?>
<rss version="2.0" xmlns:media="http://search.yahoo.com/mrss/">
	<channel>
		<title>1bid.vn</title>
    	<link>http://www.1bid.vn</link>
    	<description>Thế Giới www.xaluan.com powered site</description>
    	<copyright>COPYRIGHT 2010 XãLuận.com tin tức Việt Nam cập nhật 24 giờ</copyright>

    	<generator>XãLuận.com tin tức Việt Nam cập nhật 24 giờ</generator>
    	<docs>http://www.xaluan.com/rss.php</docs>
    	<language>en-us</language>
    	<lastBuildDate>Tue, 14 Dec 2010 02:44:59 EST</lastBuildDate>
    	<managingEditor>webmaster@xaluan.com (webmaster Xaluan)</managingEditor>
    	<webMaster>webmaster@xaluan.com (webmaster Xaluan)</webMaster>

    	<image>
      		<title>Thế Giới XãLuận.com tin tức Việt Nam cập nhật 24 giờ</title>
      		<url>http://www.xaluan.com/images/logo.gif</url>
      		<link>http://www.xaluan.com</link>
      		<width>144</width>
      		<height>48</height>
      		<description>www.xaluan.com powered site</description>
    	</image>';
		
		return $header;
	}
	
	private function writeFooter()
	{
		$footer='
			</channel>
		</rss>
		<!-- Cached '.date('d-m-Y').' -->';
		return $footer;
		
	}
	
	public function writeFile()
	{
		$file = fopen("feed.rss","w");
		//some code to be executed
		fwrite($file, $this->GetFeed());
		fclose($file);
	}

}

?>
