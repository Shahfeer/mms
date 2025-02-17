<aside id="layout-menu" class="layout-menu menu-vertical menu bg-menu-theme">
  <div class="app-brand demo">
    <a href="dashboard" class="app-brand-link">
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
    <!-- Dashboard -->
    <li <? if($site_page_name == 'dashboard') { ?>class="menu-item active"<? } else { ?>class="menu-item"<? } ?>>
      <a href="dashboard" class="menu-link">
        <i class="menu-icon tf-icons bx bx-home-circle"></i>
        <div data-i18n="Dashboard">Dashboard</div>
      </a>
    </li>

    <li class="menu-header small text-uppercase">
      <span class="menu-header-text">User Registration</span>
    </li>
    <li <? if($site_page_name == 'user_register') { ?>class="menu-item active open"<? } else { ?>class="menu-item"<? } ?>>
      <a href="javascript:void(0);" class="menu-link menu-toggle">
        <i class="menu-icon tf-icons bx bx-user-plus"></i>
        <div data-i18n="User Registration">User Registration</div>
      </a>
      <ul class="menu-sub">
        <li <? if($site_page_name == 'user_register') { ?>class="menu-item active"<? } else { ?>class="menu-item"<? } ?>>
          <a href="user_register" class="menu-link">
            <div data-i18n="Registration">Registration</div>
          </a>
        </li>
        <li class="menu-item">
          <a href="#" class="menu-link">
            <div data-i18n="User List">User List</div>
          </a>
        </li>
      </ul>
    </li>
    
    <!-- Forms & Tables -->
    <li class="menu-header small text-uppercase"><span class="menu-header-text">Reports</span></li>
    <!-- Forms -->
    <li class="menu-item">
      <a href="javascript:void(0);" class="menu-link menu-toggle">
        <i class="menu-icon tf-icons bx bx-chart"></i>
        <div data-i18n="MIS Reports">MIS Reports</div>
      </a>
      <ul class="menu-sub">
        <li class="menu-item">
          <a href="#" class="menu-link">
            <div data-i18n="Real time Hourly Report">Real time Hourly Report</div>
          </a>
        </li>
      </ul>
    </li>
    <li class="menu-item">
      <a href="javascript:void(0);" class="menu-link menu-toggle">
        <i class="menu-icon tf-icons bx bx-bar-chart-alt-2"></i>
        <div data-i18n="Form Layouts">Comparative Reports</div>
      </a>
      <ul class="menu-sub">
        <li class="menu-item">
          <a href="#" class="menu-link">
            <div data-i18n="MTD">MTD</div>
          </a>
        </li>
        <li class="menu-item">
          <a href="#" class="menu-link">
            <div data-i18n="LTD (D-1)">LTD (D-1)</div>
          </a>
        </li>
        <li class="menu-item">
          <a href="#" class="menu-link">
            <div data-i18n="LMTD">LMTD</div>
          </a>
        </li>
        <li class="menu-item">
          <a href="#" class="menu-link">
            <div data-i18n="YTD">YTD</div>
          </a>
        </li>
      </ul>
    </li>
  </ul>
</aside>