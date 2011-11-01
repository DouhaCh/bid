<div id="topheaderframe">
  <div class="inline">
        <ul id="tender_nav" class="headernavblue">
    <li><a href="/admin">Trang chính</a></li>
      <li class="home"><?php echo $html->link('Trang chủ', '/');?></li>
    </ul>
    <ul id="user_nav" class="headernavblue first">
      <li class="user_nav-myprofile">Đăng nhập bới: <?php echo $session->read('Auth.User.username'); ?></li>
      <li class="user_nav-logout"><?php echo $html->link("Đăng xuất", array('controller' => 'users', 'action' => 'logout', 'admin' => false));?></li>
    </ul>


  </div>

</div><!-- /#superheader -->
