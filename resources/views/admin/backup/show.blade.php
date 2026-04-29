<div>
    <h2>Stored Backups</h2>

    <ul>
        @foreach($backups as $backup)
            <li>
                <a href="{{ $backup['downloadUrl'] }}">{{ $backup['fileName'] }}</a>
                <small>{{ $backup['date'] }}</small>
            </li>
        @endforeach
    </ul>
</div>
