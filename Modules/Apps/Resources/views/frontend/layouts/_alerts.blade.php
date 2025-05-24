@if ($errors->any())
    <div class="alert alert-danger alert-dismissible">
        <button type="button" class="close" data-dismiss="alert">&times;</button>
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

@if (session('status'))
    <div class="alert alert-{{ session('alert') }} alert-dismissible" role="alert">
        <button type="button" class="close" data-dismiss="alert">&times;</button>
        <center>
            {{ session('status') }}
        </center>
    </div>
@endif
