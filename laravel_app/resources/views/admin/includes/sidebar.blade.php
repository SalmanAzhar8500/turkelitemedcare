<aside id="leftsidebar" class="sidebar">
    <ul class="nav nav-tabs">
        <li class="nav-item"><a class="nav-link active" href="{{ route('admin.dashboard') }}"><i class="zmdi zmdi-home m-r-5"></i>Oreo</a></li>
        <li class="nav-item"><a class="nav-link" data-toggle="tab" href="#user">Doctor</a></li>
    </ul>
    <div class="tab-content">
        <div class="tab-pane stretchRight active" id="dashboard">
            <div class="menu">
                <ul class="list">
                    <li>
                        <div class="user-info">
                            <div class="image"><a href="profile.html"><img src="{{asset('admin/assets/images/profile_av.jpg')}}" alt="User"></a></div>
                            <div class="detail">
                                <h4>Dr. Charlotte</h4>
                                <small>Neurologist</small>
                            </div>
                        </div>
                    </li>
                    <li class="header">MAIN</li>
                    <li class="{{ request()->routeIs('admin.dashboard') || request()->routeIs('admin.home') ? 'active' : '' }}"><a href="{{ route('admin.dashboard') }}"><i class="zmdi zmdi-home"></i><span>Dashboard</span></a></li>
                    <li class="{{ request()->routeIs('admin.appointments.*') ? 'active' : '' }}"><a href="{{ route('admin.appointments.index') }}"><i class="zmdi zmdi-calendar-check"></i><span>Appointment</span> </a></li>
                    <li class="{{ request()->routeIs('admin.contact-messages.*') ? 'active' : '' }}"><a href="{{ route('admin.contact-messages.index') }}"><i class="zmdi zmdi-email"></i><span>Contact Messages</span> </a></li>
                    <li><a href="javascript:void(0);" class="menu-toggle"><i class="zmdi zmdi-account-add"></i><span>Doctors</span> </a>
                        <ul class="ml-menu">
                            <li><a href="doctors.html">All Doctors</a></li>
                            <li><a href="add-doctor.html">Add Doctor</a></li>
                            <li><a href="profile.html">Doctor Profile</a></li>
                            <li><a href="events.html">Doctor Schedule</a></li>
                        </ul>
                    </li>
                    <li><a href="javascript:void(0);" class="menu-toggle"><i class="zmdi zmdi-account-o"></i><span>Patients</span> </a>
                        <ul class="ml-menu">
                            <li><a href="patients.html">All Patients</a></li>
                            <li><a href="add-patient.html">Add Patient</a></li>
                            <li><a href="patient-profile.html">Patient Profile</a></li>
                            <li><a href="patient-invoice.html">Invoice</a></li>
                        </ul>
                    </li>
                    <li><a href="javascript:void(0);" class="menu-toggle"><i class="zmdi zmdi-balance-wallet"></i><span>Payments</span> </a>
                        <ul class="ml-menu">
                            <li><a href="payments.html">Payments</a></li>
                            <li><a href="add-payments.html">Add Payment</a></li>
                            <li><a href="invoice.html">Invoice</a></li>
                        </ul>
                    </li>
                    <li><a href="javascript:void(0);" class="menu-toggle"><i class="zmdi zmdi-label-alt"></i><span>Departments</span> </a>
                        <ul class="ml-menu">
                            <li><a href="add-departments.html">Add</a></li>
                            <li><a href="all-Departments.html">All Departments</a></li>
                            <li><a href="javascript:void(0);">Cardiology</a></li>
                            <li><a href="javascript:void(0);">Pulmonology</a></li>
                            <li><a href="javascript:void(0);">Gynecology</a></li>
                            <li><a href="javascript:void(0);">Neurology</a></li>
                            <li><a href="javascript:void(0);">Urology</a></li>
                            <li><a href="javascript:void(0);">Gastrology</a></li>
                            <li><a href="javascript:void(0);">Pediatrician</a></li>
                            <li><a href="javascript:void(0);">Laboratory</a></li>
                        </ul>
                    </li>
                    <li> <a href="javascript:void(0);" class="menu-toggle"><i class="zmdi zmdi-lock"></i><span>Authentication</span> </a>
                        <ul class="ml-menu">
                            <li><a href="sign-in.html">Sign In</a> </li>
                            <li><a href="sign-up.html">Sign Up</a> </li>
                            <li><a href="forgot-password.html">Forgot Password</a> </li>
                            <li><a href="404.html">Page 404</a> </li>
                            <li><a href="500.html">Page 500</a> </li>
                            <li><a href="page-offline.html">Page Offline</a> </li>
                            <li><a href="locked.html">Locked Screen</a> </li>
                        </ul>
                    </li>
                    <li class="header">Site Setting</li>
                    <li class="{{ request()->routeIs('admin.site.services*') || request()->routeIs('admin.site.pages.services') ? 'active open' : '' }}">
                        <a href="javascript:void(0);" class="menu-toggle">
                            <i class="zmdi zmdi-blogger"></i>
                            <span>Services</span>
                        </a>

                        <ul class="ml-menu {{ request()->routeIs('admin.site.services*') || request()->routeIs('admin.site.pages.services') ? 'd-block' : '' }}">

                            <li class="{{ request()->routeIs('admin.site.services') ? 'active' : '' }}">
                                <a href="{{ route('admin.site.services') }}">All</a>
                            </li>

                            <li class="{{ request()->routeIs('admin.site.services.add') ? 'active' : '' }}">
                                <a href="{{ route('admin.site.services.add') }}">Add Services</a>
                            </li>

                        </ul>
                    </li>
                    <li class="{{ request()->routeIs('admin.patient-guides*') || request()->routeIs('admin.patient-guide.*') ? 'active open' : '' }}">
                        <a href="javascript:void(0);" class="menu-toggle">
                            <i class="zmdi zmdi-accounts-list-alt"></i>
                            <span>Patient Guide</span>
                        </a>

                        <ul class="ml-menu {{ request()->routeIs('admin.patient-guides*') || request()->routeIs('admin.patient-guide.*') ? 'd-block' : '' }}">
                            <li class="{{ request()->routeIs('admin.patient-guides.index') ? 'active' : '' }}">
                                <a href="{{ route('admin.patient-guides.index') }}">All</a>
                            </li>
                            <li class="{{ request()->routeIs('admin.patient-guides.add') ? 'active' : '' }}">
                                <a href="{{ route('admin.patient-guides.add') }}">Add</a>
                            </li>
                        </ul>
                    </li>
                    <li class="{{ request()->routeIs('admin.site.services*') || request()->routeIs('admin.site.home') || request()->routeIs('admin.site.about') || request()->routeIs('admin.site.contact*') || request()->routeIs('admin.site.pages.*') || request()->routeIs('admin.site.header-footer*') ? 'active open' : '' }}">
                        <a href="javascript:void(0);" class="menu-toggle">
                            <i class="zmdi zmdi-blogger"></i>
                            <span>Pages</span>
                        </a>

                        <ul class="ml-menu {{ request()->routeIs('admin.site.services*') || request()->routeIs('admin.site.home') || request()->routeIs('admin.site.about') || request()->routeIs('admin.site.contact*') || request()->routeIs('admin.site.pages.*') || request()->routeIs('admin.site.header-footer*') ? 'd-block' : '' }}">

                            <li class="{{ request()->routeIs('admin.site.home') ? 'active' : '' }}">
                                <a href="{{ route('admin.site.home') }}">Home</a>
                            </li>

                            <li class="{{ request()->routeIs('admin.site.about') ? 'active' : '' }}">
                                <a href="{{ route('admin.site.about') }}">About Us</a>
                            </li>
                            <li class="{{ request()->routeIs('admin.site.pages.services') ? 'active' : '' }}">
                                <a href="{{ route('admin.site.pages.services') }}">Services</a>
                            </li>
                            <li class="{{ request()->routeIs('admin.site.pages.patientguide') ? 'active' : '' }}">
                                <a href="{{ route('admin.site.pages.patientguide') }}">Patient Guide</a>
                            </li>

                            <li class="{{ request()->routeIs('admin.site.header-footer*') ? 'active' : '' }}">
                                <a href="{{ route('admin.site.header-footer') }}">Header & Footer</a>
                            </li>

                            <li class="{{ request()->routeIs('admin.site.contact*') ? 'active' : '' }}">
                                <a href="{{ route('admin.site.contact') }}">Contact Us</a>
                            </li>

                        </ul>
                    </li>
                    <li class="{{ request()->routeIs('admin.site.smtp*') ? 'active' : '' }}"><a href="{{ route('admin.site.smtp') }}"><i class="zmdi zmdi-calendar-check"></i><span>SMTP Settings</span> </a></li>

                    <li class="header">EXTRA COMPONENTS</li>
                    <li><a href="javascript:void(0);" class="menu-toggle"><i class="zmdi zmdi-blogger"></i><span>Blog</span></a>
                        <ul class="ml-menu">
                            <li><a href="blog-dashboard.html">Blog Dashboard</a></li>
                            <li><a href="blog-post.html">New Post</a></li>
                            <li><a href="blog-list.html">Blog List</a></li>
                            <li><a href="blog-grid.html">Blog Grid</a></li>
                            <li><a href="blog-details.html">Blog Single</a></li>
                        </ul>
                    </li>
                    <li><a href="javascript:void(0);" class="menu-toggle"><i class="zmdi zmdi-folder"></i><span>File Manager</span> </a>
                        <ul class="ml-menu">
                            <li><a href="file-dashboard.html">All File</a></li>
                            <li><a href="file-documents.html" >Documents</a></li>
                            <li><a href="file-media.html">Media</a></li>
                            <li><a href="file-images.html">Images</a></li>
                        </ul>
                    </li>
                    <li> <a href="javascript:void(0);" class="menu-toggle"><i class="zmdi zmdi-apps"></i><span>App</span> </a>
                        <ul class="ml-menu">
                            <li><a href="mail-inbox.html">Inbox</a></li>
                            <li><a href="chat.html">Chat</a></li>
                            <li><a href="contact.html">Contact list</a></li>
                        </ul>
                    </li>
                    <li> <a href="javascript:void(0);" class="menu-toggle"><i class="zmdi zmdi-delicious"></i><span>Widgets</span> </a>
                        <ul class="ml-menu">
                            <li><a href="widgets-app.html">Apps Widgetse</a></li>
                            <li><a href="widgets-data.html">Data Widgetse</a></li>
                        </ul>
                    </li>
                    <li> <a href="javascript:void(0);" class="menu-toggle"><i class="zmdi zmdi-copy"></i><span>Sample Pages</span> </a>
                        <ul class="ml-menu">
                            <li><a href="blank.html">Blank Page</a> </li>
                            <li><a href="https://wrraptheme.com/templates/oreo/hospital/html/rtl/index.html">RTL Support</a></li>
                            <li><a href="image-gallery.html">Image Gallery</a> </li>
                            <li><a href="profile.html">Profile</a></li>
                            <li><a href="timeline.html">Timeline</a></li>
                            <li><a href="invoice.html">Invoices</a></li>
                            <li><a href="search-results.html">Search Results</a></li>
                        </ul>
                    </li>
                    <li> <a href="javascript:void(0);" class="menu-toggle"><i class="zmdi zmdi-swap-alt"></i><span>User Interface (UI)</span> </a>
                        <ul class="ml-menu">
                            <li><a href="ui_kit.html">UI KIT</a></li>
                            <li><a href="alerts.html">Alerts</a></li>
                            <li><a href="collapse.html">Collapse</a></li>
                            <li><a href="colors.html">Colors</a></li>
                            <li><a href="dialogs.html">Dialogs</a></li>
                            <li><a href="icons.html">Icons</a></li>
                            <li><a href="list-group.html">List Group</a></li>
                            <li><a href="media-object.html">Media Object</a></li>
                            <li><a href="modals.html">Modals</a></li>
                            <li><a href="notifications.html">Notifications</a></li>
                            <li><a href="progressbars.html">Progress Bars</a></li>
                            <li><a href="range-sliders.html">Range Sliders</a></li>
                            <li><a href="sortable-nestable.html">Sortable & Nestable</a></li>
                            <li><a href="tabs.html">Tabs</a></li>
                            <li><a href="waves.html">Waves</a></li>
                        </ul>
                    </li>
                    <li class="header">Extra</li>
                    <li>
                        <div class="progress-container progress-primary m-t-10">
                            <span class="progress-badge">Traffic this Month</span>
                            <div class="progress">
                                <div class="progress-bar progress-bar-warning" role="progressbar" aria-valuenow="67" aria-valuemin="0" aria-valuemax="100" style="width: 67%;">
                                    <span class="progress-value">67%</span>
                                </div>
                            </div>
                        </div>
                        <div class="progress-container progress-info">
                            <span class="progress-badge">Server Load</span>
                            <div class="progress">
                                <div class="progress-bar progress-bar-warning" role="progressbar" aria-valuenow="86" aria-valuemin="0" aria-valuemax="100" style="width: 86%;">
                                    <span class="progress-value">86%</span>
                                </div>
                            </div>
                        </div>
                    </li>
                </ul>
            </div>
        </div>
        <div class="tab-pane stretchLeft" id="user">
            <div class="menu">
                <ul class="list">
                    <li>
                        <div class="user-info m-b-20 p-b-15">
                            <div class="image"><a href="profile.html"><img src="{{asset('admin/assets/images/profile_av.jpg')}}" alt="User"></a></div>
                            <div class="detail">
                                <h4>Dr. Charlotte</h4>
                                <small>Neurologist</small>
                            </div>
                            <div class="row">
                                <div class="col-12">
                                    <a title="facebook" href="#"><i class="zmdi zmdi-facebook"></i></a>
                                    <a title="twitter" href="#"><i class="zmdi zmdi-twitter"></i></a>
                                    <a title="instagram" href="#"><i class="zmdi zmdi-instagram"></i></a>
                                </div>
                                <div class="col-4 p-r-0">
                                    <h5 class="m-b-5">18</h5>
                                    <small>Exp</small>
                                </div>
                                <div class="col-4">
                                    <h5 class="m-b-5">125</h5>
                                    <small>Awards</small>
                                </div>
                                <div class="col-4 p-l-0">
                                    <h5 class="m-b-5">148</h5>
                                    <small>Clients</small>
                                </div>
                            </div>
                        </div>
                    </li>
                    <li>
                        <small class="text-muted">Location: </small>
                        <p>795 Folsom Ave, Suite 600 San Francisco, CADGE 94107</p>
                        <hr>
                        <small class="text-muted">Email address: </small>
                        <p>Charlotte@example.com</p>
                        <hr>
                        <small class="text-muted">Phone: </small>
                        <p>+ 202-555-0191</p>
                        <hr>
                        <small class="text-muted">Website: </small>
                        <p>dr.charlotte.com/ </p>
                        <hr>
                        <ul class="list-unstyled">
                            <li>
                                <div>Colorectal Surgery</div>
                                <div class="progress m-b-20">
                                    <div class="progress-bar l-blue " role="progressbar" aria-valuenow="89" aria-valuemin="0" aria-valuemax="100" style="width: 89%"> <span class="sr-only">62% Complete</span> </div>
                                </div>
                            </li>
                            <li>
                                <div>Endocrinology</div>
                                <div class="progress m-b-20">
                                    <div class="progress-bar l-green " role="progressbar" aria-valuenow="56" aria-valuemin="0" aria-valuemax="100" style="width: 56%"> <span class="sr-only">87% Complete</span> </div>
                                </div>
                            </li>
                            <li>
                                <div>Dermatology</div>
                                <div class="progress m-b-20">
                                    <div class="progress-bar l-amber" role="progressbar" aria-valuenow="78" aria-valuemin="0" aria-valuemax="100" style="width: 78%"> <span class="sr-only">32% Complete</span> </div>
                                </div>
                            </li>
                            <li>
                                <div>Neurophysiology</div>
                                <div class="progress m-b-20">
                                    <div class="progress-bar l-blush" role="progressbar" aria-valuenow="43" aria-valuemin="0" aria-valuemax="100" style="width: 43%"> <span class="sr-only">56% Complete</span> </div>
                                </div>
                            </li>
                        </ul>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</aside>
<!-- Right Sidebar -->
<aside id="rightsidebar" class="right-sidebar">
    <ul class="nav nav-tabs">
        <li class="nav-item"><a class="nav-link active" data-toggle="tab" href="#setting"><i class="zmdi zmdi-settings zmdi-hc-spin"></i></a></li>
        <li class="nav-item"><a class="nav-link" data-toggle="tab" href="#chat"><i class="zmdi zmdi-comments"></i></a></li>
        <li class="nav-item"><a class="nav-link" data-toggle="tab" href="#activity">Activity</a></li>
    </ul>
    <div class="tab-content">
        <div class="tab-pane slideRight active" id="setting">
            <div class="slim_scroll">
                <div class="card">
                    <h6>General Settings</h6>
                    <ul class="setting-list list-unstyled">
                        <li>
                            <div class="checkbox">
                                <input id="checkbox1" type="checkbox">
                                <label for="checkbox1">Report Panel Usage</label>
                            </div>
                        </li>
                        <li>
                            <div class="checkbox">
                                <input id="checkbox2" type="checkbox" checked="">
                                <label for="checkbox2">Email Redirect</label>
                            </div>
                        </li>
                        <li>
                            <div class="checkbox">
                                <input id="checkbox3" type="checkbox" checked="">
                                <label for="checkbox3">Notifications</label>
                            </div>
                        </li>
                        <li>
                            <div class="checkbox">
                                <input id="checkbox4" type="checkbox" checked="">
                                <label for="checkbox4">Auto Updates</label>
                            </div>
                        </li>
                    </ul>
                </div>
                <div class="card">
                    <h6>Skins</h6>
                    <ul class="choose-skin list-unstyled">
                        <li data-theme="purple">
                            <div class="purple"></div>
                        </li>
                        <li data-theme="blue">
                            <div class="blue"></div>
                        </li>
                        <li data-theme="cyan" class="active">
                            <div class="cyan"></div>
                        </li>
                        <li data-theme="green">
                            <div class="green"></div>
                        </li>
                        <li data-theme="orange">
                            <div class="orange"></div>
                        </li>
                        <li data-theme="blush">
                            <div class="blush"></div>
                        </li>
                    </ul>
                </div>
                <div class="card">
                    <h6>Account Settings</h6>
                    <ul class="setting-list list-unstyled">
                        <li>
                            <div class="checkbox">
                                <input id="checkbox5" type="checkbox" checked="">
                                <label for="checkbox5">Offline</label>
                            </div>
                        </li>
                        <li>
                            <div class="checkbox">
                                <input id="checkbox6" type="checkbox" checked="">
                                <label for="checkbox6">Location Permission</label>
                            </div>
                        </li>
                    </ul>
                </div>
                <div class="card theme-light-dark">
                    <h6>Left Menu</h6>
                    <button class="t-light btn btn-default btn-simple btn-round btn-block">Light</button>
                    <button class="t-dark btn btn-default btn-round btn-block">Dark</button>
                    <button class="m_img_btn btn btn-primary btn-round btn-block">Sidebar Image</button>
                </div>
                <div class="card">
                    <h6>Information Summary</h6>
                    <div class="row m-b-20">
                        <div class="col-7">
                            <small class="displayblock">MEMORY USAGE</small>
                            <h5 class="m-b-0 h6">512</h5>
                        </div>
                        <div class="col-5">
                            <div class="sparkline" data-type="bar" data-width="97%" data-height="25px" data-bar-Width="5" data-bar-Spacing="3" data-bar-Color="#00ced1">8,7,9,5,6,4,6,8</div>
                        </div>
                    </div>
                    <div class="row m-b-20">
                        <div class="col-7">
                            <small class="displayblock">CPU USAGE</small>
                            <h5 class="m-b-0 h6">90%</h5>
                        </div>
                        <div class="col-5">
                            <div class="sparkline" data-type="bar" data-width="97%" data-height="25px" data-bar-Width="5" data-bar-Spacing="3" data-bar-Color="#F15F79">6,5,8,2,6,4,6,4</div>
                        </div>
                    </div>
                    <div class="row m-b-20">
                        <div class="col-7">
                            <small class="displayblock">DAILY TRAFFIC</small>
                            <h5 class="m-b-0 h6">25 142</h5>
                        </div>
                        <div class="col-5">
                            <div class="sparkline" data-type="bar" data-width="97%" data-height="25px" data-bar-Width="5" data-bar-Spacing="3" data-bar-Color="#78b83e">7,5,8,7,4,2,6,5</div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-7">
                            <small class="displayblock">DISK USAGE</small>
                            <h5 class="m-b-0 h6">60.10%</h5>
                        </div>
                        <div class="col-5">
                            <div class="sparkline" data-type="bar" data-width="97%" data-height="25px" data-bar-Width="5" data-bar-Spacing="3" data-bar-Color="#457fca">7,5,2,5,6,7,6,4</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="tab-pane right_chat stretchLeft" id="chat">
            <div class="slim_scroll">
                <div class="card">
                    <div class="search">
                        <div class="input-group">
                            <input type="text" class="form-control" placeholder="Search...">
                            <span class="input-group-addon">
                                <i class="zmdi zmdi-search"></i>
                            </span>
                        </div>
                    </div>
                </div>
                <div class="card">
                    <h6>Recent</h6>
                    <ul class="list-unstyled">
                        <li class="online">
                            <a href="javascript:void(0);">
                                <div class="media">
                                    <img class="media-object " src="{{asset('admin/assets/images/xs/avatar4.jpg')}}" alt="">
                                    <div class="media-body">
                                        <span class="name">Sophia</span>
                                        <span class="message">There are many variations of passages of Lorem Ipsum available</span>
                                        <span class="badge badge-outline status"></span>
                                    </div>
                                </div>
                            </a>
                        </li>
                        <li class="online">
                            <a href="javascript:void(0);">
                                <div class="media">
                                    <img class="media-object " src="{{asset('admin/assets/images/xs/avatar5.jpg')}}" alt="">
                                    <div class="media-body">
                                        <span class="name">Grayson</span>
                                        <span class="message">All the Lorem Ipsum generators on the</span>
                                        <span class="badge badge-outline status"></span>
                                    </div>
                                </div>
                            </a>
                        </li>
                        <li class="offline">
                            <a href="javascript:void(0);">
                                <div class="media">
                                    <img class="media-object " src="{{asset('admin/assets/images/xs/avatar2.jpg')}}" alt="">
                                    <div class="media-body">
                                        <span class="name">Isabella</span>
                                        <span class="message">Contrary to popular belief, Lorem Ipsum</span>
                                        <span class="badge badge-outline status"></span>
                                    </div>
                                </div>
                            </a>
                        </li>
                        <li class="me">
                            <a href="javascript:void(0);">
                                <div class="media">
                                    <img class="media-object " src="{{asset('admin/assets/images/xs/avatar1.jpg')}}" alt="">
                                    <div class="media-body">
                                        <span class="name">John</span>
                                        <span class="message">It is a long established fact that a reader</span>
                                        <span class="badge badge-outline status"></span>
                                    </div>
                                </div>
                            </a>
                        </li>
                        <li class="online">
                            <a href="javascript:void(0);">
                                <div class="media">
                                    <img class="media-object " src="{{asset('admin/assets/images/xs/avatar3.jpg')}}" alt="">
                                    <div class="media-body">
                                        <span class="name">Alexander</span>
                                        <span class="message">Richard McClintock, a Latin professor</span>
                                        <span class="badge badge-outline status"></span>
                                    </div>
                                </div>
                            </a>
                        </li>
                    </ul>
                </div>
                <div class="card">
                    <h6>Contacts</h6>
                    <ul class="list-unstyled">
                        <li class="offline inlineblock">
                            <a href="javascript:void(0);">
                                <div class="media">
                                    <img class="media-object " src="{{asset('admin/assets/images/xs/avatar10.jpg')}}" alt="">
                                    <div class="media-body">
                                        <span class="badge badge-outline status"></span>
                                    </div>
                                </div>
                            </a>
                        </li>
                        <li class="offline inlineblock">
                            <a href="javascript:void(0);">
                                <div class="media">
                                    <img class="media-object " src="{{asset('admin/assets/images/xs/avatar6.jpg')}}" alt="">
                                    <div class="media-body">
                                        <span class="badge badge-outline status"></span>
                                    </div>
                                </div>
                            </a>
                        </li>
                        <li class="offline inlineblock">
                            <a href="javascript:void(0);">
                                <div class="media">
                                    <img class="media-object " src="{{asset('admin/assets/images/xs/avatar7.jpg')}}" alt="">
                                    <div class="media-body">
                                        <span class="badge badge-outline status"></span>
                                    </div>
                                </div>
                            </a>
                        </li>
                        <li class="offline inlineblock">
                            <a href="javascript:void(0);">
                                <div class="media">
                                    <img class="media-object " src="{{asset('admin/assets/images/xs/avatar8.jpg')}}" alt="">
                                    <div class="media-body">
                                        <span class="badge badge-outline status"></span>
                                    </div>
                                </div>
                            </a>
                        </li>
                        <li class="offline inlineblock">
                            <a href="javascript:void(0);">
                                <div class="media">
                                    <img class="media-object " src="{{asset('admin/assets/images/xs/avatar9.jpg')}}" alt="">
                                    <div class="media-body">
                                        <span class="badge badge-outline status"></span>
                                    </div>
                                </div>
                            </a>
                        </li>
                        <li class="online inlineblock">
                            <a href="javascript:void(0);">
                                <div class="media">
                                    <img class="media-object " src="{{asset('admin/assets/images/xs/avatar5.jpg')}}" alt="">
                                    <div class="media-body">
                                        <span class="badge badge-outline status"></span>
                                    </div>
                                </div>
                            </a>
                        </li>
                        <li class="offline inlineblock">
                            <a href="javascript:void(0);">
                                <div class="media">
                                    <img class="media-object " src="{{asset('admin/assets/images/xs/avatar4.jpg')}}" alt="">
                                    <div class="media-body">
                                        <span class="badge badge-outline status"></span>
                                    </div>
                                </div>
                            </a>
                        </li>
                        <li class="offline inlineblock">
                            <a href="javascript:void(0);">
                                <div class="media">
                                    <img class="media-object " src="{{asset('admin/assets/images/xs/avatar3.jpg')}}" alt="">
                                    <div class="media-body">
                                        <span class="badge badge-outline status"></span>
                                    </div>
                                </div>
                            </a>
                        </li>
                        <li class="online inlineblock">
                            <a href="javascript:void(0);">
                                <div class="media">
                                    <img class="media-object " src="{{asset('admin/assets/images/xs/avatar2.jpg')}}" alt="">
                                    <div class="media-body">
                                        <span class="badge badge-outline status"></span>
                                    </div>
                                </div>
                            </a>
                        </li>
                        <li class="offline inlineblock">
                            <a href="javascript:void(0);">
                                <div class="media">
                                    <img class="media-object " src="{{asset('admin/assets/images/xs/avatar1.jpg')}}" alt="">
                                    <div class="media-body">
                                        <span class="badge badge-outline status"></span>
                                    </div>
                                </div>
                            </a>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
        <div class="tab-pane slideLeft" id="activity">
            <div class="slim_scroll">
                <div class="card user_activity">
                    <h6>Recent Activity</h6>
                    <div class="streamline b-accent">
                        <div class="sl-item">
                            <img class="user rounded-circle" src="{{asset('admin/assets/images/xs/avatar4.jpg')}}" alt="">
                            <div class="sl-content">
                                <h5 class="m-b-0">Admin Birthday</h5>
                                <small>Jan 21 <a href="javascript:void(0);" class="text-info">Sophia</a>.</small>
                            </div>
                        </div>
                        <div class="sl-item">
                            <img class="user rounded-circle" src="{{asset('admin/assets/images/xs/avatar5.jpg')}}" alt="">
                            <div class="sl-content">
                                <h5 class="m-b-0">Add New Contact</h5>
                                <small>30min ago <a href="javascript:void(0);">Alexander</a>.</small>
                                <small><strong>P:</strong> +264-625-2323</small>
                                <small><strong>E:</strong> maryamamiri@gmail.com</small>
                            </div>
                        </div>
                        <div class="sl-item">
                            <img class="user rounded-circle" src="{{asset('admin/assets/images/xs/avatar6.jpg')}}" alt="">
                            <div class="sl-content">
                                <h5 class="m-b-0">Code Change</h5>
                                <small>Today <a href="javascript:void(0);">Grayson</a>.</small>
                                <small>The standard chunk of Lorem Ipsum used since the 1500s is reproduced</small>
                            </div>
                        </div>
                        <div class="sl-item">
                            <img class="user rounded-circle" src="{{asset('admin/assets/images/xs/avatar7.jpg')}}" alt="">
                            <div class="sl-content">
                                <h5 class="m-b-0">New Email</h5>
                                <small>45min ago <a href="javascript:void(0);" class="text-info">Fidel Tonn</a>.</small>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card">
                    <h6>Recent Attachments</h6>
                    <ul class="list-unstyled activity">
                        <li>
                            <a href="javascript:void(0)">
                                <i class="zmdi zmdi-collection-pdf l-blush"></i>
                                <div class="info">
                                    <h4>info_258.pdf</h4>
                                    <small>2MB</small>
                                </div>
                            </a>
                        </li>
                        <li>
                            <a href="javascript:void(0)">
                                <i class="zmdi zmdi-collection-text l-amber"></i>
                                <div class="info">
                                    <h4>newdoc_214.doc</h4>
                                    <small>900KB</small>
                                </div>
                            </a>
                        </li>
                        <li>
                            <a href="javascript:void(0)">
                                <i class="zmdi zmdi-image l-parpl"></i>
                                <div class="info">
                                    <h4>MG_4145.jpg')}}</h4>
                                    <small>5.6MB</small>
                                </div>
                            </a>
                        </li>
                        <li>
                            <a href="javascript:void(0)">
                                <i class="zmdi zmdi-image l-parpl"></i>
                                <div class="info">
                                    <h4>MG_4100.jpg')}}</h4>
                                    <small>5MB</small>
                                </div>
                            </a>
                        </li>
                        <li>
                            <a href="javascript:void(0)">
                                <i class="zmdi zmdi-collection-text l-amber"></i>
                                <div class="info">
                                    <h4>Reports_end.doc</h4>
                                    <small>780KB</small>
                                </div>
                            </a>
                        </li>
                        <li>
                            <a href="javascript:void(0)">
                                <i class="zmdi zmdi-videocam l-turquoise"></i>
                                <div class="info">
                                    <h4>movie2018.MKV</h4>
                                    <small>750MB</small>
                                </div>
                            </a>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</aside>
