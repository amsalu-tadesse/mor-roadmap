<style>
    #popup-settings-list {
        display: none;
        position: absolute;
        right: 10px;
        top: 50px;
        background-color: white;
        padding: 10px;
        /* border: 1px solid #ccc; */
    }

    #popup-settings-list ul {
        list-style-type: none;
        padding: 0;
        margin: 0;
    }

    #popup-settings-list li {
        padding: 5px;
    }

    #popup-settings-list li:hover {
        background-color: #f5f5f5;
    }
</style>
<!-- Navbar -->
<div class="wrapper">
    <!-- Navbar -->
    <nav class="main-header navbar navbar-expand navbar-white navbar-light">
        {{-- <nav class="main-header navbar navbar-expand navbar-white navbar-dark"> --}}
        <!-- Left navbar links -->
        <ul class="navbar-nav">
            <li class="nav-item">
                <a class="nav-link" data-widget="pushmenu" href="#" role="button">
                    <i class="fas fa-bars"></i></a>
            </li>
            <li class="nav-item d-none d-sm-inline-block">
                <a href="{{ route('admin.dashboard') }}" class="nav-link">Home</a>
            </li>
            <li class="nav-item d-none d-sm-inline-block">
                <a href="#" class="nav-link">Help</a>
            </li>
        </ul>

        <ul class="navbar-nav ml-auto">
            <li class="nav-item d-none d-sm-inline-block">
                <p class="nav-link">UserMangment</p>
            </li>
        </ul>

        <!-- Right navbar links -->
        <ul class="navbar-nav ml-auto">

            {{-- <li class="nav-item">
                <a class="nav-link" data-widget="fullscreen" href="#" role="button">
                    <i class="fas fa-expand-arrows-alt"></i>
                </a>
            </li> --}}



            <li class="nav-item dropdown">
                <a class="nav-link" data-toggle="dropdown" href="#" aria-expanded="false">
                    <i class="far fa-bell fs-1"></i>
                    @if ($notification_count > 0)
                        <span class="badge badge-danger navbar-badge"
                            id="notification_count">{{ $notification_count }}</span>
                    @endif
                </a>
                <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right" style="left: inherit; right: 0px;">
                    <span class="dropdown-item dropdown-header">Recent Notifications</span>
                    <div class="dropdown-divider"></div>

                    @foreach ($notifications as $notification)
                        <button id="update_status_{{ $notification->id }}"
                            onclick="setSeen(event, {{ $notification->id }})"
                            data-notification-id="{{ $notification->id }}"
                            class="dropdown-item {{ $notification->is_seen == 0 ? 'font-weight-bold ' : '' }}"
                            style="">
                            <i class="fas fa-bell text-primary mr-2 "></i> {{ $notification->title }}
                            <br>
                            <span
                                class="float-right text-muted text-sm">{{ $notification->created_at->diffForHumans() }}</span>
                        </button>
                        <div class="dropdown-divider"></div>
                    @endforeach

                    <a href="{{ route('admin.notifications.index') }}" class="dropdown-item dropdown-footer">See All
                        Notifications</a>
                </div>
            </li>

            <li class="nav-item" id="profile-picture-icon">
                <a class="nav-link" data-widget="control-sidebar" data-slide="true" href="#" role="button">
                    <i class="fas fa-user"></i>
                </a>
            </li>
            <li>


                <div id="popup-settings-list">
                    <ul>

                        @php
                            $user = Auth::user();
                            echo '<li>' . $user->first_name . '</li>';
                        @endphp

                        <details class="my-roles" style="color: blue">
                            <summary>My Roles</summary>
                            <ul>

                                @php
                                    $roles = $user?->roles;
                                    if ($roles) {
                                        foreach ($roles as $role) {
                                            echo '<li><a style="color:green"> ' . $role?->name . '</li></a>';
                                        }
                                    }
                                @endphp

                            </ul>
                        </details>


                        <li><a href="{{ route('admin.profile') }}">Profile setting</a></li>
                        <li><a href="/logout">Sign Out</a></li>
                    </ul>
                </div>
            </li>


        </ul>
    </nav>
</div>
<!-- /.navbar -->
<!-- Include jQuery library if not already included -->
<script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>

<!-- Add this script after including jQuery -->
<script>
    $(document).ready(function() {
        // Attach click event to notification buttons


    });






    function setSeen(e, notification_id) {


        e.preventDefault();
        e.stopPropagation();

        // Get the notification ID from data attribute
        //   var notificationId = $(this).data('notification-id');
        var notificationButton = $("#update_status_" + notification_id);

        // Make an AJAX request to update is_seen column
        $.ajax({
            type: 'POST',
            url: '{{ route('admin.update.notification') }}', // Replace with your route
            data: {
                notification_id
            },
            success: function(response) {
                // Check if the update was successful
                if (response.success) {
                    // Remove the font-weight-bold class immediately
                    notificationButton.removeClass('font-weight-bold');

                    // Update the notification count
                    var newCount = parseInt($('#notification_count').text()) - 1;
                    $('#notification_count').text(newCount);

                    // Optionally: Redirect to the notification details page
                    //window.location.href = '{{ route('admin.notifications.index') }}';
                } else {
                    // Handle the case where the update was not successful
                    //console.error('Failed to update notification status.');
                }
            },
            error: function(error) {
                console.error('AJAX request failed:', error);
            }
        });
    }
</script>


<script>
    document.getElementById("profile-picture-icon").addEventListener("click", function() {
        var popupSettingsList = document.getElementById("popup-settings-list");

        if (popupSettingsList.style.display === "none") {
            popupSettingsList.style.display = "block";
        } else {
            popupSettingsList.style.display = "none";
        }
    });

    const detailsElement = document.querySelector('.my-roles');

    detailsElement.addEventListener('toggle', event => {
        if (event.target.open) {} else {}
    });
</script>


<style>
    .dropdown-item {
        white-space: normal;
        word-wrap: break-word;
    }
</style>
