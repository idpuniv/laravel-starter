@if (session('status'))
    <div class="alert alert-success">
        {{ session('status') }}
    </div>
@endif

@if ($errors->any())
    <div class="alert alert-danger">
        {{ $errors->first() }}
    </div>
@endif

<form method="POST" action="{{ route('2fa.verify.post') }}">
    @csrf
    <label>Code de vérification :</label>
    <input type="text" name="code" required autofocus>
    <button type="submit">Valider</button>
</form>

<form id="resend-form" method="POST" action="{{ route('2fa.resend') }}" style="margin-top: 10px;">
    @csrf
    <button type="submit" id="resend-button" disabled>Renvoyer le code (<span id="timer"></span>)</button>
</form>

<script>
    let expiresAt = new Date("{{ $expiresAt }}").getTime(); // timestamp du serveur
    let resendButton = document.getElementById('resend-button');
    let timerSpan = document.getElementById('timer');

    function updateTimer() {
        let now = new Date().getTime();
        let distance = expiresAt - now;

        if (distance <= 0) {
            timerSpan.innerText = '00:00';
            resendButton.disabled = false;
            clearInterval(interval);
        } else {
            let minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
            let seconds = Math.floor((distance % (1000 * 60)) / 1000);
            timerSpan.innerText = (minutes < 10 ? '0' : '') + minutes + ':' + 
                                  (seconds < 10 ? '0' : '') + seconds;
        }
    }

    let interval = setInterval(updateTimer, 1000);
    updateTimer(); // initial call
</script>