<div class="d-flex justify-content-between align-items-center w-100">

    {{-- Logo --}}
    <div class="header-brand">

        <h2>

            TERAPOS

        </h2>

        <small>Toko Terang Abadi</small>

    </div>

    @auth

        <div class="user-panel">

            <div>

                {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}

            </div>

            <div class="user-detail">

                <div class="name">

                    {{ auth()->user()->name }}

                </div>

                <div class="role">

                    {{ auth()->user()->display_role }}

                </div>

            </div>

            <form action="{{ route('logout') }}" method="POST" onsubmit="return confirmLogout()">

                @csrf

                <button type="submit" class="btn btn-danger logout-btn-top">

                    <i class="bi bi-box-arrow-right"></i>

                    Logout

                </button>

            </form>

        </div>

    @endauth

</div>
