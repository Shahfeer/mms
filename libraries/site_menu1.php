<aside id="layout-menu" class="layout-menu menu-vertical menu bg-menu-theme">
  <div class="app-brand demo">
    <a href="user_registration" class="app-brand-link">
      <span class="app-brand-text demo menu-text fw-bolder ms-2">
				<img src="assets/img/celebmedia_logo.png" style="max-width:260px; width:100%; max-height:64px; height: 100%;">
			</span>
    </a>

    <a href="javascript:void(0);" class="layout-menu-toggle menu-link text-large ms-auto d-block d-xl-none">
      <i class="bx bx-chevron-left bx-sm align-middle"></i>
    </a>
  </div>

  <div class="menu-inner-shadow"></div>

  <ul class="menu-inner py-1">
    <li class="menu-header small text-uppercase">
      <span class="menu-header-text">Customer Registration</span>
    </li>
    <li <? if($site_page_name == 'user_registration') { ?>class="menu-item active open"<? } else { ?>class="menu-item"<? } ?>>
      <a href="javascript:void(0);" class="menu-link menu-toggle">
        <i class="menu-icon tf-icons bx bx-user-plus"></i>
        <div data-i18n="User Registration">Customer Register</div>
      </a>
      <ul class="menu-sub">
        <li <? if($site_page_name == 'user_registration') { ?>class="menu-item active"<? } else { ?>class="menu-item"<? } ?>>
          <a href="user_registration" class="menu-link">
            <div data-i18n="Registration">Registration</div>
          </a>
        </li>      
      </ul>
    </li>
  </ul>
</aside>
