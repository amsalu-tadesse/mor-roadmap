{{-- @if (session()->has('swal_msg'))
    <script>
        notification = @json(session()->pull("swal_msg"));
        swal(notification.title, notification.message, notification.type);
        <!-- 
            To prevent showing the notification when on browser back
            we can do: 
        -->
       @php 
          session()->forget('swal_msg'); 
       @endphp
    </script>
@endif --}}