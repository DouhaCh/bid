		<table width='100%' border='2' cellpadding='2' bgcolor='#C1C1C1'>
			<tr>
				<td bgcolor='#E1E1E1' width='90%'><h2 class='co'>Thong tin giao dich</h2></td>
				<td bgcolor='#C1C1C1' align='center'><h3 class='co'>VDC ONILNE</h3></td>
			</tr>
		</table>

        <center><h1>PHP Merchant Example - Response Page</h1></center>
        <div style="padding-left: 200px;">
            <a href=".">Quay l·∫°i ch·ªçn k√™nh n·∫°p</a>
        </div>
        <table width="85%" align="center" cellpadding="5" border="0">
            <tr class="title">
                <td colspan="3" height="25"><P><strong>&nbsp;Basic Transaction Fields</strong></P></td>
            </tr>
        </table>
        <table width="85%" align="center" cellpadding="5" border="0">
            <thead>
		<tr>
			<th scope="col">Name</th>
			<th scope="col">Value</th>
			<th scope="col">Description</th>
		</tr>
                </thead>
            <?php
            foreach ( $data as $key => $value ) {
                    ?>
                    <tr>
                        <td align="right" width="35%"><strong><i><?php echo $key;?></i></strong></td>
                        <td width="30%" align="left"><?php echo $value;?></td>
                        <td align="left" width="35%"><strong><i><?php echo $key;?></i></strong></td>
                    </tr>
                    <?php
            }
            ?>
	    </table>