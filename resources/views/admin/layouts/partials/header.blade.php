    <!--begin::Header-->
 <nav class="app-header navbar navbar-expand bg-body">
        <!--begin::Container-->
        <div class="container-fluid">
          <!--begin::Start Navbar Links-->
          <ul class="navbar-nav align-items-center">
            <li class="nav-item">
              <a class="nav-link" data-lte-toggle="sidebar" href="#" role="button">
                <i class="bi bi-list"></i>
              </a>
            </li>
            <li class="nav-item">
              <a href="{{ route('home') }}" target="_blank" class="nav-link d-flex align-items-center gap-1" title="ওয়েবসাইট ভিজিট করুন">
                <i class="bi bi-globe"></i>
                <span class="small fw-bold text-dark-green d-none d-sm-inline-block">ওয়েবসাইট দেখুন</span>
              </a>
            </li>
          </ul>
          <!--end::Start Navbar Links-->

          <!--begin::End Navbar Links-->
          <ul class="navbar-nav ms-auto">

            <!--begin::Messages Dropdown Menu-->
            <li class="nav-item dropdown">
              <a class="nav-link" data-bs-toggle="dropdown" href="#" id="messageBell">
                <i class="bi bi-chat-text"></i>
                <span class="navbar-badge badge text-bg-danger" id="messageCount" style="display: none;">0</span>
              </a>
              <div class="dropdown-menu dropdown-menu-lg dropdown-menu-end" style="width: 380px;">
                <div class="dropdown-header d-flex justify-content-between align-items-center">
                  <span><strong>Contact Messages</strong></span>
                  <a href="{{ route('admin.contacts.index') }}" class="text-sm">View All</a>
                </div>
                <div class="dropdown-divider"></div>
                <div id="messageList" style="max-height: 400px; overflow-y: auto;">
                  <div class="text-center py-3 text-muted">
                    <i class="bi bi-chat-left-dots me-2"></i> No new messages
                  </div>
                </div>
                <div class="dropdown-divider"></div>
                <a href="{{ route('admin.contacts.index') }}" class="dropdown-item dropdown-footer text-center">See All Messages</a>
              </div>
            </li>
            <!--end::Messages Dropdown Menu-->

            <!--begin::Notifications Dropdown Menu-->
               @include('admin.layouts.partials.notification-bell')
            <!--end::Notifications Dropdown Menu-->

            <!--begin::Fullscreen Toggle-->
            <li class="nav-item">
              <a class="nav-link" href="#" data-lte-toggle="fullscreen">
                <i data-lte-icon="maximize" class="bi bi-arrows-fullscreen"></i>
                <i data-lte-icon="minimize" class="bi bi-fullscreen-exit d-none"></i>
              </a>
            </li>
            <!--end::Fullscreen Toggle-->

            <!--begin::Color Mode Toggle (#6010)-->
            <li class="nav-item dropdown">
              <a
                class="nav-link"
                href="#"
                id="bd-theme"
                aria-label="Toggle color scheme"
                data-bs-toggle="dropdown"
                aria-expanded="false"
              >
                <i class="bi bi-sun-fill" data-lte-theme-icon="light"></i>
                <i class="bi bi-moon-fill d-none" data-lte-theme-icon="dark"></i>
                <i class="bi bi-circle-half d-none" data-lte-theme-icon="auto"></i>
              </a>
              <ul
                class="dropdown-menu dropdown-menu-end"
                aria-labelledby="bd-theme"
                style="--bs-dropdown-min-width: 8rem"
              >
                <li>
                  <button
                    type="button"
                    class="dropdown-item d-flex align-items-center"
                    data-bs-theme-value="light"
                    aria-pressed="false"
                  >
                    <i class="bi bi-sun-fill me-2"></i>
                    Light
                    <i class="bi bi-check-lg ms-auto d-none"></i>
                  </button>
                </li>
                <li>
                  <button
                    type="button"
                    class="dropdown-item d-flex align-items-center"
                    data-bs-theme-value="dark"
                    aria-pressed="false"
                  >
                    <i class="bi bi-moon-fill me-2"></i>
                    Dark
                    <i class="bi bi-check-lg ms-auto d-none"></i>
                  </button>
                </li>
                <li>
                  <button
                    type="button"
                    class="dropdown-item d-flex align-items-center active"
                    data-bs-theme-value="auto"
                    aria-pressed="true"
                  >
                    <i class="bi bi-circle-half me-2"></i>
                    Auto
                    <i class="bi bi-check-lg ms-auto d-none"></i>
                  </button>
                </li>
              </ul>
            </li>
            <!--end::Color Mode Toggle-->

            <!--begin::User Menu Dropdown-->
            <li class="nav-item dropdown user-menu">
              <a href="#" class="nav-link dropdown-toggle" data-bs-toggle="dropdown">
                <img src="{{ auth()->user()->image ? asset(auth()->user()->image) : asset('backend/assets/img/avatar5.png') }}" class="user-image rounded-circle shadow" alt="User Image" />
                <span class="d-none d-md-inline">{{ auth()->user()->name }}</span>
              </a>
              <ul class="dropdown-menu dropdown-menu-lg dropdown-menu-end">
                <!--begin::User Image-->
                <li class="user-header text-bg-primary">
                  <img src="{{ auth()->user()->image ? asset(auth()->user()->image) : asset('backend/assets/img/avatar5.png') }}" class="rounded-circle shadow"alt="User Image"/>
                  <p>
                    {{ auth()->user()->name }}
                  <small>
                    @php
                        $joinedDate = auth()->user()->created_at;
                        $formattedDate = $joinedDate ? $joinedDate->format('M Y') : 'Recently';
                        $memberSince = $joinedDate ? $joinedDate->diffForHumans() : 'Just joined';
                    @endphp
                    Member since {{ $formattedDate }}
                    <br>
                    <span class="text-light-50">({{ $memberSince }})</span>
                </small>
                  </p>
                </li>
                <!--end::User Image-->
                <!--begin::Menu Body-->

                <!--end::Menu Body-->
                <!--begin::Menu Footer-->
               <li class="user-footer">
                    <a href="{{ Route::has('admin.profile.index') ? route('admin.profile.index') : '#' }}" class="btn btn-outline-secondary">
                        <i class="fas fa-user me-1"></i> Profile
                    </a>
                    <form action="{{ route('admin.logout') }}" method="POST" class="d-inline float-end">
                        @csrf
                        <button type="submit" class="btn btn-outline-danger">
                            <i class="fas fa-sign-out-alt me-1"></i> Sign out
                        </button>
                    </form>
                </li>
                <!--end::Menu Footer-->
              </ul>
            </li>
            <!--end::User Menu Dropdown-->
          </ul>
          <!--end::End Navbar Links-->
        </div>
        <!--end::Container-->
      </nav>
      <!--end::Header-->
