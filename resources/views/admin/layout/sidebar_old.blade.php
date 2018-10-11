<aside class="main-sidebar hidden-print">
    <section class="sidebar">
        <div class="search-panel">
            <div class="search-box">
              <div class="icon pull-left"><i class="fa fa-search"></i></div>
              <input class="text" type="text" placeholder="Special Search if require">
              <div class="clearfix"></div>
            </div>
        </div>
        <!-- Sidebar Menu-->
         <div class="sidebar-content">
            <ul class="sidebar-menu" id="nav-accordion">
                <ul class="sidebar-menu" id="nav-accordion">
                <li class=" @if(Request::segment(2) == 'salesorder') active @endif">
                    <a href="<?=route('salesorder.index')?>">
                    <i class="fa fa-dashboard"></i>
                    <span>Sales Order</span>
                    </a>
                </li>
                <li class=" @if(Request::segment(2) == 'product') active @endif">
                    <a href="<?=route('product.index')?>">
                    <i class="fa fa-user"></i>
                    <span>Product Master</span>
                    </a>
                </li>
                @if($fetch_manager_admin_id['team_id'] == config('Constant.superadmin') || $fetch_manager_admin_id['team_id'] == config('Constant.admin') || $fetch_manager_admin_id['team_id'] == config('Constant.warehouse'))
                    <li class=" @if(Request::segment(2) == 'purchase-requisition') active @endif">
                        <a href="<?=route('purchase-requisition.index')?>">
                        <i class="fa fa-user"></i>
                        <span>Purchase Requisition</span>
                        </a>
                    </li>
                @endif
                @if($fetch_manager_admin_id['team_id'] == config('Constant.superadmin') || $fetch_manager_admin_id['team_id'] == config('Constant.admin'))
                    <li class=" @if(Request::segment(2) == 'purchase-requisition-approval') active @endif">
                        <a href="<?=route('purchase-requisition-approval.index')?>">
                        <i class="fa fa-user"></i>
                        <span>Purchase Requisition Approval</span>
                        </a>
                    </li>
                @endif
                <li class=" @if(Request::segment(2) == 'suppliers') active @endif">
                    <a href="<?= route('suppliers.index') ?>">
                    <i class="fa fa-user"></i>
                    <span>Supplier Master</span>
                    </a>
                </li>
                <li class=" @if(Request::segment(2) == 'customer') active @endif">
                    <a href="<?= route('customer.index') ?>">
                    <i class="fa fa-user"></i>
                    <span>Customer Master</span>
                    </a>
                </li>
                <li class=" @if(Request::segment(2) == 'managestock') active @endif">
                    <a href="<?= route('managestock.index') ?>">
                    <i class="fa fa-user"></i>
                    <span>Manage Stock</span>
                    </a>
                </li>
                <li class=" @if(Request::segment(2) == 'companymaster') active @endif">
                    <a href="<?= route('companymaster.index') ?>">
                    <i class="fa fa-user"></i>
                    <span>Company Master</span>
                    </a>
                </li>
                <!-- <li class=" @if(Request::segment(2) == 'states') active @endif">
                    <a href="<?= route('state.index') ?>">
                    <i class="fa fa-user"></i>
                    <span>State</span>
                    </a>
                </li>
                <li class=" @if(Request::segment(2) == 'cities') active @endif">
                    <a href="<?= route('cities.index') ?>">
                    <i class="fa fa-user"></i>
                    <span>City</span>
                    </a>
                </li>-->
                <!-- <li class=" @if(Request::segment(2) == 'billing') active @endif">
                    <a href="<?= route('billing.index') ?>">
                    <i class="fa fa-user"></i>
                    <span>Billing Address</span>
                    </a>
                </li> -->
               <li class="sub-menu @if(Request::segment(1) == 'admin') active @endif">
                    <a href="javascript:;">
                        <i class="fa fa-bars"></i>
                        <span>System User</span>
                        <i class="fa fa-angle-right"></i>
                    </a>
                    <ul class="treeview-menu">
                        <li class="@if(Request::segment(2) == 'designation') active @endif">
                            <a href="<?= route('designation.index') ?>">
                            <i class="fa fa-pencil-square-o"></i>
                            <span>Designation Master</span>
                            </a>
                        </li>
                        <li class="@if(Request::segment(2) == 'systemuser') active @endif">
                            <a href="<?= route('systemuser.index') ?>">
                            <i class="fa fa-users"></i>
                            <span>Users</span>
                            </a>
                        </li>
                    </ul>   
                </li>
                <!-- EMAIL DASHBOARD FOR ADMIN --> 
                <li class="sub-menu @if(Request::segment(1) == 'admin') active @endif">
                        <a href="javascript:;">
                            <i class="fa fa-bars"></i>
                            <span>EMAIL DASHBOARD FOR ADMIN</span>
                            <i class="fa fa-angle-right"></i>
                        </a>
                        <ul class="treeview-menu">
                            <li class="@if(Request::segment(2) == 'designation') active @endif">
                                <a href="<?= route('designation.index') ?>">
                                <i class="fa fa-pencil-square-o"></i>
                                <span>Designation Master</span>
                                </a>
                            </li>
                            <li class="@if(Request::segment(2) == 'systemuser') active @endif">
                                <a href="<?= route('systemuser.index') ?>">
                                <i class="fa fa-users"></i>
                                <span>Users</span>
                                </a>
                            </li>
                        </ul>   
                    </li>
                </ul>
                <li class="spacer"></li>
                <li class="logout">
                    <a href="javascript:;">
                        <i class="fa fa-user"></i>
                        <span>{{Auth::guard('admin')->user()->name}}</span>
                        <i class="fa fa-angle-right"></i>
                    </a>
                    <ul class="logout-modal">
                        <li>
                            <a href="/admin/change-password">Change Password</a>
                        </li>
                        <li>
                            <a href="<?=route('admin.logout')?>">Logout</a>
                        </li>
                    </ul>
                </li>
            </ul>
        </div>
    </section>
</aside>