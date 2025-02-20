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
      <span class="menu-header-text">Customer Registration</span>
    </li>
    <li <? if($site_page_name == 'user_register' or $site_page_name == 'user_list' ) { ?>class="menu-item active open"<? } else { ?>class="menu-item"<? } ?>>
      <a href="javascript:void(0);" class="menu-link menu-toggle">
        <i class="menu-icon tf-icons bx bx-user-plus"></i>
        <div data-i18n="User Registration">Customer Register</div>
      </a>
      <ul class="menu-sub">
        <li <? if($site_page_name == 'user_register') { ?>class="menu-item active"<? } else { ?>class="menu-item"<? } ?>>
          <a href="user_register" class="menu-link">
            <div data-i18n="Registration">Registration</div>
          </a>
        </li>
        <li <? if($site_page_name == 'user_list') { ?>class="menu-item active"<? } else { ?>class="menu-item"<? } ?>>
          <a href="user_list" class="menu-link">
            <div data-i18n="Customer List">Customer List</div>
          </a>
        </li>
      
      </ul>
    </li>

   <?/*   <li class="menu-header small text-uppercase">
      <span class="menu-header-text">Camera Registration</span>
    </li>
    <li <? if($site_page_name == 'camera_details' or $site_page_name == 'add_camera_list' ) { ?>class="menu-item active open"<? } else { ?>class="menu-item"<? } ?>>
      <a href="javascript:void(0);" class="menu-link menu-toggle">
        <i class="menu-icon tf-icons bx bxs-camera-plus"></i>
        <div data-i18n="User Registration">Camera Register</div>
      </a>
      <ul class="menu-sub">
      <li <? if($site_page_name == 'camera_details') { ?>class="menu-item active"<? } else { ?>class="menu-item"<? } ?>>
          <a href="camera_details" class="menu-link">
            <div data-i18n="Camera Details">ADD Camera</div>
          </a>
        </li>
<li <? if($site_page_name == 'add_camera_list') { ?>class="menu-item active"<? } else { ?>class="menu-item"<? } ?>>
          <a href="add_camera_list" class="menu-link">
            <div data-i18n="Camera Details">ADD Camera List</div>
          </a>
        </li>
        </ul>
    </li>*/  ?>

    <!-- Forms & Tables -->
    <li class="menu-header small text-uppercase"><span class="menu-header-text">Reports</span></li>
    <!-- Forms -->
    <li <? if($site_page_name == 'rt_hourly_report' or $site_page_name == 'mis_report' or $site_page_name == 'rt_hourly_summary_report') { ?>class="menu-item active open"<? } else { ?>class="menu-item"<? } ?>>
      <a href="javascript:void(0);" class="menu-link menu-toggle">
        <i class="menu-icon tf-icons bx bx-chart"></i>
        <div data-i18n="Reports">Reports</div>
      </a>
      <ul class="menu-sub">
        <li <? if($site_page_name == 'rt_hourly_report') { ?>class="menu-item active"<? } else { ?>class="menu-item"<? } ?>>
          <a href="rt_hourly_report" class="menu-link">
            <div data-i18n="Today Report">Today Report</div>
          </a>
        </li>
        <li <? if($site_page_name == 'rt_hourly_summary_report') { ?>class="menu-item active"<? } else { ?>class="menu-item"<? } ?>>
          <a href="rt_hourly_summary_report" class="menu-link">
            <div data-i18n="Today Summary Report">Today Summary Report</div>
          </a>
        </li>
      </ul>
    </li>

    <li <? if($site_page_name == 'mtd_report' or $site_page_name == 'ytd_report' or $site_page_name == 'lmtd_report' or $site_page_name == 'ltd_report') { ?>class="menu-item active open"<? } else { ?>class="menu-item"<? } ?>>
      <a href="javascript:void(0);" class="menu-link menu-toggle">
        <i class="menu-icon tf-icons bx bx-bar-chart-alt-2"></i>
        <div data-i18n="Form Layouts">Comparative Reports</div>
      </a>
      <ul class="menu-sub">
        <li <? if($site_page_name == 'ltd_report') { ?>class="menu-item active"<? } else { ?>class="menu-item"<? } ?>>
          <a href="ltd_report" class="menu-link">
            <div data-i18n="LTD">LTD</div>
          </a>
        </li>
        <li <? if($site_page_name == 'mtd_report') { ?>class="menu-item active"<? } else { ?>class="menu-item"<? } ?>>
          <a href="mtd_report" class="menu-link">
            <div data-i18n="MTD">MTD</div>
          </a>
        </li>
        <li <? if($site_page_name == 'lmtd_report') { ?>class="menu-item active"<? } else { ?>class="menu-item"<? } ?>>
          <a href="lmtd_report" class="menu-link">
            <div data-i18n="LMTD">LMTD</div>
          </a>
        </li>
        <li <? if($site_page_name == 'ytd_report') { ?>class="menu-item active"<? } else { ?>class="menu-item"<? } ?>>
          <a href="ytd_report" class="menu-link">
            <div data-i18n="YTD">YTD</div>
          </a>
        </li>
      </ul>
    </li>
  </ul>
</aside>
