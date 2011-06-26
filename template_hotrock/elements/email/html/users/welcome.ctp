<p><?php echo sprintf(__('Hi %s', true), $data['User']['first_name']);?>,</p>

<p><?php echo __('Thank you very much for registering with us, we hope you have lots of fun,',true);?>
<?php echo __('and find some great deals at',true);?> <?php echo $appConfigurations['name'];?>.

<p><?php echo __("Want to see all the latest auctions? What's going to be your next bargain?",true);?>
<?php echo __('Start with our homepage:',true);?></p>

<p>
    <a href="<?php echo $appConfigurations['url'];?>"><?php echo $appConfigurations['url'];?></a>
</p>

<p><?php echo __('Want to charge up your bid account and get started bidding immediately?',true);?>
<?php echo __('Follow this link:',true);?></p>

<p>
    <a href="<?php echo $appConfigurations['url'];?>/packages"><?php echo $appConfigurations['url'];?>/packages/</a>
</p>

<p><?php echo __('Want some Free Bids for your account? Invite your friends to join',true);?> <?php echo $appConfigurations['name'];?>!</p>

<p>
    <a href="<?php echo $appConfigurations['url'];?>/invites"><?php echo $appConfigurations['url'];?>/invites/</a>
</p>

<p><?php echo __('Questions? Here you can find answers to any questions you may have about',true);?> <?php echo $appConfigurations['name'];?>:</p>

<p>
    <a href="<?php echo $appConfigurations['url'];?>/contact-us"><?php echo $appConfigurations['url'];?>/contact-us/</a>
</p>


<p><?php echo __('Finally, you can complete your user profile by going to My',true);?> <?php echo $appConfigurations['name'];?> <?php echo __('here:',true);?></p>

<p>
    <a href="<?php echo $appConfigurations['url'];?>/users/edit"><?php echo $appConfigurations['url'];?>/users-edit/</a>
</p>

<p><?php echo __("Here's your new login information:",true);?></p>

<?php __('Username');?>: <?php echo $data['User']['username'];?>
</p>

<p><?php echo __('Happy bidding at',true);?> <?php echo $appConfigurations['name'];?>!</p>

<p><?php echo $appConfigurations['name'];?> team.</p>