        <!-- Menu -->

        <aside id="layout-menu" class="layout-menu menu-vertical menu bg-menu-theme" style="background-color: #075308 !important">
            <div class="app-brand demo m-0 border-bottom" style="background-color: #075308 !important">
              <a href="#" class="app-brand-link">
                <span class="app-brand-logo demo">
                  <i class="bx bx-spa" style="font-size: 2rem; color: white;"></i>
                </span>
              </a>

              <a href="javascript:void(0);" class="layout-menu-toggle menu-link text-large ms-auto d-block d-xl-none">
                <i class="bx bx-chevron-left bx-sm align-middle"></i>
              </a>
            </div>

            <div class="menu-inner-shadow"></div>

            <ul class="menu-inner py-1 mt-3">
              <!-- Dashboard -->
              <li class="menu-item {{ Route::is('dashboard') ? 'active' : '' }}">
                <a href="{{ route('dashboard') }}" class="menu-link text-white">
                  <i class="menu-icon tf-icons bx bx-home"></i>
                  <div data-i18n="Dashboard">Dashboard</div>
                </a>
              </li>

              <!-- Users -->
              <li class="menu-item {{ Route::is('users.index') ? 'active' : '' }}">
                <a href="{{ route('users.index') }}" class="menu-link text-white">
                  <i class="menu-icon tf-icons bx bx-user"></i>
                  <div data-i18n="Users">Admin</div>
                </a>
              </li>

              <!-- Patients -->
              <li class="menu-item {{ Route::is('patients.*') ? 'active' : '' }}">
                <a href="{{ route('patients.index') }}" class="menu-link text-white">
                  <i class="menu-icon tf-icons bx bx-user"></i>
                  <div data-i18n="Patients">Pasien</div>
                </a>
              </li>

              <!-- Doctors -->
              <li class="menu-item {{ Route::is('doctors.*') ? 'active' : '' }}">
                <a href="{{ route('doctors.index') }}" class="menu-link text-white">
                  <i class="menu-icon tf-icons bx bx-user"></i>
                  <div data-i18n="Doctors">Dokter</div>
                </a>
              </li>

              <!-- Schedules -->
              <li class="menu-item {{ Route::is('schedules.*') ? 'active' : '' }}">
                <a href="{{ route('schedules.index') }}" class="menu-link text-white">
                  <i class="menu-icon tf-icons bx bx-calendar"></i>
                  <div data-i18n="Schedules">Jadwal</div>
                </a>
              </li>

              <!-- Registrations -->
              <li class="menu-item {{ Route::is('registrations.*') ? 'active' : '' }}">
                <a href="{{ route('registrations.index') }}" class="menu-link text-white">
                  <i class="menu-icon tf-icons bx bx-list-ul"></i>
                  <div data-i18n="Registrations">Pendaftaran</div>
                </a>
              </li>

              <!-- Transactions -->
              <li class="menu-item {{ Route::is('transactions.*') ? 'active' : '' }}">
                <a href="{{ route('transactions.index') }}" class="menu-link text-white">
                  <i class="menu-icon tf-icons bx bx-receipt"></i>
                  <div data-i18n="Transactions">Transaksi</div>
                </a>
              </li>
            </ul>
          </aside>
          <!-- / Menu -->
